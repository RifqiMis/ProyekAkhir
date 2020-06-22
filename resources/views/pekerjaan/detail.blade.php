@extends('layouts.global')
{{-- @extends('components.notifikasi') --}}

@section('title')
    Pekerjaan
@endsection

@section('content')
    <div class="container">
        <div style="">
            <h3 class="text-left">Pekerjaan : {{ $pekerjaan->nama_pekerjaan }}</h3> 
            <div class="float-right">
                <a href="{{route('pekerjaan.index')}}" class="btn btn-sm btn-danger mb-2">kembali</a>
            </div>
            <hr>
            <form action="{{route('pekerjaan-meta.store')}}" method="POST">
                @csrf
                <div class="form-group row">
                    <label class="col-2 col-form-label">Tambah Detail</label>
                    <div class="col-5">
                        <input type="text" class="form-control" name="nama_meta" placeholder="detail pekerjaan" required>
                        <input type="hidden" class="form-control" name="id_pekerjaan" value="{{ $pekerjaan->id_pekerjaan }}">
                    </div>
                    <div class="col-1">
                        <button type="submit" class="btn btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
        <h3>Detail {{ $pekerjaan->nama_pekerjaan }} </h3>
        <br>
        @include('components.notifikasi')
        {{-- isi konten --}}
        <div class="container">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Detail ekerjaan</th>
                    <th scope="col">Pilihan</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($details as $iteration => $v)
                    <tr>
                        <td>{{$iteration+1}}</td>
                        <td>{{$v->nama_meta}} </td>
                        <td>
                            <form action="{{url("pekerjaan-meta/{$v->id_meta}")}}" method="post">
                                <a href="{{url("pekerjaan-meta/{$v->id_meta}/edit")}}" class="btn btn-outline-secondary btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $details->links() }}
        </div>
    </div>
@endsection

