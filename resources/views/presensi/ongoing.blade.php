@extends('layouts.beranda')
{{-- @extends('components.notifikasi') --}}

@section('title')
    proyek
@endsection

@section('content')

    <div class="bg-white shadow-sm mt-5">
        <div id="sedang-kerja">
            <br>
            <div class="container mt-4 mb-5">
                <div>
                    <center>
                        <img src="{{asset('storage/lundin.png')}}" class="d-none d-print-block" alt="" style="width: 100px;">
                    </center>
                    <h4 class="input text-center mb-0 mt-2">Daftar Pegawai yang Sedang Bekerja</h4>
                    <h3  class="text-center">Departemen Produksi</h3>
                </div>
                <br>
                {{-- isi terlambat --}}
                <div class="d-none d-print-block text-right font-italic">Tanggal Cetak : {{Helper::tanggal_idn(now())}}</div>
                <div class="container">
                    <form action="{{ url('home') }}" method="get">
                        <div class="row">
                            <div class="col-12">
                                <button onclick="printDiv('sedang-kerja')" class="float-right btn btn-info d-print-none ml-2" title="Cetak Data"><i class="fa fa-print"></i></button>
                            </div>
                        </div>
                    </form>
                    <br>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th scope="col">SSN</th>
                            <th scope="col">Nama Pegawai</th>
                            <th scope="col">Proyek</th>
                            <th scope="col">Pekerjaan</th>
                            <th scope="col">Waktu Presensi</th>
                            <th scope="col" class="d-print-none">Opsi</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($ongoing as $d)
                            <tr>
                                <td>{{ $d->pegawai->ssn }}</td>
                                <td>{{ $d->pegawai->nama_pegawai }}</td>
                                <td>{{ $d->proyek->deskripsi_proyek }}</td>
                                <td>{{ $d->pekerjaan->nama_pekerjaan.' '.$d->pekerjaanMeta->nama_meta }}</td>
                                <td> {{ $d->waktu_in }} </td>
                                <td scope="col" class="d-print-none">
                                    <form action="{{url("presensi-proyek/{$d->id_presensi}")}}" method="post">
                                        <a href="{{url("presensi-proyek/{$d->id_presensi}/edit")}}" class="btn btn-outline-secondary btn-sm" title="Edit Waktu">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if (Auth::user()->role == 'super admin')
                                        <button class="btn btn-outline-danger btn-sm" title="Hapus Permanen" onclick="return confirm('Hapus permanen data ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        {{ method_field('DELETE') }}
                                        @endif
                                        {{ csrf_field() }}
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
        </div>
    </div>    
    
@endsection

