<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\RiwayatPresensi;
use App\JamKerja;
use Illuminate\Support\Carbon;


class CheckOutPresensi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'presensi:out';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Untuk Pengecekan Presensi ketika waktu istirahat dan pulang';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sekarang = date('Y-m-d h:i');
        RiwayatPresensi::where('waktu_out',NULL)->each(function ($item) {

        $item->update('waktu_out',$sekarang);

        // $sekarang = date('Y-m-d h:i');
        // $jadwal     = JamKerja::whereDate('tanggal_mulai','<=',Carbon::today()->toDateString())
        //         ->whereDate('tanggal_akhir','>=',Carbon::today()->toDateString())
        //         ->where('hari_kerja', 'like', '%'.Helper::day_to_hari(date('D')).'%')
        //         ->whereTime('jam_masuk', '<=', Carbon::now())
        //         ->whereTime('jam_pulang', '>=', Carbon::now())
        //         ->where('default','n')
        //         ->first()
        //     ;

        // if(!$jadwal){
        //     // Pengecekan Jadwal Default
        //     $jadwal     = JamKerja::where('hari_kerja', 'like', '%'.Helper::day_to_hari(date('D')).'%')
        //         ->whereTime('jam_masuk', '<=', Carbon::now())
        //         ->whereTime('jam_pulang', '>=', Carbon::now())
        //         ->where('default','y')
        //         ->first()
        //     ;
        // }

        // if($jadwal){
            
        //     // pengecekan waktu sekarang jam istirahan , lalu dicheck out yang masih in dengan jam istirahat
        //     $waktu_istirahat = date('Y-m-d h:i',strtotime($jadwal->jam_mulai_istirahat));
        //     if($sekarang == $waktu_istirahat){
        //             $item->update('waktu_out',$jadwal->jam_mulai_istirahat);
        //     }

        //     // pengecekan waktu sekarang jam pulang, lalu dicheck out yang masih in dengan jam pulang
        //     $waktu_pulang = date('Y-m-d h:i',strtotime($jadwal->jam_pulang));
        //     if($sekarang == $waktu_pulang){
        //             $item->update('waktu_out',$jadwal->jam_pulang);
        //     }
            
        // }

        });
        
    }
}
