<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';

    public function jabatan()
    {
        return $this->belongsTo('App\Jabatan','id_jabatan');
    }

    public function kelompok()
    {
        return $this->belongsTo('App\KelompokPegawai','id_kelompok');
    }

}
