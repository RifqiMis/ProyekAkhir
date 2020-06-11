@extends('layouts.global')

@section('title')
    Presensi
@endsection

@section('content')
    <div class="container">
        <div>
            <h3 class="text-center">Presensi Proyek</h3>
            <hr>
        </div>

        {{-- isi konten --}}
        <div class="container row">
            <a href="{{ url('/') }}" class="btn btn-sm btn-danger"> <i class="fa fa-back"></i> Kembali</a>
        </div>
        <br>
        @include('components.notifikasi')
        <div class="container row">
            <div class="col-6">
                <div class="text-center">
                    @if($data->foto)
                        <img src="{{asset('storage/'.$data->foto)}}" style="max-height: 300px">
                    @endif
                    <hr>
                    <h4>{{$data->nama_pegawai}}</h4>
                    <p style="font-size: 14px; margin:0px;padding:0px;"> {{ $data->jabatan->nama_jabatan.' - '.$data->kelompok->nama_kelompok_pegawai }}</p>
                    <p> SSN : 
                        <span class="font-weight-bold">
                            {{ $data->ssn}}
                        </span>
                    </p>
                </div>
            </div>
            <div class="col-6 text-center">
                <h4>Pindai kartu Pekerjaan Anda</h4>
                <video id="preview" style="width: 100%;"></video>
                <form action="{{route('presensi-pekerjaan')}}" method="get" class="qr-code">
                    <div class="input-group mb-3" >
                            {{ csrf_field() }}
                            <input type="hidden" name="id_pegawai" value="{{$data->id_pegawai}}" >
                            <input type="hidden" name="ssn" value="{{$data->ssn}}" >
                            <input type="text" name="kode_pekerjaan" class="form-control" placeholder="QR Code Pekerjaan" aria-label="SSN Pegawai" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search"></i></button>
                            </div>
                    </div>
                </form>
            </div>
        </div>
        
    </div>

    {{-- <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js" type="module">
    </script> --}}

    <script type="application/javascript"> 

    window.onload=function qrCam(){
    let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
	scanner.addListener('scan', function (content) {
            $('input[name="kode_pekerjaan"]').val(content); // Pass the scanned content value to an input field
			$('.qr-code').submit();
			scanner.stop()
		});
		Instascan.Camera.getCameras().then(function (cameras) {
			if (cameras.length > 0) {
			scanner.start(cameras[0]);
			} else {
			console.error('No cameras found.');
			}
		}).catch(function (e) {
			console.error(e);
		});
    }  
        
    </script>
@endsection
