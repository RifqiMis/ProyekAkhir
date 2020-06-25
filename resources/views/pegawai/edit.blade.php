@extends('layouts.global')

@section('title')
   Tambah pegawai
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
        <h3 class="text-center">Tambah Pegawai</h3>
    </div>
    <hr>
    <br>
    
    @include('components.notifikasi')
    {{-- isi konten --}}
        <div class="container">
            <form action="{{route('pegawai.update',[$pegawai->id_pegawai])}}" method="POST" enctype="multipart/form-data">
                {{ method_field('PUT') }}
                @csrf
                <div class="row">
                    <div class="col-8">
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Nama Pegawai</label>
                            <div class="col-8">
                              <input type="text" class="form-control" name="nama_pegawai" placeholder="Nama Lengkap" value="{{$pegawai->nama_pegawai}}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">SSN</label>
                            <div class="col-8">
                              <input minlength="4" maxlength="4" type="text" class="form-control" name="ssn" placeholder="Nomor Pegawai" value="{{$pegawai->ssn}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Jenis Kelamin</label>
                            <div class="col-8">
                                <select name="jenis_kelamin" id="" class="form-control">
                                    <option value="L" @if ('{{ $pegawai->jenis_kelamin }}' == "L") {{ 'selected' }} @endif >Laki-laki</option>
                                    <option value="P" @if ('{{ $pegawai->jenis_kelamin }}' == "P") {{ 'selected' }} @endif >Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Jabatan</label>
                            <div class="col-8">
                                <select name="id_jabatan" id="" class="form-control" required>
                                    <option value="">Silahkan Pilih</option>
                                    @foreach ($jabatans as $key => $val)
                                        <option value="{{ $val->id_jabatan }}" {{ $val->id_jabatan == $pegawai->id_jabatan? 'selected' : '' }}>{{ $val->nama_jabatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Kelompok</label>
                            <div class="col-8">
                                <select name="id_kelompok" id="" class="form-control" required>
                                    <option value="">Silahkan Pilih</option>
                                    @foreach ($kelompoks as $key => $val)
                                        <option value="{{ $val->id_kelompok_pegawai }}" {{ $val->id_kelompok_pegawai == $pegawai->id_kelompok ? 'selected' : '' }}>{{ $val->nama_kelompok_pegawai }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Status</label>
                            <div class="col-8">
                                <select name="status" class="form-control" required>
                                    <option value="bekerja" {{$val->status=='bekerja'?'selected':''}} >Bekerja</option>
                              <option value="berhenti" {{$val->status=='berhenti'?'selected':''}}>Berhenti</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group row">
                            <label class="col-2 col-form-label">Foto</label>
                            <div class="col-10">
                                <small class="text-muted">Foto saat ini</small><br>
                                @if($pegawai->foto)
                                    <img src="{{asset('storage/' . $pegawai->foto)}}" width="150px"/>
                                @endif
                                <input id="upload" class="col-form-label" type="file" name="foto" onchange="readURL(this);" class="form-control border-0">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                            </div>
                        </div>
                        <!-- Uploaded image area-->
                        <div class="image-area mt-4"><img id="imageResult" src="#" alt="" class="img-fluid rounded shadow-sm mx-auto d-block"></div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="text-center col-12">
                        <button type="submit" class="btn btn-sm btn-primary mb-2">Simpan</button>
                        <a href="{{route('pegawai.index')}}" class="btn btn-sm btn-danger mb-2">kembali</a>
                    </div>
                </div>
                <br>
            </form>
        </div>
    </div>

    <script>
    </script>
@endsection

