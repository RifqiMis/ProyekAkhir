@extends('layouts.global')
{{-- @extends('components.notifikasi') --}}

@section('title')
    pegawai
@endsection

@section('content')
    
    <div class="container">
        <div style="margin-bottom:7%">
            <h3 class="text-center">Pegawai</h3>
            <a href="{{url("pegawai/create")}}" class="btn btn-primary  float-right">
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
                    <th scope="col">SSN</th>
                    <th scope="col">Pegawai</th>
                    <th scope="col">Foto</th>
                    <th scope="col">Pilihan</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($pegawais as $iteration => $pegawai)
                    <tr>
                        <td>{{$iteration+1}}</td>
                        <td><?= QrCode::size(80)->generate($pegawai->ssn) ?> </td>
                        <td>{{$pegawai->nama_pegawai}} </td>
                        <td>
                            @if($pegawai->foto)
                            <img src="{{asset('storage/'.$pegawai->foto)}}" class="img-table">
                            @endif
                        </td>
                        <td>
                            <a href="#" value="{{ action('PegawaiController@show',$pegawai->id_pegawai) }}" class="btn btn-xs btn-info modalMd" data-toggle="modal" data-target="#modalMd"><span class="glyphicon glyphicon-eye-open"></span></a>
                            <form action="{{url("pegawai/{$pegawai->id_pegawai}")}}" method="post">
                                <a href="" class="btn btn-outline-info btn-sm" title="Cetak QR">
                                    <i class="fas fa-print"></i>
                                </a>
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
            {{ $pegawais->links() }}
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="modalMd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modalMdTitle"></h4>
                </div>
                <div class="modal-body">
                    <div class="modalError"></div>
                    <div id="modalMdContent"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

