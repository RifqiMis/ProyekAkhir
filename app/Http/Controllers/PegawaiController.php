<?php

namespace App\Http\Controllers;

use App\Pegawai;
use App\Jabatan;
use App\KelompokPegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PegawaiController extends Controller
{

    // public function __construct(){
    //     $this->middleware(function ($request, $next) {
    //         if(Gate::allows('admin-role')||Gate::allows('manajer-role')) return $next($request);
    //         // abort(403, 'Anda tidak memiliki hak akses');
    //         abort(redirect()->route('home'));
    //     });
    // }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $jabatans = Jabatan::all();
        $kelompoks = KelompokPegawai::all();
        $pegawai = Pegawai::orderBy('nama_pegawai','ASC');
        if(!empty($request->cari)){
            $pegawai = $pegawai->where('nama_pegawai','like',"%$request->cari%");
        }
        if($request->id_kelompok != ''){
            $pegawai = $pegawai->where('id_kelompok',$request->id_kelompok);    
        }
        if($request->id_jabatan != ''){
            $pegawai = $pegawai->where('id_jabatan',$request->id_jabatan);    
        }
        $pegawai = $pegawai->paginate(10);
        return view('pegawai.index', ['pegawais' => $pegawai->appends(['id_kelompok' => $request->id_kelompok,'id_jabatan' => $request->id_jabatan,'cari' => $request->cari]),'input' => $request,'jabatans' => $jabatans,'kelompoks' => $kelompoks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jabatan    = Jabatan::all();
        $kelompok   = KelompokPegawai::all(); 
        return view('pegawai.add',['jabatans' => $jabatan,'kelompoks' => $kelompok]);
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
            'nama_pegawai'  => 'required|unique:pegawai',
            'ssn'           => 'required|unique:pegawai',
            'foto'          => 'required',
        ]);
  
        $data = new Pegawai;
        $data->nama_pegawai     = $request->nama_pegawai;
        $data->jenis_kelamin    = $request->jenis_kelamin;
        $data->ssn              = $request->ssn;
        $data->id_jabatan       = $request->id_jabatan;
        $data->id_kelompok      = $request->id_kelompok;
        $extension              = $request->file('foto')->extension();

        if($request->file('foto')){
            $file = $request->file('foto')->storeAs(
                'foto_pegawai', $request->ssn.'.'.$request->file('foto')->extension(),'public'
            );
            $data->foto = $file;
        }
        $data->save();
  
        return redirect()->route('pegawai.index')->with('success','Berhasil menambahkan pegawai : ' . $request->nama_pegawai );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $data = Pegawai::find($id);
        return view('pegawai.show',compact('data'))->renderSections()['content'];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Pegawai::findOrFail($id);
        $jabatan    = Jabatan::all();
        $kelompok   = KelompokPegawai::all();

        return view('pegawai.edit',['pegawai' => $data,'jabatans' => $jabatan,'kelompoks' => $kelompok]);
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
            'nama_pegawai' => [
                'required',
                Rule::unique('pegawai')->ignore($id,'id_pegawai')
            ],
            'ssn' => [
                'required',
                Rule::unique('pegawai')->ignore($id,'id_pegawai')
            ],
        ]);
  
        $data = Pegawai::find($id);
        $data->nama_pegawai = $request->nama_pegawai;
        $data->jenis_kelamin    = $request->jenis_kelamin;
        $data->ssn              = $request->ssn;
        $data->id_jabatan       = $request->id_jabatan;
        $data->id_kelompok      = $request->id_kelompok;

        $new_foto = $request->file('foto');
        if($new_foto){
            if($data->foto && file_exists(storage_path('app/public/' .$data->foto))){
                \Storage::delete('public/'. $data->foto);
            }
            $new_foto_path = $new_foto->storeAs(
                'foto_pegawai', $request->ssn.'.'.$request->file('foto')->extension(),'public'
            );
            $data->foto = $new_foto_path;
        }
        
        $data->save();
  
        return redirect()->route('pegawai.index')->with('success','Berhasil mengubah pegawai : ' . $request->nama_pegawai );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);

        if($pegawai->foto)
        Storage::delete('public/'.$pegawai->foto);

        $pegawai->delete();

        return redirect()->route('pegawai.index')->with('success','Berhasil Menghapus pegawai');
    }

    // Ajax Cari
    public function cari(Request $request){
        $cari = $request->cari;
        // $cari ='ana';
        $data = Pegawai::whereHas('jabatan',function($q) use ($cari){
            $q->where('nama_pegawai','like',"%$cari%")
              ->orWhere('ssn','like',"%$cari%")
              ->orWhere('nama_jabatan','like',"%$cari%");
        })->get();

        // return $data;
        return view('pegawai.cari',compact('data'))->renderSections()['content'];
    }
}
