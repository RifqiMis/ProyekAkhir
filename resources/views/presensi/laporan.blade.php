@extends('layouts.beranda')
{{-- @extends('components.notifikasi') --}}

@section('title')
    proyek
@endsection

@section('content')
    
<div class="bg-white shadow-sm">
    <div id="laporan">
        <br>
        <div class="container mt-4 mb-5">
            <div>
                <center>
                    <img src="{{asset('storage/lundin.png')}}" class="d-none d-print-block" alt="" style="width: 100px;">
                </center>
                <h4 class="input text-center mb-0 mt-2">Laporan Harian Presensi Proyek</h4>
                <h3  class="text-center">Departemen Produksi</h3>
            </div>
            <br>
            {{-- isi terlambat --}}
            <div class="container">
                <form action="{{ url('home') }}" method="get">
                    <div class="row">
                        <div class="col-4">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">Tanggal </span>
                                </div>
                                <input type="date" name="tanggal" class="form-control" aria-describedby="basic-addon1" value="{{ date('Y-m-d') }}" onchange="handler(tanggal);">
                                <input type="hidden" name="hari_ini" value="{{ date('Y-m-d') }}">
                                </div>
                        </div>
                        <div class="col-8">
                            <button onclick="printDiv('laporan')" class="float-right btn btn-info d-print-none ml-2" title="Cetak Data"><i class="fa fa-print" style="@media print{ display:none;}"></i></button>
                            @if (Auth::user()->role=='admin')
                                <a href="{{url("presensi-proyek/create")}}" id="tambah_data" class="btn btn-primary float-right d-print-none">
                                    <i class="fas fa-plus"></i> Tambah Data
                                </a>
                            @endif
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
                        <th scope="col">Waktu kerja</th>
                        <th scope="col">Total Waktu</th>
                        @if (Auth::user()->role=='admin')
                            <th scope="col" class="d-print-none">Opsi</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody id="hasil-data">
                    </tbody>
                </table>
            </div>
        </div>
        <br>
    </div>
</div>

@if (Auth::user()->role=='admin')
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
                <div class="container">
                    <form action="{{ url('home') }}" method="get">
                        <div class="row">
                            <div class="col-4">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">Tanggal </span>
                                    </div>
                                    <input type="date" name="tanggal" class="form-control" aria-describedby="basic-addon1" value="{{ date('Y-m-d') }}" onchange="handler(tanggal);">
                                    <input type="hidden" name="hari_ini" value="{{ date('Y-m-d') }}">
                                    </div>
                            </div>
                            <div class="col-8">
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
                                <td>{{ $d->id_proyek }}</td>
                                <td>{{ $d->pegawai->nama_pegawai }}</td>
                                <td>{{ $d->proyek->deskripsi_proyek }}</td>
                                <td>{{ $d->pekerjaan->nama_pekerjaan.' '.$d->pekerjaanMeta->nama_meta }}</td>
                                <td> {{ $d->waktu_in }} </td>
                                <td scope="col" class="d-print-none">
                                    <form action="{{url("presensi-proyek/{$d->id_presensi}")}}" method="post">
                                        <a href="{{url("presensi-proyek/{$d->id_presensi}/edit")}}" class="btn btn-outline-secondary btn-sm" title="Edit Waktu">
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
                </div>
            </div>
            <br>
        </div>
    </div>
@endif
    
    
<script type="application/javascript">

    $(document).ready(function() {

        // Dapatkan tanggal diawal load
        var tanggal = $('input[name="tanggal"').val();
        if(tanggal != '')
        {
            handler(tanggal);
        }
            
    });

    // Fungsi dapatkan pegawai
    function handler(tanggal){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        // definisi tanggal ketika onchange
        var tanggal = $('input[name="tanggal"').val();
        var token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url     : '{{ url('laporan-harian') }}',
            data    : {tanggal:tanggal,_token:token},
			method		: "POST",
            success:function(result) {
                    $('#hasil-data').html(result);
                }
			});
        
        if(tanggal != $('input[name="hari_ini"').val()){
            $('#tambah_data').addClass('d-none');
        }
        if(tanggal == $('input[name="hari_ini"').val()){
            $('#tambah_data').removeClass('d-none');
        }
    }
 
</script>
@endsection

