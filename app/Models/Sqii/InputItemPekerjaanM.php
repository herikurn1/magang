<?php

namespace App\Models\Sqii;

use Illuminate\Database\Eloquent\Model;
use DB;

class InputItemPekerjaanM extends Model
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
            DB::raw(' ISNULL(a.BOBOT,0) as BOBOT, a.KD_KAWASAN, a.KD_CLUSTER, a.BLOK, a.NOMOR, b.KD_TIPE, b.NM_TIPE')
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

    public static function data_item_pekerjaan_h($kd_kawasan,$kd_cluster,$jns_pekerjan,$kd_tahapan)
    {
        $q = DB::connection('sqii2')
        ->table('SQII_ITEM_PEKERJAAN as a')
        ->select(
            DB::raw(' ISNULL(a.BOBOT,0) as BOBOT, a.KD_ITEM_PEKERJAAN, a.KD_KAWASAN, a.KD_CLUSTER, a.KD_TAHAP, a.JENIS_PEKERJAAN, a.FLAG_HEADER, A.NM_PEKERJAAN')
        )
        ->where('a.FLAG_AKTIF', '=', 'A')
        ->where('a.KD_KAWASAN', '=', $kd_kawasan)
        ->where('a.KD_CLUSTER', '=', $kd_cluster)
        ->where('a.JENIS_PEKERJAAN', '=', $jns_pekerjan)
        ->where('a.KD_TAHAP', '=', $kd_tahapan)
        //->where('a.FLAG_HEADER', '=', 'H')
        ->orderBy('a.URUT_HEADER', 'asc')
        ->orderBy('a.URUT_DETAIL', 'asc')
        ->orderBy('a.KD_ITEM_PEKERJAAN', 'asc')
        ->get();

        return $q;
    }

    public static function data_item_pekerjaan_d($kd_kawasan,$kd_cluster,$kd_item_pekerjaan)
    {
        $q = DB::connection('sqii2')
        ->table('SQII_ITEM_PEKERJAAN as a')
        ->select(
            'a.KD_ITEM_PEKERJAAN',  'a.KD_KAWASAN', 'a.KD_CLUSTER', 'a.KD_TAHAP', 'a.JENIS_PEKERJAAN', 'a.FLAG_HEADER', 'a.NM_PEKERJAAN'
        )
        ->where('a.FLAG_AKTIF', '=', 'Y')
        ->where('a.KD_KAWASAN', '=', $kd_kawasan)
        ->where('a.KD_CLUSTER', '=', $kd_cluster)
        ->where('a.KD_ITEM_PEKERJAAN', '=', $kd_item_pekerjaan)
        ->where('a.FLAG_HEADER', '=', 'D')
        ->orderBy('a.KD_ITEM_PEKERJAAN', 'asc')
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

    public static function data_tahapan($kd_kawasan,$kd_cluster)
    {
        $q = DB::connection('sqii2')
        ->select("
            SELECT KD_TAHAP, NM_TAHAP, FLAG_AKTIF
            FROM SQII_TAHAP_PEKERJAAN
            WHERE 1=1
                AND KD_KAWASAN = '".$kd_kawasan."' 
                AND KD_CLUSTER = '".$kd_cluster."' 
                AND FLAG_AKTIF = 'A'
            ORDER BY CAST(SUBSTRING(NM_TAHAP + '0', PATINDEX('%[0-9]%', NM_TAHAP + '0'), LEN(NM_TAHAP + '0')) AS INT)"
        );

        return $q;
    }

    public static function item_header_detail($tipe_h_d,$jns_pekerjan,$kd_tahapan,$parent_id)
    {

        $q = DB::connection('sqii2')
        ->table('SQII_MST_ITEM_PEKERJAAN as a')
        ->select( 
            'a.KD_ITEM_PEKERJAAN', 'a.PARENT_ID', 'a.KD_TAHAP', 'a.JENIS_PEKERJAAN', 'a.FLAG_HEADER', 'a.NM_PEKERJAAN', 'a.URUT_HEADER', 'a.URUT_DETAIL' 
        )
        ->where('a.JENIS_PEKERJAAN', '=', $jns_pekerjan)
        ->where('a.KD_TAHAP', '=', $kd_tahapan)
        ->where('a.FLAG_HEADER', '=', $tipe_h_d)
        ->where('a.PARENT_ID', '=', $parent_id)
        ->where('a.FLAG_AKTIF', '=', 'A')
        ->get();

        return $q;
    }

    public static function simpan_dt($kd_item_pekerjaan,$parent_id,$kd_kawasan,$kd_cluster,$kd_tahap,$jenis_pekerjaan,$flag_header,$nm_pekerjaan,$urut_header,$urut_detail,$user){

        $result = DB::connection('sqii2')
        ->table('SQII_ITEM_PEKERJAAN')
        ->insert(
        [
            'KD_ITEM_PEKERJAAN' => $kd_item_pekerjaan,
            'PARENT_ID'         => $parent_id,
            'KD_KAWASAN'        => $kd_kawasan,
            'KD_CLUSTER'        => $kd_cluster,
            'KD_TAHAP'          => $kd_tahap,
            'JENIS_PEKERJAAN'   => $jenis_pekerjaan,
            'FLAG_HEADER'       => $flag_header,
            'NM_PEKERJAAN'      => $nm_pekerjaan,
            'URUT_HEADER'       => $urut_header,
            'URUT_DETAIL'       => $urut_detail,
            'FLAG_AKTIF'        => 'A',
            'USER_ENTRY'        => $user,
            'TGL_ENTRY'         => now(),
        ]);        
    }

    public static function delete_item_pekerjaan($kd_item_pekerjaan,$kd_kawasan,$kd_cluster){

        $result = DB::connection('sqii2')
        ->table('SQII_ITEM_PEKERJAAN')
        ->where('KD_ITEM_PEKERJAAN','=',$kd_item_pekerjaan)
        ->where('KD_KAWASAN','=',$kd_kawasan)
        ->where('KD_CLUSTER','=',$kd_cluster)
        ->delete();       

        return $result;
    }

    public static function update_bobot($bobot,$kd_item_pekerjaan,$kd_kawasan,$kd_cluster,$user){

        $result = DB::connection('sqii2')
        ->table('SQII_ITEM_PEKERJAAN')
        ->where('KD_ITEM_PEKERJAAN','=',$kd_item_pekerjaan)
        ->where('KD_KAWASAN','=',$kd_kawasan)
        ->where('KD_CLUSTER','=',$kd_cluster)
        ->update(
            [
            'BOBOT'         => $bobot,
            'TGL_UPDATE'    => now(),
            'USER_UPDATE'   => $user
            ]
        );     

        $sql = "SELECT BOBOT_DTL = CAST(".$bobot." as numeric(5,2)) / COUNT(*)  
                FROM SQII_ITEM_PEKERJAAN 
                WHERE 1=1
                    AND KD_KAWASAN = '".$kd_kawasan."'
                    AND KD_CLUSTER = '".$kd_cluster."'
                    AND PARENT_ID = '".$kd_item_pekerjaan."'
                ";
                // DD($sql);
        $query_dtl = DB::connection('sqii2')
        ->select($sql);     

        $bobot_dtl = $query_dtl[0]->BOBOT_DTL;

        $result2 = DB::connection('sqii2')
        ->table('SQII_ITEM_PEKERJAAN')
        ->where('PARENT_ID','=',$kd_item_pekerjaan)
        ->where('KD_KAWASAN','=',$kd_kawasan)
        ->where('KD_CLUSTER','=',$kd_cluster)
        ->update(
            [
            'BOBOT'         => $bobot_dtl,
            'TGL_UPDATE'    => now(),
            'USER_UPDATE'   => $user
            ]
        ); 

        return $result;
    }

    public static function total_bobot($kd_kawasan,$kd_cluster,$jns_pekerjan,$kd_tahapan){

            $sql1 = "
                SELECT SUM(BOBOT) AS TOTAL_BOBOT --,A.*
                FROM SQII_ITEM_PEKERJAAN A
                WHERE KD_KAWASAN = '".$kd_kawasan."'
                    AND KD_CLUSTER = '".$kd_cluster."'
                    AND FLAG_HEADER = 'D'
                    AND FLAG_AKTIF = 'A'";

            $query1 = DB::connection('sqii2')
            ->select($sql1);        

            $total_bobot = $query1[0]->TOTAL_BOBOT;            

        return $total_bobot;
    }

    public static function cek_total_bobot($kd_kawasan,$kd_cluster,$kd_item_pekerjaan,$bobot){

            $sql1 = "
                SELECT (SUM(BOBOT) + CAST(".$bobot." as numeric(5,2)) ) - 100 AS CEK_TOTAL_BOBOT --,A.*
                FROM SQII_ITEM_PEKERJAAN A
                WHERE KD_KAWASAN = '".$kd_kawasan."'
                    AND KD_CLUSTER = '".$kd_cluster."'
                    AND FLAG_HEADER = 'D'
                    AND FLAG_AKTIF = 'A' 
                    AND PARENT_ID NOT IN ('".$kd_item_pekerjaan."')
                    ";
        // DD($sql1);
            $query1 = DB::connection('sqii2')
            ->select($sql1);        

            $cek_total_bobot = $query1[0]->CEK_TOTAL_BOBOT;            

        return $cek_total_bobot;
    }
}
