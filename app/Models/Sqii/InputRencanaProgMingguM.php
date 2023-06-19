<?php

namespace App\Models\Sqii;

use Illuminate\Database\Eloquent\Model;
use DB;

class InputRencanaProgMingguM extends Model
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

    public static function data_master_rencana_progres($kd_kawasan,$kd_cluster,$kd_tahapan)
    {
        $q = DB::connection('sqii2')
        ->table('SQII_RENCANA_PROGRESS as a')
        ->select(
            DB::raw(' a.KD_KAWASAN, a.KD_CLUSTER, a.KD_TAHAP, a.KD_PERIODE, a.NM_PERIODE, CONVERT(CHAR(10),A.TGL_AWAL,103) TGL_AWAL, CONVERT(CHAR(10),A.TGL_AKHIR,103) TGL_AKHIR, A.PROGRESS')
        )
        ->where('a.FLAG_AKTIF', '=', 'Y')
        ->where('a.KD_KAWASAN', '=', $kd_kawasan)
        ->where('a.KD_CLUSTER', '=', $kd_cluster)
        ->where('a.KD_TAHAP', '=', $kd_tahapan)
        //->where('a.FLAG_HEADER', '=', 'H')
        ->orderBy('a.NM_PERIODE', 'asc')
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
        ->table('SQII_TAHAP_PEKERJAAN as a')
        ->select( 
            'a.KD_TAHAP', 'a.NM_TAHAP'
        )
        ->where('a.KD_KAWASAN', '=', $kd_kawasan)
        ->where('a.KD_CLUSTER', '=', $kd_cluster)
        ->where('a.FLAG_AKTIF', '=', 'A')
        ->orderBy('a.NM_TAHAP', 'asc')
        ->get();

        return $q;
    }


    public static function simpan_dt($kd_kawasan,$kd_cluster,$kd_tahap,$nm_periode,$periode1,$periode2,$progress,$user){

        $sql = "EXEC SP_KD_PERIODE ";
        $sql.= "'', ";
        $sql.= "'New' ";

        $query = DB::connection('sqii2')
        ->select($sql);     

        $kd_periode = $query[0]->ROW_ID;

        $result = DB::connection('sqii2')
        ->table('SQII_RENCANA_PROGRESS')
        ->insert(
        [
            'KD_KAWASAN'        => $kd_kawasan,
            'KD_CLUSTER'        => $kd_cluster,
            'KD_TAHAP'          => $kd_tahap,
            'KD_PERIODE'        => $kd_periode,
            'NM_PERIODE'        => $nm_periode,
            'TGL_AWAL'          => $periode1,
            'TGL_AKHIR'         => $periode2,
            'PROGRESS'          => $progress,
            'FLAG_AKTIF'        => 'Y',
            'USER_ENTRY'        => $user,
            'TGL_ENTRY'         => now(),
        ]);  
        DD($result);
    }

    public static function delete_periode($kd_tahap,$kd_kawasan,$kd_cluster,$kd_periode){

        $result = DB::connection('sqii2')
        ->table('SQII_RENCANA_PROGRESS')
        ->where('KD_TAHAP','=',$kd_tahap)
        ->where('KD_KAWASAN','=',$kd_kawasan)
        ->where('KD_CLUSTER','=',$kd_cluster)
        ->where('KD_PERIODE','=',$kd_periode)
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

        return $result;
    }

    public static function cek_minggu_pertama($kd_kawasan,$kd_cluster)
    {

        $sql = "
            SELECT 
                (CASE WHEN ISNULL(CONVERT(CHAR(10),MIN(TGL_AWAL),103),'N') = 'N' THEN CONVERT(CHAR(10),GETDATE(),103) ELSE CONVERT(CHAR(10),MIN(TGL_AWAL),103) END) AS TGL_AWAL
            FROM SQII_RENCANA_PROGRESS
            WHERE 1=1 
                AND KD_KAWASAN = '".$kd_kawasan."'
                AND KD_CLUSTER = '".$kd_cluster."'

        ";  
        // DD($sql);
        $q = DB::connection('sqii2')
        ->select($sql);
        
        return $q;
    }
}
