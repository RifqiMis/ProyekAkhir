<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pekerjaan extends Model
{
    protected $table = 'pekerjaan';
    protected $primaryKey = 'id_pekerjaan';

    public function pekerjaan()
    {
        return $this->hasMany('App\PekerjaanMeta','id_pekerjaan');
    }

    public function riwayat()
    {
        return $this->hasMany('App\RiwayatPekerjaan','id_pekerjaan');
    }

    public function riwayatPresensi()
    {
        return $this->hasMany('App\RiwayatPresensi','id_pekerjaan');
    }
}
