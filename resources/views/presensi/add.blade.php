@extends('layouts.global')

@section('title')
   Tambah Proyek
@endsection

@section('content')
<div class="container">
    <div>
        <center>
            <img src="{{asset('storage/lundin.png')}}" class="d-none d-print-block" alt="" style="width: 100px;">
        </center>
        <h4 class="input text-center mb-0 mt-2">Tambah Presensi Proyek Pegawai</h4>
        <h3  class="text-center">Departemen Produksi</h3>
    </div>
    <hr>
    {{-- isi terlambat --}}
    <div class="container  mb-5 mt-4">
        <form action="{{route('presensi-proyek.store')}}" method="post">
            @csrf
        <div class="row ml-0 mr-0">
            <div class="col-4">
                <div class="input-group">
                    <input type="text" name="cari" class="form-control" onkeyup="load_pegawai();" placeholder="Cari Pegawai Berdasarkan Nama/SSN" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <span class="input-group-text" id="basic-addon2">
                          <i class="fa fa-search"></i>
                      </span>
                    </div>
                </div>
                <small class="form-text text-muted">Masukkan setidaknya 3 karakter.</small>
                <div id="hasil"></div>
            </div>
            <div class="col-8">
                <div class="form-group form-row">
                    <label for="inputPassword" class="col-sm-3 col-form-label">Tanggal</label>
                    <div class="col-sm-5">
                    <input type="text" class="form-control" value="{{ date('Y-m-d') }}" required disabled>
                    <input type="hidden" class="form-control" name="tanggal" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="form-group form-row">
                    <label class="col-sm-3 col-form-label">Pegawai</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control" name="pegawai" id="nama_pegawai" placeholder="Silahkan cari pegawai" disabled>
                    <input type="hidden" class="form-control" name="id_pegawai" id="id_pegawai"  required>
                    </div>
                </div>
                <div class="form-group form-row">
                    <label class="col-sm-3 col-form-label">Proyek</label>
                    <div class="col-sm-5">
                        <select name="id_proyek" class="form-control" required>
                            <option value="">Silahkan Pilih</option>
                            @foreach ($proyek as $key => $val)
                                <option value="{{ $val->id_proyek }}">{{ $val->deskripsi_proyek }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group form-row">
                    <label class="col-sm-3 col-form-label">Pekerjaan</label>
                    <div class="col-sm-5">
                        <select class="form-control" name="id_pekerjaan" id="id_pekerjaan" required>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select class="form-control" name="id_meta" id="id_meta" required>
                        </select>
                    </div>
                </div>
                <div class="form-row form-group">
                    <label for="" class="col-sm-3 col-form-label">Waktu</label>
                    <div class="col-sm-4">
                        <input type="Time" name="jam_mulai" class="form-control" required>
                        <small class="form-text text-muted">AM(Pagi) & PM(Sore).</small>
                    </div>
                    <span for="" class="col-sm-1 col-form-label text-center">S/d</span>
                    <div class="col-sm-4">
                        <input type="time" name="jam_akhir" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <div class="text-center">
                            <button id="btn" type="submit" class="btn btn-sm btn-primary mb-2">Simpan</button>
                            <a href="{{route('presensi-proyek.index')}}" class="btn btn-sm btn-danger mb-2">kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div>
    
</div>

<script type="application/javascript"> 

    // Mencari Pegawai
    var int_pencarian;
	function load_pegawai()
	{
		$('#hasil').html('');

		var dataForm = {
			cari : $('input[name="cari"]').val(),
            _token : $('meta[name="csrf-token"]').attr('content')
		}

		if(int_pencarian != undefined && int_pencarian != '')
		{
			clearTimeout(int_pencarian);
		}

		if(dataForm.cari == undefined || dataForm.cari == '')
		{
			return false;
		}

		if(dataForm.cari.length < 3)
		{
			return false;
		}

		$('#hasil').html('Memuat Data ...');
		int_pencarian = setTimeout(function(){
			clearTimeout(int_pencarian);

			$.ajax({
                url         :  '{{ url('pegawai-cari') }}',
				method		: "POST",
				data 		: dataForm,
				success		: function(result){
					$('#hasil').html(result);
				}
			})		


		}, 500);
		return false;
	}

    //  Ajax untuk meta pekerjaan dropdown  
        $(document).ready(function() {
            // Cek data pegawai
            $('button').on('click', function() {
                if($('#nama_pegawai').val()==''){
                    alert('Pegawai Belum dipilih');
                }
            });

            // mencari pekerjaan meta
            $('select[name="id_pekerjaan"]').on('focus change', function() {
                var stateID = $(this).val();
                if(stateID) {
                    $.ajax({
                        url:  '{{ url('pekerjaan') }}' + '/' +  $('select[name="id_proyek"]').val()  + '/' + $(this).val() + '/metaPresen',
                        type: "GET",
                        dataType: "json",
                        success:function(data) {

                            $('select[name="id_meta"]').empty();
                            $.each(data, function(key, value) {
                                $('select[name="id_meta"]').append('<option value="'+ key +'">'+ value +'</option>');
                            });

                        }
                    });
                }else{
                    $('select[name="id_meta"]').empty();
                }
            });

            // mencari pekerjaan
            $('select[name="id_proyek"]').on('change', function() {
                var stateID = $(this).val();
                if(stateID) {
                    $.ajax({
                        url:  '{{ url('pekerjaan') }}' + '/' + $(this).val() + '/metaKerja',
                        type: "GET",
                        dataType: "json",
                        success:function(data) {

                            $('select[name="id_pekerjaan"]').empty();
                            $.each(data, function(key, value) {
                                $('select[name="id_pekerjaan"]').append('<option value="'+ key +'">'+ value +'</option>');
                            });
                            $('select[name="id_pekerjaan"]').focus();

                        }
                    });
                }else{
                    $('select[name="id_pekerjaan"]').empty();
                }
            });
        });

    function tambah_data(nama,id,)
	{	
		$('#nama_pegawai').val(nama);
		$('#id_pegawai').val(id);
	}
</script>
@endsection
