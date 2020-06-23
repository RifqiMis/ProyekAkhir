<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

use App\KelompokPegawai;
use App\Jabatan;
use App\Pegawai;
use App\Pekerjaan;
use App\PekerjaanMeta;
use App\Proyek;
use App\RiwayatPekerjaan;
use App\RiwayatPresensi;

class BerandaController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     */
    public function index(Request $request)
    {
        $departemen['supervisor']   = Pegawai::where('id_jabatan','1')->get()->count();
        $departemen['kelompok']     = KelompokPegawai::count();
        $departemen['pegawai']      = Pegawai::count();
        $jabatans                   = Jabatan::all();
        $kelompoks                  = KelompokPegawai::all();

        $proyek     = DB::table('proyek');
        if($request->status != ''){
            $proyek = Proyek::where('status_proyek',$request->status);    
        }

        if(!empty($request->cari)){
            $proyek = Proyek::where('deskripsi_proyek','like',"%$request->cari%");
        }
        
        $proyek = $proyek->paginate(10);

        return view('beranda.index', ['proyeks' => $proyek->appends(['status' => $request->status,'cari' => $request->cari]),'input' => $request, 'departemen' => $departemen ]);
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $proyek     = Proyek::find($id);
        $pekerjaan  = RiwayatPekerjaan::select('riwayat_pekerjaan.id_pekerjaan')
            ->where('id_proyek',$id)
            ->join('pekerjaan', 'pekerjaan.id_pekerjaan', '=', 'riwayat_pekerjaan.id_pekerjaan')
            ->orderBy('pekerjaan.nama_pekerjaan','ASC')
            ->groupBy('riwayat_pekerjaan.id_pekerjaan')
            ->get();
        $details    = RiwayatPekerjaan::where('id_proyek',$id)
            ->join('pekerjaan', 'pekerjaan.id_pekerjaan', '=', 'riwayat_pekerjaan.id_pekerjaan')
            ->join('pekerjaan_meta', 'pekerjaan_meta.id_meta', '=', 'riwayat_pekerjaan.id_meta')
            ->orderBy('pekerjaan.nama_pekerjaan','ASC')
            ->orderBy('pekerjaan_meta.nama_meta','ASC')
            ->get();
        return view('beranda.detail', compact(['proyek','pekerjaan','details']));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     */
    public function pegawai(Request $request)
    {
        $jabatan    = Jabatan::all();
        $kelompok   = KelompokPegawai::orderBy('nama_kelompok_pegawai','ASC')->get();
        $proyek     = Proyek::find($request->id_proyek);
        $meta       = PekerjaanMeta::find($request->id_meta);
        $kerja      = Pekerjaan::find($request->id_pekerjaan);
        $pekerjaan  = RiwayatPresensi::where('id_proyek',$request->id_proyek)
            ->where('id_pekerjaan',$request->id_pekerjaan)
            ->where('id_meta',$request->id_meta)
            ->where('waktu_out','!=',NULL)
            ->join('pegawai', 'pegawai.id_pegawai', '=', 'riwayat_presensi.id_pegawai')
            ->join('jabatan', 'jabatan.id_jabatan', '=', 'pegawai.id_jabatan')
            ->join('kelompok_pegawai', 'kelompok_pegawai.id_kelompok_pegawai', '=', 'pegawai.id_kelompok');
        
        if(!empty($request->cari)){
            $cari = $request->cari;
            $pekerjaan= $pekerjaan->where(function($q) use ($cari){
                $q->where('pegawai.nama_pegawai','like',"%$cari%")
                    ->orWhere('jabatan.nama_jabatan','like',"%$cari%")
                    ->orWhere('kelompok_pegawai.nama_kelompok_pegawai','like',"%$cari%");
            });
        }

        if($request->tanggal_mulai != ''){
            $pekerjaan = $pekerjaan->whereDate('riwayat_presensi.created_at','>=',$request->tanggal_mulai);        
        }
        if($request->tanggal_akhir != ''){
            $pekerjaan = $pekerjaan->whereDate('riwayat_presensi.created_at','<=',$request->tanggal_akhir);        
        }

        if(!empty($request->id_jabatan))
            $pekerjaan    = $pekerjaan->where('pegawai.id_jabatan',$request->id_jabatan);
        
        if(!empty($request->id_kelompok))
            $pekerjaan    = $pekerjaan->where('pegawai.id_kelompok',$request->id_kelompok);
        
        if(!empty($request->paginate_number)){
            if($request->paginate_number==999)
            $pekerjaan    = $pekerjaan->get();
            else
            $pekerjaan    = $pekerjaan->paginate($request->paginate_number);
        }
        else
            $pekerjaan    = $pekerjaan->paginate(10);
        
        $pekerjaan    = $pekerjaan->appends([
            'status'        => $request->status,
            'id_pekerjaan'  => $request->id_pekerjaan,
            'id_proyek'     => $request->id_proyek,
            'id_meta'       => $request->id_meta,
            'id_jabatan'    => $request->id_jabatan,
            'id_kelompok'   => $request->id_kelompok,
            'cari'          => $request->cari,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir
        ]);

        $input = $request;
        
        return view('beranda.pegawai', compact(['proyek','pekerjaan','meta','kerja','input','jabatan','kelompok']));
    }

    public function pengerjaanHariIni(Request $request)
    {
        $pekerjaan = '';
        $pekerjaanMeta = '';
        $proyek     = Proyek::where('id_proyek',$request->id_proyek)->first();
        $details    = RiwayatPresensi::whereDate('waktu_in',Date('Y-m-d'))
            ->where('id_proyek',$request->id_proyek);
        
        if(!empty($request->id_pekerjaan)){
            $details    = $details->where('id_pekerjaan',$request->id_pekerjaan);
            $pekerjaan  = Pekerjaan::where('id_pekerjaan',$request->id_pekerjaan)->first();
        }
            
        if(!empty($request->id_meta)){
            $details    = $details->where('id_meta',$request->id_meta);
            $pekerjaanMeta = PekerjaanMeta::where('id_meta',$request->id_meta)->first();
        }

        if($request->status==1){
            $details    = $details->where('waktu_out',NULL);
        }elseif($request->status==0){
            $details    = $details->where('waktu_out','!=',NULL);
        }

        if(!empty($request->paginate_number))
            $details    = $details->paginate($request->paginate_number);
        else
            $details    = $details->paginate(10);

        $details    = $details->appends([
            'status' => $request->status,
            'id_pekerjaan' => $request->id_pekerjaan,
            'id_proyek' => $request->id_proyek,
            'id_meta' => $request->id_meta,
        ]);
        $input      = $request;

        return view('beranda.detailHariIni',compact('details','input','proyek','pekerjaan','pekerjaanMeta'));

    }
}
