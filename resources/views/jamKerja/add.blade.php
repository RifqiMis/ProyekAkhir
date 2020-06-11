@extends('layouts.global')

@section('title')
   Tambah Jadwal Kerja
@endsection

@section('content')
    <div class="container">
        <h3 class="text-center">Tambah Jadwal Kerja</h3>
    </div>
    <hr>
    <br>
    @include('components.notifikasi')
    {{-- isi konten --}}
        <div class="container">
            <form action="{{route('jam-kerja.store')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-8">
                        <div class="form-row form-group">
                            <label for="" class="col-sm-3 col-form-label">Jam Kerja</label>
                            <div class="col">
                                <input type="time" name="jam_masuk" class="form-control">
                            </div>
                            <span for="" class="col-sm-1 col-form-label text-center">S/d</span>
                            <div class="col">
                                <input type="time" name="jam_pulang" class="form-control">
                            </div>
                        </div>
                        <div class="form-row form-group">
                            <label for="" class="col-sm-3 col-form-label">Jam Istirahat</label>
                            <div class="col">
                                <input type="time" name="jam_mulai_istirahat" class="form-control">
                            </div>
                            <span for="" class="col-sm-1 col-form-label text-center">S/d</span>
                            <div class="col">
                                <input type="time" name="jam_selesai_istirahat" class="form-control">
                            </div>
                        </div>
                        <div class="form-row form-group">
                            <label for="" class="col-sm-3 col-form-label">Tanggal Berlaku</label>
                            <div class="col-sm-4">
                                <input type="date" name="tanggal_mulai" class="form-control">
                            </div>
                            <span for="" class="col-sm-1 col-form-label text-center">S/d</span>
                            <div class="col-sm-4">
                                <input type="date" name="tanggal_akhir" class="form-control">
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <label for="inputPassword" class="col-sm-3 col-form-label">Keterangan</label>
                            <div class="col-sm-9">
                              <input type="text" class="form-control" id="inputPassword" name="keterangan" placeholder="Berisi deskripsi / nama jam kerja" required>
                            </div>
                        </div>
                        <div class="form-row form-group">
                            <label for="" class="col-sm-3 col-form-label">Toleransi Telat</label>
                            <div class="col-sm-3">
                                <input type="number" name="toleransi" class="form-control" placeholder="Contoh : 15" required>
                            </div>
                            <span class="col col-form-label text-left"> Menit </span>
                        </div>
                    </div>             
                    <div class="col-4">
                        <label for="">Hari Kerja</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text">
                                <input type="checkbox" name="hari_kerja[]" aria-label="Checkbox for following text input" value="1">
                              </div>
                            </div>
                            <input type="text" class="form-control" aria-label="Text input with checkbox" value="Senin" disabled>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text">
                                <input type="checkbox" name="hari_kerja[]" aria-label="Checkbox for following text input" value="2">
                              </div>
                            </div>
                            <input type="text" class="form-control" aria-label="Text input with checkbox" value="Selasa" disabled>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text">
                                <input type="checkbox" name="hari_kerja[]" aria-label="Checkbox for following text input" value="3">
                              </div>
                            </div>
                            <input type="text" class="form-control" aria-label="Text input with checkbox" value="Rabu" disabled>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text">
                                <input type="checkbox" name="hari_kerja[]" aria-label="Checkbox for following text input" value="4">
                              </div>
                            </div>
                            <input type="text" class="form-control" aria-label="Text input with checkbox" value="Kamis" disabled>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text">
                                <input type="checkbox" name="hari_kerja[]" aria-label="Checkbox for following text input" value="5">
                              </div>
                            </div>
                            <input type="text" class="form-control" aria-label="Text input with checkbox" value="Jumat" disabled>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text">
                                <input type="checkbox" name="hari_kerja[]" aria-label="Checkbox for following text input" value="6">
                              </div>
                            </div>
                            <input type="text" class="form-control" aria-label="Text input with checkbox" value="Sabtu" disabled>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text">
                                <input type="checkbox" name="hari_kerja[]" aria-label="Checkbox for following text input" value="7">
                              </div>
                            </div>
                            <input type="text" class="form-control" aria-label="Text input with checkbox" value="Minggu" disabled>
                        </div>
                    </div>

                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <div class="text-center">
                            <button type="submit" class="btn btn-sm btn-primary mb-2">Simpan</button>
                            <a href="{{route('jam-kerja.index')}}" class="btn btn-sm btn-danger mb-2">kembali</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <br>
    </div>
@endsection

