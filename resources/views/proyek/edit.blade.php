@extends('layouts.global')

@section('title')
   Edit Proyek
@endsection

@section('content')
    <style>

        #upload-label {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
        }

        .image-area {
            border: 2px dashed rgba(255, 255, 255, 0.7);
            padding: 1rem;
            position: relative;
        }

        .image-area::before {
            content: 'Uploaded image result';
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
            position: absolute;
            transform: translate(-50%, -50%);
            font-size: 0.8rem;
            z-index: 1;
        }

        .image-area img {
            z-index: 2;
            position: relative;
        }
    </style>
    <div class="container">
        <h3 class="text-center">Edit Proyek</h3>
    </div>
    <hr>
    <br>
    @include('components.notifikasi')
    {{-- isi konten --}}
        <div class="container">
            <form action="{{route('proyek.update',[$proyek->id_proyek])}}" method="POST" enctype="multipart/form-data">
                {{ method_field('PUT') }}
                @csrf
                <div class="form-group row">
                    <label class="col-2 col-form-label">Kode Proyek</label>
                    <div class="col-5">
                        <input type="text" class="form-control" name="id_proyek" placeholder="Kode proyek" value="{{$proyek->id_proyek}}" disabled>
                        <input type="hidden" name="id_proyek" value="{{$proyek->id_proyek}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Deskripsi</label>
                    <div class="col-5">
                        <input type="text" class="form-control" name="deskripsi_proyek" placeholder="Nama proyek" value="{{$proyek->deskripsi_proyek}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Status</label>
                    <select name="status_proyek" class="custom-select col-4" style="margin-left:15px;">
                        <option value="1" {{ ( 1 == $proyek->status_proyek) ? 'selected' : '' }}>Dikerjakan</option>
                        <option value="0" {{ ( 0 == $proyek->status_proyek) ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Foto</label>
                    <div class="col-10">
                        @if($proyek->foto)
                            <small class="text-muted">Foto saat ini</small><br>
                            <img src="{{asset('storage/' . $proyek->foto)}}" width="150px"/>
                        @endif
                        <input id="upload" class="" type="file" name="foto" onchange="readURL(this);" class="form-control border-0"><br>
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                    </div>
                </div>
                <!-- Uploaded image area-->
                <div class="image-area mt-4"><img id="imageResult" src="#" alt="" class="img-fluid rounded shadow-sm mx-auto d-block"></div>
                <div class="form-group row">
                    <label class="col-2 col-form-label"></label>
                    <div class="col-5">
                        <button type="submit" class="btn btn-sm btn-primary mb-2">Simpan</button>
                        <a href="{{route('proyek.index')}}" class="btn btn-sm btn-danger mb-2">kembali</a>
                    </div>
                </div>
                <br>
            </form>
        </div>
    </div>
@endsection

