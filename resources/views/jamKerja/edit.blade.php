@extends('layouts.global')

@section('title')
   Tambah Jadwal Kerja
@endsection

@section('content')
    <div class="container">
        <h3 class="text-center">Edit Jadwal Kerja</h3>
    </div>
    <hr>
    <br>
    @include('components.notifikasi')
    {{-- isi konten --}}
        <div class="container">
            <form action="{{route('jam-kerja.update',[$data->id_jam_kerja])}}" method="POST">
                {{ method_field('PUT') }}
                @csrf
                <div class="row">
                    <div class="col-8">
                        <div class="form-row form-group">
                            <label for="" class="col-sm-3 col-form-label">Jam Kerja</label>
                            <input type="hidden" name="default" value="{{ $data->default }}">
                            <div class="col">
                                <input type="time" name="jam_masuk" class="form-control" value="{{ $data->jam_masuk }}">
                            </div>
                            <span for="" class="col-sm-1 col-form-label text-center">S/d</span>
                            <div class="col">
                                <input type="time" name="jam_pulang" class="form-control" value="{{ $data->jam_pulang }}">
                            </div>
                        </div>
                        <div class="form-row form-group">
                            <label for="" class="col-sm-3 col-form-label">Jam Istirahat</label>
                            <div class="col">
                                <input type="time" name="jam_mulai_istirahat" class="form-control" value="{{ $data->jam_mulai_istirahat }}">
                            </div>
                            <span for="" class="col-sm-1 col-form-label text-center">S/d</span>
                            <div class="col">
                                <input type="time" name="jam_selesai_istirahat" class="form-control" value="{{ $data->jam_selesai_istirahat }}">
                            </div>
                        </div>
                        <div class="form-row form-group">
                            <label for="" class="col-sm-3 col-form-label">Tanggal Berlaku</label>
                            <div class="col-sm-4">
                                <input type="date" name="tanggal_mulai" class="form-control" value="{{ $data->tanggal_mulai }}">
                            </div>
                            <span for="" class="col-sm-1 col-form-label text-center">S/d</span>
                            <div class="col-sm-4">
                                <input type="date" name="tanggal_akhir" class="form-control" value="{{ $data->tanggal_akhir }}" >
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <label for="inputPassword" class="col-sm-3 col-form-label">Keterangan</label>
                            <div class="col-sm-9">
                            <input type="text" class="form-control" id="inputPassword" name="keterangan" placeholder="Berisi deskripsi / nama jam kerja" value="{{ $data->keterangan }}" required>
                            </div>
                        </div>
                        <div class="form-row form-group">
                            <label for="" class="col-sm-3 col-form-label">Toleransi Telat</label>
                            <div class="col-sm-3">
                                <input type="number" name="toleransi" class="form-control" placeholder="Contoh : 15" required value="{{ Helper::time_to_int($data->toleransi) }}">
                            </div>
                            <span class="col col-form-label text-left"> Menit </span>
                        </div>
                    </div>             
                    <div class="col-4">
                        <label for="">Hari Kerja</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text">
                                <input type="checkbox" name="hari_kerja[]" aria-label="Checkbox for following text input" value="1" @if (in_array("1",explode(',',$data->hari_kerja))) {{ 'checked'}} @endif >
                              </div>
                            </div>
                            <input type="text" class="form-control" aria-label="Text input with checkbox" value="Senin" disabled>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text">
                                <input type="checkbox" name="hari_kerja[]" aria-label="Checkbox for following text input" value="2" @if (in_array("2",explode(',',$data->hari_kerja))) {{ 'checked'}} @endif>
                              </div>
                            </div>
                            <input type="text" class="form-control" aria-label="Text input with checkbox" value="Selasa" disabled>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text">
                                <input type="checkbox" name="hari_kerja[]" aria-label="Checkbox for following text input" value="3" @if (in_array("3",explode(',',$data->hari_kerja))) {{ 'checked'}} @endif>
                              </div>
                            </div>
                            <input type="text" class="form-control" aria-label="Text input with checkbox" value="Rabu" disabled>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text">
                                <input type="checkbox" name="hari_kerja[]" aria-label="Checkbox for following text input" value="4" @if (in_array("4",explode(',',$data->hari_kerja))) {{ 'checked'}} @endif>
                              </div>
                            </div>
                            <input type="text" class="form-control" aria-label="Text input with checkbox" value="Kamis" disabled>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text">
                                <input type="checkbox" name="hari_kerja[]" aria-label="Checkbox for following text input" value="5" @if (in_array("5",explode(',',$data->hari_kerja))) {{ 'checked'}} @endif>
                              </div>
                            </div>
                            <input type="text" class="form-control" aria-label="Text input with checkbox" value="Jumat" disabled>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text">
                                <input type="checkbox" name="hari_kerja[]" aria-label="Checkbox for following text input" value="6" @if (in_array("6",explode(',',$data->hari_kerja))) {{ 'checked'}} @endif>
                              </div>
                            </div>
                            <input type="text" class="form-control" aria-label="Text input with checkbox" value="Sabtu" disabled>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text">
                                <input type="checkbox" name="hari_kerja[]" aria-label="Checkbox for following text input" value="7" @if (in_array("7",explode(',',$data->hari_kerja))) {{ 'checked'}} @endif>
                              </div>
                            </div>
                            <input type="text" class="form-control" aria-label="Text input with checkbox" value="Minggu" disabled>
                        </div>
                    </div>

                </div>
                <div class="form-group row">
                    {{-- <label class="col-1 col-form-label"></label> --}}
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

