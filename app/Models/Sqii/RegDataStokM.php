<?php

namespace App\Models\Sqii;

use Illuminate\Database\Eloquent\Model;
use DB;

class RegDataStokM extends Model
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

	public static function data_blok_no($kd_kawasan,$kd_cluster,$tahap_bangun)
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
        ->where('a.TAHAP_BANGUN', '=', $tahap_bangun)
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

    public static function tipe_rumah($kd_kawasan, $keyword)
    {
        $q = DB::connection('sqii2')
        ->table('SQII_TIPE_RUMAH as a')
        ->select( 
            'a.KD_TIPE', 'a.NM_TIPE'
        )
        ->where('a.KD_KAWASAN', '=', $kd_kawasan)
        ->where('a.FLAG_AKTIF', '=', 'Y')
        ->where(function($wh) use ($keyword){
            $wh->where('a.KD_TIPE', 'like', '%'.$keyword.'%')
            ->orWhere('a.NM_TIPE', 'like', '%'.$keyword.'%');
        })
        ->groupBy('a.KD_TIPE')
        ->groupBy('a.NM_TIPE')
        ->orderBy('a.KD_TIPE', 'asc')
        ->paginate(10);
        //->get();

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

    public static function get_stok_kr($kd_kawasan,$kd_cluster){

        if ($kd_kawasan == 'SPTK'){ // SERPONG
            // call data local
            $sql = DB::connection('sqii')
            ->select("
                SELECT 
                    'SPTK' KD_KAWASAN,
                    LTRIM(RTRIM(A.BLOK)) BLOK,
                    LTRIM(RTRIM(A.NOMOR)) NOMOR,
                    CASE
                        WHEN LTRIM(RTRIM(ISNULL(C.KD_GRUP_SEKTOR,''))) = '' THEN LTRIM(RTRIM(A.KD_SEKTOR))
                    ELSE C.KD_GRUP_SEKTOR
                    END AS KD_SEKTOR,
                    LTRIM(RTRIM(A.KD_SEKTOR)) KD_SEKTOR_SRIS,
                    C.DESKRIPSI,
                    A.STOK_ID,
                    LTRIM(RTRIM(A.KD_JENIS)) KD_JENIS,
                    LTRIM(RTRIM(A.KD_TIPE)) KD_TIPE,
                    A.FLAG_AKTIF,
                    KONTRAKTOR_ID = 
                    (
                        SELECT TOP 1
                            Y.KONTRAKTOR_ID
                        FROM [DBCOR].[SPK].[dbo].BLOK_RUMAH_SPK X
                        INNER JOIN [DBCOR].[SPK].[dbo].SPK Y ON
                            Y.KD_PERUSAHAAN = A.KD_PERUSAHAAN
                            AND X.SPK_ID    = Y.SPK_ID  
                            AND X.STOK_ID   = A.STOK_ID
                        WHERE 1=1
                            AND YEAR(TGL_SPK)> 2011 
                    )
                FROM [DBPSS].[SRIS].[dbo].[STOK] A
                INNER JOIN [DBCOR].[SRIS].[dbo].JENIS_BANGUNAN B ON
                    A.KD_JENIS = B.KD_JENIS
                INNER JOIN [DBPSS].[SRIS].[dbo].[SEKTOR] C ON
                    A.KD_SEKTOR = C.KD_SEKTOR
                    AND C.FLAG_AKTIF = 'A'
                WHERE 1=1 
                    AND A.KD_PERUSAHAAN IN ('SSPG','SPCK','KSLV','KSVT','KSLL')
                    AND A.FLAG_AKTIF    = 'A'
                    AND B.KD_JENIS_TARGET   <> 'KAV'
                    AND A.KD_SEKTOR LIKE '%".$kd_cluster."%'
                    AND A.PARENT_ID IS NULL
                AND YEAR(A.TGL_ENTRY) > 2010   
            ");
            // DD($sql);
        }else if ($kd_kawasan == 'BKTK'){ // BEKASI
            // call data local
            $sql = DB::connection('sqii')
            ->select("
                SELECT 
                    'BKTK' KD_KAWASAN,
                    LTRIM(RTRIM(A.BLOK)) BLOK,
                    LTRIM(RTRIM(A.NOMOR)) NOMOR,
                    CASE
                        WHEN LTRIM(RTRIM(ISNULL(C.KD_GRUP_SEKTOR,''))) = '' THEN LTRIM(RTRIM(A.KD_SEKTOR))
                    ELSE C.KD_GRUP_SEKTOR
                    END AS KD_SEKTOR,
                    LTRIM(RTRIM(A.KD_SEKTOR)) KD_SEKTOR_SRIS,
                    C.DESKRIPSI,
                    A.STOK_ID,
                    LTRIM(RTRIM(A.KD_JENIS)) KD_JENIS,
                    LTRIM(RTRIM(A.KD_TIPE)) KD_TIPE,
                    A.FLAG_AKTIF,
                    KONTRAKTOR_ID = 
                    (
                        SELECT TOP 1
                            Y.KONTRAKTOR_ID
                        FROM [DBCOR].[SPK].[dbo].BLOK_RUMAH_SPK X
                        INNER JOIN [DBCOR].[SPK].[dbo].SPK Y ON
                            Y.KD_PERUSAHAAN = A.KD_PERUSAHAAN
                            AND X.SPK_ID    = Y.SPK_ID  
                            AND X.STOK_ID   = A.STOK_ID
                        WHERE 1=1
                            AND YEAR(TGL_SPK)> 2011 
                    )
                FROM [DBCOR].[SRIS].[dbo].[STOK] A
                INNER JOIN [DBCOR].[SRIS].[dbo].JENIS_BANGUNAN B ON
                    A.KD_JENIS = B.KD_JENIS
                INNER JOIN [DBCOR].[SRIS].[dbo].[SEKTOR] C ON
                    A.KD_SEKTOR = C.KD_SEKTOR
                    AND C.FLAG_AKTIF = 'A'
                WHERE 1=1
                    AND A.KD_PERUSAHAAN IN ('SMSF','SBKS')
                    AND A.FLAG_AKTIF    = 'A'
                    AND A.KD_SEKTOR LIKE '%".$kd_cluster."%'
                    AND A.PARENT_ID IS NULL
                    AND B.KD_JENIS_TARGET <> 'KAV'
                    AND YEAR(A.TGL_ENTRY) > 2010   
            ");

        }else if ($kd_kawasan == 'SKRW'){ // KARAWANG
            // call data local
            $sql = DB::connection('sqii')
            ->select("
                SELECT 
                    'SKRW' KD_KAWASAN,
                    LTRIM(RTRIM(A.BLOK)) BLOK,
                    LTRIM(RTRIM(A.NOMOR)) NOMOR,
                    CASE
                        WHEN LTRIM(RTRIM(ISNULL(C.KD_GRUP_SEKTOR,''))) = '' THEN LTRIM(RTRIM(A.KD_SEKTOR))
                    ELSE C.KD_GRUP_SEKTOR
                    END AS KD_SEKTOR,
                    LTRIM(RTRIM(A.KD_SEKTOR)) KD_SEKTOR_SRIS,
                    C.DESKRIPSI,
                    A.STOK_ID,
                    LTRIM(RTRIM(A.KD_JENIS)) KD_JENIS,
                    LTRIM(RTRIM(A.KD_TIPE)) KD_TIPE,
                    A.FLAG_AKTIF,
                    KONTRAKTOR_ID = 
                    (
                        SELECT TOP 1
                            Y.KONTRAKTOR_ID
                        FROM [DBCOR].[SPK].[dbo].BLOK_RUMAH_SPK X
                        INNER JOIN [DBCOR].[SPK].[dbo].SPK Y ON
                            Y.KD_PERUSAHAAN = A.KD_PERUSAHAAN
                            AND X.SPK_ID    = Y.SPK_ID  
                            AND X.STOK_ID   = A.STOK_ID
                        WHERE 1=1
                            AND YEAR(TGL_SPK)> 2011 
                    )
                FROM [DBCOR].[SRIS].[dbo].[STOK] A
                INNER JOIN [DBCOR].[SRIS].[dbo].JENIS_BANGUNAN B ON
                    A.KD_JENIS = B.KD_JENIS
                INNER JOIN [DBCOR].[SRIS].[dbo].[SEKTOR] C ON
                    A.KD_SEKTOR = C.KD_SEKTOR
                    AND C.FLAG_AKTIF = 'A'
                WHERE 1=1
                    AND A.KD_PERUSAHAAN IN ('SKRW','SMTH')
                    AND A.FLAG_AKTIF    = 'A'
                    AND A.KD_SEKTOR LIKE '%".$kd_cluster."%'
                    AND A.PARENT_ID IS NULL
                    AND B.KD_JENIS_TARGET <> 'KAV'
                    AND YEAR(A.TGL_ENTRY) > 2010   
            ");

        }else if ($kd_kawasan == 'MKPP'){ // BANDUNG
            // call data local
            $sql = DB::connection('sqii')
            ->select("
                SELECT 
                    'MKPP' KD_KAWASAN,
                    LTRIM(RTRIM(A.BLOK)) BLOK,
                    LTRIM(RTRIM(A.NOMOR)) NOMOR,
                    CASE
                        WHEN LTRIM(RTRIM(ISNULL(C.KD_GRUP_SEKTOR,''))) = '' THEN LTRIM(RTRIM(A.KD_SEKTOR))
                    ELSE C.KD_GRUP_SEKTOR
                    END AS KD_SEKTOR,
                    LTRIM(RTRIM(A.KD_SEKTOR)) KD_SEKTOR_SRIS,
                    C.DESKRIPSI,
                    A.STOK_ID,
                    LTRIM(RTRIM(A.KD_JENIS)) KD_JENIS,
                    LTRIM(RTRIM(A.KD_TIPE)) KD_TIPE,
                    A.FLAG_AKTIF,
                    KONTRAKTOR_ID = 
                    (
                        SELECT TOP 1
                            Y.KONTRAKTOR_ID
                        FROM [DBCOR].[SPK].[dbo].BLOK_RUMAH_SPK X
                        INNER JOIN [DBCOR].[SPK].[dbo].SPK Y ON
                            Y.KD_PERUSAHAAN = A.KD_PERUSAHAAN
                            AND X.SPK_ID    = Y.SPK_ID  
                            AND X.STOK_ID   = A.STOK_ID
                        WHERE 1=1
                            AND YEAR(TGL_SPK)> 2011 
                    )
                FROM [DBCOR].[SRIS].[dbo].[STOK] A
                INNER JOIN [DBCOR].[SRIS].[dbo].JENIS_BANGUNAN B ON
                    A.KD_JENIS = B.KD_JENIS
                INNER JOIN [DBCOR].[SRIS].[dbo].[SEKTOR] C ON
                    A.KD_SEKTOR = C.KD_SEKTOR
                    AND C.FLAG_AKTIF = 'A'
                WHERE 1=1
                    AND A.KD_PERUSAHAAN = 'MKPP'
                    AND A.FLAG_AKTIF    = 'A'
                    AND A.KD_SEKTOR LIKE '%".$kd_cluster."%'
                    AND A.PARENT_ID IS NULL
                    AND B.KD_JENIS_TARGET <> 'KAV'
                    AND YEAR(A.TGL_ENTRY) > 2010  
            ");

        }
        // else if ($kd_kawasan == 'EKTK'){ // KELAPA GADING
        //     // call data local
        //     $sql = DB::connection('sqii')
        //     ->select("
        //         SELECT 
        //             'EKTK' KD_KAWASAN,
        //             LTRIM(RTRIM(A.BLOK)) BLOK,
        //             LTRIM(RTRIM(A.NOMOR)) NOMOR,
        //             LTRIM(RTRIM(A.KD_SEKTOR)) KD_SEKTOR,
        //             C.DESKRIPSI,
        //             A.STOK_ID,
        //             LTRIM(RTRIM(A.KD_JENIS)) KD_JENIS,
        //             LTRIM(RTRIM(A.KD_TIPE)) KD_TIPE,
        //             A.FLAG_AKTIF,
        //             KONTRAKTOR_ID = 
        //             (
        //                 SELECT TOP 1
        //                     Y.KONTRAKTOR_ID
        //                 FROM [DBCOR].[SPK].[dbo].BLOK_RUMAH_SPK X
        //                 INNER JOIN [DBCOR].[SPK].[dbo].SPK Y ON
        //                     Y.KD_PERUSAHAAN = A.KD_PERUSAHAAN
        //                     AND X.SPK_ID    = Y.SPK_ID  
        //                     AND X.STOK_ID   = A.STOK_ID
        //                 WHERE 1=1
        //                     AND YEAR(TGL_SPK)> 2011 
        //             )
        //         FROM [DBCOR].[SRIS].[dbo].[STOK] A
        //         INNER JOIN [DBCOR].[SRIS].[dbo].JENIS_BANGUNAN B ON
        //             A.KD_JENIS = B.KD_JENIS
        //         INNER JOIN [DBCOR].[SRIS].[dbo].[SEKTOR] C ON
        //             A.KD_SEKTOR = C.KD_SEKTOR
        //         WHERE 1=1
        //             AND A.KD_PERUSAHAAN = 'SKLG'
        //             AND A.FLAG_AKTIF    = 'A'
        //             AND A.KD_SEKTOR     = '".$kd_cluster."'
        //             AND A.PARENT_ID IS NULL
        //             AND B.KD_JENIS_TARGET <> 'KAV'
        //             AND YEAR(A.TGL_ENTRY) > 2010  
        //     ");

        // }
        else if ($kd_kawasan == 'SGMC'){ // MAKASAR
            // call data local
            $sql = DB::connection('sqii')
            ->select("
                SELECT 
                    'SGMC' KD_KAWASAN,
                    LTRIM(RTRIM(A.BLOK)) BLOK,
                    LTRIM(RTRIM(A.NOMOR)) NOMOR,
                    CASE
                        WHEN LTRIM(RTRIM(ISNULL(C.KD_GRUP_SEKTOR,''))) = '' THEN LTRIM(RTRIM(A.KD_SEKTOR))
                    ELSE C.KD_GRUP_SEKTOR
                    END AS KD_SEKTOR,
                    LTRIM(RTRIM(A.KD_SEKTOR)) KD_SEKTOR_SRIS,
                    C.DESKRIPSI,
                    A.STOK_ID,
                    LTRIM(RTRIM(A.KD_JENIS)) KD_JENIS,
                    LTRIM(RTRIM(A.KD_TIPE)) KD_TIPE,
                    A.FLAG_AKTIF,
                    KONTRAKTOR_ID = 
                    (
                        SELECT TOP 1
                            Y.KONTRAKTOR_ID
                        FROM [DBCOR].[SPK].[dbo].BLOK_RUMAH_SPK X
                        INNER JOIN [DBCOR].[SPK].[dbo].SPK Y ON
                            Y.KD_PERUSAHAAN = A.KD_PERUSAHAAN
                            AND X.SPK_ID    = Y.SPK_ID  
                            AND X.STOK_ID   = A.STOK_ID
                        WHERE 1=1
                            AND YEAR(TGL_SPK)> 2011 
                    )
                FROM [DBCOR].[SRIS].[dbo].[STOK] A 
                INNER JOIN [DBCOR].[SRIS].[dbo].JENIS_BANGUNAN B ON
                    A.KD_JENIS  = B.KD_JENIS
                INNER JOIN [DBCOR].[SRIS].[dbo].[SEKTOR] C ON
                    A.KD_SEKTOR = C.KD_SEKTOR
                    AND C.FLAG_AKTIF = 'A'
                WHERE 1=1
                    AND A.KD_PERUSAHAAN IN ('SGMC','MKTK')
                    AND A.FLAG_AKTIF    = 'A'
                    AND A.KD_SEKTOR LIKE '%".$kd_cluster."%'
                    AND A.PARENT_ID IS NULL
                    AND B.KD_JENIS_TARGET   <> 'KAV'
                    AND YEAR(A.TGL_ENTRY) > 2010
            ");

        }else if ($kd_kawasan == 'KCJA'){ // BOGOR
            // call data local
            $sql = DB::connection('sqii')
            ->select("
                SELECT 
                    'KCJA' KD_KAWASAN,
                    LTRIM(RTRIM(A.BLOK)) BLOK,
                    LTRIM(RTRIM(A.NOMOR)) NOMOR,
                    CASE
                        WHEN LTRIM(RTRIM(ISNULL(C.KD_GRUP_SEKTOR,''))) = '' THEN LTRIM(RTRIM(A.KD_SEKTOR))
                    ELSE C.KD_GRUP_SEKTOR
                    END AS KD_SEKTOR,
                    LTRIM(RTRIM(A.KD_SEKTOR)) KD_SEKTOR_SRIS,
                    C.DESKRIPSI,
                    A.STOK_ID,
                    LTRIM(RTRIM(A.KD_JENIS)) KD_JENIS,
                    LTRIM(RTRIM(A.KD_TIPE)) KD_TIPE,
                    A.FLAG_AKTIF,
                    KONTRAKTOR_ID = 
                    (
                        SELECT TOP 1
                            Y.KONTRAKTOR_ID
                        FROM [DBCOR].[SPK].[dbo].BLOK_RUMAH_SPK X
                        INNER JOIN [DBCOR].[SPK].[dbo].SPK Y ON
                            Y.KD_PERUSAHAAN = A.KD_PERUSAHAAN
                            AND X.SPK_ID    = Y.SPK_ID  
                            AND X.STOK_ID   = A.STOK_ID
                        WHERE 1=1
                            AND YEAR(TGL_SPK)> 2011 
                    )
                FROM [DBCOR].[SRIS].[dbo].[STOK] A 
                INNER JOIN [DBCOR].[SRIS].[dbo].JENIS_BANGUNAN B ON
                    A.KD_JENIS  = B.KD_JENIS
                INNER JOIN [DBCOR].[SRIS].[dbo].[SEKTOR] C ON
                    A.KD_SEKTOR = C.KD_SEKTOR
                    AND C.FLAG_AKTIF = 'A'
                WHERE 1=1
                    AND A.KD_PERUSAHAAN IN ('KCJA')
                    AND A.FLAG_AKTIF    = 'A'
                    AND A.KD_SEKTOR LIKE '%".$kd_cluster."%'
                    AND A.PARENT_ID IS NULL
                    AND B.KD_JENIS_TARGET   <> 'KAV'
                    AND YEAR(A.TGL_ENTRY) > 2010
            ");

        }else {
            // call data local
            $sql = DB::connection('sqii') // KELAPA GADING
            ->select("
                SELECT 
                    'EKTK' KD_KAWASAN,
                    LTRIM(RTRIM(A.BLOK)) BLOK,
                    LTRIM(RTRIM(A.NOMOR)) NOMOR,
                    CASE
                        WHEN LTRIM(RTRIM(ISNULL(C.KD_GRUP_SEKTOR,''))) = '' THEN LTRIM(RTRIM(A.KD_SEKTOR))
                    ELSE C.KD_GRUP_SEKTOR
                    END AS KD_SEKTOR,
                    LTRIM(RTRIM(A.KD_SEKTOR)) KD_SEKTOR_SRIS,
                    C.DESKRIPSI,
                    A.STOK_ID,
                    LTRIM(RTRIM(A.KD_JENIS)) KD_JENIS,
                    LTRIM(RTRIM(A.KD_TIPE)) KD_TIPE,
                    A.FLAG_AKTIF,
                    KONTRAKTOR_ID = 
                    (
                        SELECT TOP 1
                            Y.KONTRAKTOR_ID
                        FROM [DBCOR].[SPK].[dbo].BLOK_RUMAH_SPK X
                        INNER JOIN [DBCOR].[SPK].[dbo].SPK Y ON
                            Y.KD_PERUSAHAAN = A.KD_PERUSAHAAN
                            AND X.SPK_ID    = Y.SPK_ID  
                            AND X.STOK_ID   = A.STOK_ID
                        WHERE 1=1
                            AND YEAR(TGL_SPK)> 2011 
                    )
                FROM [DBCOR].[SRIS].[dbo].[STOK] A 
                INNER JOIN [DBCOR].[SRIS].[dbo].JENIS_BANGUNAN B ON
                    A.KD_JENIS  = B.KD_JENIS
                INNER JOIN [DBCOR].[SRIS].[dbo].[SEKTOR] C ON
                    A.KD_SEKTOR = C.KD_SEKTOR
                    AND C.FLAG_AKTIF = 'A'
                WHERE 1=1
                    AND A.KD_PERUSAHAAN = 'SKLG'
                    AND A.FLAG_AKTIF    = 'A'
                    AND A.PARENT_ID IS NULL
                    AND A.KD_SEKTOR LIKE '%".$kd_cluster."%'
                    AND B.KD_JENIS_TARGET   <> 'KAV'
                    AND YEAR(A.TGL_ENTRY) > 2010     
            ");
        }
        //$sql = $this->lib->db_sqii("SELECT * FROM V_SQII_ALL_STOK");
        // $sql = DB::connection('sqii')
        // ->select("

        //     select  KD_KAWASAN, BLOK ,NOMOR,
        //             KD_CLUSTER AS KD_SEKTOR,
        //             STOK_ID,
        //             KD_JENIS,
        //             KD_TIPE,
        //             FLAG_AKTIF            
        //     FROM SQII_STOK WHERE 
        //         KD_KAWASAN = '".$kd_kawasan."' 
        //         and kd_cluster IN ('".$kd_cluster."')               
               
        // ");

        return $sql;
    }    

    public static function cek_stok_sync($STOK_ID,$KD_KAWASAN){
            // call data cloud
        return DB::connection('sqii2')
                ->table('SQII_STOK')
                ->where('STOK_ID',  '=', $STOK_ID)
                ->where('KD_KAWASAN',  '=', $KD_KAWASAN)
                ->where('FLAG_KARTU_RUMAH',  '=', 'Y')
                ->doesntExist();
    }    
    
    public static function insert_stok($kd_kawasan, $blok, $nomor, $kd_sektor, $stok_id, $kd_jenis, $kd_tipe_x,$flag_aktif_x, $user, $kd_sektor_sris){
            // insert data cloud
    DB::beginTransaction();
    
        try 
        {
            $result = DB::connection('sqii2')
            ->table('SQII_STOK')
            ->insert(
            [
                'KD_KAWASAN'        => $kd_kawasan,
                'KD_CLUSTER'        => $kd_sektor,
                'BLOK'              => $blok,
                'NOMOR'             => $nomor,
                'STOK_ID'           => $stok_id,
                'KD_JENIS'          => $kd_jenis,
                'KD_TIPE'           => $kd_tipe_x,
                'FLAG_KARTU_RUMAH'  => 'Y',
                'KD_CLUSTER_SRIS'   => $kd_sektor_sris,
                'FLAG_AKTIF'        => $flag_aktif_x,
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
            --select a.KD_JENIS, a.KD_TIPE, a.DESKRIPSI, a.KD_SEKTOR, a.KD_KAWASAN, a.FLAG_AKTIF            
            --from V_SQII_ALL_TIPE_RUMAH a
            --where 1=1
            --    and a.KD_KAWASAN = '".$kd_kawasan."'

            select a.KD_JENIS, a.KD_TIPE, a.nm_tipe as DESKRIPSI, a.kd_cluster as KD_SEKTOR, a.KD_KAWASAN, a.FLAG_AKTIF            
            from SQII_TIPE_RUMAH a
            where 1=1
                and a.KD_KAWASAN = '".$kd_kawasan."' 
                and a.kd_cluster IN ('".$kd_cluster."')               
               
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
                AND A.KD_JENIS IN ('RKN','KAV')
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

    public static function data_tahap($kd_kawasan,$kd_cluster)
    {
        $sql = DB::connection('sqii2')
        ->select("
            select 
                DISTINCT TAHAP_BANGUN
            FROM SQII_STOK A
            where 1=1
                AND KD_CLUSTER = '".$kd_cluster."'
                AND A.KD_KAWASAN = '".$kd_kawasan."'             
        ");
        return $sql;
    } 

    public static function cek_tp_rmh($kd_kawasan,$kd_cluster,$kd_tipe,$kd_jenis)
    {
        $q = DB::connection('sqii2')
        ->table('SQII_TIPE_RUMAH as a')
        ->select(
            'a.KD_KAWASAN', 'a.KD_CLUSTER', 'a.KD_TIPE', 'a.KD_JENIS'
        )        ->where('a.FLAG_AKTIF', '=', 'Y')
        ->where('a.KD_KAWASAN', '=', $kd_kawasan)
        ->where('a.KD_CLUSTER', '=', $kd_cluster)
        ->where('a.KD_TIPE', '=', $kd_tipe)
        ->where('a.KD_JENIS', '=', $kd_jenis)
        ->where('a.FLAG_AKTIF', '=', 'Y')
        ->get();

        return $q;
    }

    public static function simpan_tp_rmh($kd_kawasan,$kd_cluster,$kd_jenis,$kd_tipe,$nm_tipe,$user){
        $result = DB::connection('sqii2')
        ->table('SQII_TIPE_RUMAH')
        ->insert(
        [
            'KD_KAWASAN'        => $kd_kawasan,
            'KD_CLUSTER'        => $kd_cluster,
            'NM_TIPE'           => $nm_tipe,
            'KD_JENIS'          => $kd_jenis,
            'KD_TIPE'           => $kd_tipe,
            'FLAG_AKTIF'        => 'Y',
            'USER_ENTRY'        => $user,
            'TGL_ENTRY'         => now(),
        ]);        
    }

    public static function update_tp_rmh($kd_kawasan,$kd_cluster,$kd_jenis,$kd_tipe,$nm_tipe,$user){
        $result = DB::connection('sqii2')
        ->table('SQII_TIPE_RUMAH')
        ->where('KD_KAWASAN','=',$kd_kawasan)
        ->where('KD_CLUSTER','=',$stok_id)
        ->where('KD_JENIS','=',$kd_jenis)
        ->where('KD_TIPE','=',$kd_tipe)
        ->where('FLAG_AKTIF','=','Y')
        ->update(
            [
            'NM_TIPE'       => $nm_tipe,
            'TGL_UPDATE'    => now(),
            'USER_UPDATE'   => $user
            ]
        );        
     
    }
}
