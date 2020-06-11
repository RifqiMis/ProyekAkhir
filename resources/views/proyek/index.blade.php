@extends('layouts.global')
{{-- @extends('components.notifikasi') --}}

@section('title')
    Proyek
@endsection

@section('content')
    <div class="container">
        <div style="margin-bottom:7%">
            <h3 class="text-center">Proyek</h3>
            <a href="{{url("proyek/create")}}" class="btn btn-primary  float-right">
                <i class="fas fa-plus"></i> Tambah Data
            </a>
        </div>

        @include('components.notifikasi')

        {{-- isi konten --}}
        <div class="container">
            <form action="{{ url('proyek') }}" method="GET">
                <div class="row">
                    <div class="col-4">
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
                    <div class="col-4">
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
                    <th scope="col">Kode Kapal</th>
                    <th scope="col">Desain Kapal</th>
                    <th scope="col">Proyek</th>
                    <th scope="col">Status</th>
                    <th scope="col">Pilihan</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($proyeks as $iteration => $proyek)
                    <tr>
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
                            @endif
                        </td>
                        <td>
                            <form action="{{url("proyek/{$proyek->id_proyek}")}}" method="post">
                                <a href="{{url("proyek/{$proyek->id_proyek}")}}" class="btn btn-outline-primary btn-sm" title="Daftar Pekerjaan">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                                <a href="{{url("proyek/{$proyek->id_proyek}/edit")}}" class="btn btn-outline-secondary btn-sm @if ($proyek->status_proyek=='0') {{'d-none'}} @endif " title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-outline-danger btn-sm @if ($proyek->status_proyek=='0') {{'d-none'}} @endif" title="Hapus Permanen" onclick="return confirm('Hapus permanen data ini?')">
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
            {{ $proyeks->links() }}
        </div>
    </div>
@endsection

