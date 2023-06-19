<?php

namespace App\Models\Sqii;

use Illuminate\Database\Eloquent\Model;
use DB;

class InputTahapPekerjaanM extends Model
{

	public static function show_tahap_pekerjaan($kd_kawasan,$kd_cluster)
	{
        $q = DB::connection('sqii2')
        ->select("
            SELECT KD_TAHAP, NM_TAHAP, FLAG_AKTIF
            FROM SQII_TAHAP_PEKERJAAN
            WHERE 1=1
                AND KD_KAWASAN = '".$kd_kawasan."' 
                AND KD_CLUSTER = '".$kd_cluster."' 
            ORDER BY CAST(SUBSTRING(NM_TAHAP + '0', PATINDEX('%[0-9]%', NM_TAHAP + '0'), LEN(NM_TAHAP + '0')) AS INT)"
        );
    	return $q;
	}

    public static function search_dt($keyword)
    {

        $q = DB::connection('sqii2')
        ->table('SQII_TAHAP_PEKERJAAN as a')
        ->select(
            'a.KD_TAHAP', 'a.NM_TAHAP'
        )
        ->where(function($wr) use ($keyword){
            $wr->where('a.KD_TAHAP', 'LIKE', '%'.$keyword.'%');
            $wr->Where('a.NM_TAHAP', 'LIKE', '%'.$keyword.'%');
            $wr->Where('a.FLAG_AKTIF', '=', 'A');
        })
        ->orderBy('a.NM_TAHAP', 'asc')
        ->get();

        return $q;
    }

    public static function simpan_dt($kd_kawasan,$kd_cluster,$kd_tahap,$nm_tahap,$user){
        // $q = DB::connection('sqii2')
        // ->select(
        //     "
        //     SELECT ISNULL(MAX(KD_TAHAP),0) + 1  AS KD_MAX 
        //     FROM SQII_TAHAP_PEKERJAAN 
        //     WHERE KD_KAWASAN = '".$kd_kawasan."' AND KD_CLUSTER = '".$kd_cluster."' 
        //     "
        // );
        // //DD($q);
        // $kd = 1;
        // foreach ($q as $value) 
        // $kd = $value->KD_MAX;        
        

        $result = DB::connection('sqii2')
        ->table('SQII_TAHAP_PEKERJAAN')
        ->insert(
        [
            'KD_TAHAP'      => $kd_tahap,
            'NM_TAHAP'      => $nm_tahap,
            'KD_KAWASAN'    => $kd_kawasan,
            'KD_CLUSTER'    => $kd_cluster,
            'FLAG_AKTIF'    => 'A',
            'USER_ENTRY'    => $user,
            'TGL_ENTRY'     => now(),
        ]
        );        
    }

    public static function update_dt($kd_kawasan,$kd_cluster,$kd_tahap,$nm_tahap,$user){
        $result = DB::connection('sqii2')
        ->table('SQII_TAHAP_PEKERJAAN')
        ->where('KD_TAHAP','=',$kd_tahap)
        ->where('KD_KAWASAN','=',$kd_kawasan)
        ->where('KD_CLUSTER','=',$kd_cluster)
        ->where('FLAG_AKTIF','=','A')
        ->update(
            [
            'NM_TAHAP'       => $nm_tahap,
            'USER_UPDATE'    => $user,
            'TGL_UPDATE'     => now()
            ]
        );        
     
    }

    public static function delete_dt($kd_tahap,$user){
        $result = DB::connection('sqii2')
        ->table('SQII_TAHAP_PEKERJAAN')
        ->where('KD_TAHAP','=',$kd_tahap)
        ->where('FLAG_AKTIF','=','A')
        ->update(
            [
            'FLAG_AKTIF' => 'N',
            'USER_UPDATE'    => $user,
            'TGL_UPDATE'     => now()
            ]
        );        
     
    }

    public static function data_kawasan()
    {
        $q = DB::connection('sqii2')
        ->table('SQII_KAWASAN as a')
        ->select( 
            'a.KD_KAWASAN', 'a.NM_KAWASAN'
        )
        ->where('a.FLAG_AKTIF', '=', 'Y')
        ->get();

        return $q;
    }

    public static function data_cluster($kd_kawasan)
    {
        $q = DB::connection('sqii2')
        ->table('SQII_CLUSTER as a')
        ->select( 
            'a.KD_CLUSTER', 'a.NM_CLUSTER'
        )
        ->where('a.KD_KAWASAN', '=', $kd_kawasan)
        ->where('a.FLAG_AKTIF', '=', 'Y')
        ->orderBy('a.NM_CLUSTER', 'asc')
        ->get();

        return $q;
    }

    public static function mst_tahap_pekerjaan($kd_kawasan,$kd_cluster)
    {
        $q = DB::connection('sqii2')
        ->select(
            "
            SELECT KD_TAHAP, NM_TAHAP
            FROM SQII_MST_TAHAP_PEKERJAAN 
            WHERE KD_TAHAP NOT IN (
                SELECT KD_TAHAP
                FROM SQII_TAHAP_PEKERJAAN 
                WHERE KD_KAWASAN = '".$kd_kawasan."' AND KD_CLUSTER = '".$kd_cluster."' 
                )
                AND FLAG_AKTIF  = 'A'
            ORDER BY CAST(SUBSTRING(NM_TAHAP + '0', PATINDEX('%[0-9]%', NM_TAHAP + '0'), LEN(NM_TAHAP + '0')) AS INT)
            "
        );
        // $q = DB::connection('sqii2')
        // ->table('SQII_MST_TAHAP_PEKERJAAN as a')
        // ->select(
        //     'a.KD_TAHAP', 'a.NM_TAHAP'
        // )
        // ->where('a.FLAG_AKTIF', '=', 'A')
        // ->orderBy('a.NM_TAHAP', 'asc')
        // ->get();

        return $q;
    }

    public static function upd_fg($kd_kawasan,$kd_cluster,$kd_tahap,$fg_aktif,$user){
        $result = DB::connection('sqii2')
        ->table('SQII_TAHAP_PEKERJAAN')
        ->where('KD_KAWASAN','=',$kd_kawasan)
        ->where('KD_CLUSTER','=',$kd_cluster)
        ->where('KD_TAHAP','=',$kd_tahap)
        ->update(
            [
            'FLAG_AKTIF'        => $fg_aktif,
            'USER_UPDATE'       => $user,
            'TGL_UPDATE'        => now()
            ]
        );        
     
    }    

}
