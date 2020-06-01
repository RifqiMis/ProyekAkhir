<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatan';
    protected $primaryKey = 'id_jabatan';

    public function pegawai()
    {
        return $this->hasMany('App\Pegawai','id_jabatan');
    }
}
