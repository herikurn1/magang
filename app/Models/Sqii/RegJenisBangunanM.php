<?php

namespace App\Models\Sqii;

use Illuminate\Database\Eloquent\Model;
use DB;

class RegJenisBangunanM extends Model
{
	public static function show_jenis_bangunan()
	{
        $q = DB::connection('sqii2')
        ->table('SQII_JENIS_BANGUNAN as a')
        ->select(
            'a.KD_JENIS', 'a.NM_JENIS'
        )
        ->where('a.FLAG_AKTIF', '=', 'Y')
        ->orderBy('a.KD_JENIS', 'asc')
        ->get();

    	return $q;
	}

    public static function sync_dt()
    {
        $q = DB::connection('sqii2')
        ->table('SQII_JENIS_BANGUNAN as a')
        ->select(
            'a.KD_JENIS', 'a.NM_JENIS'
        )
        ->where('a.FLAG_AKTIF', '=', 'Y')
        ->orderBy('a.KD_JENIS', 'asc')
        ->get();

        return $q;
        // 
        // NANTI PAKE YG INI YA
        // 
        // $sql = "EXEC SQII_N_SP_SYNC_JENIS_BANGUNAN ";
        // $sql.= "'".$user_id."'";
        // $q = DB::select($sql);

        // return $q;
    }        

    public static function sync_dt_v2($user)
    {
        // get cluster
        // call data local
        $q = DB::connection('sqii')
        ->select("
            SELECT A.KD_JENIS, A.DESKRIPSI, A.FLAG_AKTIF
            FROM [DBCOR].[SRIS].[DBO].[JENIS_BANGUNAN] A
            UNION
            SELECT A.KD_JENIS, A.DESKRIPSI, A.FLAG_AKTIF
            FROM [DBPSS].[SRIS].[DBO].[JENIS_BANGUNAN] A
        ");

        foreach ($q as $row) {

            $kd_jenis = $row->KD_JENIS;
            $deskripsi = $row->DESKRIPSI;
            $flag_aktif = $row->FLAG_AKTIF;
            
            $flag_aktif_jenis = 'N';
            if($flag_aktif == "A"){
                $flag_aktif_jenis = 'Y';
            }
            
            // cek data cloud sudah ada atau blm
            $cek_jenis_bangunan_doesn_exists = DB::connection('sqii2')
                ->table('SQII_JENIS_BANGUNAN')
                ->where('KD_JENIS',  '=', $kd_jenis)
                ->doesntExist();

            if($cek_jenis_bangunan_doesn_exists){
                // insert data ke cloud
                $result = DB::connection('sqii2')
                ->table('SQII_JENIS_BANGUNAN')
                ->insert(
                [
                    'KD_JENIS'      => $kd_jenis,
                    'NM_JENIS'      => $deskripsi,
                    'FLAG_AKTIF'    => $flag_aktif_jenis,
                    'USER_ENTRY'    => $user,
                    'TGL_ENTRY'     => now(),
                ]
                );
            }
        }
    }      
}
