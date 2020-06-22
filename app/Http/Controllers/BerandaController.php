<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

use App\KelompokPegawai;
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
        $proyek     = Proyek::find($request->id_proyek);
        $meta       = PekerjaanMeta::find($request->id_meta);
        $kerja      = Pekerjaan::find($request->id_pekerjaan);
        $pekerjaan  = RiwayatPresensi::where('id_proyek',$request->id_proyek)
            ->where('id_pekerjaan',$request->id_pekerjaan)
            ->where('id_meta',$request->id_meta)
            ->where('waktu_out','!=',NULL)
            ->join('pegawai', 'pegawai.id_pegawai', '=', 'riwayat_presensi.id_pegawai')
            ->join('jabatan', 'jabatan.id_jabatan', '=', 'pegawai.id_jabatan')
            ->join('kelompok_pegawai', 'kelompok_pegawai.id_kelompok_pegawai', '=', 'pegawai.id_kelompok')
            ->get();

        // return $kerja;

        return view('beranda.pegawai', compact(['proyek','pekerjaan','meta','kerja']));
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
