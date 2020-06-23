@extends('layouts.global')

@section('title')
   Pekerjaan Proyek
@endsection

@section('content')

    <div class="container mb-5"> 
        <div style="">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{url('home').'/'.$proyek->id_proyek}}">Rekapitulasi {{ $proyek->deskripsi_proyek }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"> {{ $kerja->nama_pekerjaan }} {{ $meta->nama_meta}} </li>
                </ol>
            </nav>
            <div class="float-right">
                <a href="{{url('home').'/'.$proyek->id_proyek}}" class="btn btn-sm btn-danger mb-2">kembali</a>
            </div>
            <hr>
        </div>
        <br>
        {{-- isi konten --}}
        <div id="laporan">
            <div class="container">
                <center>
                    <img src="{{asset('storage/lundin.png')}}" class="d-none d-print-block" alt="" style="width: 100px;">
                </center>
                <h4 class="input text-center mb-0 mt-2">Detail Presensi</h4>
                <button onclick="printDiv('laporan')" class="float-right btn btn-info d-print-none" title="Cetak Data"><i class="fa fa-print" style="@media print{ display:none;}"></i></button>
                <h4  class="text-center mb-3">{{ $proyek->deskripsi_proyek}} {{ $kerja->nama_pekerjaan }} {{ $meta->nama_meta}}</h4>
                <div class="d-none d-print-block text-right">Tanggal Cetak : {{Helper::tanggal_idn(now())}}</div>
                <p class="mb-0"> Kode Pekerjaan : {{ $proyek->id_proyek.'-'.$kerja->id_pekerjaan.'-'.$meta->id_meta}}</p>
                <p class="mb-0"> Tanggal Mulai Proyek : {{ Helper::tanggal_idn($proyek->created_at) }}</p>
                <p class="mb-0"> Status Proyek : 
                    @if ($proyek->status_proyek=='1')
                        {{ 'Pengerjaan' }}
                    @else
                        {{ 'Selesai Pada '.date('d/m/Y',strtotime($proyek->tanggal_selesai)) }}
                    @endif
                </p>
                <p class="mb-4"> Total Waktu : 
                    <span style="font-weight:bold">
                        <?php 
                        use Illuminate\Support\Carbon;
                        $total = array();
                        $riwayat = \App\RiwayatPresensi::select('waktu_in','waktu_out')
                                        ->where('id_proyek',$proyek->id_proyek)
                                        ->where('id_pekerjaan',$kerja->id_pekerjaan)
                                        ->where('id_meta',$meta->id_meta)
                                        ->where('waktu_out','!=',NULL)
                                        ->get(); 
                        foreach ($riwayat as $key => $value) {
                            $total[] = Helper::time2Diff($value->waktu_in,$value->waktu_out);
                        }
                        echo Helper::SumTime($total);    
                        ?>
                    </span>
                </p>

                <form action="{{ url('riwayat-pegawai') }}" method="GET">
                    <input type="hidden" name="id_proyek" value="{{$proyek->id_proyek}}">
                    <input type="hidden" name="id_pekerjaan" value="{{$kerja->id_pekerjaan}}">
                    <input type="hidden" name="id_meta" value="{{$meta->id_meta}}">
                    <div class="row mb-0">
                        <div class="col-3">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <label class="input-group-text" for="inputGroupSelect01">Jabatan</label>
                                </div>
                                <select class="custom-select" id="inputGroupSelect01" name="id_jabatan">
                                  <option value="">Semua</option>
                                    @foreach ($jabatan as $item)
                                        <option value="{{$item->id_jabatan}}" @if ($input->id_jabatan == $item->id_jabatan) {{'selected'}} @endif>
                                            {{$item->nama_jabatan}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <label class="input-group-text" for="inputGroupSelect01">Kelompok</label>
                                </div>
                                <select class="custom-select" id="inputGroupSelect01" name="status">
                                  <option value="">Semua</option>
                                  @foreach ($kelompok as $item)
                                    <option value="{{$item->id_kelompok_pegawai}}" @if ($input->id_kelompok_pegawai == $item->id_kelompok_pegawai) {{'selected'}} @endif>
                                        {{$item->nama_kelompok_pegawai}}
                                    </option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-1  d-print-none">
                            <select class="form-control" name="paginate_number" id="paginate_number">
                                <option @if ($input->paginate_number==10){{'selected'}} @endif value="10">10</option>
                                <option @if ($input->paginate_number==25){{'selected'}} @endif value="25">25</option>
                                <option @if ($input->paginate_number==50){{'selected'}} @endif value="50">50</option>
                                <option @if ($input->paginate_number==100){{'selected'}} @endif value="100">100</option>
                            </select>
                        </div>
                        <div class="col-5">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">Tanggal </span>
                                </div>
                                    <input type="date" name="tanggal_mulai" id="tanggal" class="form-control" aria-describedby="basic-addon1" value="@if (!empty($input->tanggal_mulai)){{$input->tanggal_mulai}}@endif">
                                    <input type="date" name="tanggal_akhir" id="tanggal" class="form-control" aria-describedby="basic-addon1" value="@if (!empty($input->tanggal_akhir)){{$input->tanggal_akhir}}@endif">
                                </div>
                        </div>
                        <div class="col-2 d-print-none">
                            <div class="form-group">
                            <input type="text" name="cari" class="form-control" placeholder="Cari Proyek" value="@if (!empty($input->cari)){{$input->cari}}@endif">
                            </div>
                        </div>
                        <div class="col-2 d-print-none">
                            <button class="btn btn-primary" type="submit">Filter</button>
                        </div>                    
                    </div>
                </form>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Jabatan</th>
                        <th scope="col">Kelompok Kerja</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Presensi</th>
                        <th scope="col">Waktu</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($pekerjaan as $iteration => $v)
                        <tr>
                            <td>{{$pekerjaan->firstItem() + $iteration}}</td>
                            <td>{{$v->pegawai->nama_pegawai}}</td>
                            <td>{{$v->nama_jabatan}}</td>
                            <td>{{$v->nama_kelompok_pegawai}} </td>
                            <td>{{Helper::tanggal_idn($v->waktu_in) }} </td>
                            <td>{{Carbon::parse($v->waktu_in)->format('H:i:s') }} - {{Carbon::parse($v->waktu_out)->format('H:i:s') }}</td>
                            <td> 
                                {{Helper::humanJam(Helper::time2Diff($v->waktu_in,$v->waktu_out))}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{$pekerjaan->links()}}
            </div>
        </div>
        
    </div>

@endsection