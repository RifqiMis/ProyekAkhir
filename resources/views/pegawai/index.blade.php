@extends('layouts.global')
{{-- @extends('components.notifikasi') --}}

@section('title')
    pegawai
@endsection

@section('content')

<div id="laporan">
    <div class="container">
        <div style="margin-bottom:7%">
            <center>
                <img src="{{asset('storage/lundin.png')}}" class="d-none d-print-block" alt="" style="width: 100px;">
            </center>
            <h3 class="text-center">Pegawai</h3>
            <a href="{{url("pegawai/create")}}" class="btn btn-primary float-right d-print-none ">
                <i class="fas fa-plus"></i> Tambah Data
            </a>
        </div>

        @include('components.notifikasi')

        {{-- isi konten --}}
        <div class="container">
            <form action="{{ url('pegawai') }}" method="GET">
                <div class="row d-print-none">
                    <div class="col-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <label class="input-group-text" for="inputGroupSelect01">Jabatan</label>
                            </div>
                            <select class="custom-select" id="inputGroupSelect01" name="id_jabatan">
                              <option value="">Semua</option>
                              @foreach ($jabatans as $key => $val)
                                <option value="{{ $val->id_jabatan }}" {{ $val->id_jabatan == $input->id_jabatan ? 'selected' : '' }}>{{ $val->nama_jabatan }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <label class="input-group-text" for="inputGroupSelect01">Kelompok</label>
                            </div>
                            <select class="custom-select" id="inputGroupSelect01" name="id_kelompok">
                                <option value="">Semua</option>
                                @foreach ($kelompoks as $key => $val)
                                    <option value="{{ $val->id_kelompok_pegawai }}" {{ $val->id_kelompok_pegawai == $input->id_kelompok ? 'selected' : '' }}>{{ $val->nama_kelompok_pegawai }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                        <input type="text" name="cari" class="form-control" placeholder="Cari Proyek" value="@if (!empty($input->cari)){{$input->cari}}@endif">
                        </div>
                    </div>
                    <div class="col-2">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                        <button onclick="printDiv('laporan')" class="float-right btn btn-info d-print-none ml-2" title="Cetak Data"><i class="fa fa-print"></i></button>
                    </div>                    
                </div>
            </form>
            <div class="d-none d-print-block text-right font-italic">Tanggal Cetak : {{Helper::tanggal_idn(now())}}</div>
            <br>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">SSN</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Jabatan</th>
                    <th scope="col">Kelompok Kerja</th>
                    <th scope="col">Foto</th>
                    <th scope="col" class="d-print-none">Pilihan</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($pegawais as $it => $pegawai)
                    <tr>
                        <td>{{$pegawais->firstItem() + $it}}</td>
                        <td>{{$pegawai->ssn}} </td>
                        <td>{{$pegawai->nama_pegawai}} </td>
                        <td> 
                            {{$pegawai->jabatan->nama_jabatan}}
                        </td>
                        <td> 
                            {{$pegawai->kelompok->nama_kelompok_pegawai}}
                        </td>
                        <td>
                            @if($pegawai->foto)
                            <img src="{{asset('storage/'.$pegawai->foto)}}" class="img-table">
                            @endif
                        </td>
                        <td class="d-print-none">
                            <form action="{{url("pegawai/{$pegawai->id_pegawai}")}}" method="post">
                                <a href="#" value="{{ action('PegawaiController@show',$pegawai->id_pegawai) }}" class="btn btn-sm btn-outline-info modalMd" data-toggle="modal" data-target="#modalMd" title="Kartu Pegawai"><i class="fas fa-print"></i></a>
                                {{-- <a href="{{url("pegawai/{$pegawai->id_pegawai}")}}" class="btn btn-outline-info btn-sm" title="Lihat QR Code Pegawai">
                                    <i class="fas fa-print"></i>
                                </a> --}}
                                <a href="{{url("pegawai/{$pegawai->id_pegawai}/edit")}}" class="btn btn-outline-secondary btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-outline-danger btn-sm" title="Hapus Permanen" onclick="return confirm('Hapus permanen data ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row d-print-none">
                {{ $pegawais->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

