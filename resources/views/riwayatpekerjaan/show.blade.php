@extends('layouts.global')

@section('title')
    Kartu Pekerja
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
        border: 2px solid rgb(33, 145, 173);
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
                " class="d-none d-print-block">
                <div class="row">
                    <div class=" col-12 text-center" style="margin-bottom: 10px;">
                        <img src="{{asset('storage/logo.png')}}" style="width: 80px;"> 
                    </div>
                    <div class="col-6">
                        <p id="pekerjaan" style="font-size: 13px;margin:0px;" class="font-weight-bold">{{ $data->pekerjaan->nama_pekerjaan.' '.$data->pekerjaanMeta->nama_meta }}</p>
                        <p id="proyek" style="font-size: 10px"> {{ $data->proyek->deskripsi_proyek }}</p>
                        <img id="print2" src="{{asset('storage/'.$data->proyek->foto)}}" style="max-width: 100%">
                    </div>
                    <div class="col-6">
                        <?php
                            $qr = $data->id_proyek.'-'.$data->id_pekerjaan.'-'.$data->id_meta;
                            echo QrCode::size(140)->margin(0)->generate($qr) ?>
                        <p style="font-size: 10px;margin:0px;" class="font-weight-bold text-center">{{ $qr }}</p>
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
        nama = $('#proyek').html() + ' ' + $('#pekerjaan').html()
        // // -- standar
        domtoimage.toBlob(document.getElementById(div))
        .then(function (blob) {
            if(nama){
                window.saveAs(blob,nama+".png");
            }else{
                window.saveAs(blob, "Pekerjaan.png");
            }
        });

    // // -- resize to big 
    // var node = document.getElementById(div);
    // const scale = 750 / node.offsetWidth;
    // this.shot_loading = true;

    // domtoimage
    // .toPng(node, {
    //     height: node.offsetHeight * scale,
    //     width: node.offsetWidth * scale,
    //     style: {
    //     transform: "scale(" + scale + ")",
    //     transformOrigin: "top left",
    //     width: node.offsetWidth + "px",
    //     height: node.offsetHeight + "px"
    //     }
    // })
    // .then(dataUrl => {
    //     let link = document.createElement('a')
    //                         link.download = 'imageQR.jpeg'
    //                         link.href = dataUrl
    //                         link.click()
    //     this.baseData = dataUrl;
    //     this.shot_loading = false;
    // })
    // .catch(error => {
    //     this.shot_loading = false;
    //     console.error("oops, something went wrong!", error);
    // });

 }

 $('#dom').click(function () {
        domtoimage
            .toJpeg(document.getElementById('print'), {
                quality: 1
            })
            .then(function (dataUrl) {
                let link = document.createElement('a')
                link.download = 'imageQR.jpg'
                link.href = dataUrl
                link.click()
            })
    })

</script>
@endsection