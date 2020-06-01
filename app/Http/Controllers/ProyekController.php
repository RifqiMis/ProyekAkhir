<?php

namespace App\Http\Controllers;

use App\Proyek;
use App\Pekerjaan;
use App\RiwayatPekerjaan;
use App\PekerjaanMeta;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProyekController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $proyek = Proyek::paginate(10);
        return view('proyek.index', ['proyeks' => $proyek]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('proyek.add');
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
            'id_proyek' => 'required|unique:proyek',
            'deskripsi_proyek' => 'required|unique:proyek',
        ]);
  
        $data = new Proyek;
        $data->id_proyek = $request->id_proyek;
        $data->deskripsi_proyek = $request->deskripsi_proyek;
        $data->status_proyek = '1';
        if($request->file('foto')){
            $file = $request->file('foto')->storeAs(
                'foto_proyek', $request->id_proyek.'.'.$request->file('foto')->extension(),'public'
            );
            $data->foto = $file;
        }

        $data->save();
        return redirect()->route('proyek.index')->with('success','Berhasil menambahkan proyek : ' . $request->deskripsi_proyek );
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
        $pekerjaan  = Pekerjaan::all();
        $details    = RiwayatPekerjaan::where('id_proyek',$id)->paginate(10);
        return view('proyek.detail', compact(['proyek','pekerjaan','details']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Proyek::findOrFail($id);

        return view('proyek.edit',['proyek' => $data]);
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
        $this->validate($request, [
            'deskripsi_proyek' => [
                'required',
            ],
            'status_proyek' => 'required|max:1|',
            'id_proyek' => [
                'required',
                Rule::unique('proyek')->ignore($id,'id_proyek','deskripsi_proyek')
            ],
        ]);
  
        $data = Proyek::find($id);
        $data->deskripsi_proyek = $request->deskripsi_proyek;
        $data->status_proyek = $request->status_proyek;
        $new_foto = $request->file('foto');
        if($new_foto){
            if($data->foto && file_exists(storage_path('app/public/' .$data->foto))){
                \Storage::delete('public/'. $data->foto);
            }
            $new_foto_path = $new_foto->storeAs(
                'foto_proyek', $id.'.'.$request->file('foto')->extension(),'public'
            );
            $data->foto = $new_foto_path;
        }

        $data->save();
  
        return redirect()->route('proyek.index')->with('success','Berhasil mengubah proyek : ' . $request->deskripsi_proyek );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $proyek = Proyek::findOrFail($id);
        if($proyek->foto && file_exists(storage_path('app/public/' .$proyek->foto))){
            \Storage::delete('public/'. $proyek->foto);
        }
        $proyek->delete();

        return redirect()->route('proyek.index')->with('success','Berhasil Menghapus proyek');
    }
}
