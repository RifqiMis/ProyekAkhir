
@section('content')
    <table class="table table-hover">
        <tbody>
            @if(count($data)>0)
                @foreach($data as $key => $c):
                    <tr>
                        <td>
                            {{$c->nama_pegawai}}<br/>
                            <small>SSN : {{$c->ssn}}</small><br/>	
                            <small>Jabatan : {{$c->jabatan->nama_jabatan}}</small><br/>	
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" onclick="tambah_data('{{$c->nama_pegawai}}', '{{$c->id_pegawai}}')">
                                <i class="fa fa-location-arrow"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="2"> <div class="alert alert-danger">Data tidak ditemukan</div>
                    </td>
                </tr>
            @endif
        </small>
    </table>  
@endsection

