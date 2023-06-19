<?php

namespace App\Models\Cmstrhub;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use DB;

class m_dashboard extends Model
{
    public static function show_grafik_pengajuan($kd_unit){
		$q = DB::select("
            SELECT 
                KETERANGAN AS BULAN1,
                TAHUN1 =
                (
                    SELECT
                        YEAR(DATEADD(YEAR, -1, GETDATE()))
                    FROM TRX_KOMPLAIN XA
                    WHERE 1=1
                        AND YEAR(XA.TGL_KOMPLAIN) > YEAR(DATEADD(YEAR, -1, GETDATE()))
                        AND XA.KD_PERUSAHAAN    = '".$kd_unit."'
                    GROUP BY
                    YEAR(XA.TGL_KOMPLAIN)
                ),
                JML_PENGAJUAN1 = 
                (
                    SELECT 
                        COUNT(NO_KOMPLAIN)
                    FROM 
                    (
                        SELECT 
                            A.NO_KOMPLAIN,
                            MONTH(A.TGL_KOMPLAIN) BULAN,
                            YEAR(A.TGL_KOMPLAIN) TAHUN
                        FROM TRX_KOMPLAIN A
                        WHERE 1=1
                            AND YEAR(A.TGL_KOMPLAIN) <= YEAR(DATEADD(YEAR, -1, GETDATE()))
                            AND A.STATUS_PROGRESS IN ('I','F')
                            AND A.KD_PERUSAHAAN    = '".$kd_unit."'
                    ) V_JML1
                    WHERE 1=1
                        AND BULAN		= A.KD_FUNGSI
                        AND TAHUN		= YEAR(DATEADD(YEAR, -1, GETDATE()))
                ),
                KETERANGAN AS BULAN2,
                TAHUN2 = 
                (
                    SELECT 
                        YEAR(XA.TGL_KOMPLAIN)
                    FROM TRX_KOMPLAIN XA
                    WHERE 1=1
                        AND YEAR(XA.TGL_KOMPLAIN) = YEAR(GETDATE())
                        AND XA.KD_PERUSAHAAN    = '".$kd_unit."'
                    GROUP BY
                        YEAR(XA.TGL_KOMPLAIN)
                ),
                JML_PENGAJUAN2 = 
                (
                    SELECT 
                        COUNT(NO_KOMPLAIN)
                    FROM 
                    (
                        SELECT 
                            A.NO_KOMPLAIN,
                            MONTH(A.TGL_KOMPLAIN) BULAN,
                            YEAR(A.TGL_KOMPLAIN) TAHUN
                        FROM TRX_KOMPLAIN A
                        WHERE 1=1
                            AND YEAR(A.TGL_KOMPLAIN) = YEAR(GETDATE()) 
                            AND A.STATUS_PROGRESS IN ('I','F')
                            AND A.KD_PERUSAHAAN    = '".$kd_unit."'
                    ) V_JML2
                    WHERE 1=1
                        AND BULAN		= A.KD_FUNGSI
                        AND TAHUN		= YEAR(GETDATE()) 
                )
            FROM MST_HARDCODE A
            WHERE 1=1
                AND A.KD_SYS	= 'H'
                AND A.NM_FUNGSI	= 'NM_BULAN'
		");
		
		return $q;
    }

    public static function get_panel($kd_unit, $title){
        $q = DB::select("
                SELECT COUNT(NO_DOKUMEN) AS TOTAL_DATA
                FROM TR_TRX_HUB
                WHERE TITLE_PROGRESS = '".$title."'
                AND KD_PERUSAHAAN = '".$kd_unit."'
        ");

        return $q;
    }

    public static function get_data($kd_unit, $title_prog){

        $where_status = "";
		if($title_prog != ""){
			$where_status = " '".$title_prog."' ";
		} else {
			$where_status = " '%' ";
		}

        $q = DB::table('tr_trx_hub as a')
            ->select('a.*', 'b.NM_LAYANAN', 'c.NM_TUJUAN', 'd.NM_JENIS', 'e.NM_PROGRESS')
            ->join('tr_mst_layanan as b', 'a.kd_layanan', '=', 'b.kd_layanan')
            ->leftjoin('tr_mst_layanandtl as c', 'a.kd_tujuan', '=', 'c.kd_tujuan')
            ->leftjoin('tr_mst_layananitem as d', 'a.kd_jenis', '=', 'd.kd_jenis')
            ->join('tr_mst_progress as e', 'a.status', '=', 'e.kd_progress')
            ->where('a.kd_perusahaan', '=', $kd_unit)
            ->whereRaw('a.title_progress LIKE '.$where_status.' ')
            ->orderby('rowid', 'desc')
            ->get();

        return $q;
    }
}
