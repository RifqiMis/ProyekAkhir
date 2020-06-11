@extends('layouts.global')

@section('title')
   Tambah Proyek
@endsection

@section('content')
<div class="container">
    <div>
        <center>
            <img src="{{asset('storage/lundin.png')}}" class="d-none d-print-block" alt="" style="width: 100px;">
        </center>
        <h4 class="input text-center mb-0 mt-2">Ubah Presensi Proyek Pegawai</h4>
        <h3  class="text-center">Departemen Produksi</h3>
    </div>
    <hr>
    {{-- isi terlambat --}}
    <div class="container  mb-5 mt-4">
        <form action="{{route('presensi-proyek.update',[$data->id_presensi])}}" method="POST">
            {{ method_field('PUT') }}
            @csrf
        <div class="row ml-0 mr-0">
            <div class="col-12">
                <div class="form-group form-row">
                    <label for="inputPassword" class="col-sm-3 col-form-label">Tanggal</label>
                    <div class="col-sm-5">
                    <input type="text" class="form-control" value="{{ date('Y-m-d',strtotime($data->waktu_in)) }}" required disabled>
                    <input type="hidden" class="form-control" name="tanggal" value="{{ date('Y-m-d',strtotime($data->waktu_in)) }}" required>
                    </div>
                </div>
                <div class="form-group form-row">
                    <label class="col-sm-3 col-form-label">Pegawai</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="pegawai" id="nama_pegawai" value="{{$data->pegawai->nama_pegawai}}" placeholder="Silahkan cari pegawai" disabled>
                    </div>
                </div>
                <div class="form-group form-row">
                    <label class="col-sm-3 col-form-label">Proyek</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="deskripsi_proyek" value="{{$data->proyek->deskripsi_proyek}}" disabled>
                    </div>
                </div>
                <div class="form-group form-row">
                    <label class="col-sm-3 col-form-label">Pekerjaan</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="nama_pekerjaan" value="{{$data->pekerjaan->nama_pekerjaan}}" disabled>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="nama_meta" value="{{$data->pekerjaanMeta->nama_meta}}" disabled>
                    </div>
                </div>
                <div class="form-row form-group">
                    <label for="" class="col-sm-3 col-form-label">Waktu</label>
                    <div class="col-sm-4">
                        <input type="Time" name="jam_mulai" class="form-control" required @if(!empty($data->waktu_in)) value="{{date('H:i:s',strtotime($data->waktu_in))}}"@endif>
                        <small class="form-text text-muted">*AM(Pagi) & PM(Sore).</small>
                    </div>
                    <span for="" class="col-sm-1 col-form-label text-center">S/d</span>
                    <div class="col-sm-4">
                        <input type="time" name="jam_akhir" class="form-control" required  @if(!empty($data->waktu_out)) value="{{date('H:i:s',strtotime($data->waktu_out))}}" @endif>
                    </div>
                </div>
                @if(!empty($data->telat)) 
                <div class="form-row form-group">
                    <label for="" class="col-sm-3 col-form-label text-danger">Telat</label>
                    <div class="col-sm-4">
                        <input type="number" name="telat" class="form-control text-danger" value="{{$data->telat}}">
                        <small class="form-text text-muted text-danger">*Dalam Satuan Menit, Lama telat harus diganti manual</small>
                    </div>
                </div>
                @else 
                    <input type="hidden" name="telat" class="form-control" value="0">
                @endif
                <div class="form-group row">
                    <div class="col-12">
                        <div class="text-center">
                            <button id="btn" type="submit" class="btn btn-sm btn-primary mb-2">Simpan</button>
                            <a href="{{route('presensi-proyek.index')}}" class="btn btn-sm btn-danger mb-2">kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div>
    
</div>

<script type="application/javascript"> 
    //  Ajax untuk meta pekerjaan dropdown  
        $(document).ready(function() {
            // Cek data pegawai
            $('button').on('click', function() {
                if($('#nama_pegawai').val()==''){
                    alert('Pegawai Belum dipilih');
                }
            });

        });

</script>
@endsection
