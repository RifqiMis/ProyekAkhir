
@section('content')
    @foreach ($data as $d)
    <tr>
        <td>{{ $d->ssn }}</td>
        <td>{{ $d->nama_pegawai }}</td>
        <td>{{ $d->jabatan->nama_jabatan }}</td>
        <td>{{ $d->kelompok->nama_kelompok_pegawai }}</td>
    </tr>
    @endforeach
@endsection

@section('none')
    <tr>
        <td colspan="4">Tidak Ada Pegawai Yang Absen</td>
    </tr>
@endsection

