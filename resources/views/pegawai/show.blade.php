@extends('layouts.global')

{{-- @section('title', 'CRUD data') --}}
@section('content')
<div id="printableArea" class="container">
    <style>
        @page {
            /* size: A4 landscape; */
            size: 85mm 55mm;
            margin: 1mm;
            max-width: 85mm;
        }
    </style>
        <div class="media">
            <p class="align-self-start mr-2"><?= QrCode::size(140)->generate($data->ssn) ?><?= $data->ssn ?></p>
            <div class="media-body">
                <p class="mb-0">
                    @if($data->foto)
                        <img src="{{asset('storage/'.$data->foto)}}" style="width: auto;height : 40mm;">
                    @endif
                </p>
            <span class="mb-0">{{$data->nama_pegawai}}</span>
            </div>
          </div>
</div>
@endsection