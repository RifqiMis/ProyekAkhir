@extends('layouts.global')
{{-- @extends('components.notifikasi') --}}

@section('title')
    Proyek
@endsection

@section('content')
<div id="laporan">
    <div class="container">
        <div style="margin-bottom:5%">
            <center>
                <img src="{{asset('storage/lundin.png')}}" class="d-none d-print-block" alt="" style="width: 100px;">
            </center>
            <h3 class="text-center">Proyek</h3>
            <button onclick="printDiv('laporan')" class="float-right btn btn-info d-print-none ml-2" title="Cetak Data"><i class="fa fa-print"></i></button>
            @if (Auth::user()->role=='admin')
            <a href="{{url("proyek/create")}}" class="btn btn-primary float-right d-print-none">
                <i class="fas fa-plus"></i> Tambah Data
            </a>
            @endif
            <div class="d-none d-print-block text-right font-italic">Tanggal Cetak : {{Helper::tanggal_idn(now())}}</div>
        </div>

        @include('components.notifikasi')

        {{-- isi konten --}}
        <div class="container">
            <form action="{{ url('proyek') }}" method="GET">
                <div class="row d-print-none">
                    <div class="col-2">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <label class="input-group-text" for="inputGroupSelect01">Status</label>
                            </div>
                            <select class="custom-select" id="inputGroupSelect01" name="status">
                              <option value="">Semua</option>
                              <option @if ($input->status=='1'){{'selected'}}@endif value="1">Dikerjakan</option>
                              <option @if ($input->status=='0'){{'selected'}}@endif value="0">Selesai</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Tanggal </span>
                            </div>
                                <input type="date" name="tanggal_mulai" id="tanggal" class="form-control" aria-describedby="basic-addon1" value="@if (!empty($input->tanggal_mulai)){{$input->tanggal_mulai}}@endif">
                                <input type="date" name="tanggal_akhir" id="tanggal" class="form-control" aria-describedby="basic-addon1" value="@if (!empty($input->tanggal_akhir)){{$input->tanggal_akhir}}@endif">
                            </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                        <input type="text" name="cari" class="form-control" placeholder="Cari Proyek" value="@if (!empty($input->cari)){{$input->cari}}@endif">
                        </div>
                    </div>
                    <div class="col-2">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                    </div>                    
                </div>
            </form>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">Tanggal Mulai</th>
                    <th scope="col">Kode Kapal</th>
                    <th scope="col">Desain Kapal</th>
                    <th scope="col">Proyek</th>
                    <th scope="col">Status</th>
                    @if (Auth::user()->role=='admin')
                        <th scope="col" class="d-print-none">Pilihan</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                    @foreach($proyeks as $iteration => $proyek)
                    <tr>
                        <td>{{Helper::tanggal_idn($proyek->created_at)}}</td>
                        <td>{{$proyek->id_proyek}}</td>
                        <td>
                            @if($proyek->foto)
                            <img src="{{asset('storage/'.$proyek->foto)}}" class="img-table">
                            @endif
                        </td>
                        <td>{{$proyek->deskripsi_proyek}} </td>
                        <td> 
                            @if ($proyek->status_proyek=='1')
                                <div class="badge badge-success">
                                    {{ 'Pengerjaan' }}
                                </div>
                            @else
                                <div class="badge badge-secondary">
                                    {{ 'Selesai' }}
                                </div>
                                <br>
                                {{ 'Tanggal :'.Helper::tanggal_idn($proyek->tanggal_selesai) }}
                                <br>
                                {{ 'Lama : '.Helper::day2Diff($proyek->created_at,$proyek->tanggal_selesai) }}
                            @endif
                        </td>
                    @if (Auth::user()->role=='admin'||Auth::user()->role=='super admin')
                        <td class="d-print-none">
                            <form action="{{url("proyek/{$proyek->id_proyek}")}}" method="post">
                                <a href="{{url("proyek/{$proyek->id_proyek}")}}" class="btn btn-outline-primary btn-sm" title="Daftar Pekerjaan">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                                <a href="{{url("proyek/{$proyek->id_proyek}/edit")}}" class="btn btn-outline-secondary btn-sm @if ($proyek->status_proyek=='0') {{'d-none'}} @endif " title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if (Auth::user()->role == 'super admin')
                                <button class="btn btn-outline-danger btn-sm @if ($proyek->status_proyek=='0') {{'d-none'}} @endif" title="Hapus Permanen" onclick="return confirm('Hapus permanen data ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                @endif
                            </form>
                        </td>
                    @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-print-none">
                {{ $proyeks->links() }}
            </div>
        </div>
    </div>
</div>
    
@endsection

