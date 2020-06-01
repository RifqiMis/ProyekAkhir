<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiwayatPekerjaan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'riwayat_pekerjaan';

    public function proyek()
    {
        return $this->belongsTo('App\Proyek','id_proyek');
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
