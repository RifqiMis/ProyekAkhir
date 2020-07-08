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
                    <li class="breadcrumb-item active" aria-current="page">Detail Pekerjaan {{ $proyek->deskripsi_proyek.' '}} 
                        @if (!empty($input->id_pekerjaan))
                            {{$pekerjaan->nama_pekerjaan.' '}}
                        @endif
                        @if (!empty($input->id_meta))
                            {{$pekerjaanMeta->nama_meta.' '}}
                        @endif
                        Hari ini
                    </li>
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
                <h4 class="input text-center mb-0 mt-2">Pengerjaan Proyek</h4>
                <button onclick="printDiv('laporan')" class="float-right btn btn-info d-print-none" title="Cetak Data"><i class="fa fa-print" style="@media print{ display:none;}"></i></button>
                <h4  class="text-center mb-3">{{ $proyek->deskripsi_proyek.' '}}
                    @if (!empty($input->id_pekerjaan))
                        {{$pekerjaan->nama_pekerjaan.' '}}
                    @endif
                    @if (!empty($input->id_meta))
                        {{$pekerjaanMeta->nama_meta.' '}}
                    @endif
                </h4>
                <div class="d-none d-print-block text-right">Tanggal Cetak : {{Helper::tanggal_idn(now())}}</div>
                <p class="mb-0"> Kode Proyek : {{ $proyek->id_proyek}}</p>
                <p class="mb-0"> Tanggal Mulai Proyek : {{ Helper::tanggal_idn($proyek->created_at) }}</p>
                <p class="mb-5"> Tanggal : {{Helper::tanggal_idn(now())}}</p>
                <form action="{{url('beranda/pengerjaan')}}" method="GET">
                    <input type="hidden" name="id_proyek" value="{{$proyek->id_proyek}}">
                    @if (!empty($input->id_pekerjaan))
                        <input type="hidden" name="id_pekerjaan" value="{{$input->id_pekerjaan}}">
                    @endif
                    @if (!empty($input->id_meta))
                        <input type="hidden" name="id_meta" value="{{$input->id_meta}}">
                    @endif
                    <div class="row mb-3">
                        <div class="col-1  d-print-none">
                            <select class="form-control" name="paginate_number" id="paginate_number">
                                <option @if ($input->paginate_number==10){{'selected'}} @endif value="10">10</option>
                                <option @if ($input->paginate_number==25){{'selected'}} @endif value="25">25</option>
                                <option @if ($input->paginate_number==50){{'selected'}} @endif value="50">50</option>
                                <option @if ($input->paginate_number==100){{'selected'}} @endif value="100">100</option>
                                <option @if ($input->paginate_number==0){{'selected'}} @endif value="0">All</option>
                            </select>
                        </div>
                        <div class="input-group col-3">
                            <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">Status</label>
                            </div>
                            <select class="custom-select" id="inputGroupSelect01" name="status">
                                <option value="">Semua</option>
                                <option @if ($input->status=='dikerjakan'){{'selected'}}@endif value="dikerjakan">Dikerjakan</option>
                                <option @if ($input->status=='selesai'){{'selected'}}@endif value="selesai">Selesai</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                        </div> 
                    </div>
                </form>
                <table class="table table-hover mt-3">
                    <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Pegawai</th>
                        <th scope="col">Pekerjaan</th>
                        <th scope="col">Waktu Mulai</th>
                        <th scope="col">Waktu Selesai</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($details as $it => $v)
                        <tr>
                            <td>{{$details->firstItem() + $it}}</td>
                            <td>{{$v->pegawai->nama_pegawai}}</td>
                            <td>{{$v->pekerjaan->nama_pekerjaan}} {{$v->pekerjaanMeta->nama_meta}}</td>
                            <td> {{date('H:i:s',strtotime($v->waktu_in))}} </td>
                            <td> 
                                @if ($v->waktu_out != NULL)
                                    {{date('H:i:s',strtotime($v->waktu_out))}} 
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{$details->links()}}
            </div>
        </div>
        
    </div>

@endsection