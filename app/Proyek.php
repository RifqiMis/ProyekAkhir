<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proyek extends Model
{
    protected $table = 'proyek';
    protected $primaryKey = 'id_proyek';

    public function riwayatPekerjaan()
    {
        return $this->belongsTo('App\RiwayatPekerjaan','id_proyek');
    }

    public function riwayatPresensi()
    {
        return $this->hasMany('App\RiwayatPresensi','id_proyek');
    }
}
