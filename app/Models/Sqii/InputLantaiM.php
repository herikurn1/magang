<?php

namespace App\Models\Sqii;

use Illuminate\Database\Eloquent\Model;
use DB;

class InputLantaiM extends Model
{

	public static function show_lantai()
	{
        $q = DB::connection('sqii2')
        ->table('SQII_LANTAI as a')
        ->select(
            'a.KD_LANTAI', 'a.NM_LANTAI'
        )
        ->where('a.FLAG_AKTIF', '=', 'Y')
        ->orderBy('a.NM_LANTAI', 'asc')
        ->get();

    	return $q;
	}

    public static function search_dt($keyword)
    {

        $q = DB::connection('sqii2')
        ->table('SQII_LANTAI as a')
        ->select(
            'a.KD_LANTAI', 'a.NM_LANTAI'
        )
        ->where(function($wr) use ($keyword){
            $wr->where('a.KD_LANTAI', 'LIKE', '%'.$keyword.'%');
            $wr->Where('a.NM_LANTAI', 'LIKE', '%'.$keyword.'%');
            $wr->Where('a.FLAG_AKTIF', '=', 'Y');
        })
        ->orderBy('a.NM_LANTAI', 'asc')
        ->get();

        return $q;
    }

    public static function kd_lantai()
    {

        $q = DB::connection('sqii2')
        ->select(
            'SELECT TOP 1 (KD_LANTAI + 1) AS KD_MAX FROM SQII_LANTAI ORDER BY cast(KD_LANTAI as numeric(5)) DESC'
        );

        $kd = 1;
        foreach ($q as $value) 
        $kd = $value->KD_MAX;

        return $kd;
    } 

    public static function simpan_dt($nm_lantai,$user){
        $q = DB::connection('sqii2')
        ->select(
            'SELECT TOP 1 (KD_LANTAI + 1) AS KD_MAX FROM SQII_LANTAI ORDER BY cast(KD_LANTAI as numeric(5)) DESC'
        );

        $kd = 1;
        foreach ($q as $value) 
        $kd = $value->KD_MAX;        
        

        $result = DB::connection('sqii2')
        ->table('SQII_LANTAI')
        ->insert(
        [
            'KD_LANTAI'     => $kd,
            'NM_LANTAI'     => $nm_lantai,
            'FLAG_AKTIF'    => 'Y',
            'USER_ENTRY'    => $user,
            'TGL_ENTRY'     => now(),
        ]
        );        
    }

    public static function update_dt($kd_lantai,$nm_lantai,$user){
        $result = DB::connection('sqii2')
        ->table('SQII_LANTAI')
        ->where('KD_LANTAI','=',$kd_lantai)
        ->where('FLAG_AKTIF','=','Y')
        ->update(
            [
            'NM_LANTAI' => $nm_lantai,
            'USER_UPDATE'    => $user,
            'TGL_UPDATE'     => now()
            ]
        );        
     
    }

    public static function delete_dt($kd_lantai,$user){
        $result = DB::connection('sqii2')
        ->table('SQII_LANTAI')
        ->where('KD_LANTAI','=',$kd_lantai)
        ->where('FLAG_AKTIF','=','Y')
        ->update(
            [
            'FLAG_AKTIF' => 'N',
            'USER_UPDATE'    => $user,
            'TGL_UPDATE'     => now()
            ]
        );        
     
    }
}
