<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiwayatPresensi extends Model
{
    protected $table = 'riwayat_presensi';
    protected $primaryKey = 'id_presensi';

    public function proyek()
    {
        return $this->belongsTo('App\Proyek','id_proyek');
    }

    public function pegawai()
    {
        return $this->belongsTo('App\Pegawai','id_pegawai');
    }

    public function pekerjaan()
    {
        return $this->belongsTo('App\Pekerjaan','id_pekerjaan');
    }

    public function pekerjaanMeta()
    {
        return $this->belongsTo('App\PekerjaanMeta','id_meta');
    }
}
