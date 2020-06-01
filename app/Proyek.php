<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proyek extends Model
{
    protected $table = 'proyek';
    protected $primaryKey = 'id_proyek';
    // public $timestamps = false;

    public function riwayatPekerjaan()
    {
        return $this->belongsTo('App\RiwayatPekerjaan','id_proyek');
    }
}
