<?php

namespace App\Models\Sqii;

use Illuminate\Database\Eloquent\Model;
use DB;

class ManagementPetugasM extends Model
{

	public static function data_blok_no($nik_petugas)
	{

        $sql = "EXEC SQII_N_SP_ENTRY_JABATAN_LIST_BAWAHAN ";
        $sql.= "'".$nik_petugas."'";
        
        $q = DB::connection('sqii2')
        ->select($sql);

    	return $q;
	}

    public static function available_stok($nik_petugas)
    {

        $sql = "EXEC SQII_N_SP_ENTRY_JABATAN_AVAILABLE_PETUGAS ";
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


    public static function nik_petugas($keyword)
    {
        $q = DB::connection('sqii2')
        ->table('SQII_USER as a')
        ->select( 
            'a.USER_ID', 'a.NAMA', 'b.NM_JABATAN', 'b.KD_JABATAN', 'c.KD_KAWASAN', 'a.FLAG_AKTIF'
        )
        ->leftjoin('SQII_MST_JABATAN as b', function($join) {
            $join->on('b.KD_JABATAN', '=', 'a.KD_JABATAN');
        })
        ->leftjoin('SQII_KAWASAN_USER as c', function($join) {
            $join->on('c.USER_ID', '=', 'a.USER_ID');
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

    public static function nik_karyawan($keyword)
    {
        $q = DB::connection('sqii2')
        ->table('SQII_USER as a')
        ->select( 
            'a.USER_ID', 'a.NAMA', 'a.FLAG_AKTIF', 'b.KD_JABATAN',
        )
        ->leftjoin('SQII_MST_JABATAN as b', function($join) {
            $join->on('b.KD_JABATAN', '=', 'a.KD_JABATAN');
        })
        ->where('a.FLAG_AKTIF', '=', 'A')
        ->where(function($wh) use ($keyword){
            $wh->where('a.NAMA', 'like', '%'.$keyword.'%');
        })
        ->orderBy('a.NAMA', 'asc')
        ->paginate(10);
        //->get();

        return $q;
    }

    public static function nik_karyawan_v2($keyword)
    {
        $q = DB::connection('hrms')
        ->table('MST_KARYAWAN as a')
        ->select( 
            'a.NO_INDUK', 'a.NAMA'
        )
        ->where('a.FLAG_AKTIF', '=', 'A')
        ->where(function($wh) use ($keyword){
            $wh->where('a.NAMA', 'like', '%'.$keyword.'%');
        })
        ->orderBy('a.NAMA', 'asc')
        ->paginate(10);
        //->get();

        return $q;
    }

    public static function simpan_dt($kd_kawasan, $kd_jabatan, $nik_petugas, $nm_petugas, $flag_aktif, $user){

        $result = DB::connection('sqii2')
        ->table('SQII_USER')
        ->insert(
        [
            'USER_ID'           => $nik_petugas,
            'KD_JABATAN'        => $kd_jabatan,
            'NAMA'              => $nm_petugas,
            'PASSWORD'          => '123456',
            'FLAG_AKTIF'        => $flag_aktif,
            'USER_ENTRY'        => $user,
            'TGL_ENTRY'         => now(),
        ]);    

        $result = DB::connection('sqii2')
        ->table('SQII_KAWASAN_USER')
        ->insert(
        [
            'USER_ID'           => $nik_petugas,
            'KD_KAWASAN'        => $kd_kawasan,
            'USER_ENTRY'        => $user,
            'TGL_ENTRY'         => now(),
        ]);        
    }

    public static function update_dt($kd_jabatan, $nik_petugas, $nm_petugas, $flag_aktif, $user){
        $result = DB::connection('sqii2')
        ->table('SQII_USER')
        ->where('USER_ID','=',$nik_petugas)
        ->update(
            [
            'KD_JABATAN'        => $kd_jabatan,
            'NAMA'              => $nm_petugas,
            'FLAG_AKTIF'        => $flag_aktif,
            'TGL_UPDATE'        => now(),
            'USER_UPDATE'       => $user
            ]
        );         
    }

    public static function simpan_bawahan($id_petugas, $id_bawahan, $user){

        $result = DB::connection('sqii2')
        ->table('SQII_BAWAHAN')
        ->insert(
        [
            'USER_ID'           => $id_petugas,
            'USER_ID_BAWAHAN'   => $id_bawahan,
            'USER_ENTRY'        => $user,
            'TGL_ENTRY'         => now(),
        ]);           
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

    public static function delete_dt($nik_petugas){

        $result = DB::connection('sqii2')
        ->table('SQII_USER')
        ->where('USER_ID','=',$nik_petugas)
        ->delete();       

        return $result;
    }

    public static function delete_bawahan($id_petugas, $id_bawahan, $user){

        $result = DB::connection('sqii2')
        ->table('SQII_BAWAHAN')
        ->where('USER_ID','=',$id_petugas)
        ->where('USER_ID_BAWAHAN','=',$id_bawahan)
        ->delete();       

        return $result;
    }

    public static function mst_jabatan()
    {
        $q = DB::connection('sqii2')
        ->table('SQII_MST_JABATAN as a')
        ->select( 
            'a.KD_JABATAN', 'a.NM_JABATAN'
        )
        ->orderBy('a.NM_JABATAN', 'asc')
        ->get();

        return $q;
    }

    public static function simpan_user($user_email, $user_nama, $kd_jabatan, $user){

        $result = DB::connection('sqii2')
        ->table('SQII_USER')
        ->insert(
        [
            'USER_ID'       => $user_email,
            'KD_JABATAN'    => $kd_jabatan,
            'NAMA'          => $user_nama,
            'PASSWORD'      => '$2y$10$EERBCW4bYI/gQeJ66f99TuZZ89ZMFArKPShwlSf31OmwOgJGNOkSW',
            'FLAG_AKTIF'    => 'A',
            'USER_ENTRY'    => $user,
            'TGL_ENTRY'     => now(),
        ]);           
    }

}
