
@section('content')
    @foreach ($data as $d)
    <tr>
        <td>{{ $d->ssn }}</td>
        <td>{{ $d->nama_pegawai }}</td>
        <td>{{ $d->jabatan->nama_jabatan }}</td>
        <td>{{ $d->kelompok->nama_kelompok_pegawai }}</td>
    </tr>
    @endforeach
    <tr>
        <td colspan="2">
        </td>
        <td colspan="2">
            <div class="absen">
                {{$data->links()}}
            </div>
        </td>
    </tr>
@endsection

@section('none')
    <tr>
        <td colspan="4">Seluruh Pegawai telah melakukan presensi pekerjaan</td>
    </tr>
@endsection

