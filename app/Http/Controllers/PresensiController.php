<?php

namespace App\Http\Controllers;

use App\Pegawai;
use App\Proyek;
use App\Pekerjaan;
use App\Helper;
use App\PekerjaanMeta;
use App\RiwayatPresensi;
use App\JamKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('presensi.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     * 
     */
    public function pegawai(Request  $request)
    {
        $waktu = date('H:i:s');
        $hari_ini   = date('Y-m-d');
            $jadwal     = JamKerja::whereDate('tanggal_mulai','<=',Carbon::today()->toDateString())
                ->whereDate('tanggal_akhir','>=',Carbon::today()->toDateString())
                ->where('hari_kerja', 'like', '%'.Helper::day_to_hari(date('D')).'%')
                ->whereTime('jam_masuk', '<=', Carbon::now())
                ->whereTime('jam_pulang', '>=', Carbon::now())
                ->where('default','n')
                ->first()
            ;

            if(!$jadwal){
                // Pengecekan Jadwal Defautl
                $jadwal     = JamKerja::where('hari_kerja', 'like', '%'.Helper::day_to_hari(date('D')).'%')
                    ->whereTime('jam_masuk', '<=', Carbon::now())
                    ->whereTime('jam_pulang', '>=', Carbon::now())
                    ->where('default','y')
                    ->first()
                ;
                if(!$jadwal){
                    return redirect('')->with('danger','Saat ini tidak ada jadwal kerja' );
                }
            }

            // Pengecekan Istirahat
            if(Helper::time_to_int($waktu) >= Helper::time_to_int($jadwal->jam_mulai_istirahat) && Helper::time_to_int($waktu) < Helper::time_to_int($jadwal->jam_selesai_istirahat))
            {
                return redirect('')->with('danger','Maaf saat ini masih waktu istirahat' );
            }

            if($jadwal){
                $data = Pegawai::where('ssn',$request->ssn)
                    ->where('status','bekerja')
                    ->first();
                if($data){
                    return view('presensi.pekerjaan',compact('data'));
                }else{
                    return redirect('')->with('danger','SSN tidak terdaftar ! ' );
                }
            }
    }

    public function pekerjaan(Request  $request)
    {

        $waktu = date('H:i:s');
        $kode = explode('-',$request->kode_pekerjaan);
        
        // Pengecekan Kode QR
        if (count($kode)==3){
            $proyek         = Proyek::find($kode[0]);
            $pekerjaan      = Pekerjaan::find($kode[1]);
            $pekerjaanMeta  = PekerjaanMeta::find($kode[2]);

            if(!$proyek||!$pekerjaan||!$pekerjaanMeta)
            return redirect("/presensi-pegawai?ssn=$request->ssn")->with('danger','Pekerjaan tidak terdaftar' );

        }else{
            return redirect("/presensi-pegawai?ssn=$request->ssn")->with('danger','Format Kode Pekerjaan Salah !' );
        }

        // Pengecekan proyek
        // $cek_status = $proyek->where('status_proyek',0);
        if($proyek->status_proyek == 0){
            return redirect("/presensi-pegawai?ssn=$request->ssn")->with('warning','Maaf,Presensi Gagal ! Proyek '.$proyek->deskripsi_proyek.' selesai' );
        }
        
        if($proyek&&$pekerjaan&&$pekerjaanMeta){
            // Pengecekan Jadwal custom
            $hari_ini   = date('Y-m-d');
            $jadwal     = JamKerja::whereDate('tanggal_mulai','<=',Carbon::today()->toDateString())
                ->whereDate('tanggal_akhir','>=',Carbon::today()->toDateString())
                ->where('hari_kerja', 'like', '%'.Helper::day_to_hari(date('D')).'%')
                ->whereTime('jam_masuk', '<=', Carbon::now())
                ->whereTime('jam_pulang', '>=', Carbon::now())
                ->where('default','n')
                ->first()
            ;

            if(!$jadwal){
                // Pengecekan Jadwal Defautl
                $jadwal     = JamKerja::where('hari_kerja', 'like', '%'.Helper::day_to_hari(date('D')).'%')
                    ->whereTime('jam_masuk', '<=', Carbon::now())
                    ->whereTime('jam_pulang', '>=', Carbon::now())
                    ->where('default','y')
                    ->first()
                ;
                if(!$jadwal){
                    return redirect("/presensi-pegawai?ssn=$request->ssn")->with('danger','Saat ini tidak ada jadwal kerja' );
                }
            }

            // Pengecekan Istirahat
            if(Helper::time_to_int($waktu) >= Helper::time_to_int($jadwal->jam_mulai_istirahat) && Helper::time_to_int($waktu) < Helper::time_to_int($jadwal->jam_selesai_istirahat))
            {
                return redirect("/presensi-pegawai?ssn=$request->ssn")->with('danger','Maaf saat ini masih waktu istirahat' );
            }

            if($jadwal){
                $data               = new RiwayatPresensi;
                $data->id_pegawai   = $request->id_pegawai;
                $data->id_proyek    = $proyek->id_proyek;
                $data->id_pekerjaan = $pekerjaan->id_pekerjaan;
                $data->id_meta      = $pekerjaanMeta->id_meta;

                $cek_presensi = RiwayatPresensi::where('id_pegawai',$data->id_pegawai)
                                ->where('waktu_in', '>=', date('Y-m-d H:i:s',strtotime($jadwal->jam_masuk)))
                                ->first();
                // jika terdapat data
                if($cek_presensi){
                    // Pengecekan belum di checkout
                    $cek_pekerjaan = RiwayatPresensi::where('id_pegawai',$data->id_pegawai)
                        ->whereDate('waktu_in',$hari_ini)
                        ->where('waktu_out','=',NULL)
                        ->first();

                    if($cek_pekerjaan){
                        // cek apakah sudah presensi dengan kartu yang sama
                        $cek_kerja = RiwayatPresensi::where('id_pegawai',$data->id_pegawai)
                                    ->whereDate('waktu_in',$hari_ini)
                                    ->where('waktu_out','=',NULL)
                                    ->where('id_proyek',$data->id_proyek)
                                    ->where('id_pekerjaan',$data->id_pekerjaan)
                                    ->where('id_meta',$data->id_meta)
                                    ->first()
                                    ;

                        if($cek_kerja){

                            return redirect("/presensi-pegawai?ssn=$request->ssn")->with('warning','Anda sudah melakukan presensi di Pekerjaan ini' );

                        }

                        $cek_pekerjaan->waktu_out = Carbon::now();
                        $cek_pekerjaan->save();

                        $data->waktu_in = Carbon::now();
                        $data->save();
                        return redirect('')->with('success','Presensi Pekerjaan Berhasil' );

                    }else{
                        $data->waktu_in = Carbon::now();
                        $data->save();
                        return redirect('')->with('success','Presensi Pekerjaan Berhasil' );
                    }

                // tentukan data hari itu kosong , kalau kosong isi waktu in jam masuk
                }elseif(empty($cek_presensi)){
                    // ini untuk yang telat
                    if(Helper::time_to_int($waktu) > (Helper::time_to_int($jadwal->jam_masuk) + Helper::time_to_int($jadwal->toleransi)) ){
                        $telat = Helper::time2Diff($jadwal->jam_masuk,Carbon::now());
                        
                        $data->waktu_in = Carbon::now();
                        $data->telat    = Helper::time_to_int($telat);
                        $data->save();
                        return redirect('')->with('warning','Presensi Pekerjaan Berhasil, Anda baru melakukan presensi satu kali' );
                    
                    // ini untuk presensi sebelum waktu toleransi telat
                    }else{
                        
                        // waktu in berisi jam masuk normal
                        $data->waktu_in = date('Y-m-d H:i:s',strtotime($hari_ini.$jadwal->jam_masuk));
                        $data->save();
                        return redirect('')->with('success','Presensi Pekerjaan Berhasil' );
                    
                    }

                }

                return $data;
            }

        }else{
            return redirect("/presensi-pegawai?ssn=$request->ssn")->with('danger','Kode Pekerjaan Tidak Terdaftar !' );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RiwayatPresensi  $riwayatPresensi
     * @return \Illuminate\Http\Response
     */
    public function show(RiwayatPresensi $riwayatPresensi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RiwayatPresensi  $riwayatPresensi
     * @return \Illuminate\Http\Response
     */
    public function edit(RiwayatPresensi $riwayatPresensi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RiwayatPresensi  $riwayatPresensi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RiwayatPresensi $riwayatPresensi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RiwayatPresensi  $riwayatPresensi
     * @return \Illuminate\Http\Response
     */
    public function destroy(RiwayatPresensi $riwayatPresensi)
    {
        //
    }

    public function get()
    {
            $jadwal     = JamKerja::select('jam_pulang','jam_mulai_istirahat','jam_selesai_istirahat')->whereDate('tanggal_mulai','<=',Carbon::today()->toDateString())
                ->whereDate('tanggal_akhir','>=',Carbon::today()->toDateString())
                ->where('hari_kerja', 'like', '%'.Helper::day_to_hari(date('D')).'%')
                ->whereTime('jam_masuk', '<=', Carbon::now())
                ->whereTime('jam_pulang', '>=', Carbon::now()->subMinutes(5))
                ->where('default','n')
                ->first()
            ;

            if(!$jadwal){
                // Pengecekan Jadwal Defautl
                $jadwal     = JamKerja::select('jam_pulang','jam_mulai_istirahat','jam_selesai_istirahat')->where('hari_kerja', 'like', '%'.Helper::day_to_hari(date('D')).'%')
                    ->where('default','y')
                    ->whereTime('jam_masuk', '<=', Carbon::now())
                    ->whereTime('jam_pulang', '>=', Carbon::now()->subMinutes(5))
                    ->first()
                ;
            }

        if($jadwal){
            // return $jadwal;
            $hari_ini               = date('Y-m-d');
            $sekarang               = date('H:i');
            $waktu_istirahat        = date('H:i',strtotime($jadwal->jam_mulai_istirahat));
            $waktu_masuk_istirahat  = date('H:i',strtotime($jadwal->jam_selesai_istirahat));
            $waktu_pulang           = date('H:i',strtotime($jadwal->jam_pulang));
                
            // pengecekan waktu sekarang jam istirahan , lalu dicheck out yang masih in dengan jam istirahat
            if($sekarang == $waktu_istirahat){
                // mengecek pegawai
                $data = RiwayatPresensi::where('waktu_out',NULL)
                ->whereDate('waktu_in',date('Y-m-d'))
                ->get();
                // perulangan pegawai
                foreach ($data as $key => $item) {
                    $item->waktu_out = date('Y-m-d H:i:s',strtotime($jadwal->jam_mulai_istirahat));
                    $item->save();
                }
            
            // pengecekan waktu sekarang jam pulang, lalu dicheck out yang masih in dengan jam pulang
            }elseif($sekarang == $waktu_pulang){
                // mengecek pegawai
                $data = RiwayatPresensi::where('waktu_out',NULL)
                ->whereDate('waktu_in',date('Y-m-d'))
                ->get();
                // perulangan pegawai
                foreach ($data as $key => $item) {
                    $item->waktu_out = date('Y-m-d H:i:s',strtotime($jadwal->jam_pulang));
                    $item->save();
                }
            }elseif($sekarang == $waktu_masuk_istirahat){
                $data_istirahat = RiwayatPresensi::whereTime('waktu_out',$jadwal->jam_mulai_istirahat)
                ->whereDate('waktu_out',date('Y-m-d'))
                ->get();

                foreach ($data_istirahat as $v) {
                    $input = new RiwayatPresensi;
                    $input->id_pegawai      = $v->id_pegawai;
                    $input->id_proyek       = $v->id_proyek;
                    $input->id_pekerjaan    = $v->id_pekerjaan;
                    $input->id_meta         = $v->id_meta;
                    $input->id_meta         = $v->id_meta;
                    $input->waktu_in        = date('Y-m-d H:i:s',strtotime($waktu_masuk_istirahat));
                    $input->save();
                }
            }

        }
    }
}
