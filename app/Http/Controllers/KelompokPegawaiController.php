<?php

namespace App\Http\Controllers;

use App\KelompokPegawai;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelompokPegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kelompok_pegawai = KelompokPegawai::paginate(10);
        return view('kelompokPegawai.index', ['kelompok_pegawais' => $kelompok_pegawai]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kelompokPegawai.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nama_kelompok_pegawai' => 'required|unique:kelompok_pegawai',
        ]);
  
        $data = new KelompokPegawai;
        $data->nama_kelompok_pegawai = $request->nama_kelompok_pegawai;
        $data->save();
  
        return redirect()->route('kelompok_pegawai.index')->with('success','Berhasil menambahkan jabatan : ' . $request->nama_kelompok_pegawai );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KelompokPegawai  $kelompokPegawai
     * @return \Illuminate\Http\Response
     */
    public function show(KelompokPegawai $kelompokPegawai)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KelompokPegawai  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = KelompokPegawai::findOrFail($id);

        return view('kelompokPegawai.edit',['kelompok_pegawai' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KelompokPegawai  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama_kelompok_pegawai' => [
                'required',
                Rule::unique('kelompok_pegawai')->ignore($id,'id_kelompok_pegawai')
            ],
        ]);
  
        $data = KelompokPegawai::find($id);
        $data->nama_kelompok_pegawai = $request->nama_kelompok_pegawai;
        $data->save();
  
        return redirect()->route('kelompok_pegawai.index')->with('success','Berhasil mengubah jabatan : ' . $request->nama_kelompok_pegawai );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KelompokPegawai  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kelompok_pegawai = KelompokPegawai::findOrFail($id);
        $kelompok_pegawai->delete();

        return redirect()->route('kelompok_pegawai.index')->with('success','Berhasil Menghapus Jabatan');
    }
}
