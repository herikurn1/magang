<?php

namespace App\Models\Sqii;

use Illuminate\Database\Eloquent\Model;
use DB;

class InputDenahLantaiM extends Model
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

	public static function data_blok_no($kd_kawasan,$kd_cluster)
	{
        $q = DB::connection('sqii2')
        ->table('SQII_STOK as a')
        ->select(
            'a.KD_KAWASAN', 'a.KD_CLUSTER', 'a.BLOK', 'a.NOMOR', 'b.KD_TIPE', 'b.NM_TIPE'
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

    public static function tipe_rumah($kd_kawasan, $kd_cluster)
    {
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

    public static function simpan_dt($kd_lantai, $path_foto_denah, $foto_denah, $path_foto_denah_2, $foto_denah_2, $kd_kawasan, $kd_jenis, $kd_tipe, $user, $tgl){

        $result = DB::connection('sqii2')
        ->table('SQII_LANTAI_TIPE_RUMAH')
        ->insert(
        [
            'KD_LANTAI'         => $kd_lantai,
            'KD_KAWASAN'        => $kd_kawasan,
            'KD_JENIS'          => $kd_jenis,
            'KD_TIPE'           => $kd_tipe,
            'PATH_FOTO_DENAH'   => $path_foto_denah,
            'SRC_FOTO_DENAH'    => $foto_denah,
            'PATH_FOTO_DENAH_2' => $path_foto_denah_2,
            'SRC_FOTO_DENAH_2'  => $foto_denah_2,
            'USER_ENTRY'        => $user,
            'TGL_ENTRY'         => now()
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

    public static function denah_lantai($kd_kawasan, $kd_jenis, $kd_tipe)
    {
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

    public static function data_lantai()
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

    public static function delete_dt($kd_kawasan,$kd_jenis,$kd_tipe,$kd_lantai){

        $result = DB::connection('sqii2')
        ->table('SQII_LANTAI_TIPE_RUMAH')
        ->where('kd_kawasan','=',$kd_kawasan)
        ->where('kd_jenis','=',$kd_jenis)
        ->where('kd_tipe','=',$kd_tipe)
        ->where('kd_lantai','=',$kd_lantai)
        ->delete();       

        return $result;
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

}
