
@section('content')
    @foreach ($data as $d)
    <tr>
        <td>{{ $d->pegawai->ssn }}</td>
        <td>{{ $d->pegawai->nama_pegawai }}</td>
        <td>{{ $d->proyek->deskripsi_proyek }}</td>
        <td>{{ $d->pekerjaan->nama_pekerjaan.' '.$d->pekerjaanMeta->nama_meta }}</td>
        <td>{{ $d->waktu_in }}</td>
        <td>{{ Helper::humanJam(Helper::jam_min($d->telat)) }}</td>
    </tr>
    @endforeach
    <tr>
        <td colspan="3">
        </td>
        <td colspan="3">
            <div class="terlambat">
                {{$data->links()}}
            </div>
        </td>
    </tr>
@endsection

@section('none')
    <tr>
        <td colspan="6">Tidak Ada Pegawai Yang Terlambat</td>
    </tr>
@endsection

