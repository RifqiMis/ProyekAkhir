@extends('layouts.global')
{{-- @extends('components.notifikasi') --}}

@section('title')
    Jam Kerja
@endsection

@section('content')
<div id="laporan">
    <div class="container">
        <div style="margin-bottom:7%">
            <h3 class="text-center">Pengaturan Jam Kerja</h3>
            <button onclick="printDiv('laporan')" class="float-right btn btn-info d-print-none ml-2" title="Cetak Data"><i class="fa fa-print"></i></button>
            <a href="{{route("jam-kerja.create")}}" class="btn btn-primary float-right d-print-none">
                <i class="fas fa-plus"></i> Tambah Data
            </a>
        </div>
        <div class="d-none d-print-block text-right font-italic">Tanggal Cetak : {{Helper::tanggal_idn(now())}}</div>
        <br>
        @include('components.notifikasi')

        {{-- isi konten --}}
        <div class="container">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Keterangan</th>
                    <th scope="col">Hari Kerja</th>
                    <th scope="col">Pilihan</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($jamkerjas as $iteration => $jamkerja)
                    <tr>
                        <td>{{$jamkerjas->firstItem() + $it}}</td>
                        <td>
                            @if ($jamkerja->default == 'y')
                                <div class="badge badge-success">
                                    {{ 'Default' }}
                                </div>
                                {{$jamkerja->keterangan}}
                            @elseif ($jamkerja->default == 'n')
                                {{$jamkerja->keterangan . ' ( ' . $jamkerja->tanggal_mulai  . ' sd ' . $jamkerja->tanggal_akhir . ' )' }}
                            @endif
                        </td>
                        <td>{{Helper::hari($jamkerja->hari_kerja)}}</td>
                        <td>
                            <form action="{{url("jam-kerja/{$jamkerja->id_jam_kerja}")}}" method="post">
                                <a href="{{url("jam-kerja/{$jamkerja->id_jam_kerja}/edit")}}" class="btn btn-outline-secondary btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-outline-danger btn-sm" title="Hapus Permanen" onclick="return confirm('Hapus permanen data ini?')" @if ($jamkerja->default == "y") {{'disabled'}} @endif>
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
                {{ $jamkerjas->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

