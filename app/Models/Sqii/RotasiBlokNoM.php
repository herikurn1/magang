<?php

namespace App\Models\Sqii;

use Illuminate\Database\Eloquent\Model;
use DB;

class RotasiBlokNoM extends Model
{

	public static function data_blok_no($kd_kawasan,$kd_cluster,$nik_petugas)
	{

        $sql = "EXEC SQII_N_SP_ALOKASI_PETUGAS ";
        $sql.= "'".$kd_kawasan."',";
        $sql.= "'".$kd_cluster."',";
        $sql.= "'".$nik_petugas."'";
        
        $q = DB::connection('sqii2')
        ->select($sql);

    	return $q;
	}

    public static function available_stok($kd_kawasan,$kd_cluster,$nik_petugas)
    {

        $sql = "EXEC SQII_N_SP_ALOKASI_AVAILABLE_PETUGAS ";
        $sql.= "'".$kd_kawasan."',";
        $sql.= "'".$kd_cluster."',";
        $sql.= "'".$nik_petugas."'";
        
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

    public static function nik_petugas($keyword)
    {
        $q = DB::connection('sqii2')
        ->table('SQII_USER as a')
        ->select( 
            'a.USER_ID', 'a.NAMA', 'b.NM_JABATAN', 'b.KD_JABATAN'
        )
        ->join('SQII_MST_JABATAN as b', function($join) {
            $join->on('b.KD_JABATAN', '=', 'a.KD_JABATAN');
        })
        ->where('a.FLAG_AKTIF', '=', 'A')
        ->where('a.KD_JABATAN', '<>', 'ADMIN')
        ->where(function($wh) use ($keyword){
            $wh->where('a.NAMA', 'like', '%'.$keyword.'%');
        })
        ->orderBy('a.NAMA', 'asc')
        ->paginate(10);
        //->get();

        return $q;
    }

    public static function simpan_dt($kd_kawasan,$kd_cluster,$blok,$nomor,$nik_petugas,$user){

        $result = DB::connection('sqii2')
        ->table('SQII_ALOKASI_PENUGASAN')
        ->insert(
        [
            'KD_KAWASAN'        => $kd_kawasan,
            'KD_CLUSTER'        => $kd_cluster,
            'BLOK'              => $blok,
            'NOMOR'             => $nomor,
            'USER_ID'           => $nik_petugas,
            'FLAG_AKTIF'        => 'A',
            'USER_ENTRY'        => $user,
            'TGL_ENTRY'         => now(),
        ]);        
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

    public static function search_blok_no($kd_kawasan,$kd_cluster, $keyword)
    {
        $q = DB::connection('sqii2')
        ->table('SQII_STOK as a')
        ->select( 
            'a.KD_KAWASAN', 'a.KD_CLUSTER', 'a.BLOK', 'a.NOMOR', 'a.KD_TIPE', 'a.KD_JENIS', 'a.STOK_ID', 'b.NM_TIPE'
        )
        ->join('SQII_TIPE_RUMAH as b', function($join) {
            $join->on('b.KD_TIPE', '=', 'a.KD_TIPE');
        })
        ->where('a.KD_KAWASAN', '=', $kd_kawasan)
        ->where('a.KD_CLUSTER', '=', $kd_cluster)
        ->where('a.FLAG_AKTIF', '=', 'Y')
        ->where('a.FLAG_KARTU_RUMAH', '=', 'N')
        ->where(function($wh) use ($keyword){
            $wh->where('a.BLOK', 'like', '%'.$keyword.'%')
            ->orWhere('a.NOMOR', 'like', '%'.$keyword.'%')
            ->orWhere('a.KD_TIPE', 'like', '%'.$keyword.'%');
        })
        ->orderBy('a.STOK_ID', 'asc')
        ->paginate(10);
        //->get();

        return $q;
    }

    public static function delete_dt($kd_kawasan,$kd_cluster,$blok,$nomor,$nik_petugas,$user){

        $result = DB::connection('sqii2')
        ->table('SQII_ALOKASI_PENUGASAN')
        ->where('KD_KAWASAN','=',$kd_kawasan)
        ->where('KD_CLUSTER','=',$kd_cluster)
        ->where('BLOK','=',$blok)
        ->where('NOMOR','=',$nomor)
        ->where('USER_ID','=',$nik_petugas)
        ->delete();       

        return $result;
    }

    public static function user_baru($user_lama,$kd_jabatan)
    {
        $q = DB::connection('sqii2')
        ->table('SQII_USER as a')
        ->select(
            'a.USER_ID', 'a.NAMA', 'b.NM_JABATAN'
        )      
        ->join('SQII_MST_JABATAN as b', function($join) {
            $join->on('b.KD_JABATAN', '=', 'a.KD_JABATAN');
        })  
        ->where('a.FLAG_AKTIF', '=', 'A')
        ->where('a.KD_JABATAN', '=', $kd_jabatan)
        ->where('a.USER_ID', '<>', $user_lama)
        ->orderBy('a.NAMA', 'asc')
        ->get();

        return $q;
    }

    public static function save_rotasi($kd_kawasan,$kd_cluster,$blok,$nomor,$user_lama,$user_baru){

        $result = DB::connection('sqii2')
        ->table('SQII_ALOKASI_PENUGASAN')
        ->where('KD_KAWASAN','=',$kd_kawasan)
        ->where('KD_CLUSTER','=',$kd_cluster)
        ->where('BLOK','=',$blok)
        ->where('NOMOR','=',$nomor)
        ->where('USER_ID','=',$user_lama)
        ->update(
            ['USER_ID'       => $user_baru]
        );   

        $result2 = DB::connection('sqii2')
        ->table('SQII_KUNJUNGAN_P35_LOG')
        ->where('KD_KAWASAN','=',$kd_kawasan)
        ->where('KD_CLUSTER','=',$kd_cluster)
        ->where('BLOK','=',$blok)
        ->where('NOMOR','=',$nomor)
        ->where('USER_ID','=',$user_lama)
        ->update(
            ['USER_ID'       => $user_baru]
        );   

        return $result;      
    }

}
