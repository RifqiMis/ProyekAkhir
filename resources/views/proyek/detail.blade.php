@extends('layouts.global')

@section('title')
   Pekerjaan Proyek
@endsection

@section('content')
    <div class="container">
        <div style="">
            <h3 class="text-left">Proyek : {{ $proyek->deskripsi_proyek }}</h3> 
            <div class="float-right">
                <a href="{{route('proyek.index')}}" class="btn btn-sm btn-danger mb-2">kembali</a>
            </div>
            <hr>
            <form action="{{route('riwayat-pekerjaan.store')}}" method="POST">
                @csrf
                <div class="form-group row">
                    <label class="col-2 col-form-label">Tambah Pekerjaan</label>
                    <div class="col-4">
                        <input type="hidden" name="id_proyek" value="{{ $proyek->id_proyek }}">
                        <select name="id_pekerjaan" class="form-control" required>
                            <option value="">Silahkan Pilih</option>
                            @foreach ($pekerjaan as $key => $val)
                                <option value="{{ $val->id_pekerjaan }}">{{ $val->nama_pekerjaan }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-4">
                        <select class="form-control" name="id_meta" id="id_meta" required>
                        </select>
                    </div>
                    <div class="col-2">
                        <button type="submit" class="btn btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
        <br>
        <h3>Daftar Pekerjaan {{ $proyek->deskripsi_proyek }} </h3>
        <br>
        @include('components.notifikasi')
        {{-- isi konten --}}
        <div class="container">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Pekerjaan</th>
                    <th scope="col">Detail Pekerjaan</th>
                    <th scope="col">Waktu Penambahan</th>
                    <th scope="col">Pilihan</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($details as $iteration => $v)
                    <tr>
                        <td>{{$iteration+1}}</td>
                        <td>{{$v->pekerjaan->nama_pekerjaan}} </td>
                        <td>{{$v->pekerjaanMeta->nama_meta}} </td>
                        <td>
                            {{$v->created_at}}
                        </td>
                        <td>
                            <a href="#" value="{{ action('RiwayatPekerjaanController@show',$v->id) }}" class="btn btn-sm btn-outline-info modalMd" data-toggle="modal" data-target="#modalMd" title="Kartu Pekerjaan"><i class="fas fa-eye"></i> Lihat Kartu</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $details->links() }}
        </div>
        <hr>
        
    </div>

<script type="application/javascript"> 
    //  Ajax untuk meta pekerjaan dropdown  
        $(document).ready(function() {
            $('select[name="id_pekerjaan"]').on('change', function() {
                var stateID = $(this).val();
                if(stateID) {
                    $.ajax({
                        url:  '{{ url('pekerjaan') }}' + '/' + $(this).val() + '/meta',
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
        });
</script>

@endsection