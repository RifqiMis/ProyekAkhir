@extends('layouts.global')
{{-- @extends('components.notifikasi') --}}

@section('title')
    Pekerjaan
@endsection

@section('content')
    <div class="container">
        <div style="margin-bottom:7%">
            <h3 class="text-center">Master Pekerjaan</h3>
            <a href="{{url("pekerjaan/create")}}" class="btn btn-primary  float-right">
                <i class="fas fa-plus"></i> Tambah Data
            </a>
        </div>

        @include('components.notifikasi')

        {{-- isi konten --}}
        <div class="container">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Pekerjaan</th>
                    <th scope="col">Pilihan</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($pekerjaans as $iteration => $pekerjaan)
                    <tr>
                        <td>{{$iteration+1}}</td>
                        <td>{{$pekerjaan->nama_pekerjaan}} </td>
                        <td>
                            <form action="{{url("pekerjaan/{$pekerjaan->id_pekerjaan}")}}" method="post">
                                <a href="{{url("pekerjaan/{$pekerjaan->id_pekerjaan}")}}" class="btn btn-outline-info btn-sm" title="Tambah Detail">
                                    <i class="fas fa-plus"></i>
                                </a>
                                <a href="{{url("pekerjaan/{$pekerjaan->id_pekerjaan}/edit")}}" class="btn btn-outline-secondary btn-sm" title="Edit">
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
            {{ $pekerjaans->links() }}
        </div>
    </div>
@endsection

