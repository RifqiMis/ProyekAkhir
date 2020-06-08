<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PekerjaanMeta extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pekerjaan_meta';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_meta';
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    public function pekerjaan()
    {
        return $this->belongsTo('App\Pekerjaan','id_pekerjaan');
    }

    public function riwayatPekerjaan()
    {
        return $this->belongsTo('App\RiwayatPekerjaan','id_meta');
    }

    public function riwayatPresensi()
    {
        return $this->hasMany('App\RiwayatPresensi','id_meta');
    }
}
