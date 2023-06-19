<?php

namespace App\Models\Sqii;

use Illuminate\Database\Eloquent\Model;
use DB;

class InputCatatDefectM extends Model
{
	public static function show_data_item_defect($kd_item_defect)
	{
        $q = DB::connection('sqii2')
        ->table('SQII_CATATAN as a')
        ->select(
            'a.KD_CATATAN', 'a.KD_ITEM_DEFECT', 'a.DESKRIPSI', 'b.NM_ITEM_DEFECT'
        )
        ->join('SQII_ITEM_DEFECT as b', function($join) {
            $join->on('b.KD_ITEM_DEFECT', '=', 'a.KD_ITEM_DEFECT');
        })
        ->where('a.FLAG_AKTIF', '=', 'Y')
        ->where('b.KD_ITEM_DEFECT', '=', $kd_item_defect)
        ->orderBy('a.KD_CATATAN', 'asc')
        ->get();

    	return $q;
	}

    public static function show_data_kategori_defect()
    {
        $q = DB::connection('sqii2')
        ->table('SQII_ITEM_DEFECT as a')
        ->select(
            'a.KD_ITEM_DEFECT', 'a.NM_ITEM_DEFECT'
        )
        ->where('a.FLAG_AKTIF', '=', 'Y')
        ->orderBy('a.NM_ITEM_DEFECT', 'asc')
        ->get();

        return $q;
    }

    public static function simpan_dt($kd_item_defect,$deskripsi,$user){
        $q = DB::connection('sqii2')
        ->select(
            'SELECT TOP 1 (KD_CATATAN + 1) AS KD_MAX FROM SQII_CATATAN ORDER BY cast(KD_CATATAN as numeric(5)) DESC'
        );

        $kd = 1;
        foreach ($q as $value) 
        $kd = $value->KD_MAX;        
        

        $result = DB::connection('sqii2')
        ->table('SQII_CATATAN')
        ->insert(
        [
            'KD_ITEM_DEFECT'    => $kd_item_defect,
            'KD_CATATAN'        => $kd,
            'DESKRIPSI'         => $deskripsi,
            'FLAG_AKTIF'        => 'Y',
            'USER_ENTRY'        => $user,
            'TGL_ENTRY'         => now(),
        ]
        );        
    }

    public static function update_dt($kd_item_defect,$kd_catatan,$deskripsi,$user){
        $result = DB::connection('sqii2')
        ->table('SQII_CATATAN')
        ->where('KD_ITEM_DEFECT','=',$kd_item_defect)
        ->where('KD_CATATAN','=',$kd_catatan)
        ->update(
            [
            'DESKRIPSI'         => $deskripsi,
            'USER_UPDATE'       => $user,
            'TGL_UPDATE'        => now()
            ]
        );        
     
    }

    public static function delete_dt($kd_item_defect,$kd_catatan,$user){
        $result = DB::connection('sqii2')
        ->table('SQII_CATATAN')
        ->where('KD_ITEM_DEFECT','=',$kd_item_defect)
        ->where('KD_CATATAN','=',$kd_catatan)
        ->where('FLAG_AKTIF','=','Y')
        ->update(
            [
            'FLAG_AKTIF'        => 'N',
            'USER_UPDATE'       => $user,
            'TGL_UPDATE'        => now()
            ]
        );        
     
    }
}
