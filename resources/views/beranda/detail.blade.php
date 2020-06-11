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
                    <li class="breadcrumb-item active" aria-current="page">Rekapitulasi {{ $proyek->deskripsi_proyek}}</li>
                </ol>
            </nav>
            <div class="float-right">
                <a href="{{route('home.index')}}" class="btn btn-sm btn-danger mb-2">kembali</a>
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
                <h4 class="input text-center mb-0 mt-2">Rekapitulasi Waktu Pengerjaan Proyek</h4>
                <button onclick="printDiv('laporan')" class="float-right btn btn-info d-print-none" title="Cetak Data"><i class="fa fa-print" style="@media print{ display:none;}"></i></button>
                <h3  class="text-center mb-3">{{ $proyek->deskripsi_proyek}}</h3>
                <p class="mb-0"> Kode Proyek : {{ $proyek->id_proyek}}</p>
                <p class="mb-0"> Tanggal Input Proyek : {{ date('d/m/Y',strtotime($proyek->created_at)) }}</p>
                <p class="mb-0"> Status Proyek : 
                    @if ($proyek->status_proyek=='1')
                        {{ 'Pengerjaan' }}
                    @else
                        {{ 'Selesai Pada '.date('d/m/Y',strtotime($proyek->tanggal_selesai)) }}
                    @endif
                </p>
                <p class="mb-0"> Total Waktu : 
                    <span style="font-weight:bold">
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
                    </span>
                </p>
                <ul class="mb-4">
                    @foreach ($pekerjaan as $item)  
                        <li>{{$item->pekerjaan->nama_pekerjaan}} : 
                            <?php 
                                $total = array();
                                $riwayat = \App\RiwayatPresensi::select('waktu_in','waktu_out')
                                                ->where('id_proyek',$proyek->id_proyek)
                                                ->where('id_pekerjaan',$item->id_pekerjaan)
                                                ->where('waktu_out','!=',NULL)
                                                ->get(); 
                                foreach ($riwayat as $key => $value) {
                                    $total[] = Helper::time2Diff($value->waktu_in,$value->waktu_out);
                                }
                                echo Helper::SumTime($total);    
                            ?>
                        </li>
                    @endforeach
                </ul>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Pekerjaan</th>
                        <th scope="col">Detail Pekerjaan</th>
                        <th scope="col">Waktu</th>
                        <th scope="col" class="d-print-none">Pilihan</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($details as $iteration => $v)
                        <tr>
                            <td>{{$iteration+1}}</td>
                            <td>{{$v->pekerjaan->nama_pekerjaan}}</td>
                            <td>{{$v->pekerjaanMeta->nama_meta}} </td>
                            <td>
                                <?php 
                                $total = array();
                                $riwayat = \App\RiwayatPresensi::select('waktu_in','waktu_out')
                                                ->where('id_proyek',$proyek->id_proyek)
                                                ->where('id_pekerjaan',$v->id_pekerjaan)
                                                ->where('id_meta',$v->id_meta)
                                                ->where('waktu_out','!=',NULL)
                                                ->get(); 
                                foreach ($riwayat as $key => $value) {
                                    $total[] = Helper::time2Diff($value->waktu_in,$value->waktu_out);
                                }
                                echo Helper::SumTime($total);    
                                ?>
                            </td>
                            <td class="d-print-none">
                                <form action="{{route('riwayat-pegawai')}}" method="GET">
                                    <input type="hidden" value="{{$proyek->id_proyek}}" name="id_proyek">
                                    <input type="hidden" value="{{$v->id_pekerjaan}}" name="id_pekerjaan">
                                    <input type="hidden" value="{{$v->id_meta}}" name="id_meta">
                                    <button type="submit" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i> Lihat Detail
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>

@endsection