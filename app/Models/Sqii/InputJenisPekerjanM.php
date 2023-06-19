<?php

namespace App\Models\Sqii;

use Illuminate\Database\Eloquent\Model;
use DB;

class InputJenisPekerjanM extends Model
{
	public static function show_jenis_pekerjaan()
	{
        $q = DB::connection('sqii2')
        ->table('SQII_KATEGORI_DEFECT')
        ->select(
            'KD_KATEGORI_DEFECT', 'NM_KATEGORI_DEFECT', 'DESKRIPSI', 'TIPE_DENAH'
        )
        ->where('FLAG_AKTIF', '=', 'Y')
        ->orderBy('NM_KATEGORI_DEFECT', 'asc')
        ->get();

    	return $q;
	}

    public static function kd_kategori_defect()
    {

        $q = DB::connection('sqii2')
        ->select(
            'SELECT TOP 1 (KD_KATEGORI_DEFECT + 1) AS KD_MAX FROM SQII_KATEGORI_DEFECT ORDER BY cast(KD_KATEGORI_DEFECT as numeric(5)) DESC'
        );

        $kd = 1;
        foreach ($q as $value) 
        $kd = $value->KD_MAX;

        return $kd;
    } 

    public static function simpan_dt($nm_kategori_defect,$deskripsi,$tipe_denah,$user){
        $q = DB::connection('sqii2')
        ->select(
            'SELECT TOP 1 (KD_KATEGORI_DEFECT + 1) AS KD_MAX FROM SQII_KATEGORI_DEFECT ORDER BY cast(KD_KATEGORI_DEFECT as numeric(5)) DESC'
        );

        $kd = 1;
        foreach ($q as $value) 
        $kd = $value->KD_MAX;        
        

        $result = DB::connection('sqii2')
        ->table('SQII_KATEGORI_DEFECT')
        ->insert(
        [
            'KD_KATEGORI_DEFECT'     => $kd,
            'NM_KATEGORI_DEFECT'     => $nm_kategori_defect,
            'DESKRIPSI'             => $deskripsi,
            'TIPE_DENAH'             => $tipe_denah,
            'FLAG_AKTIF'            => 'Y',
            'USER_ENTRY'            => $user,
            'TGL_ENTRY'             => now(),
        ]
        );        
    }

    public static function update_dt($kd_kategori_defect,$nm_kategori_defect,$deskripsi,$tipe_denah,$user){
        $result = DB::connection('sqii2')
        ->table('SQII_KATEGORI_DEFECT')
        ->where('KD_KATEGORI_DEFECT','=',$kd_kategori_defect)
        ->where('FLAG_AKTIF','=','Y')
        ->update(
            [
            'NM_KATEGORI_DEFECT' => $nm_kategori_defect,
            'DESKRIPSI'         => $deskripsi,
            'TIPE_DENAH'        => $tipe_denah,
            'USER_UPDATE'       => $user,
            'TGL_UPDATE'        => now()
            ]
        );        
     
    }

    public static function delete_dt($kd_kategori_defect,$user){
        $result = DB::connection('sqii2')
        ->table('SQII_KATEGORI_DEFECT')
        ->where('KD_KATEGORI_DEFECT','=',$kd_kategori_defect)
        ->where('FLAG_AKTIF','=','Y')
        ->update(
            [
            'FLAG_AKTIF'    => 'N',
            'USER_UPDATE'    => $user,
            'TGL_UPDATE'     => now()
            ]
        );        
     
    }
}
