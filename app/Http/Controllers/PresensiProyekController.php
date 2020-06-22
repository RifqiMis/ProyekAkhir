<?php

namespace App\Http\Controllers;
use App\RiwayatPresensi;
use Illuminate\Http\Request;
use App\Proyek;
use App\Pekerjaan;
use App\PekerjaanMeta;
use App\KelompokPegawai;

class PresensiProyekController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kelompok = KelompokPegawai::all();
        $proyek = Proyek::all();
        return view('presensi.laporan',compact(['kelompok','proyek']));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ongoing()
    {
        $ongoing = RiwayatPresensi::where('waktu_out',NULL)->get();
        return view('presensi.ongoing',compact(['ongoing']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $proyek = Proyek::where('status_proyek',1)->get();
        return view('presensi.add',compact(['proyek']));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        $this->validate($request, [
            'id_pegawai' => 'required',
        ]);

        $data = new RiwayatPresensi;
        $data->id_proyek = $request->id_proyek;
        $data->id_pegawai = $request->id_pegawai;
        $data->id_meta = $request->id_meta;
        $data->id_pekerjaan = $request->id_pekerjaan;
        $data->waktu_in = date('Y-m-d H:i:s',strtotime($request->tanggal.' '.$request->jam_mulai));
        $data->waktu_out = date('Y-m-d H:i:s',strtotime($request->tanggal.' '.$request->jam_akhir));

        $data->save();

        return redirect()->route('presensi-proyek.index')->with('success','Berhasil menambahkan data ');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = RiwayatPresensi::findOrFail($id);

        return view('presensi.edit',['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
  
        $data = RiwayatPresensi::find($id);
        $data->waktu_in = date('Y-m-d H:i:s',strtotime($request->tanggal.' '.$request->jam_mulai));
        $data->waktu_out = date('Y-m-d H:i:s',strtotime($request->tanggal.' '.$request->jam_akhir));
        $data->telat = $request->telat;

        $data->save();
  
        return redirect()->route('presensi-proyek.index')->with('success','Berhasil mengubah presensi');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $proyek = RiwayatPresensi::findOrFail($id);
        $proyek->delete();

        return redirect()->route('presensi-proyek.index')->with('success','Berhasil Menghapus data');
    }

    public function laporan(Request $request)
    {

        $data = RiwayatPresensi::where('waktu_out','!=',NULL)
        ->join('pegawai', 'pegawai.id_pegawai', '=', 'riwayat_presensi.id_pegawai')
        ->orderBy('pegawai.nama_pegawai','ASC');
        if($request->has('tanggal')){
            $data->whereDate('waktu_in',$request->tanggal);
        }
        $data = $data->get();

        return view('presensi.tabel',compact('data'))->renderSections()['content'];

    }

    public function laporanPegawai(Request $request)
    {

        $data = RiwayatPresensi::where('waktu_out','!=',NULL)
        ->join('pegawai', 'pegawai.id_pegawai', '=', 'riwayat_presensi.id_pegawai')
        ->orderBy('pegawai.nama_pegawai','ASC');
        if($request->has('tanggal')){
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $data->whereDate('waktu_in',$request->tanggal)
            ->Where('pegawai.nama_pegawai', 'like', '%'.$query.'%');
        }

        if($request->kelompok != 0){
            $data = $data->where('pegawai.id_kelompok',$request->kelompok);
        }

        if($request->proyek != 0){
            $data = $data->where('id_proyek',$request->proyek);
        }

        if($request->pekerjaan != 0){
            $data = $data->where('id_pekerjaan',$request->pekerjaan);
        }

        if($request->meta != 0){
            $data = $data->where('id_meta',$request->meta);
        }

        if($request->num != NULL){
            $data = $data->paginate($request->num);
        }elseif($request->num == NULL) {
            $data = $data->get();
        }

        return view('presensi.tabel',compact('data'))->renderSections()['content'];

    }
}
