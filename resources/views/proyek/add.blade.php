@extends('layouts.global')

@section('title')
   Tambah Proyek
@endsection

@section('content')
<style>
    #upload {
        opacity: 0;
    }

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
        <h3 class="text-center">Tambah Proyek</h3>
    </div>
    <hr>
    <br>
    @include('components.notifikasi')
    {{-- isi konten --}}
        <div class="container">
            <form action="{{ route('proyek.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label class="col-2 col-form-label">Kode Proyek</label>
                    <div class="col-5">
                      <input type="text" class="form-control" name="id_proyek" placeholder="kode proyek">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Proyek</label>
                    <div class="col-5">
                      <input type="text" class="form-control" name="deskripsi_proyek" placeholder="nama proyek">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Foto</label>
                    <div class="col-10">
                        <input id="upload" class="col-form-label" type="file" name="foto" onchange="readURL(this);" class="form-control border-0">
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

