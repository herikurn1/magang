<?php

namespace App\Models\Sqii;

use Illuminate\Database\Eloquent\Model;
use DB;

class RegTipeBangunanM extends Model
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
    }

    public static function data_jenis_bangunan()
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
        // call data CLOUD
        $sql = DB::connection('sqii2')
        ->select("
            SELECT B.KD_JENIS,B.NM_JENIS,ISNULL(C.JML_LANTAI,0) JML_LANTAI,A.KD_TIPE, A.NM_TIPE --,A.*
            FROM SQII_TIPE_RUMAH A
                LEFT JOIN SQII_JENIS_BANGUNAN B ON B.KD_JENIS = A.KD_JENIS
                LEFT JOIN ( SELECT 
                                COUNT(*) JML_LANTAI,KD_JENIS,KD_TIPE,KD_KAWASAN 
                            FROM SQII_LANTAI_TIPE_RUMAH 
                            GROUP BY KD_JENIS,KD_TIPE,KD_KAWASAN ) C 
                    ON C.KD_JENIS = A.KD_JENIS 
                    AND C.KD_TIPE = A.KD_TIPE
                    AND C.KD_KAWASAN = A.KD_KAWASAN
            WHERE 1=1
                AND ISNULL(A.FLAG_AKTIF,'N') = 'Y'
                AND A.KD_KAWASAN = '".$kd_kawasan."'
                AND A.KD_CLUSTER = '".$kd_cluster."'
            ORDER BY A.NM_TIPE
        ");
        return $sql;
    }

    public static function data_denah($kd_kawasan,$kd_jenis,$kd_tipe)
    {
        // call data CLOUD
        $sql = DB::connection('sqii2')
        ->select("
            SELECT B.NM_LANTAI,A.PATH_FOTO_DENAH,A.SRC_FOTO_DENAH, A.PATH_FOTO_DENAH_2,A.SRC_FOTO_DENAH_2, A.KD_LANTAI
            FROM SQII_LANTAI_TIPE_RUMAH A
                LEFT JOIN SQII_LANTAI B ON B.KD_LANTAI = A.KD_LANTAI
            WHERE 1=1
                AND A.KD_JENIS = '".$kd_jenis."'
                AND A.KD_TIPE = '".$kd_tipe."'
                AND A.KD_KAWASAN = '".$kd_kawasan."'
            ORDER BY A.KD_LANTAI

        ");
        return $sql;
    }

    public static function insert_data_denah($kd_lantai, $kd_jenis, $kd_tipe, $kd_kawasan, $path_foto_denah, $src_foto_denah, $path_foto_denah_2, $src_foto_denah_2, $user){
    DB::beginTransaction();
    
        try 
        {
            $result = DB::connection('sqii2')
            ->table('SQII_TIPE_RUMAH')
            ->insert(
            [
                'kd_lantai'         => $kd_lantai,
                'kd_jenis'          => $kd_jenis,
                'kd_tipe'           => $kd_tipe,
                'kd_kawasan'        => $kd_kawasan,
                'path_foto_denah'   => $path_foto_denah,
                'src_foto_denah'    => $src_foto_denah,
                'path_foto_denah_2' => $path_foto_denah_2,
                'src_foto_denah_2'  => $src_foto_denah_2,
                'USER_ENTRY'        => $user,
                'TGL_ENTRY'         => now(),
            ]
            );  
            // Commit Transaction
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            // Rollback Transaction
            DB::rollback();
            return $e;
        } 
    }       

    public static function get_tipe_rumah_view($kd_kawasan,$kd_cluster){
        // call data local  and a.KD_SEKTOR = '".$kd_cluster."'
        $sql = DB::connection('sqii')
        ->select("
            select a.KD_JENIS, a.KD_TIPE, a.DESKRIPSI, a.KD_SEKTOR, a.KD_KAWASAN, a.FLAG_AKTIF, A.KD_MODEL, A.NM_MODEL    
            from V_SQII_ALL_TIPE_RUMAH_V2 a
            where 1=1
                and a.KD_KAWASAN = '".$kd_kawasan."'
                AND A.KD_SEKTOR IN ('".$kd_cluster."')    

            --select a.KD_JENIS, a.KD_TIPE, a.nm_tipe as DESKRIPSI, a.kd_cluster as KD_SEKTOR, a.KD_KAWASAN, a.FLAG_AKTIF            
            --from SQII_TIPE_RUMAH a
            --where 1=1
            --    and a.KD_KAWASAN = '".$kd_kawasan."' 
            --    and a.kd_cluster IN ('".$kd_cluster."')               
               
        ");
        return $sql;
    }    

    public static function cek_tipe_rumah_exists($kd_jenis, $kd_tipe, $kd_kawasan){
            // call data cloud
        return DB::connection('sqii2')
                ->table('SQII_TIPE_RUMAH')
                ->where('KD_JENIS',  '=', $kd_jenis)
                ->where('KD_TIPE',  '=', $kd_tipe)
                ->where('KD_KAWASAN',  '=', $kd_kawasan)
                ->doesntExist();
    }

    public static function insert_tipe_rumah($kd_jenis, $kd_tipe, $nm_tipe, $kd_cluster, $kd_kawasan, $flag_aktif, $user){
    DB::beginTransaction();
    
        try 
        {
            $result = DB::connection('sqii2')
            ->table('SQII_TIPE_RUMAH')
            ->insert(
            [
                'kd_jenis'          => $kd_jenis,
                'kd_tipe'           => $kd_tipe,
                'nm_tipe'           => $nm_tipe,
                'kd_cluster'        => $kd_cluster,
                'kd_kawasan'        => $kd_kawasan,
                'flag_aktif'        => $flag_aktif,
                'USER_ENTRY'        => $user,
                'TGL_ENTRY'         => now(),
            ]
            );  
            // Commit Transaction
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            // Rollback Transaction
            DB::rollback();
            return $e;
        } 
    }    

   public static function get_lantai_tipe_rumah($kd_kawasan,$kd_cluster){
        // call data local
        $sql = DB::connection('sqii')
        ->select("
            select 
                A.KD_LANTAI, A.KD_JENIS, A.KD_TIPE, A.KD_KAWASAN, A.PATH_FOTO_DENAH, A.SRC_FOTO_DENAH, A.PATH_FOTO_DENAH_2, A.SRC_FOTO_DENAH_2, A.KETERANGAN
            FROM SQII_LANTAI_TIPE_RUMAH A
            where 1=1
                --AND A.KD_JENIS IN ('RKN','KAV','RMH')
                --AND A.KD_JENIS = 'RMH'
                AND A.KD_KAWASAN = '".$kd_kawasan."'             
        ");
        return $sql;
    }    

    public static function cek_lantai_tipe_rumah_exists($kd_lantai,$kd_jenis, $kd_tipe, $kd_kawasan){
            // call data cloud
        return DB::connection('sqii2')
                ->table('SQII_LANTAI_TIPE_RUMAH')
                ->where('KD_LANTAI',  '=', $kd_lantai)
                ->where('KD_JENIS',  '=', $kd_jenis)
                ->where('KD_TIPE',  '=', $kd_tipe)
                ->where('KD_KAWASAN',  '=', $kd_kawasan)
                ->doesntExist();
    }

    public static function ins_lantai_tipe_rumah($kd_lantai,$kd_jenis, $kd_tipe, $kd_kawasan, $path_foto_denah, $src_foto_denah, $path_foto_denah_2, $src_foto_denah_2, $keterangan,$user){
    DB::beginTransaction();
    
        try 
        {
            $result = DB::connection('sqii2')
            ->table('SQII_LANTAI_TIPE_RUMAH')
            ->insert(
            [
                'KD_LANTAI'             => $kd_lantai,
                'KD_JENIS'              => $kd_jenis,
                'KD_TIPE'               => $kd_tipe,
                'KD_KAWASAN'            => $kd_kawasan,
                'PATH_FOTO_DENAH'       => $path_foto_denah,
                'SRC_FOTO_DENAH'        => $src_foto_denah,
                'PATH_FOTO_DENAH_2'     => $path_foto_denah_2,
                'SRC_FOTO_DENAH_2'      => $src_foto_denah_2,
                'KETERANGAN'            => $keterangan,
                'USER_ENTRY'            => $user,
                'TGL_ENTRY'             => now(),
            ]
            );  
            // Commit Transaction
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            // Rollback Transaction
            DB::rollback();
            return $e;
        } 
    }

    public static function available_stok($kd_kawasan,$kd_jenis,$kd_tipe)
    {

        $sql = DB::connection('sqii2')
        ->select("
            SELECT KD_LANTAI,NM_LANTAI 
            FROM SQII_LANTAI 
            WHERE 1=1
                AND KD_LANTAI NOT IN (
                    SELECT KD_LANTAI FROM SQII_LANTAI_TIPE_RUMAH 
                    WHERE 1=1
                        AND KD_KAWASAN = '".$kd_kawasan."'
                        AND KD_JENIS = '".$kd_jenis."'
                        AND KD_TIPE = '".$kd_tipe."' 
                )
                AND FLAG_AKTIF = 'Y'           
        ");
        return $sql;
    }   

    public static function delete_denah($kd_lantai, $kd_kawasan, $kd_jenis, $kd_tipe, $user){

        $result = DB::connection('sqii2')
        ->table('SQII_LANTAI_TIPE_RUMAH')
        ->where('KD_LANTAI','=',$kd_lantai)
        ->where('KD_KAWASAN','=',$kd_kawasan)
        ->where('KD_JENIS','=',$kd_jenis)
        ->where('KD_TIPE','=',$kd_tipe)
        ->delete();       

        return $result;
    }    
 
    // public static function data_model($kd_kawasan,$kd_jenis,$kd_tipe)
    // {
    //     // call data CLOUD
    //     $sql = DB::connection('sqii2')
    //     ->select("
    //         SELECT B.NM_LANTAI,A.PATH_FOTO_DENAH,A.SRC_FOTO_DENAH, A.PATH_FOTO_DENAH_2,A.SRC_FOTO_DENAH_2, A.KD_LANTAI
    //         FROM SQII_LANTAI_TIPE_RUMAH A
    //             LEFT JOIN SQII_LANTAI B ON B.KD_LANTAI = A.KD_LANTAI
    //         WHERE 1=1
    //             AND A.KD_JENIS = '".$kd_jenis."'
    //             AND A.KD_TIPE = '".$kd_tipe."'
    //             AND A.KD_KAWASAN = '".$kd_kawasan."'
    //         ORDER BY A.KD_LANTAI

    //     ");
    //     return $sql;
    // }
}
