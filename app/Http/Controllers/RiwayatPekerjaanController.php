<?php

namespace App\Http\Controllers;

use App\RiwayatPekerjaan;
use Illuminate\Http\Request;

class RiwayatPekerjaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Browsershot::html('<h1>Hello world!!</h1>')->save('example.pdf');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cek = RiwayatPekerjaan::where([
            ['id_proyek',$request->id_proyek],
            ['id_pekerjaan',$request->id_pekerjaan],
            ['id_meta',$request->id_meta]
        ])->count();
        if($cek){
            return redirect('proyek/'.$request->id_proyek)->with('danger','Pekerjaan sudah ada, silahkan cek tabel dibawah' );
        }else{
            $data = new RiwayatPekerjaan;
            $data->id_proyek    = $request->id_proyek;
            $data->id_pekerjaan = $request->id_pekerjaan;
            $data->id_meta      = $request->id_meta;
           
            $data->save();
            return redirect('proyek/'.$request->id_proyek)->with('success','Berhasil menambahkan pekerjaan' );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Request $request, $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $data = RiwayatPekerjaan::find($id);
        return view('riwayatPekerjaan.show',compact('data'))->renderSections()['content'];

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RiwayatPekerjaan  $riwayatPekerjaan
     * @return \Illuminate\Http\Response
     */
    public function edit(RiwayatPekerjaan $riwayatPekerjaan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RiwayatPekerjaan  $riwayatPekerjaan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RiwayatPekerjaan $riwayatPekerjaan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RiwayatPekerjaan  $riwayatPekerjaan
     * @return \Illuminate\Http\Response
     */
    public function destroy(RiwayatPekerjaan $riwayatPekerjaan)
    {
        //
    }
}
