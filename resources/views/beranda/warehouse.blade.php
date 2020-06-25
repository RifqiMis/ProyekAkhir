@section('content')
    @foreach ($data as $d)
    <tr>
        <td>{{ $d->pegawai->ssn }}</td>
        <td>{{ $d->pegawai->nama_pegawai }}</td>
        <td>
            @if($d->pegawai->foto)
            <img src="{{asset('storage/'.$d->pegawai->foto)}}" class="img-table">
            @endif
        </td>
        <td>{{ $d->proyek->deskripsi_proyek }}</td>
        <td>{{ $d->pekerjaan->nama_pekerjaan.' '.$d->pekerjaanMeta->nama_meta }}</td>
        <td>{{ date('H:i:s',strtotime($d->waktu_in)) }} WIB</td>
    </tr>
    @endforeach
    <tr>
        <td colspan="3">
        </td>
        <td colspan="3">
            <div class="warehouse">
                {{$data->links()}}
            </div>
        </td>
    </tr>
@endsection

@section('none')
    <tr>
        <td colspan="6">Tidak ada data</td>
    </tr>
@endsection