<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KelompokPegawai extends Model
{
    protected $table = 'kelompok_pegawai';
    protected $primaryKey = 'id_kelompok_pegawai';

    public function pegawai()
    {
        return $this->hasMany('App\Pegawai','id_kelompok_pegawai');
    }
}
