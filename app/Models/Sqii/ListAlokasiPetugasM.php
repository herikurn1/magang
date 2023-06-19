<?php

namespace App\Models\Sqii;

use Illuminate\Database\Eloquent\Model;
use DB;

class ListAlokasiPetugasM extends Model
{

	public static function data_blok_no($kd_kawasan='',$kd_cluster='',$kd_tipe='',$blok='')
	{

        $sql = "EXEC SQII_N_SP_DATA_ALOKASI_PENUGASAN ";
        $sql.= "'".$kd_kawasan."',";
        $sql.= "'".$kd_cluster."',";
        $sql.= "'".$kd_tipe."',";
        $sql.= "'".$blok."'";

        $q = DB::connection('sqii2')
        ->select($sql);

    	return $q;
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

    public static function tipe_rumah($kd_kawasan, $kd_cluster,$keyword)
    {
        $q = DB::connection('sqii2')
        ->table('SQII_TIPE_RUMAH as a')
        ->select( 
            'a.KD_TIPE', 'a.NM_TIPE'
        )
        ->join('SQII_STOK as b', function($join) {
            $join->on('b.KD_TIPE', '=', 'a.KD_TIPE')
                ->on('b.KD_KAWASAN', '=', 'a.KD_KAWASAN')
                ->on('b.KD_CLUSTER', '=', 'a.KD_CLUSTER');
        })
        ->where('a.KD_KAWASAN', '=', $kd_kawasan)
        ->where('a.KD_CLUSTER', '=', $kd_cluster)
        ->where('a.FLAG_AKTIF', '=', 'Y')
        ->where(function($wh) use ($keyword){
            $wh->where('a.KD_TIPE', 'like', '%'.$keyword.'%')
            ->orWhere('a.NM_TIPE', 'like', '%'.$keyword.'%');
        })
        ->groupBy('a.KD_TIPE')
        ->groupBy('a.NM_TIPE')
        ->orderBy('a.KD_TIPE', 'asc')
        //->paginate(10);
        ->get();

        return $q;
    }

    public static function simpan_dt($kd_kawasan,$kd_cluster,$blok,$nomor,$kd_jenis,$kd_tipe,$user){
        // $q = DB::connection('sqii2')

        // ->select(
        //     'SELECT MAX(ISNULL(STOK_ID,0))+1 AS STOK_ID FROM SQII_STOK WHERE 1=1 AND FLAG_KARTU_RUMAH = 'N' '
        // );

        $db_stok_id = DB::connection('sqii2')
                ->table('SQII_STOK')
                ->where('FLAG_KARTU_RUMAH',  '=', 'N')
                ->max('STOK_ID');

        $stok_id = $db_stok_id + 1;
        // foreach ($q as $value) 
        // $stok_id = $value->STOK_ID;        
        

        $result = DB::connection('sqii2')
        ->table('SQII_STOK')
        ->insert(
        [
            'KD_KAWASAN'        => $kd_kawasan,
            'KD_CLUSTER'        => $kd_cluster,
            'BLOK'              => $blok,
            'NOMOR'             => $nomor,
            'STOK_ID'           => $stok_id,
            'KD_JENIS'          => $kd_jenis,
            'KD_TIPE'           => $kd_tipe,
            'FLAG_KARTU_RUMAH'  => 'N',
            'FLAG_AKTIF'        => 'Y',
            'USER_ENTRY'        => $user,
            'TGL_ENTRY'         => now(),
        ]
        );        
    }

    public static function update_dt($kd_kawasan,$kd_cluster,$blok,$nomor,$kd_jenis,$kd_tipe,$stok_id, $user){
        $result = DB::connection('sqii2')
        ->table('SQII_STOK')
        ->where('KD_KAWASAN','=',$kd_kawasan)
        ->where('STOK_ID','=',$stok_id)
        ->where('FLAG_AKTIF','=','Y')
        ->where('FLAG_KARTU_RUMAH','=','N')
        ->update(
            [
            'BLOK'          => $blok,
            'NOMOR'         => $nomor,
            'KD_CLUSTER'    => $kd_cluster,
            'KD_JENIS'      => $kd_jenis,
            'KD_TIPE'       => $kd_tipe,
            'TGL_UPDATE'    => now(),
            'USER_UPDATE'   => $user
            ]
        );        
     
    }

    public static function cek_stok($kd_kawasan,$kd_cluster,$blok,$nomor)
    {
        $q = DB::connection('sqii2')
        ->table('SQII_STOK as a')
        ->select(
            'a.KD_KAWASAN', 'a.KD_CLUSTER', 'a.BLOK', 'a.NOMOR', 'a.KD_TIPE', 'a.KD_JENIS'
        )        ->where('a.FLAG_AKTIF', '=', 'Y')
        ->where('a.KD_KAWASAN', '=', $kd_kawasan)
        ->where('a.KD_CLUSTER', '=', $kd_cluster)
        ->where('a.BLOK', '=', $blok)
        ->where('a.NOMOR', '=', $nomor)
        ->where('a.FLAG_KARTU_RUMAH', '=', 'N')
        ->where('a.FLAG_AKTIF', '=', 'Y')
        ->orderBy('a.BLOK', 'asc')
        ->orderBy('a.NOMOR', 'asc')
        ->get();

        return $q;
    }

    public static function data_blok($kd_kawasan,$kd_cluster,$kd_tipe)
    {
        $q = DB::connection('sqii2')
        ->table('SQII_STOK as a')
        ->select( 
            'a.BLOK'
        )
        ->where('a.KD_KAWASAN', '=', $kd_kawasan)
        ->where('a.KD_CLUSTER', '=', $kd_cluster)
        ->where('a.KD_TIPE', '=', $kd_tipe)
        ->where('a.FLAG_AKTIF', '=', 'Y')
        ->groupBy('a.BLOK')
        ->orderBy('a.BLOK', 'asc')
        ->get();

        return $q;
    }

}
