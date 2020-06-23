<?php

namespace App\Http\Controllers;

use App\Pegawai;
use App\RiwayatPresensi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RiwayatPresensiController extends Controller
{
        
    public function terlambat(Request $request)
    {

        $data = RiwayatPresensi::where('telat','!=',0)
        ->join('pegawai', 'pegawai.id_pegawai', '=', 'riwayat_presensi.id_pegawai')
        ->orderBy('pegawai.nama_pegawai','ASC');
        if($request->has('tanggal')){
            $data->whereDate('waktu_in',$request->tanggal);
        }
        if($request->get('kelompok')){
            $data = $data->where('pegawai.id_kelompok',$request->kelompok);
        }

        if($request->get('query')){
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $data   = $data->Where('pegawai.nama_pegawai', 'like', '%'.$query.'%');
        }

        if($request->get('jabatan')){
            $data = $data->where('pegawai.id_jabatan',$request->jabatan);
        }

        if($request->num != NULL){
            $data = $data->paginate($request->num);
        }elseif($request->num == NULL) {
            $data = $data->get();
        }

        if(count($data)>0)
            return view('beranda.terlambat',compact('data'))->renderSections()['content'];
        else
            return view('beranda.terlambat',compact('data'))->renderSections()['none'];

    }

    public function absen(Request $request)
    {

        $data;

        $pegawai = RiwayatPresensi::select('id_pegawai')
            ->whereDate('waktu_in',$request->tanggalAbsen)
            ->groupBy('id_pegawai')
            ->get();

        if(count($pegawai)==0)
            return '<tr><td colspan="4">Data Kosong</td></tr>';

        foreach ($pegawai as $key => $value) {
            $id_pegawai[$key] = $value->id_pegawai;
        }

        $data = Pegawai::whereNotIn('id_pegawai',$id_pegawai);

        if($request->get('kelompok')){
            $data = $data->where('pegawai.id_kelompok',$request->kelompok);
        }

        if($request->get('query')){
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $data   = $data->Where('pegawai.nama_pegawai', 'like', '%'.$query.'%');
        }

        if($request->get('jabatan')){
            $data = $data->where('pegawai.id_jabatan',$request->jabatan);
        }

        if($request->num != NULL){
            $data = $data->paginate($request->num);
        }elseif($request->num == NULL) {
            $data = $data->get();
        }

        if(count($data)>0)
            return view('beranda.absen',compact('data'))->renderSections()['content'];
        else
            return view('beranda.absen',compact('data'))->renderSections()['none'];

    }

    public function pengerjaanHariIni(Request $request)
    {

        $data = RiwayatPresensi::whereDate('waktu_in',Date('Y-m-d'))
            ->groupBy('id_proyek','id_pekerjaan','id_meta')
            ->paginate(4);

        if(count($data)>0)
            return view('beranda.tabel',compact('data'))->renderSections()['content'];
        else
            return view('beranda.tabel',compact('data'))->renderSections()['none'];

    }

    public function akumulasi(Request $request)
    {
        $waktu = Carbon::now();

        $pegawai = RiwayatPresensi::select('id_pegawai')
            ->whereDate('waktu_in',$waktu)
            ->groupBy('id_pegawai')
            ->get();

        if(count($pegawai)==0){
            $data[0] = 0;
        }else{
            foreach ($pegawai as $key => $value) {
                $id_pegawai[$key] = $value->id_pegawai;
            }
            $data[0] = Pegawai::whereNotIn('id_pegawai',$id_pegawai)
                ->get()
                ->count();
        }

        $data[1] = RiwayatPresensi::where('telat','!=',0)
            ->whereDate('waktu_in',$waktu)
            ->get()
            ->count();
        $data[2] = RiwayatPresensi::where('waktu_out',NULL)
            ->whereDate('waktu_in',$waktu)
            ->get()
            ->count();
        $data[3] = RiwayatPresensi::where('waktu_out','!=',NULL)
            ->whereDate('waktu_in',$waktu)
            ->get()
            ->count();

        return json_encode($data);

    }

    public function akumulasiPegawai(Request $request)
    {
        $waktu = Carbon::now();
        $data['total'] = Pegawai::all()->count();

        $pegawai = RiwayatPresensi::select('id_pegawai')
            ->whereDate('waktu_in',$waktu)
            ->groupBy('id_pegawai')
            ->get();

        if(count($pegawai)==0){
            $data['presensi'][0] = Pegawai::all()->count();
            $data['presensi'][1] = 0;
            return $data;
        }else{
            foreach ($pegawai as $key => $value) {
                $id_pegawai[$key] = $value->id_pegawai;
            }
            $data['presensi'][0] = Pegawai::whereNotIn('id_pegawai',$id_pegawai)
                ->get()
                ->count();
            $data['presensi'][1] = $data['total'] - $data['presensi'][0];
        }

        // data terlambat
        // $data['presensi'][1] = RiwayatPresensi::where('telat','!=',0)
        //     ->whereDate('waktu_in',$waktu)
        //     ->get()
        //     ->count();
        return json_encode($data);

    }
}
