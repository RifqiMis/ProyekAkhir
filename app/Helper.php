<?php

namespace App;
use Illuminate\Support\Carbon;

class Helper
{
    public static function hari($isi){
        $array = explode(',',$isi);
        for ($i=0; $i < count($array); $i++) { 
            if($array[$i]==1)
                $array[$i] = 'Senin';
            elseif ($array[$i]==2)
                $array[$i] = 'Selasa';
            elseif ($array[$i]==3)
                $array[$i] = 'Rabu';
            elseif ($array[$i]==4)
                $array[$i] = 'Kamis';
            elseif ($array[$i]==5)
                $array[$i] = "Jum'at";
            elseif ($array[$i]==6)
                $array[$i] = 'Sabtu';
            elseif ($array[$i]==7)
                $array[$i] = 'Minggu';
        }
        return implode(', ',$array);
    }

    public static function day_to_hari($isi){
        if($isi=='Mon'){
            $isi = 1;
        }elseif($isi=='Tue'){
            $isi = 2;
        }elseif($isi=='Wed'){
            $isi = 3;
        }elseif($isi=='Thu'){
            $isi = 4;
        }elseif($isi=='Fri'){
            $isi = 5;
        }elseif($isi=='Sat'){
            $isi = 6;
        }elseif($isi=='Sun'){
            $isi = 7;
        }
        return $isi;
    }

    public static function jam_min($minutes){
        if($minutes <= 0) 
            return Date("H:i:s",strtotime("00:00:00"));
        else    
            $hasil = sprintf("%02d",floor($minutes / 60)).':'.sprintf("%02d",str_pad(($minutes % 60), 2, "0", STR_PAD_LEFT)).':00';
            return Date("H:i:s",strtotime("$hasil"));
    }

    /**
     * Mengkonversi data berformat waktu menjadi string
     *
     * @param  $time
     * @return ($hours * 60) + $minutes;
     */
    public static function time_to_int($time){
        if($time != '0') {
            list($hours, $minutes) = explode(':', $time);
            return ($hours * 60) + $minutes;
        }
        return 0;
    }

    /**
     * Mengkonversi nilai, Contoh : 1 menjadi 01
     *
     * @param  $id
     * @return $id
     */
    public static function convert_id($id){
        if( strlen($id) == 1 ){
            return '0'.$id;
        }else
            return $id;
    }

    //convert 2 time to diff
    public static function time2Diff($waktu_in,$waktu_out){
        $date1 = Carbon::parse($waktu_in);
        $date2 = Carbon::parse($waktu_out);
        return $date2->diff($date1)->format("%H:%I:%S");
    }


    public static function SumTime($times) {
        $minutes = 0; //declare minutes either it gives Notice: Undefined variable
        // loop throught all the times
        foreach ($times as $time) {
            list($hour, $minute) = explode(':', $time);
            $minutes += $hour * 60;
            $minutes += $minute;
        }
    
        $hours = floor($minutes / 60);
        $minutes -= $hours * 60;
    
        // the time already formatted
        $jam = sprintf('%02d:%02d', $hours, $minutes);

        // total jam format
        $array1=explode(':',$jam);
        $total_jam=ltrim($array1[0],'0');
        $total_menit=ltrim($array1[1],'0');
        if($total_jam!=0&&$total_menit!=0){
            $result = $total_jam.' jam '.$total_menit.' menit';
        }elseif($total_jam!=0){
            $result = $total_jam.' jam ';
        }elseif($total_menit!=0){
            $result = $total_menit.' menit ';
        }else{
            $result = '-';
        }
        
        return $result;
    }

    public static function humanJam($jam){
        // total jam format
        $array1=explode(':',$jam);
        $total_jam=ltrim($array1[0],'0');
        $total_menit=ltrim($array1[1],'0');
        if($total_jam!=0&&$total_menit!=0){
            $result = $total_jam.' jam '.$total_menit.' menit';
        }elseif($total_jam!=0){
            $result = $total_jam.' jam ';
        }elseif($total_menit!=0){
            $result = $total_menit.' menit ';
        }else{
            $result = '-';
        }
        
        return $result;
    }
}
