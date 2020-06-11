@extends('layouts.global')

@section('title')
    Presensi
@endsection

@section('content')
    <div class="container">
        <div>
            <h3 class="text-center">Presensi Proyek</h3>
        </div>
        <hr>
        @include('components.notifikasi')

        {{-- isi konten --}}
        <div class="container mx-aut0 text-center" style="width: 50%">
            <h4>Pindai kartu pegawai Anda</h4>
            <video id="preview" style="width: 100%;"></video>
            <form action="{{route('presensi-pegawai')}}" method="get" class="qr-code">
                <div class="input-group mb-3" >
                        {{-- {{ csrf_field() }} --}}
                        <input type="text" name="ssn" class="form-control" placeholder="SSN Pegawai" aria-label="SSN Pegawai" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search"></i></button>
                        </div>
                </div>
            </form>
            <button onclick="qrCam()">Buka Kamera</button>
            {{-- <button onclick="scanner.stop()">Tutup Kamera</button> --}}
        </div>
    </div>
    {{-- <script src="{{ asset('js/all.js') }}" type="module"></script> --}}

    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js" type="module">
    </script>

    <script type="application/javascript"> 

    window.onload=function qrCam(){
    let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
	scanner.addListener('scan', function (content) {
            $('input[name="ssn"]').val(content); // Pass the scanned content value to an input field
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
