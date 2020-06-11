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
                <p class="mb-0"> Kode Pekerjaan : {{ $proyek->id_proyek.'-'.$kerja->id_pekerjaan.'-'.$meta->id_meta}}</p>
                <p class="mb-0"> Tanggal Input Proyek : {{ date('d/m/Y',strtotime($proyek->created_at)) }}</p>
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
                            <td>{{$iteration+1}}</td>
                            <td>{{$v->pegawai->nama_pegawai}}</td>
                            <td>{{$v->nama_jabatan}}</td>
                            <td>{{$v->nama_kelompok_pegawai}} </td>
                            <td>{{Carbon::parse($v->waktu_in)->format('d-m-Y') }} </td>
                            <td>{{Carbon::parse($v->waktu_in)->format('H:i:s') }} - {{Carbon::parse($v->waktu_out)->format('H:i:s') }}</td>
                            <td> 
                                {{Helper::humanJam(Helper::time2Diff($v->waktu_in,$v->waktu_out))}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>

@endsection