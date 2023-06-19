<?php

namespace App\Models\Cmstrhub;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use DB;

class m_daftarpengajuan extends Model
{
    public static function get_data($title_prog, $kd_layanan, $kd_tujuan, $kd_jenis){

        $where_status = "";
		if($title_prog != ""){
			$where_status = " '".$title_prog."' ";
		} else {
			$where_status = " '%' ";
		}

        $where_layanan = "";
		if($kd_layanan != ""){
			$where_layanan = " '".$kd_layanan."' ";
		} else {
			$where_layanan = " '%' ";
		}

        $where_tujuan = "";
		if($kd_tujuan != ""){
			$where_tujuan = " '".$kd_tujuan."' ";
		} else {
			$where_tujuan = " '%' ";
		}

        $where_jenis = "";
		if($kd_jenis != ""){
			$where_jenis = " '".$kd_jenis."' ";
		} else {
			$where_jenis = " '%' ";
		}

        $q = DB::table('tr_trx_hub as a')
            ->select('a.*', 'b.NM_LAYANAN', 'c.NM_TUJUAN', 'd.NM_JENIS', 'e.NM_PROGRESS')
            ->join('tr_mst_layanan as b', 'a.kd_layanan', '=', 'b.kd_layanan')
            ->leftjoin('tr_mst_layanandtl as c', 'a.kd_tujuan', '=', 'c.kd_tujuan')
            ->leftjoin('tr_mst_layananitem as d', 'a.kd_jenis', '=', 'd.kd_jenis')
            ->join('tr_mst_progress as e', 'a.status', '=', 'e.kd_progress')
            ->whereRaw('a.title_progress LIKE '.$where_status.' ')
            ->whereRaw('a.kd_layanan LIKE '.$where_layanan.' ')
            ->whereRaw('a.kd_tujuan LIKE '.$where_tujuan.' ')
            ->whereRaw('a.kd_jenis LIKE '.$where_jenis.' ')
            ->orderby('rowid', 'desc')
            ->get();

        return $q;
    }

    public static function get_dtl_data($no_dokumen){
        $q = DB::table('tr_trx_hub as a')
            ->select('a.*', 'b.NM_LAYANAN', 'c.NM_TUJUAN', 'd.NM_JENIS', 'e.NM_PROGRESS')
            ->join('tr_mst_layanan as b', 'a.kd_layanan', '=', 'b.kd_layanan')
            ->leftjoin('tr_mst_layanandtl as c', 'a.kd_tujuan', '=', 'c.kd_tujuan')
            ->leftjoin('tr_mst_layananitem as d', 'a.kd_jenis', '=', 'd.kd_jenis')
            ->join('tr_mst_progress as e', 'a.status', '=', 'e.kd_progress')
            ->where('a.no_dokumen', '=', $no_dokumen)
            ->orderby('rowid', 'asc')
            ->get();

        return $q;
    }

    public static function layanan(){
        $q = DB::table('tr_mst_layanan')
            ->select('*')
            ->where('flag_aktif', '=', 'Y')
            ->orderby('urut', 'asc')
            ->get();

        return $q;
    }   

    public static function layanan_dtl($kd_unit, $kd_layanan){
        $q = DB::table('tr_mst_layanandtl')
            ->select('*')
            ->where('kd_perusahaan', '=', $kd_unit)
            ->where('kd_layanan', '=', $kd_layanan)
            ->where('flag_aktif', '=', 'Y')
            ->orderby('rowid', 'asc')
            ->get();

        return $q;
    }

    public static function layanan_item($kd_unit, $kd_layanan, $kd_tujuan){
        $q = DB::table('tr_mst_layananitem')
            ->select('*')
            ->where('kd_perusahaan', '=', $kd_unit)
            ->where('kd_layanan', '=', $kd_layanan)
            ->where('kd_tujuan', '=', $kd_tujuan)
            ->where('flag_aktif', '=', 'Y')
            ->orderby('rowid', 'asc')
            ->get();

        return $q;
    }

    public static function status(){
        $q = DB::table('tr_mst_hardcode')
            ->select('*')
            ->where('NM_FUNGSI', '=', 'TITLE_PROGRESS')
            ->orderby('KD_FUNGSI', 'ASC')
            ->get();

        return $q;
    }

}