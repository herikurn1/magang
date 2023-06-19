<?php

namespace App\Models\Sqii;

use Illuminate\Database\Eloquent\Model;
use DB;

class GenQrcodeM extends Model
{
	public static function show_data_item_defect($kd_kategori)
	{
        $q = DB::connection('sqii2')
        ->table('SQII_ITEM_DEFECT as a')
        ->select(
            'a.KD_ITEM_DEFECT', 'a.NM_ITEM_DEFECT', 'b.KD_KATEGORI_DEFECT', 'b.NM_KATEGORI_DEFECT'
        )
        ->join('SQII_KATEGORI_DEFECT as b', function($join) {
            $join->on('b.KD_KATEGORI_DEFECT', '=', 'a.KD_KATEGORI_DEFECT');
        })
        ->where('a.FLAG_AKTIF', '=', 'Y')
        ->where('b.KD_KATEGORI_DEFECT', '=', $kd_kategori)
        ->orderBy('a.NM_ITEM_DEFECT', 'asc')
        ->get();

    	return $q;
	}

    public static function simpan_dt($nm_item_defect,$kd_kategori_defect,$user){
        $q = DB::connection('sqii2')
        ->select(
            'SELECT TOP 1 (KD_ITEM_DEFECT + 1) AS KD_MAX FROM SQII_ITEM_DEFECT ORDER BY cast(KD_ITEM_DEFECT as numeric(5)) DESC'
        );

        $kd = 1;
        foreach ($q as $value) 
        $kd = $value->KD_MAX;        
        

        $result = DB::connection('sqii2')
        ->table('SQII_ITEM_DEFECT')
        ->insert(
        [
            'KD_ITEM_DEFECT'     => $kd,
            'NM_ITEM_DEFECT'     => $nm_item_defect,
            'KD_KATEGORI_DEFECT' => $kd_kategori_defect,
            'FLAG_AKTIF'        => 'Y',
            'USER_ENTRY'        => $user,
            'TGL_ENTRY'         => now(),
        ]
        );        
    }

    public static function update_dt($kd_item_defect,$nm_item_defect,$user){
        $result = DB::connection('sqii2')
        ->table('SQII_ITEM_DEFECT')
        ->where('KD_ITEM_DEFECT','=',$kd_item_defect)
        ->where('FLAG_AKTIF','=','Y')
        ->update(
            [
            'NM_LANTAI' => $nm_lantai,
            'USER_UPDATE'    => $user,
            'TGL_UPDATE'     => now()
            ]
        );        
     
    }

    public static function delete_dt($kd_item_defect,$user){
        $result = DB::connection('sqii2')
        ->table('SQII_ITEM_DEFECT')
        ->where('KD_ITEM_DEFECT','=',$kd_item_defect)
        ->where('FLAG_AKTIF','=','Y')
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

    public static function search_dt($kd_kawasan,$kd_cluster)
    {
        $q = DB::connection('sqii2')
        ->table('SQII_STOK as a')
        ->select(
            'a.KD_KAWASAN', 'a.KD_CLUSTER', 'a.BLOK', 'a.NOMOR', 'b.KD_TIPE', 'b.NM_TIPE', 'a.KD_QRCODE'
        )
        ->join('SQII_TIPE_RUMAH as b', function($join) {
            $join->on('b.KD_TIPE', '=', 'a.KD_TIPE');
        })
        ->where('a.FLAG_AKTIF', '=', 'Y')
        ->where('a.KD_KAWASAN', '=', $kd_kawasan)
        ->where('a.KD_CLUSTER', '=', $kd_cluster)
        ->where('b.FLAG_AKTIF', '=', 'Y')
        ->orderBy('a.BLOK', 'asc')
        ->orderBy('a.NOMOR', 'asc')
        ->get();

        return $q;
    }
}
