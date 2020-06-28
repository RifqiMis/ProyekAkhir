@extends('layouts.beranda')
{{-- @extends('components.notifikasi') --}}

@section('title')
    proyek
@endsection

@section('content')
    @if (!in_array(Auth::user()->role,['hrd','warehouse']))
    <div class="row">
        <div class="col-8">
            <div class="bg-white shadow-sm">
                {{-- <br>
                <div class="container">
                   <canvas id="myChart"></canvas>
                </div>
                <br> --}}
                <br>
                <div class="container overflow-auto" style="height: 370px">
                    <h3 class="text-center mb-5">Pengerjaan Proyek Kapal Hari ini</h3>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Kode Proyek</th>
                            <th scope="col">Nama Proyek</th>
                            <th scope="col">Pekerjaan</th>
                        </tr>
                        </thead>
                        <tbody id="proyek_hari">
                        </tbody>
                    </table>
                    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                </div>
                <br>
            </div>
        </div>
        <div class="col-4">
            <div class="bg-white shadow-sm">
                <br>
                <div class="container" style="position: relative;">
                    <canvas id="doughnutChart"></canvas>
                </div>
                <br>
            </div>
            <div class="bg-white shadow-sm" style="margin-top: 25px">
                <div class="container pt-3 pb-2">
                    <canvas id="myChartPie"></canvas>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="bg-white shadow-sm" id="rekap">
        <br>
        <div class="container">
            <div>
                <center>
                    <img src="{{asset('storage/lundin.png')}}" class="d-none d-print-block" alt="" style="width: 100px;">
                </center>
                <h3 class="text-center">Rekapitulasi Waktu Pengerjaan Proyek Kapal</h3>
            </div>
            <br>
            {{-- isi rekapitulasi --}}
            <div class="container">
                <form action="{{ url('home') }}" method="get" class="d-print-none">
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
                        <div class="col-2">
                            <button onclick="printDiv('rekap')" class="float-right btn btn-info d-print-none" title="Cetak data yang ditampilkan"><i class="fa fa-print"></i></button>
                        </div> 
                    </div>
                </form>
                <div class="d-none d-print-block text-right font-italic">Tanggal Cetak : {{Helper::tanggal_idn(now())}}</div>
                <br>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Kode Proyek</th>
                        <th scope="col">Nama Kapal</th>
                        <th scope="col">Status</th>
                        <th scope="col">Total Waktu</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($proyeks as $it => $proyek)
                        <tr>
                            <td>{{$proyeks->firstItem() + $it}}</td>
                            <td>{{$proyek->id_proyek}} </td>
                            <td><a href="{{url("home/{$proyek->id_proyek}")}}">{{$proyek->deskripsi_proyek}}</a> </td>
                            <td>
                                @if ($proyek->status_proyek=='1')
                                    <div class="badge badge-success d-print-none">
                                        {{ 'Dikerjakan' }}
                                    </div>
                                    <div class="d-none d-print-block">
                                        {{ 'Dikerjakan' }}
                                    </div>
                                @else
                                    <div class="badge badge-secondary d-print-none">
                                        {{ 'Selesai' }}
                                    </div>
                                    <div class="d-none d-print-block">
                                        {{ 'Selesai' }}
                                    </div>
                                    <br>
                                    {{ 'Tanggal :'.Helper::tanggal_idn($proyek->tanggal_selesai) }}
                                    <br>
                                    {{ 'Lama : '.Helper::day2Diff($proyek->created_at,$proyek->tanggal_selesai) }}
                                @endif
                            </td>
                            <td>
                                <?php 
                                    $total = array();
                                    $riwayat = \App\RiwayatPresensi::select('waktu_in','waktu_out')
                                                    ->where('id_proyek',$proyek->id_proyek)
                                                    ->where('waktu_out','!=',NULL)
                                                    ->get(); 
                                    foreach ($riwayat as $key => $value) {
                                        $total[] = Helper::time2Diff($value->waktu_in,$value->waktu_out);
                                    }
                                    echo Helper::SumTime($total);
                                ?>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $proyeks->links() }}
            </div>
        </div>
        <br>
    </div>

    {{-- Pegawai yang tidak hadir --}}
    <div id="laporan-absen">
        <div class="bg-white shadow-sm" style="margin-top: 25px">
            <br>
            <div class="container">
                <div>
                    <center>
                        <img src="{{asset('storage/lundin.png')}}" class="d-none d-print-block" alt="" style="width: 100px;">
                    </center>
                    <h4 class="input text-center mb-0 mt-2">Ketidak Hadiran Pegawai</h4>
                    <h3  class="text-center">Departemen Produksi</h3>
                </div>
                <br>
                <div class="container">
                    <div class="d-none d-print-block text-right font-italic">Tanggal Cetak : {{Helper::tanggal_idn(now())}}</div>
                    <div class="row mb-2">
                        <div class="col-12">
                            <button onclick="printDiv('laporan-absen')" class="float-right btn btn-info d-print-none" title="Cetak Data"><i class="fa fa-print"></i></button>
                        </div>
                    </div>
                    <form action="{{ url('home') }}" method="get">
                        <div class="row">
                            <div class="col-1  d-print-none">
                                <select class="form-control" name="paginate-number" id="paginate-number">
                                    <option value="5">5</option>
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
                                    <input type="date" id="tanggalAbsen" name="tanggalAbsen" class="form-control" aria-describedby="basic-addon1" value="{{ date('Y-m-d') }}">
                                  </div>
                            </div>
                            <div class="col-3">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                      <label class="input-group-text" for="kelompok">Kelompok</label>
                                    </div>
                                    <select class="custom-select" id="kelompok" name="kelompok">
                                      <option value="0">Semua</option>
                                      @foreach ($kelompoks as $v)
                                        <option value="{{$v->id_kelompok_pegawai}}">{{$v->nama_kelompok_pegawai}}</option>
                                      @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                      <label class="input-group-text">Jabatan</label>
                                    </div>
                                    <select class="custom-select" id="jabatan" name="jabatan">
                                      <option value="">Semua</option>
                                      @foreach ($jabatans as $key => $val)
                                        <option value="{{ $val->id_jabatan }}">{{ $val->nama_jabatan }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-2 d-print-none">
                                <div class="form-group">
                                 <input type="text" name="serach" id="serach" class="form-control" placeholder="Cari nama pegawai"/>
                                </div>
                            </div>
                        </div>
                    </form>
                    <br>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th scope="col">SSN</th>
                            <th scope="col">Nama Pegawai</th>
                            <th scope="col">Jabatan</th>
                            <th scope="col">Kelompok Kerja</th>
                        </tr>
                        </thead>
                        <tbody id="absen">
                        </tbody>
                    </table>
                    <input type="hidden" name="hidden_page_absen" id="hidden_page_absen" value="1" />
                </div>
    
            </div>
            <br>
        </div>
    </div>
    @endif
    
    @if (in_array(Auth::user()->role,['hrd','admin','super admin','manajer']))
    <div id="laporan">
        <div class="bg-white shadow-sm" style="margin-top: 25px">
            <br>
            <div class="container">
                <div>
                    <center>
                        <img src="{{asset('storage/lundin.png')}}" class="d-none d-print-block" alt="" style="width: 100px;">
                    </center>
                    <h4 class="input text-center mb-0 mt-2">Keterlambatan Presensi Proyek</h4>
                    <h3  class="text-center">Departemen Produksi</h3>
                </div>
                <div class="d-none d-print-block text-right font-italic">Tanggal Cetak : {{Helper::tanggal_idn(now())}}</div>
                <br>
                {{-- isi terlambat --}}
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-12">
                            <button onclick="printDiv('laporan')" class="float-right btn btn-info d-print-none" title="Cetak Data"><i class="fa fa-print"></i></button>
                        </div>
                    </div>
                    <form action="{{ url('home') }}" method="get">
                        <div class="row">
                            <div class="col-1  d-print-none">
                                <select class="form-control" name="paginate-number-terlambat" id="paginate-number-terlambat">
                                    <option value="5">5</option>
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
                                    <input type="date" id="tanggalTerlambat" name="tanggalTerlambat" class="form-control" aria-describedby="basic-addon1" value="{{ date('Y-m-d') }}">
                                  </div>
                            </div>
                            <div class="col-3">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                      <label class="input-group-text" for="kelompokT">Kelompok</label>
                                    </div>
                                    <select class="custom-select" id="kelompokT" name="kelompokT">
                                      <option value="0">Semua</option>
                                      @foreach ($kelompoks as $v)
                                        <option value="{{$v->id_kelompok_pegawai}}">{{$v->nama_kelompok_pegawai}}</option>
                                      @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                      <label class="input-group-text">Jabatan</label>
                                    </div>
                                    <select class="custom-select" id="jabatanT" name="jabatanT">
                                      <option value="">Semua</option>
                                      @foreach ($jabatans as $key => $val)
                                        <option value="{{ $val->id_jabatan }}">{{ $val->nama_jabatan }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-2 d-print-none">
                                <div class="form-group">
                                 <input type="text" name="serachT" id="serachT" class="form-control" placeholder="Cari nama pegawai"/>
                                </div>
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
                            <th scope="col">Keterlambatan</th>
                        </tr>
                        </thead>
                        <tbody id="telat">
                        </tbody>
                    </table>
                    <input type="hidden" name="hidden_page_terlambat" id="hidden_page_terlambat" value="1" />
                </div>
    
            </div>
            <br>
        </div>
    </div>
    @endif

    @if (in_array(Auth::user()->role,['warehouse']))
    <div id="laporan-warehouse">
        <div class="bg-white shadow-sm" style="margin-top: 25px">
            <br>
            <div class="container">
                <div>
                    <center>
                        <img src="{{asset('storage/lundin.png')}}" class="d-none d-print-block" alt="" style="width: 100px;">
                    </center>
                    <h4 class="input text-center mb-0 mt-2">Presensi Pekerja yang Berjalan pada {{Helper::tanggal_idn(now())}} </h4>
                    <h3  class="text-center">Departemen Produksi</h3>
                </div>
                <div class="d-none d-print-block text-right font-italic">Tanggal Cetak : {{Helper::tanggal_idn(now())}} {{date('H:i')}} WIB</div>
                <br>
                {{-- isi ongoing --}}
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-12">
                            <button onclick="printDiv('laporan-warehouse')" class="float-right btn btn-info d-print-none" title="Cetak Data"><i class="fa fa-print"></i></button>
                        </div>
                    </div>
                    <form action="{{ url('home') }}" method="get">
                        <div class="row">
                            <div class="col-1  d-print-none">
                                <select class="form-control" name="paginate-number-warehouse" id="paginate-number-warehouse">
                                    <option value="5">5</option>
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
                                      <label class="input-group-text" for="kelompokW">Kelompok</label>
                                    </div>
                                    <select class="custom-select" id="kelompokW" name="kelompokW">
                                      <option value="0">Semua</option>
                                      @foreach ($kelompoks as $v)
                                        <option value="{{$v->id_kelompok_pegawai}}">{{$v->nama_kelompok_pegawai}}</option>
                                      @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                      <label class="input-group-text">Jabatan</label>
                                    </div>
                                    <select class="custom-select" id="jabatanW" name="jabatanW">
                                      <option value="">Semua</option>
                                      @foreach ($jabatans as $key => $val)
                                        <option value="{{ $val->id_jabatan }}">{{ $val->nama_jabatan }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3 d-print-none">
                                <div class="form-group">
                                 <input type="text" name="serachW" id="serachW" class="form-control" placeholder="Cari nama pegawai"/>
                                 <input type="hidden" id="tanggalWarehouse" name="tanggalWarehouse" class="form-control" aria-describedby="basic-addon1" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                    </form>
                    <br>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th scope="col">SSN</th>
                            <th scope="col">Nama Pegawai</th>
                            <th scope="col">Foto</th>
                            <th scope="col">Proyek</th>
                            <th scope="col">Pekerjaan</th>
                            <th scope="col">Waktu Presensi</th>
                        </tr>
                        </thead>
                        <tbody id="warehouse">
                        </tbody>
                    </table>
                    <input type="hidden" name="hidden_page_warehouse" id="hidden_page_warehouse" value="1" />
                </div>
    
            </div>
            <br>
        </div>
    </div>
    @endif
    
<script type="application/javascript">

    $(document).ready(function() {

        var page = $('#hidden_page').val();
        pengerjaan_kapal(page);


        function pengerjaan_kapal(page)
        {
            $.ajax({
                url:  '{{ url('pengerjaan') }}' + '?page=' +page,
                success:function(data)
                {
                    $('#proyek_hari').html(data);
                },
                error: function(){
                    alert('error!');
                }

            })
        }

        $(document).on('click', '.hari-ini .pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            $('#hidden_page').val(page);

            $('li').removeClass('active');
                $(this).parent().addClass('active');
            pengerjaan_kapal(page);
        });


    // Chart pie
        $.ajax({
            url:  '{{ url('akumulasi') }}' + '-pegawai',
            type: "GET",
            dataType: "json",
            success:function(data) {
                var today = new Date();
                var ctx = document.getElementById('myChartPie').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Tidak Hadir', 'Hadir',],
                        datasets: [{
                            label: 'Presensi Proyek Kapal pada '+today.getDate()+'-'+(today.getMonth()+1)+'-'+today.getFullYear(),
                            data: data.presensi,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        title: {
                            display: true,
                            text: 'Total Pegawai : ' + data.total
                        }
                    }
                });
            }
        });

        //doughnut
        $.ajax({
            url:  '{{ url('proyek') }}' + '-total',
            type: "GET",
            dataType: "json",
            success:function(data) {
                var ctxD = document.getElementById("doughnutChart").getContext('2d');
                var myLineChart = new Chart(ctxD, {
                    type: 'doughnut',
                    data: {
                        labels: ["Proyek Selesai", "Proyek Dikerjakan"],
                        datasets: [{
                            data: data.grafik,
                            // data: total,
                            backgroundColor: ["rgba(150, 150, 150, 0.5)","rgba(75, 192, 192, 0.5)"],
                            hoverBackgroundColor: ["rgba(150, 150, 150, 0.2)","rgba(75, 192, 192, 0.2)"],
                            borderColor: [
                                'rgba(150, 150, 150, 1)',
                                'rgba(75, 192, 192, 1)',
                            ],
                            borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            title: {
                                display: true,
                                text: 'Total Proyek : ' + data.total
                            }
                        }
                });
            }
        });

    // Load data tidak hadir
    // Dapatkan tanggal diawal load
        var tanggalAbsen = $('input[name="tanggalAbsen"').val();
        var query = $('#serach').val();
        var num = $('#paginate-number').val();
        var kelompok = $('#kelompok').val();
        var jabatan = $('#jabatan').val();
        var page = $('#hidden_page_absen').val();
        fetch_data(page, tanggalAbsen, query, num, kelompok, jabatan);

        function fetch_data(page, tanggalAbsen, query, num, kelompok, jabatan)
        {
            $.ajax({
                url:"/pegawai-absen?page="+page+"&tanggalAbsen="+tanggalAbsen+"&query="+query+"&num="+num+"&kelompok="+kelompok+"&jabatan="+jabatan,
                success:function(data)
                {
                    $('#absen').html('');
                    $('#absen').html(data);
                }
            })

        }

        $(document).on('keyup', '#serach', function(){
            var tanggalAbsen = $('input[name="tanggalAbsen"').val();
            var query = $('#serach').val();
            var num = $('#paginate-number').val();
            var kelompok = $('#kelompok').val();
            var jabatan = $('#jabatan').val();
            var page = $('#hidden_page_absen').val();
            fetch_data(page, tanggalAbsen, query, num, kelompok, jabatan);
        });

        $(document).on('change', '#tanggalAbsen', function(){
            var tanggalAbsen = $('input[name="tanggalAbsen"').val();
            var query = $('#serach').val();
            var num = $('#paginate-number').val();
            var kelompok = $('#kelompok').val();
            var jabatan = $('#jabatan').val();
            var page = 1;
            fetch_data(page, tanggalAbsen, query, num, kelompok, jabatan);
        });

        $(document).on('change', '#kelompok', function(){
            var tanggalAbsen = $('input[name="tanggalAbsen"').val();
            var query = $('#serach').val();
            var num = $('#paginate-number').val();
            var kelompok = $('#kelompok').val();
            var jabatan = $('#jabatan').val();
            var page = $('#hidden_page_absen').val();
            fetch_data(page, tanggalAbsen, query, num, kelompok, jabatan);
        });

        $(document).on('change', '#jabatan', function(){
            var tanggalAbsen = $('input[name="tanggalAbsen"').val();
            var query = $('#serach').val();
            var num = $('#paginate-number').val();
            var kelompok = $('#kelompok').val();
            var jabatan = $('#jabatan').val();
            var page = $('#hidden_page_absen').val();
            fetch_data(page, tanggalAbsen, query, num, kelompok, jabatan);
        });

        $(document).on('change', '#paginate-number', function(){
            var tanggalAbsen = $('input[name="tanggalAbsen"').val();
            var query = $('#serach').val();
            var num = $('#paginate-number').val();
            var kelompok = $('#kelompok').val();
            var jabatan = $('#jabatan').val();
            var page = $('#hidden_page_absen').val();
            fetch_data(page, tanggalAbsen, query, num, kelompok, jabatan);
        });
        
        $(document).on('click', '.absen .pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            $('#hidden_page_absen').val(page);

            var tanggalAbsen = $('input[name="tanggalAbsen"').val();
            var query = $('#serach').val();
            var num = $('#paginate-number').val();
            var kelompok = $('#kelompok').val();
            var jabatan = $('#jabatan').val();

            $('li').removeClass('active');
                $(this).parent().addClass('active');
            fetch_data(page, tanggalAbsen, query, num, kelompok, jabatan);
        });


        // Keterlambatan Pegawai 
        var tanggalAbsen = $('input[name="tanggalTerlambat"').val();
        var query = $('#serachT').val();
        var num = $('#paginate-number-terlambat').val();
        var kelompok = $('#kelompokT').val();
        var jabatan = $('#jabatanT').val();
        var page = $('#hidden_page_terlambat').val();
        fetch_data_terlambat(page, tanggalAbsen, query, num, kelompok, jabatan);

        function fetch_data_terlambat(page, tanggalAbsen, query, num, kelompok, jabatan)
        {
            $.ajax({
                url:"/pegawai-terlambat?page="+page+"&tanggal="+tanggalAbsen+"&query="+query+"&num="+num+"&kelompok="+kelompok+"&jabatan="+jabatan,
                success:function(data)
                {
                    $('#telat').html('');
                    $('#telat').html(data);
                }
            })

        }

        $(document).on('keyup', '#serachT', function(){
            var tanggalAbsen = $('input[name="tanggalTerlambat"').val();
            var query = $('#serachT').val();
            var num = $('#paginate-number-terlambat').val();
            var kelompok = $('#kelompokT').val();
            var jabatan = $('#jabatanT').val();
            var page = $('#hidden_page_terlambat').val();
            fetch_data_terlambat(page, tanggalAbsen, query, num, kelompok, jabatan);
        });

        $(document).on('change', '#tanggalTerlambat', function(){
            var tanggalAbsen = $('input[name="tanggalTerlambat"').val();
            var query = $('#serachT').val();
            var num = $('#paginate-number-terlambat').val();
            var kelompok = $('#kelompokT').val();
            var jabatan = $('#jabatanT').val();
            var page = 1;
            fetch_data_terlambat(page, tanggalAbsen, query, num, kelompok, jabatan);
        });

        $(document).on('change', '#kelompokT', function(){
            var tanggalAbsen = $('input[name="tanggalTerlambat"').val();
            var query = $('#serachT').val();
            var num = $('#paginate-number-terlambat').val();
            var kelompok = $('#kelompokT').val();
            var jabatan = $('#jabatanT').val();
            var page = $('#hidden_page_terlambat').val();
            fetch_data_terlambat(page, tanggalAbsen, query, num, kelompok, jabatan);
        });

        $(document).on('change', '#jabatanT', function(){
            var tanggalAbsen = $('input[name="tanggalTerlambat"').val();
            var query = $('#serachT').val();
            var num = $('#paginate-number-terlambat').val();
            var kelompok = $('#kelompokT').val();
            var jabatan = $('#jabatanT').val();
            var page = $('#hidden_page_terlambat').val();
            fetch_data_terlambat(page, tanggalAbsen, query, num, kelompok, jabatan);
        });

        $(document).on('change', '#paginate-number-terlambat', function(){
            var tanggalAbsen = $('input[name="tanggalTerlambat"').val();
            var query = $('#serachT').val();
            var num = $('#paginate-number-terlambat').val();
            var kelompok = $('#kelompokT').val();
            var jabatan = $('#jabatanT').val();
            var page = $('#hidden_page_terlambat').val();
            fetch_data_terlambat(page, tanggalAbsen, query, num, kelompok, jabatan);
        });
        
        $(document).on('click', '.terlambat .pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            $('#hidden_page_absen').val(page);

            var tanggalAbsen = $('input[name="tanggalTerlambat"').val();
            var query = $('#serachT').val();
            var num = $('#paginate-number-terlambat').val();
            var kelompok = $('#kelompokT').val();
            var jabatan = $('#jabatanT').val();

            $('li').removeClass('active');
                $(this).parent().addClass('active');
            fetch_data_terlambat(page, tanggalAbsen, query, num, kelompok, jabatan);
        });


        // Warehouse 
        var tanggalWarehouse = $('input[name="tanggalWarehouse"').val();
        var query = $('#serachW').val();
        var num = $('#paginate-number-warehouse').val();
        var kelompok = $('#kelompokW').val();
        var jabatan = $('#jabatanW').val();
        var page = 1;
        fetch_data_warehouse(page, tanggalWarehouse, query, num, kelompok, jabatan);

        function fetch_data_warehouse(page, tanggalWarehouse, query, num, kelompok, jabatan)
        {
            $.ajax({
                url:"/warehouse?page="+page+"&tanggal="+tanggalWarehouse+"&query="+query+"&num="+num+"&kelompok="+kelompok+"&jabatan="+jabatan,
                success:function(data)
                {
                    $('#warehouse').html('');
                    $('#warehouse').html(data);
                }
            })

        }

        $(document).on('keyup', '#serachW', function(){
            var tanggalWarehouse = $('input[name="tanggalWarehouse"').val();
            var query = $('#serachW').val();
            var num = $('#paginate-number-warehouse').val();
            var kelompok = $('#kelompokW').val();
            var jabatan = $('#jabatanW').val();
            var page = 1;
            fetch_data_warehouse(page, tanggalWarehouse, query, num, kelompok, jabatan);
        });

        $(document).on('change', '#tanggalWarehouse', function(){
            var tanggalWarehouse = $('input[name="tanggalWarehouse"').val();
            var query = $('#serachW').val();
            var num = $('#paginate-number-warehouse').val();
            var kelompok = $('#kelompokW').val();
            var jabatan = $('#jabatanW').val();
            var page = 1;
            fetch_data_warehouse(page, tanggalWarehouse, query, num, kelompok, jabatan);
        });

        $(document).on('change', '#kelompokW', function(){
            var tanggalWarehouse = $('input[name="tanggalWarehouse"').val();
            var query = $('#serachW').val();
            var num = $('#paginate-number-warehouse').val();
            var kelompok = $('#kelompokW').val();
            var jabatan = $('#jabatanW').val();
            var page = 1;
            fetch_data_warehouse(page, tanggalWarehouse, query, num, kelompok, jabatan);
        });

        $(document).on('change', '#jabatanW', function(){
            var tanggalWarehouse = $('input[name="tanggalWarehouse"').val();
            var query = $('#serachW').val();
            var num = $('#paginate-number-warehouse').val();
            var kelompok = $('#kelompokW').val();
            var jabatan = $('#jabatanW').val();
            var page = 1;
            fetch_data_warehouse(page, tanggalWarehouse, query, num, kelompok, jabatan);
        });

        $(document).on('change', '#paginate-number-warehouse', function(){
            var tanggalWarehouse = $('input[name="tanggalWarehouse"').val();
            var query = $('#serachW').val();
            var num = $('#paginate-number-warehouse').val();
            var kelompok = $('#kelompokW').val();
            var jabatan = $('#jabatanW').val();
            var page = 1;
            fetch_data_warehouse(page, tanggalWarehouse, query, num, kelompok, jabatan);
        });
        
        $(document).on('click', '.warehouse .pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            $('#hidden_page_absen').val(page);

            var tanggalWarehouse = $('input[name="tanggalWarehouse"').val();
            var query = $('#serachW').val();
            var num = $('#paginate-number-warehouse').val();
            var kelompok = $('#kelompokW').val();
            var jabatan = $('#jabatanW').val();

            $('li').removeClass('active');
            $(this).parent().addClass('active');
            fetch_data_warehouse(page, tanggalWarehouse, query, num, kelompok, jabatan);

        });
            
    });
 
</script>
@endsection

