
@section('content')
    @if (!empty($data))
        @foreach ($data as $it => $d)
        <tr>
            <td>{{$data->firstItem() + $it}}</td>
            <td>{{ $d->id_proyek}}</td>
            <td>
                <a href=" {{url('beranda').'/pengerjaan?id_proyek='.$d->id_proyek}} " title="Semua Pekerjaan {{ $d->proyek->deskripsi_proyek }} hari ini">{{ $d->proyek->deskripsi_proyek }}</a>
            </td>
            <td>
                <a href=" {{url('beranda').'/pengerjaan?id_proyek='.$d->id_proyek.'&id_pekerjaan='.$d->id_pekerjaan}} " title="Semua Pekerjaan {{ $d->proyek->deskripsi_proyek }} di {{ $d->pekerjaan->nama_pekerjaan}} hari ini">{{ $d->pekerjaan->nama_pekerjaan}}</a> 
                
                <a href=" {{url('beranda').'/pengerjaan?id_proyek='.$d->id_proyek.'&id_pekerjaan='.$d->id_pekerjaan.'&id_meta='.$d->id_meta}} " title="Semua Pekerjaan {{ $d->proyek->deskripsi_proyek }} di {{ $d->pekerjaan->nama_pekerjaan}} {{$d->pekerjaanMeta->nama_meta }} hari ini">{{$d->pekerjaanMeta->nama_meta }}</a>
            </td>
        </tr>
        @endforeach
        <tr>
            <td colspan="2">
            </td>
            <td colspan="2" class="hari-ini">
                    {{$data->links()}}
            </td>
        </tr>
    @else
        <tr>
            <td colspan="4">Tidak Ada Data</td>
        </tr>
    @endif
@endsection

@section('none')
    <tr>
        <td colspan="4">Belum ada Presensi</td>
    </tr>
@endsection
