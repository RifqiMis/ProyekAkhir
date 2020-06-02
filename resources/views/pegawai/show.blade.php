@extends('layouts.global')

@section('title')
    Kartu Presensi Pegawai
@endsection

@section('content')
<div>
    <button onclick="printDiv('print')" class="btn btn-info" title="Cetak Kartu"><i class="fas fa-print"></i> .pdf</button> 
    <button onclick="downloadDom('print')" class="btn btn-success" title="Cetak Kartu"><i class="fas fa-print"></i> .jpg</button> 
</div>
<hr>
<div style="margin-left:10%;">
    <div id="print" style="
    height: 54mm;
    width: 85mm;
    ">
        <div style="
        background-color : #fff;
        border-radius: 4%;
        border: 2px solid rgb(173, 33, 33);
        height: 54mm;
        width: 85mm; 
        padding: 5px;
        background-repeat: no-repeat;
        background-position: center; 
        background-size: 50mm 50mm;
        background-image: url({{asset('storage/bg-logo.png')}});
        " class="garis">
                <img src="{{asset('storage/bg-logo.png')}}" style="
                height:50mm;
                width:50mm;
                position:absolute;
                margin-left:15mm;
                display:none;
                " class="img-bg">
                <div class="row">
                    <div class="col-12 text-center" style="margin-bottom: 10px;">
                        <img src="{{asset('storage/logo.png')}}" style="width: 80px;"> 
                    </div>
                    <div class="col-6 text-center">
                        <img  src="{{asset('storage/'.$data->foto)}}" style="max-height: 30mm;">
                        <p id="nama" style="font-size: 14px;margin:0px;" class="font-weight-bold">{{ $data->nama_pegawai }}</p>
                        <p style="font-size: 12px; margin:0px;padding:0px;"> {{ $data->jabatan->nama_jabatan.' - '.$data->kelompok->nama_kelompok_pegawai }}</p>
                    </div>
                    <div class="col-6">
                        <?php
                            $qr = $data->ssn;
                            echo QrCode::size(140)->margin(0)->generate($qr) ?>
                        <p id="ssn" style="font-size: 10px;margin:0px;" class="font-weight-bold text-center">{{ $qr }}</p>
                    </div>
                </div>
            
        </div>
    </div>
</div>
<script src="http://cdn.jsdelivr.net/g/filesaver.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.5.2/dom-to-image.min.js">
    // -- dom jpg press
    // $('#dom').click(function () {
    //     domtoimage
    //         .toJpeg(document.getElementById('print2'), {
    //             quality: 0.95
    //         })
    //         .then(function (dataUrl) {
    //             let link = document.createElement('a')
    //             link.download = 'imageQR.jpeg'
    //             link.href = dataUrl
    //             link.click()
    //         })
    // })
</script>

<script type="application/javascript"> 

    function downloadDom(div){
        // Nama file jpg
        nama = $('#ssn').html() + ' ' + $('#nama').html()
        // // -- standar
        domtoimage.toBlob(document.getElementById(div))
        .then(function (blob) {
            if(nama){
                window.saveAs(blob,""+nama+".png");
            }else{
                window.saveAs(blob, "Pegawai.png");
            }
        });
    }

</script>
@endsection