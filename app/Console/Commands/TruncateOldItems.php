<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\RiwayatPresensi;
use App\JamKerja;
use App\Helper;

class TruncateOldItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'item:hapus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tes hapus data';

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
        $data = RiwayatPresensi::where('id_pegawai','16')
        ->whereDate('waktu_in',date('Y-m-d'))
        ->get();
        
        $data->each(function ($item) {
            if($item->waktu_out == NULL){
                $item->waktu_out = date('Y-m-d H:i:s');
                $item->save();
            }elseif($item->waktu_out != NULL){
                $item->telat = '1';
                $item->save();
            }
            
        });
    }
}
