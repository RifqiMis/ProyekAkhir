
@section('content')
    @if (!empty($data))
        @foreach ($data as $d)
        <tr>
            <td>{{ $d->id_proyek }}</td>
            <td>{{ $d->pegawai->nama_pegawai }}</td>
            <td>{{ $d->proyek->deskripsi_proyek }}</td>
            <td>{{ $d->pekerjaan->nama_pekerjaan.' '.$d->pekerjaanMeta->nama_meta }}</td>
            <td> {{date('h:i:s',strtotime($d->waktu_in)).' - '.date('h:i:s',strtotime($d->waktu_out))}} </td>
            <td>{{ Helper::humanJam(Helper::time2Diff($d->waktu_in,$d->waktu_out)) }}</td>
            @if (Auth::user()->role=='admin')
                <td scope="col" class="d-print-none">
                    <form action="{{url("presensi-proyek/{$d->id_presensi}")}}" method="post">
                        <a href="{{url("presensi-proyek/{$d->id_presensi}/edit")}}" class="btn btn-outline-secondary btn-sm" title="Edit Waktu">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if ( date('Y-m-d',strtotime($d->waktu_in)) == date('Y-m-d'))
                            <button class="btn btn-outline-danger btn-sm" title="Hapus Permanen" onclick="return confirm('Hapus permanen data ini?')">
                                <i class="fas fa-trash"></i>
                            </button>
                            {{ method_field('DELETE') }}
                        @endif
                        {{ csrf_field() }}
                    </form>
                </td>
            @endif
        </tr>
        @endforeach
    @else
        <tr>
            <td colspan="3">Tidak Ada Pegawai Yang Terlambat</td>
        </tr>
    @endif
@endsection

