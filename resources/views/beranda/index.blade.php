@extends('layouts.beranda')
{{-- @extends('components.notifikasi') --}}

@section('title')
    proyek
@endsection

@section('content')
    @if (Auth::user()->role!='hrd')
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
                {{-- <br>
                <div class="container text-center" style="position: relative;">
                    <h3>Departemen Produksi</h3>
                    <div class="row" style="margin-top: 15px">
                        <div class="col-6">
                            <a href=""><h2 style="margin-bottom: 0px">{{ $departemen['supervisor'] }}</h2></a>
                            <p>Supervisor</p>
                        </div>
                        <div class="col-6">
                            <a href=""><h2 style="margin-bottom: 0px">{{ $departemen['kelompok'] }}</h2></a>
                            <p>Kelompok</p>
                        </div>
                        <div class="col-4">
                            <a href=""><h2 style="margin-bottom: 0px">{{ $departemen['pegawai'] }}</h2></a>
                            <p>Pekerja</p>
                        </div>
                    </div>
                </div>
                <br> --}}
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
                    <h4 class="input text-center mb-0 mt-2">Daftar Pegawai yang Tidak Hadir</h4>
                    <h3  class="text-center">Departemen Produksi</h3>
                </div>
                <br>
                {{-- isi terlambat --}}
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
                    <form action="{{ url('home') }}" method="get">
                        <div class="row">
                            <div class="col-4">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text" id="basic-addon1">Tanggal </span>
                                    </div>
                                    <input type="date" name="tanggal" class="form-control" aria-describedby="basic-addon1" value="{{ date('Y-m-d') }}" onchange="handler(tanggal);">
                                  </div>
                            </div>
                            <div class="col-8">
                            <button onclick="printDiv('laporan')" class="float-right btn btn-info d-print-none" title="Cetak Data"><i class="fa fa-print" style="@media print{ display:none;}"></i></button>
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
                </div>
    
            </div>
            <br>
        </div>
    </div>
    
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
                            backgroundColor: ["rgba(75, 192, 192, 1)","rgba(150, 150, 150, 1)"],
                            hoverBackgroundColor: ["rgba(75, 192, 192, 0.5)","rgba(150, 150, 150, 0.5)"]
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

        // Dapatkan Pegawai yang terlambat diawal load
        var tanggal = $('input[name="tanggal"').val();
        if(tanggal != '')
        {
            handler(tanggal);
        }

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
            
    });

    // Fungsi dapatkan pegawai
    function handler(tanggal){

        // definisi tanggal ketika onchange
        var tanggal = $('input[name="tanggal"').val();
        var token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url     : '{{ url('pegawai-terlambat') }}',
            data    : {tanggal:tanggal,_token:token},
			method		: "POST",
            success:function(result) {
                    $('#telat').html(result);
                }
		});
    }
 
</script>
@endsection

