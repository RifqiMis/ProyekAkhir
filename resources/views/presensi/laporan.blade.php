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
            <div class="d-none d-print-block text-right font-italic">Tanggal Cetak : {{Helper::tanggal_idn(now())}}</div>
            {{-- isi terlambat --}}
            <div class="container">
                <form action="{{ url('home') }}" method="get">
                    <div class="row">
                        <div class="col-1  d-print-none">
                            <select class="form-control" name="paginate-number" id="paginate-number">
                                {{-- <option value="5">5</option> --}}
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="0">All</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">Tanggal </span>
                                </div>
                                    <input type="date" name="tanggal" id="tanggal" class="form-control" aria-describedby="basic-addon1" value="{{ date('Y-m-d') }}">
                                    <input type="hidden" name="hari_ini" value="{{ date('Y-m-d') }}">
                                </div>
                        </div>
                        <div class="col-2  d-print-none">
                            <div class="form-group">
                             <input type="text" name="serach" id="serach" class="form-control" placeholder="Cari nama pegawai"/>
                            </div>
                        </div>
                        <div class="col-6">
                            <button onclick="printDiv('laporan')" class="float-right btn btn-info d-print-none ml-2" title="Cetak Data"><i class="fa fa-print"></i></button>
                            @if (Auth::user()->role=='admin')
                                <a href="{{url("presensi-proyek/create")}}" id="tambah_data" class="btn btn-primary float-right d-print-none">
                                    <i class="fas fa-plus"></i> Tambah Data
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <label class="input-group-text" for="kelompok">Kelompok</label>
                            </div>
                            <select class="custom-select" id="kelompok" name="kelompok">
                              <option value="0">Semua</option>
                              @foreach ($kelompok as $v)
                                <option value="{{$v->id_kelompok_pegawai}}">{{$v->nama_kelompok_pegawai}}</option>
                              @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <label class="input-group-text" for="proyek">Proyek</label>
                            </div>
                            <select class="custom-select" id="proyek" name="id_proyek">
                              <option value="0">Semua</option>
                              @foreach ($proyek as $v)
                                <option value="{{$v->id_proyek}}">{{$v->deskripsi_proyek}}</option>
                              @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <label class="input-group-text" for="proyek">Pekerjaan</label>
                            </div>
                            <select class="form-control" name="id_pekerjaan" id="id_pekerjaan" required>
                            </select>
                            <select class="form-control" name="id_meta" id="id_meta" required>
                            </select>
                        </div>
                    </div>
                </div>
                <br>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">NO</th>
                        <th scope="col">SSN</th>
                        <th scope="col">Nama Pegawai</th>
                        <th scope="col">Proyek</th>
                        <th scope="col">Pekerjaan</th>
                        <th scope="col">Waktu kerja</th>
                        <th scope="col">Total Waktu</th>
                        @if (Auth::user()->role=='admin'||Auth::user()->role=='super admin')
                            <th scope="col" class="d-print-none">Opsi</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody id="hasil-data">
                    </tbody>
                </table>
                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
            </div>
        </div>
        <br>
    </div>
</div>
    
<script type="application/javascript">

    // $(document).ready(function() {

    //     // Dapatkan tanggal diawal load
    //     var tanggal = $('input[name="tanggal"').val();
    //     if(tanggal != '')
    //     {
    //         handler();
    //     }
            
    // });

    // Fungsi dapatkan pegawai
    // function handler(){

    //     // definisi tanggal ketika onchange
    //     var tanggal = $('input[name="tanggal"').val();
    //     var token = $('meta[name="csrf-token"]').attr('content');
    //     $.ajax({
    //         url     : '{{ url('laporan-harian') }}',
    //         data    : {tanggal:tanggal,_token:token},
	// 		method		: "POST",
    //         success:function(result) {
    //                 $('#hasil-data').html(result);
    //             }
	// 		});
        
    // }


    $(document).ready(function(){

        // Dapatkan tanggal diawal load
        var tanggal = $('input[name="tanggal"').val();
        var query = $('#serach').val();
        var proyek = $('#proyek').val();
        var page = $('#hidden_page').val();
        var num = $('#paginate-number').val();
        var kelompok = $('#kelompok').val();
        var pekerjaan = $('#id_pekerjaan').val();
        var meta = $('#id_meta').val();
        fetch_data(page, tanggal, query, num, kelompok, proyek, pekerjaan, meta)

        function fetch_data(page, tanggal, query, num, kelompok, proyek, pekerjaan, meta)
        {
            $.ajax({
                url:"/laporan-harian-pegawai?page="+page+"&tanggal="+tanggal+"&query="+query+"&num="+num+"&kelompok="+kelompok+"&proyek="+proyek+"&pekerjaan="+pekerjaan+"&meta="+meta,
                success:function(data)
                {
                    $('#hasil-data').html('');
                    $('#hasil-data').html(data);
                }
            })

            if(tanggal != $('input[name="hari_ini"').val()){
                $('#tambah_data').addClass('d-none');
            }
            if(tanggal == $('input[name="hari_ini"').val()){
                $('#tambah_data').removeClass('d-none');
            }
        }

        $(document).on('keyup', '#serach', function(){
            var num = $('#paginate-number').val();
            var tanggal = $('input[name="tanggal"').val();
            var query = $('#serach').val();
            var page = $('#hidden_page').val();
            var kelompok = $('#kelompok').val();
            var proyek = $('#proyek').val();
            var pekerjaan = $('#id_pekerjaan').val();
            var meta = $('#id_meta').val();

            fetch_data(page, tanggal, query, num, kelompok, proyek, pekerjaan, meta);
        });

        $(document).on('change', '#tanggal', function(){
            var num = $('#paginate-number').val();
            var tanggal = $('input[name="tanggal"').val();
            var query = $('#serach').val();
            var page = 1;
            var kelompok = $('#kelompok').val();
            var proyek = $('#proyek').val();
            var pekerjaan = $('#id_pekerjaan').val();
            var meta = $('#id_meta').val();

            fetch_data(page, tanggal, query, num, kelompok, proyek, pekerjaan, meta);
        });

        $(document).on('change', '#kelompok', function(){
            var num = $('#paginate-number').val();
            var tanggal = $('input[name="tanggal"').val();
            var query = $('#serach').val();
            var page = $('#hidden_page').val();
            var kelompok = $('#kelompok').val();
            var proyek = $('#proyek').val();
            var pekerjaan = $('#id_pekerjaan').val();
            var meta = $('#id_meta').val();

            fetch_data(page, tanggal, query, num, kelompok, proyek, pekerjaan, meta);
        });

        $(document).on('change', '#proyek', function(){
            var num = $('#paginate-number').val();
            var tanggal = $('input[name="tanggal"').val();
            var query = $('#serach').val();
            var page = $('#hidden_page').val();
            var kelompok = $('#kelompok').val();
            var proyek = $('#proyek').val();
            var pekerjaan = $('#id_pekerjaan').val();
            var meta = $('#id_meta').val();

            fetch_data(page, tanggal, query, num, kelompok, proyek, pekerjaan, meta);
        });

        $(document).on('change', '#id_pekerjaan', function(){
            var num = $('#paginate-number').val();
            var tanggal = $('input[name="tanggal"').val();
            var query = $('#serach').val();
            var page = $('#hidden_page').val();
            var kelompok = $('#kelompok').val();
            var proyek = $('#proyek').val();
            var pekerjaan = $('#id_pekerjaan').val();
            var meta = $('#id_meta').val();

            fetch_data(page, tanggal, query, num, kelompok, proyek, pekerjaan, meta);
        });

        $(document).on('change', '#id_meta', function(){
            var num = $('#paginate-number').val();
            var tanggal = $('input[name="tanggal"').val();
            var query = $('#serach').val();
            var page = $('#hidden_page').val();
            var kelompok = $('#kelompok').val();
            var proyek = $('#proyek').val();
            var pekerjaan = $('#id_pekerjaan').val();
            var meta = $('#id_meta').val();

            fetch_data(page, tanggal, query, num, kelompok, proyek, pekerjaan, meta);
        });

        $(document).on('change', '#paginate-number', function(){
            var num = $('#paginate-number').val();
            var tanggal = $('input[name="tanggal"').val();
            var query = $('#serach').val();
            var page = $('#hidden_page').val();
            var kelompok = $('#kelompok').val();
            var proyek = $('#proyek').val();
            var pekerjaan = $('#id_pekerjaan').val();
            var meta = $('#id_meta').val();

            fetch_data(page, tanggal, query, num, kelompok, proyek, pekerjaan, meta);
        });
        
        $(document).on('click', '.pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            $('#hidden_page').val(page);
            var tanggal = $('input[name="tanggal"').val();
            var query = $('#serach').val();
            var num = $('#paginate-number').val();
            var kelompok = $('#kelompok').val();
            var proyek = $('#proyek').val();
            var pekerjaan = $('#id_pekerjaan').val();
            var meta = $('#id_meta').val();

            $('li').removeClass('active');
                $(this).parent().addClass('active');
            fetch_data(page, tanggal, query, num, kelompok, proyek, pekerjaan, meta);
        });

        // mencari pekerjaan meta
        $('select[name="id_pekerjaan"]').on('focus change', function() {
            var stateID = $(this).val();
            if(stateID) {
                $.ajax({
                    url:  '{{ url('pekerjaan') }}' + '/' +  $('select[name="id_proyek"]').val()  + '/' + $(this).val() + '/metaPresen',
                    type: "GET",
                    dataType: "json",
                    success:function(data) {

                        $('select[name="id_meta"]').empty();
                        $('select[name="id_meta"]').append('<option value="0">Semua</option>');
                        $.each(data, function(key, value) {
                            $('select[name="id_meta"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });

                    }
                });
            }else{
                $('select[name="id_meta"]').empty();
            }
        });

        // mencari pekerjaan
        $('select[name="id_proyek"]').on('change', function() {
            var stateID = $(this).val();
            if(stateID) {
                $.ajax({
                    url:  '{{ url('pekerjaan') }}' + '/' + $(this).val() + '/metaKerja',
                    type: "GET",
                    dataType: "json",
                    success:function(data) {

                        $('select[name="id_pekerjaan"]').empty();
                        $('select[name="id_pekerjaan"]').append('<option value="0">Semua</option>');
                        $.each(data, function(key, value) {
                            $('select[name="id_pekerjaan"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                        $('select[name="id_pekerjaan"]').focus();

                    }
                });
            }else{
                $('select[name="id_pekerjaan"]').empty();
                $('select[name="id_meta"]').empty();
            }
        });

    });
 
</script>
@endsection

