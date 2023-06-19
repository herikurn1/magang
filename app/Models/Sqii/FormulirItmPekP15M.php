<?php

namespace App\Models\Sqii;

use Illuminate\Database\Eloquent\Model;
use DB;

class FormulirItmPekP15M extends Model
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

    public static function search_dt($kd_kawasan,$kd_cluster,$nik_petugas,$periode_1,$periode_2,$tahap_bangun=1)
    {
        $sql = "EXEC [SQII_N_SP_LAPORAN_KINERJA_P3515] ";
        $sql.= "'".$kd_kawasan."',";
        $sql.= "'".$kd_cluster."',";
        $sql.= "'".$nik_petugas."',";
        $sql.= "'".$periode_1."',";
        $sql.= "'".$periode_2."',";
        $sql.= "'".$tahap_bangun."'";
        
        $q = DB::connection('sqii2')
        ->select($sql);

        return $q;
    }  

    public static function lap_defect($kd_kawasan,$kd_cluster,$nik_petugas,$tot_unit,$periode_1,$periode_2,$tahap_bangun)
    {
        $sql = "EXEC [SQII_N_SP_LAPORAN_KINERJA_P3515_BI] ";
        $sql.= "'".$kd_kawasan."',";
        $sql.= "'".$kd_cluster."',";
        $sql.= "'".$nik_petugas."',";
        $sql.= "'".$tot_unit."',";
        $sql.= "'".$periode_1."',";
        $sql.= "'".$periode_2."',";
        $sql.= "'".$tahap_bangun."'";
        
        $q = DB::connection('sqii2')
        ->select($sql);

        return $q;
    }

    public static function lap_detail_kualitas($kd_kawasan,$kd_cluster,$nik_petugas,$kd_kategori_defect,$periode_1,$periode_2,$tahap_bangun)
    {
        $sql = "EXEC [SQII_N_SP_LAPORAN_KINERJA_P3515_BI_TAHAP] ";
        $sql.= "'".$kd_kawasan."',";
        $sql.= "'".$kd_cluster."',";
        $sql.= "'".$nik_petugas."',";
        $sql.= "'".$kd_kategori_defect."',";
        $sql.= "'".$periode_1."',";
        $sql.= "'".$periode_2."',";
        $sql.= "'".$tahap_bangun."'";
        
        $q = DB::connection('sqii2')
        ->select($sql);

        return $q;
    }

    public static function lap_formulir_kualitas_bangunan($kd_kawasan,$kd_cluster,$nik_petugas,$kd_kategori_defect,$no_formulir,$periode_1,$periode_2,$tahap_bangun)
    {
        $sql = "EXEC [SQII_N_SP_LAPORAN_FORMULIR_KUALITAS_2.1] ";
        $sql.= "'".$kd_kawasan."',";
        $sql.= "'".$kd_cluster."',";
        $sql.= "'".$nik_petugas."',";
        $sql.= "'".$kd_kategori_defect."',";
        $sql.= "'".$no_formulir."',";
        $sql.= "'".$periode_1."',";
        $sql.= "'".$periode_2."',";
        $sql.= "'".$tahap_bangun."'";
        
        $q = DB::connection('sqii2')
        ->select($sql);

        return $q;
    }    

    public static function lap_detail_ageing($kd_kawasan,$kd_cluster,$nik_petugas,$tot_unit,$periode_1,$periode_2,$tipe_ageing,$tahap_bangun)
    {

        $ageing = "";
        switch ($tipe_ageing) {
          case "A1":
            $ageing = "DATEDIFF(DAY, A.TGL_JATUH_TEMPO_PERBAIKAN, A.[TGL_ENTRY_FLAG_CLOSING])>0 AND DATEDIFF(DAY, A.TGL_JATUH_TEMPO_PERBAIKAN, A.[TGL_ENTRY_FLAG_CLOSING])<8";
            break;
          case "A2":
            $ageing = "DATEDIFF(DAY, A.TGL_JATUH_TEMPO_PERBAIKAN, A.[TGL_ENTRY_FLAG_CLOSING])>7 AND DATEDIFF(DAY, A.TGL_JATUH_TEMPO_PERBAIKAN, A.[TGL_ENTRY_FLAG_CLOSING])<14";
            break;
          default:
            $ageing = "DATEDIFF(DAY, A.TGL_JATUH_TEMPO_PERBAIKAN, A.[TGL_ENTRY_FLAG_CLOSING])>13";
        }
        $sql = "
            SELECT     
                A.BLOK,A.NOMOR, A.NO_FORMULIR, A.PATH_FOTO_DENAH, A.SRC_FOTO_DENAH, A.PATH_FOTO_DEFECT, A.SRC_FOTO_DEFECT    
                ,CONVERT(VARCHAR(10), A.TGL_FOTO, 103) AS TGL_FOTO    
                ,CONVERT(VARCHAR(10), A.TGL_JATUH_TEMPO_PERBAIKAN, 103) AS TGL_JATUH_TEMPO_PERBAIKAN    
                ,CONVERT(VARCHAR(10), A.[TGL_ENTRY_FLAG_CLOSING], 103) AS TGL_SELESAI    
                ,STATUS_DEFECT =     
                CASE A.STATUS_DEFECT     
                WHEN 'S' THEN 'SEDANG'    
                WHEN 'B' THEN 'BERAT'    
                ELSE ''    
                END,    
                C.KD_KATEGORI_DEFECT, 
                C.NM_KATEGORI_DEFECT, 
                C.DESKRIPSI AS NM_ITEM_DEFECT_LAMA, 
                B.NM_ITEM_DEFECT AS DESKRIPSI  , 
                D.NM_LANTAI, 
                AGEING = DATEDIFF(DAY, A.TGL_JATUH_TEMPO_PERBAIKAN, A.[TGL_ENTRY_FLAG_CLOSING]),
                NM_ITEM_DEFECT = ISNULL( (STUFF((SELECT CAST('; ' + BB.CATATAN AS VARCHAR(MAX))   
                  FROM SQII_CATATAN_GOSHOW BB  
                 WHERE 1=1  
                    AND BB.TGL_KUNJUNGAN = A.TGL_KUNJUNGAN  
                    AND BB.USER_ID = A.USER_ID  
                    AND BB.KD_KAWASAN = A.KD_KAWASAN  
                    AND BB.KD_CLUSTER = A.KD_CLUSTER  
                    AND BB.BLOK = A.BLOK  
                    AND BB.NOMOR = A.NOMOR  
                    AND BB.KD_LANTAI = A.KD_LANTAI  
                    AND BB.KD_JENIS = A.KD_JENIS  
                    AND BB.KD_TIPE = A.KD_TIPE  
                    AND BB.KD_ITEM_DEFECT = A.KD_ITEM_DEFECT  
                FOR XML PATH ('')), 1, 2, '')) ,'-')
            FROM SQII_GOSHOW_FOTO A    
                INNER JOIN SQII_ITEM_DEFECT B    
                    ON B.KD_ITEM_DEFECT = A.KD_ITEM_DEFECT    
                INNER JOIN SQII_KATEGORI_DEFECT C    
                    ON C.KD_KATEGORI_DEFECT = B.KD_KATEGORI_DEFECT    
                LEFT JOIN SQII_LANTAI D ON D.KD_LANTAI = A.KD_LANTAI
                INNER JOIN SQII_KUNJUNGAN BB 
                    ON BB.KD_KAWASAN = A.KD_KAWASAN 
                    AND BB.KD_CLUSTER = A.KD_CLUSTER
                    AND BB.BLOK = A.BLOK
                    AND BB.NOMOR = A.NOMOR
                    AND BB.TGL_KUNJUNGAN = A.TGL_KUNJUNGAN
                    AND BB.USER_ID = A.USER_ID
                INNER JOIN SQII_STOK E
                    ON E.KD_KAWASAN = A.KD_KAWASAN
                    AND E.KD_CLUSTER = A.KD_CLUSTER
                    AND E.BLOK = A.BLOK
                    AND E.NOMOR = A.NOMOR
            WHERE 1=1    
                AND A.USER_ID = '".$nik_petugas."'    
                AND A.KD_KAWASAN = '".$kd_kawasan."'    
                AND A.KD_CLUSTER = '".$kd_cluster."'    
                AND CONVERT(CHAR(8),A.TGL_KUNJUNGAN,112) >= '".$periode_1."'    
                AND CONVERT(CHAR(8),A.TGL_KUNJUNGAN,112) <= '".$periode_2."'  
                AND ".$ageing."  
                AND ISNULL(BB.SRC_FOTO_SELFIE,'') <> ''
                AND E.TAHAP_BANGUN = '".$tahap_bangun."'
        ";  
        // DD($sql);
        $q = DB::connection('sqii2')
        ->select($sql);

        return $q;
    }    

   public static function lap_detail_ageing_v2($kd_kawasan,$kd_cluster,$nik_petugas,$tot_unit,$periode_1,$periode_2,$tipe_ageing,$tahap_bangun)
    {

        $ageing = "";
        switch ($tipe_ageing) {
          case "A1":
            $ageing = "DATEDIFF(DAY, AA.TGL_JATUH_TEMPO_PERBAIKAN, AA.[TGL_ENTRY_FLAG_CLOSING])>0 AND DATEDIFF(DAY, AA.TGL_JATUH_TEMPO_PERBAIKAN, AA.[TGL_ENTRY_FLAG_CLOSING])<8";
            break;
          case "A2":
            $ageing = "DATEDIFF(DAY, AA.TGL_JATUH_TEMPO_PERBAIKAN, AA.[TGL_ENTRY_FLAG_CLOSING])>7 AND DATEDIFF(DAY, AA.TGL_JATUH_TEMPO_PERBAIKAN, AA.[TGL_ENTRY_FLAG_CLOSING])<14";
            break;
          default:
            $ageing = "DATEDIFF(DAY, AA.TGL_JATUH_TEMPO_PERBAIKAN, AA.[TGL_ENTRY_FLAG_CLOSING])>13";
        }
        $sql = "
            SELECT D.*
            FROM V_ALOKASI_PENUGASAN_JABATAN A 
                INNER JOIN SQII_STOK C
                    ON C.KD_KAWASAN = A.KD_KAWASAN
                    AND C.KD_CLUSTER = A.KD_CLUSTER
                    AND C.BLOK = A.BLOK
                    AND C.NOMOR = A.NOMOR
                INNER JOIN (
                    SELECT *
                    FROM (
                        SELECT 
                            AA.BLOK,AA.NOMOR, AA.NO_FORMULIR, AA.PATH_FOTO_DENAH, AA.SRC_FOTO_DENAH, AA.PATH_FOTO_DEFECT, AA.SRC_FOTO_DEFECT    
                            ,CONVERT(VARCHAR(10), AA.TGL_FOTO, 103) AS TGL_FOTO    
                            ,CONVERT(VARCHAR(10), AA.TGL_JATUH_TEMPO_PERBAIKAN, 103) AS TGL_JATUH_TEMPO_PERBAIKAN    
                            ,CONVERT(VARCHAR(10), AA.[TGL_ENTRY_FLAG_CLOSING], 103) AS TGL_SELESAI    
                            ,STATUS_DEFECT =     
                            CASE AA.STATUS_DEFECT     
                            WHEN 'S' THEN 'SEDANG'    
                            WHEN 'B' THEN 'BERAT'    
                            ELSE ''    
                            END,    
                            C.KD_KATEGORI_DEFECT, 
                            C.NM_KATEGORI_DEFECT, 
                            C.DESKRIPSI AS NM_ITEM_DEFECT_LAMA, 
                            B.NM_ITEM_DEFECT AS DESKRIPSI  , 
                            D.NM_LANTAI, 
                            AGEING = DATEDIFF(DAY, AA.TGL_JATUH_TEMPO_PERBAIKAN, AA.[TGL_ENTRY_FLAG_CLOSING]),
                            NM_ITEM_DEFECT = ISNULL( (STUFF((SELECT CAST('; ' + BB.CATATAN AS VARCHAR(MAX))   
                                FROM SQII_CATATAN_GOSHOW BB  
                                WHERE 1=1  
                                AND BB.TGL_KUNJUNGAN = AA.TGL_KUNJUNGAN  
                                AND BB.USER_ID = AA.USER_ID  
                                AND BB.KD_KAWASAN = AA.KD_KAWASAN  
                                AND BB.KD_CLUSTER = AA.KD_CLUSTER  
                                AND BB.BLOK = AA.BLOK  
                                AND BB.NOMOR = AA.NOMOR  
                                AND BB.KD_LANTAI = AA.KD_LANTAI  
                                AND BB.KD_JENIS = AA.KD_JENIS  
                                AND BB.KD_TIPE = AA.KD_TIPE  
                                AND BB.KD_ITEM_DEFECT = Aa.KD_ITEM_DEFECT  
                            FOR XML PATH ('')), 1, 2, '')) ,'-'),
                            AA.KD_KAWASAN, AA.KD_CLUSTER
                        FROM SQII_GOSHOW_FOTO AA     
                            INNER JOIN SQII_ITEM_DEFECT B    
                                ON B.KD_ITEM_DEFECT = AA.KD_ITEM_DEFECT    
                            INNER JOIN SQII_KATEGORI_DEFECT C    
                                ON C.KD_KATEGORI_DEFECT = B.KD_KATEGORI_DEFECT    
                            LEFT JOIN SQII_LANTAI D ON D.KD_LANTAI = AA.KD_LANTAI
                            INNER JOIN SQII_KUNJUNGAN BB 
                                ON BB.KD_KAWASAN = AA.KD_KAWASAN 
                                AND BB.KD_CLUSTER = AA.KD_CLUSTER
                                AND BB.BLOK = AA.BLOK
                                AND BB.NOMOR = AA.NOMOR
                                AND BB.TGL_KUNJUNGAN = AA.TGL_KUNJUNGAN
                                AND BB.USER_ID = AA.USER_ID
                            INNER JOIN SQII_STOK E
                                ON E.KD_KAWASAN = AA.KD_KAWASAN
                                AND E.KD_CLUSTER = AA.KD_CLUSTER
                                AND E.BLOK = AA.BLOK
                                AND E.NOMOR = AA.NOMOR
                            INNER JOIN SQII_USER G
                                ON G.USER_ID = AA.USER_ID    
                        WHERE 1=1  
                            AND AA.KD_KAWASAN = '".$kd_kawasan."'
                            AND AA.KD_CLUSTER = '".$kd_cluster."'
                            AND CONVERT(CHAR(8),AA.TGL_KUNJUNGAN,112) >= '".$periode_1."'    
                            AND CONVERT(CHAR(8),AA.TGL_KUNJUNGAN,112) <= '".$periode_2."'  
                            AND ".$ageing." 
                            AND ISNULL(BB.SRC_FOTO_SELFIE,'') <> ''  
                            AND G.KD_JABATAN IN ('SM','BI','QC')
                        ) TBL_GO_SHOW
                ) D
                    ON D.KD_KAWASAN = A.KD_KAWASAN
                    AND D.KD_CLUSTER = A.KD_CLUSTER
                    AND D.BLOK = A.BLOK
                    AND D.NOMOR = A.NOMOR
            WHERE 1=1 
                AND A.KD_CLUSTER = '".$kd_cluster."'
                AND A.KD_KAWASAN = '".$kd_kawasan."' 
                AND A.KD_JABATAN = 'BI' 
                AND C.TAHAP_BANGUN = '".$tahap_bangun."' 
                AND A.USER_ID = '".$nik_petugas."'
                AND ISNULL(C.FLAG_ST,'T') = 'T'
        ";  
        // DD($sql);
        $q = DB::connection('sqii2')
        ->select($sql);

        return $q;
    }    

    public static function lap_cycle_time($kd_kawasan,$kd_cluster,$nik_petugas,$tot_unit,$periode_1,$periode_2)
    { //EXEC SQII_N_SP_LAPORAN_CYCLE_TIME 'SPTK','RNG','iqbal@gmail.com','2020-09-23','2020-09-26' 
        $sql = "EXEC SQII_N_SP_LAPORAN_CYCLE_TIME ";
        //$sql = "EXEC SQII_N_SP_LAPORAN_DEFECT12 ";
        $sql.= "'".$kd_kawasan."',";
        $sql.= "'".$kd_cluster."',";
        $sql.= "'".$nik_petugas."',";
        //$sql.= "'".$tot_unit."',";
        $sql.= "'".$periode_1."',";
        $sql.= "'".$periode_2."'";
        
        $q = DB::connection('sqii2')
        ->raw($sql);

        return $q;
    }    

    public static function lap_cycle_time_col($kd_kawasan,$kd_cluster,$nik_petugas,$tot_unit,$periode_1,$periode_2)
    { //EXEC SQII_N_SP_LAPORAN_CYCLE_TIME 'SPTK','RNG','iqbal@gmail.com','2020-09-23','2020-09-26' 
        $sql = "EXEC SQII_N_SP_LAPORAN_CYCLE_TIME_COL ";
        //$sql = "EXEC SQII_N_SP_LAPORAN_DEFECT12 ";
        $sql.= "'".$kd_kawasan."',";
        $sql.= "'".$kd_cluster."',";
        $sql.= "'".$nik_petugas."',";
        //$sql.= "'".$tot_unit."',";
        $sql.= "'".$periode_1."',";
        $sql.= "'".$periode_2."'";
        
        $q = DB::connection('sqii2')
        ->select($sql);

        return $q;
    }   

    public static function lap_cycle_time_col_v2($kd_kawasan,$kd_cluster,$nik_petugas,$tahap_bangun)
    { 
        $sql = "
            SELECT A.BLOK+'/'+A.NOMOR AS KOLOM
            FROM SQII_ALOKASI_PENUGASAN A
                INNER JOIN SQII_STOK B
                    ON B.KD_KAWASAN = A.KD_KAWASAN
                    AND B.KD_CLUSTER = A.KD_CLUSTER
                    AND B.BLOK = A.BLOK
                    AND B.NOMOR = A.NOMOR
            WHERE 1=1
                AND A.USER_ID = '".$nik_petugas."'
                AND A.KD_KAWASAN = '".$kd_kawasan."'
                AND A.KD_CLUSTER = '".$kd_cluster."'
                AND B.TAHAP_BANGUN = '".$tahap_bangun."' 
                --AND ISNULL(B.FLAG_ST,'T') = 'T'
        ";  
        //DD($sql);
        $q = DB::connection('sqii2')
        ->select($sql);

        return $q;
    }  

    public static function lap_cycle_time_v2($kd_kawasan,$kd_cluster,$nik_petugas,$tot_unit,$periode_1,$periode_2,$type,$tahap_bangun)
    { //EXEC SQII_N_SP_LAPORAN_CYCLE_TIME_COL_V2 'SPTK','RFD','dorry.indra@gmail.com','20210401','20210530','2'
        $sql = "EXEC [SQII_N_SP_LAPORAN_CYCLE_TIME_COL_V2_2.2.1.MOD] ";
        $sql.= "'".$kd_kawasan."',";
        $sql.= "'".$kd_cluster."',";
        $sql.= "'".$nik_petugas."',";
        $sql.= "'".str_replace("-","",$periode_1)."',";
        $sql.= "'".str_replace("-","",$periode_2)."',";
        $sql.= "'".$type."',";
        $sql.= "'".$tahap_bangun."'";
        // dd($sql);
        $q = DB::connection('sqii2')
        ->select($sql);
        // echo 'aoo';die;
        // dd($q);

        return $q;
    }  

    public static function lap_grafik_defect($kd_kawasan,$kd_cluster,$nik_petugas,$periode_1,$periode_2,$kd_kategori,$tahap_bangun)
    {
        $sql = "SET NOCOUNT ON ; EXEC [SP_LAPORAN_GRAFIS_PIE_CHART_V2_2.2] ";
        $sql.= "'".$kd_kawasan."',";
        $sql.= "'".$kd_cluster."',";
        $sql.= "'".$nik_petugas."',";
        $sql.= "'".$periode_1."',";
        $sql.= "'".$periode_2."',";
        $sql.= "'".$kd_kategori."',";
        $sql.= "'".$tahap_bangun."'";
        
        $q = DB::connection('sqii2')
        ->select($sql);

        return $q;
    }    

    public static function data_kontraktor($kd_kawasan,$kd_cluster)
    {
        $sql = DB::connection('sqii2')
        ->select("
            SELECT B.USER_ID, NAMA 
            FROM SQII_ALOKASI_PENUGASAN A WITH(NOLOCK)
                INNER JOIN SQII_USER B
                ON B.USER_ID = A.USER_ID
            WHERE 1=1
                AND B.KD_JABATAN = 'KTT'
                AND KD_CLUSTER = '".$kd_cluster."'
                AND A.KD_KAWASAN = '".$kd_kawasan."'
            GROUP BY B.USER_ID, NAMA
            ORDER BY NAMA
        ");
        return $sql;
    }

    public static function data_kunjungan($kd_kawasan,$kd_cluster,$nik_petugas,$periode_1,$periode_2,$tahap_bangun)
    {
        $sql = DB::connection('sqii2')
        ->select("
            SELECT CONVERT(CHAR(8),A.TGL_KUNJUNGAN,112) TGL_KUNJUNGAN, JML = 1
            FROM SQII_KUNJUNGAN A
                INNER JOIN SQII_STOK B
                    ON B.KD_KAWASAN = A.KD_KAWASAN
                    AND B.KD_CLUSTER = A.KD_CLUSTER
                    AND B.BLOK = A.BLOK
                    AND B.NOMOR = A.NOMOR
            WHERE 1=1
                AND ISNULL(SRC_FOTO_SELFIE,'') <> ''
                AND A.USER_ID = '".$nik_petugas."'
                AND A.KD_KAWASAN = '".$kd_kawasan."'
                AND A.KD_CLUSTER = '".$kd_cluster."' 
                AND CONVERT(CHAR(8),A.TGL_KUNJUNGAN,112) >= '".$periode_1."' 
                AND CONVERT(CHAR(8),A.TGL_KUNJUNGAN,112) <= '".$periode_2."'
                AND ISNULL(SRC_FOTO_SELFIE,'') <> ''
                AND B.TAHAP_BANGUN = '".$tahap_bangun."'
                --AND ISNULL(B.FLAG_ST,'T') = 'T'
            GROUP BY CONVERT(CHAR(8),A.TGL_KUNJUNGAN,112)
            ORDER BY CONVERT(CHAR(8),A.TGL_KUNJUNGAN,112)          
        ");
        return $sql;
    } 

    public static function search_dt_st($kd_kawasan,$kd_cluster,$nik_petugas,$tahap_bangun=1)
    {
        $sql = DB::connection('sqii2')
        ->select("
            SELECT                        
                SUM(TBL_JML_UNIT.JML_UNIT) AS TOTAL_ST         
            FROM SQII_BAWAHAN A     
                /*1. QUERY UNTUK MENDAPAT BAWAHAN SM YG JABATANNYA BI*/
                LEFT JOIN SQII_USER B              
                    ON A.USER_ID_BAWAHAN = B.USER_ID              
                INNER JOIN SQII_MST_JABATAN C              
                    ON C.KD_JABATAN = B.KD_JABATAN              
                LEFT JOIN (
                    /*2. QUERY UNTUK MENDAPAT JUMLAH UNIT BAWAHAN SM YG JABATANNYA BI */
                    SELECT USER_ID, AA.KD_KAWASAN, AA.KD_CLUSTER , JML_UNIT = CAST(COUNT(*) AS NUMERIC(18,0))              
                    FROM SQII_ALOKASI_PENUGASAN AA  
                        INNER JOIN SQII_STOK BB 
                            ON BB.KD_KAWASAN = AA.KD_KAWASAN
                            AND BB.KD_CLUSTER = AA.KD_CLUSTER
                            AND BB.BLOK = AA.BLOK
                            AND BB.NOMOR = AA.NOMOR
                    WHERE 1=1 AND BB.TAHAP_BANGUN = '".$tahap_bangun."'  AND ISNULL(BB.FLAG_ST,'T') = 'Y'
                    GROUP BY USER_ID, AA.KD_KAWASAN, AA.KD_CLUSTER  
                    ) TBL_JML_UNIT 
                    ON TBL_JML_UNIT.USER_ID = A.USER_ID_BAWAHAN                
            WHERE 1=1              
                AND A.USER_ID = '".$nik_petugas."'           
                AND B.KD_JABATAN = 'BI'              
                AND TBL_JML_UNIT.KD_KAWASAN = '".$kd_kawasan."'         
                AND TBL_JML_UNIT.KD_CLUSTER = '".$kd_cluster."'       
        ");

        return $sql;
    }

    public static function search_dtl_temuan($kd_kawasan,$kd_cluster,$kd_tahapan,$user_kontraktor)
    {
        $kontraktor = "";
        $tahap = "";
        switch ($user_kontraktor) {
          case "All":
            $kontraktor = "";
            break;
          default:
            $kontraktor = " AND AA.USER_ID        = '".$user_kontraktor."'";
        }
        switch ($kd_tahapan) {
          case "All":
            $tahap      = "";
            break;
          default:
            $tahap      = " AND A.KD_TAHAP          = '".$kd_tahapan."'";
        }
        $sql = "
            SELECT 
                TBL_COUNT.USER_ID,TBL_COUNT.NAMA,TBL_COUNT.BLOK,TBL_COUNT.NOMOR, C.NM_TIPE
            FROM 
            (   SELECT 
                    A.KD_KAWASAN
                    , A.KD_CLUSTER
                    , B.BLOK
                    , B.NOMOR
                    , A.KD_ITEM_PEKERJAAN
                    , A.BOBOT
                    , A.FLAG_HEADER
                    , A.NM_PEKERJAAN
                    , C.USER_ID
                    , C.NAMA
                FROM SQII_ITEM_PEKERJAAN A
                    INNER JOIN SQII_KUNJUNGAN_P35 B WITH (NOLOCK)
                            ON B.KD_KAWASAN     = A.KD_KAWASAN
                            AND B.KD_CLUSTER    = A.KD_CLUSTER
                            AND B.KD_ITEM_PEKERJAAN = A.KD_ITEM_PEKERJAAN
                    INNER JOIN (
                                SELECT AA.USER_ID,AA.KD_KAWASAN, AA.KD_CLUSTER, AA.BLOK, AA.NOMOR, B.NAMA
                                FROM SQII_ALOKASI_PENUGASAN AA
                                    INNER JOIN SQII_USER B
                                        ON B.USER_ID        = AA. USER_ID
                                WHERE 1=1
                                        AND B.KD_JABATAN    = 'KTT'
                                        AND AA.KD_KAWASAN   = '".$kd_kawasan."'
                                        AND AA.KD_CLUSTER   = '".$kd_cluster."'
                                        ".$kontraktor."
                    ) C         ON B.KD_KAWASAN     = C.KD_KAWASAN 
                                AND B.KD_CLUSTER    = C.KD_CLUSTER
                                AND B.BLOK          = C.BLOK 
                                AND B.NOMOR         = C.NOMOR
                WHERE 1=1
                        AND A.KD_KAWASAN        = '".$kd_kawasan."'
                        AND A.KD_CLUSTER        = '".$kd_cluster."'
                        AND A.JENIS_PEKERJAAN   = 'E'
                        AND A.FLAG_AKTIF        = 'A'
                        ".$tahap."
                GROUP BY 
                    A.KD_KAWASAN, A.KD_CLUSTER, B.BLOK, B.NOMOR, A.KD_ITEM_PEKERJAAN, A.BOBOT, A.FLAG_HEADER , A.NM_PEKERJAAN, C.USER_ID, C.NAMA
            ) TBL_COUNT
                LEFT JOIN SQII_STOK B
                    ON B.KD_KAWASAN     = '".$kd_kawasan."'
                    AND B.KD_CLUSTER    = '".$kd_cluster."'
                    AND B.BLOK          = TBL_COUNT.BLOK
                    AND B.NOMOR         = TBL_COUNT.NOMOR
                LEFT JOIN SQII_TIPE_RUMAH C
                    ON  C.KD_CLUSTER    = B.KD_CLUSTER
                    AND C.KD_KAWASAN    = B.KD_KAWASAN
                    AND C.KD_TIPE       = B.KD_TIPE
                    AND C.KD_JENIS      = B.KD_JENIS
            GROUP BY 
                TBL_COUNT.BLOK,TBL_COUNT.NOMOR, C.NM_TIPE,TBL_COUNT.USER_ID,TBL_COUNT.NAMA
            ORDER BY 
                TBL_COUNT.BLOK,TBL_COUNT.NOMOR
        ";  
        // DD($sql);
        $q = DB::connection('sqii2')
        ->select($sql);

        return $q;
    }   

    public static function nik_petugas($keyword,$kd_kawasan,$kd_cluster)
    {
        $q = DB::connection('sqii2')
        ->table('SQII_USER as a')
        ->select( 
            'a.USER_ID', 'a.NAMA', 'b.NM_JABATAN', 'b.KD_JABATAN', 'c.KD_KAWASAN', 'a.FLAG_AKTIF'
        )
        ->leftjoin('SQII_MST_JABATAN as b', function($join) {
            $join->on('b.KD_JABATAN', '=', 'a.KD_JABATAN');
        })
        ->leftjoin('SQII_ALOKASI_PENUGASAN as c', function($join) {
            $join->on('c.USER_ID', '=', 'a.USER_ID');
        })
        ->where('a.FLAG_AKTIF', '=', 'A')
        ->where('a.KD_JABATAN', '<>', 'ADMIN')
        ->where('a.KD_JABATAN', '=', 'SM')
        ->where(function($wh) use ($keyword,$kd_kawasan,$kd_cluster){
            $wh->where('a.NAMA', 'like', '%'.$keyword.'%');
            $wh->where('c.KD_KAWASAN', 'like', '%'.$kd_kawasan.'%');
            $wh->where('c.KD_CLUSTER', 'like', '%'.$kd_cluster.'%');
        })
        ->orderBy('a.NAMA', 'asc')
        ->distinct()
        ->paginate(10);
        //->get();

        return $q;
    }    

    public static function data_tahap_pekerjaan($kd_kawasan,$kd_cluster)
    {
        $sql = DB::connection('sqii2')
        ->select("
            SELECT KD_TAHAP, NM_TAHAP
            FROM SQII_TAHAP_PEKERJAAN
            WHERE 1=1
                AND KD_KAWASAN = '".$kd_kawasan."'
                AND KD_CLUSTER = '".$kd_cluster."'
                AND FLAG_AKTIF = 'A'
            ORDER BY NM_TAHAP
        ");
        return $sql;
    }    

    public static function cetak_formulir_p15($kd_kawasan,$kd_cluster,$kd_tahapan,$user_kontraktor,$blok,$nomor)
    {
        $sql = "
            SELECT A.KD_ITEM_PEKERJAAN,FLAG_HEADER,NM_PEKERJAAN,STATUS,B.USER_ID,   
            'V' = CASE WHEN STATUS = 'V' THEN '&radic;' ELSE '' END,
            'O'= CASE WHEN STATUS_LOG = 'O' THEN STATUS_LOG ELSE '' END,
            'P' = '', B.USER_ID
            FROM SQII_ITEM_PEKERJAAN A
                LEFT JOIN (
                    SELECT 
                        A.USER_ID, A.KD_KAWASAN, A.KD_CLUSTER, A.BLOK, A.NOMOR, A.KD_ITEM_PEKERJAAN, A.STATUS, A.USER_ENTRY, A.TGL_ENTRY,C.STATUS AS STATUS_LOG
                    FROM SQII_KUNJUNGAN_P35 A WITH (NOLOCK)
                        INNER JOIN SQII_USER B
                            ON B.USER_ID            = A. USER_ID
                        LEFT JOIN SQII_KUNJUNGAN_P35_LOG C WITH (NOLOCK) 
                            ON C.USER_ID            = B.USER_ID
                            AND C.KD_KAWASAN        = A.KD_KAWASAN
                            AND C.KD_CLUSTER        = A.KD_CLUSTER
                            AND C.BLOK              = A.BLOK
                            AND C.NOMOR             = A.NOMOR
                            AND C.KD_ITEM_PEKERJAAN = A.KD_ITEM_PEKERJAAN
                            AND C.STATUS            = 'O'   
                    WHERE 1=1
                            AND A.KD_KAWASAN    = '".$kd_kawasan."'
                            AND A.KD_CLUSTER    = '".$kd_cluster."'
                            AND A.BLOK          = '".$blok."'
                            AND A.NOMOR         = '".$nomor."'
                            AND B.KD_JABATAN    = 'BI'
                    GROUP BY 
                        A.USER_ID, A.KD_KAWASAN, A.KD_CLUSTER, A.BLOK, A.NOMOR, A.KD_ITEM_PEKERJAAN, A.STATUS , A.USER_ENTRY, A.TGL_ENTRY,C.STATUS
                  ) B
                            ON B.KD_KAWASAN     = A.KD_KAWASAN
                            AND B.KD_CLUSTER    = A.KD_CLUSTER
                            AND B.KD_ITEM_PEKERJAAN = A.KD_ITEM_PEKERJAAN
                LEFT JOIN (
                            SELECT AA.KD_KAWASAN, AA.KD_CLUSTER, AA.BLOK, AA.NOMOR 
                            FROM SQII_ALOKASI_PENUGASAN AA
                                INNER JOIN SQII_USER B
                                    ON B.USER_ID        = AA. USER_ID
                            WHERE 1=1
                                    AND B.KD_JABATAN    = 'KTT'
                                    AND AA.KD_KAWASAN   = '".$kd_kawasan."'
                                    AND AA.KD_CLUSTER   = '".$kd_cluster."'
                                    AND AA.USER_ID      = '".$user_kontraktor."'
                                    AND AA.BLOK         = '".$blok."'
                                    AND AA.NOMOR        = '".$nomor."'
                ) C         ON B.KD_KAWASAN     = C.KD_KAWASAN 
                            AND B.KD_CLUSTER    = C.KD_CLUSTER
                            AND B.BLOK          = C.BLOK 
                            AND B.NOMOR         = C.NOMOR
                LEFT JOIN SQII_TAHAP_PEKERJAAN E
                            ON E.KD_KAWASAN = A.KD_KAWASAN
                            AND E.KD_CLUSTER = A.KD_CLUSTER
                            AND E.KD_TAHAP = A.KD_TAHAP
            WHERE 1=1
                    AND A.KD_CLUSTER        = '".$kd_cluster."'
                    AND A.KD_KAWASAN        = '".$kd_kawasan."'
                    AND A.JENIS_PEKERJAAN   = 'E'
                    AND A.FLAG_AKTIF        = 'A'
                    AND A.KD_TAHAP          = '".$kd_tahapan."'
            ORDER BY A.URUT_HEADER, A.URUT_DETAIL
        ";  
        // DD($sql);
        $q = DB::connection('sqii2')
        ->select($sql);

        return $q;
    }   

    public static function nama_jabatan($kd_kawasan,$kd_cluster,$blok,$nomor,$kd_jabatan)
    {
        $sql = "
            SELECT B.NAMA, B.KD_JABATAN
                ,CAST(DAY(GETDATE()) as CHAR(2))+' '+SUBSTRING('Jan Feb Mar Apr Mei Jun Jul Agt Sep Okt Nov Des ', ( MONTH(GETDATE()) * 4) - 3, 3)+' '+CAST(YEAR(GETDATE()) AS CHAR(4)) AS TGL
            FROM SQII_ALOKASI_PENUGASAN A
                INNER JOIN SQII_USER B
                    ON B.USER_ID = A.USER_ID
            WHERE 1=1
                AND A.KD_KAWASAN = '".$kd_kawasan."'
                AND A.KD_CLUSTER = '".$kd_cluster."'
                AND A.BLOK = '".$blok."'
                AND A.NOMOR = '".$nomor."'
                AND KD_JABATAN IN ('".$kd_jabatan."')
            ";
        $q = DB::connection('sqii2')
        ->select($sql);

        return $q;
    } 



}
