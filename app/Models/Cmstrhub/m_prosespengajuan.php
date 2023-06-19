<?php

namespace App\Models\Cmstrhub;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use DB;

class m_prosespengajuan extends Model
{
    public static function get_data($kd_unit, $title_prog, $kd_layanan, $kd_tujuan, $kd_jenis){

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
            ->where('a.kd_perusahaan', '=', $kd_unit)
            ->whereRaw('a.title_progress LIKE '.$where_status.' ')
            ->whereRaw('a.kd_layanan LIKE '.$where_layanan.' ')
            ->whereRaw('a.kd_tujuan LIKE '.$where_tujuan.' ')
            ->whereRaw('a.kd_jenis LIKE '.$where_jenis.' ')
            ->orderby('rowid', 'desc')
            ->get();

        return $q;
    }

    public static function get_dtl_data($kd_unit, $no_dokumen){
        $q = DB::table('tr_trx_hub as a')
            ->select('a.*', 'b.NM_LAYANAN', 'c.NM_TUJUAN', 'd.NM_JENIS', 'e.NM_PROGRESS', 'f.NM_PERIODE')
            ->join('tr_mst_layanan as b', 'a.kd_layanan', '=', 'b.kd_layanan')
            ->leftjoin('tr_mst_layanandtl as c', 'a.kd_tujuan', '=', 'c.kd_tujuan')
            ->leftjoin('tr_mst_layananitem as d', 'a.kd_jenis', '=', 'd.kd_jenis')
            ->join('tr_mst_progress as e', 'a.status', '=', 'e.kd_progress')
            ->leftjoin('tr_mst_periode as f', 'a.periode', '=', 'f.kd_periode')
            ->where('a.kd_perusahaan', '=', $kd_unit)
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

    public static function tipe_idkaryawan(){
        $q = DB::table('tr_mst_hardcode')
            ->select('*')
            ->where('kd_sys', '=', 'H')
            ->where('nm_fungsi', '=', 'STS_PENGAJUAN')
            ->orderby('urut_no', 'asc')
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

    public static function confirm_data($kd_unit,$no_dokumen,$kd_layanan,$kd_tujuan,$kd_jenis,$user,$tgl){

        $q = DB::table('TR_TRX_HUB')
            ->where('KD_PERUSAHAAN', '=', $kd_unit)
            ->where('NO_DOKUMEN', '=', $no_dokumen)
            ->where('KD_LAYANAN', '=', $kd_layanan)
            ->update(
                [
                    'STATUS'		            => 'I',
                    'TITLE_PROGRESS'		    => '2',
                    'USER_UPDATE'               => $user,
                    'TGL_UPDATE'                => $tgl
                ]
            );

        $q = DB::table('TR_TRX_HUB_DTL')
            ->insert([
                'KD_PERUSAHAAN'         => $kd_unit, 
                'NO_DOKUMEN'            => $no_dokumen, 
                'KD_LAYANAN'            => $kd_layanan, 
                'KD_TUJUAN'             => $kd_tujuan,
                'KD_JENIS'              => $kd_jenis,
                'NO_URUT'               => '2',
                'TGL_PROGRESS'          => $tgl,
                'NILAI_PROGRESS'        => 10,
                'USER_PROGRESS'         => 'TR', 
                'TITLE_PROGRESS'        => '2', 
                'URAIAN_PROGRESS'       => null, 
                'STATUS_PROGRESS'       => 'I', 
                'FOTO_PEKERJAAN'        => null, 
                'FOTO_SELESAI'          => null, 
                'USER_ENTRY'            => $user,
                'TGL_ENTRY'             => $tgl
            ]);

        return $q;
    }

    public static function progress_data($kd_unit,$no_dokumen,$kd_layanan,$kd_tujuan,$kd_jenis,$status,$title,$tgl_progress,$catatan,$fileName,$fileName_done,$user,$tgl){

        $q = DB::table('TR_TRX_HUB')
            ->where('KD_PERUSAHAAN', '=', $kd_unit)
            ->where('NO_DOKUMEN', '=', $no_dokumen)
            ->where('KD_LAYANAN', '=', $kd_layanan)
            ->update(
                [
                    'STATUS'		            => $status,
                    'TITLE_PROGRESS'		    => $title,
                    'USER_UPDATE'               => $user,
                    'TGL_UPDATE'                => $tgl
                ]
            );

        $get_urut = DB::table('TR_TRX_HUB_DTL')
			->select('NO_URUT')
			->where('KD_PERUSAHAAN', '=', $kd_unit)
			->where('NO_DOKUMEN', '=', $no_dokumen)
			->orderBy('NO_URUT', 'DESC')
			->limit(1)
			->get();

		$no_urut='';
		foreach ($get_urut as $get_urut_row) {
			$no_urut = ($get_urut_row->NO_URUT) + 1;
		}

        $q = DB::table('TR_TRX_HUB_DTL')
            ->insert([
                'KD_PERUSAHAAN'         => $kd_unit, 
                'NO_DOKUMEN'            => $no_dokumen, 
                'KD_LAYANAN'            => $kd_layanan, 
                'KD_TUJUAN'             => $kd_tujuan,
                'KD_JENIS'              => $kd_jenis,
                'NO_URUT'               => $no_urut,
                'TGL_PROGRESS'          => $tgl_progress,
                'NILAI_PROGRESS'        => 0,
                'USER_PROGRESS'         => 'TR', 
                'TITLE_PROGRESS'        => $title, 
                'URAIAN_PROGRESS'       => $catatan, 
                'STATUS_PROGRESS'       => $status, 
                'FOTO_PEKERJAAN'        => $fileName, 
                'USER_ENTRY'            => $user,
                'TGL_ENTRY'             => $tgl
            ]);

        return $q;
    }

    public static function progress_done($kd_unit,$no_dokumen,$kd_layanan,$kd_tujuan,$kd_jenis,$status,$title,$tgl_progress,$catatan,$fileName_done,$user,$tgl){

        $q = DB::table('TR_TRX_HUB')
            ->where('KD_PERUSAHAAN', '=', $kd_unit)
            ->where('NO_DOKUMEN', '=', $no_dokumen)
            ->where('KD_LAYANAN', '=', $kd_layanan)
            ->update(
                [
                    'STATUS'		            => $status,
                    'TITLE_PROGRESS'		    => $title,
                    'USER_UPDATE'               => $user,
                    'TGL_UPDATE'                => $tgl
                ]
            );

        $get_urut = DB::table('TR_TRX_HUB_DTL')
			->select('NO_URUT')
			->where('KD_PERUSAHAAN', '=', $kd_unit)
			->where('NO_DOKUMEN', '=', $no_dokumen)
			->orderBy('NO_URUT', 'DESC')
			->limit(1)
			->get();

		$no_urut='';
		foreach ($get_urut as $get_urut_row) {
			$no_urut = ($get_urut_row->NO_URUT) + 1;
		}

        $q = DB::table('TR_TRX_HUB_DTL')
            ->insert([
                'KD_PERUSAHAAN'         => $kd_unit, 
                'NO_DOKUMEN'            => $no_dokumen, 
                'KD_LAYANAN'            => $kd_layanan, 
                'KD_TUJUAN'             => $kd_tujuan,
                'KD_JENIS'              => $kd_jenis,
                'NO_URUT'               => $no_urut,
                'TGL_PROGRESS'          => $tgl_progress,
                'NILAI_PROGRESS'        => 100,
                'USER_PROGRESS'         => 'TR', 
                'TITLE_PROGRESS'        => $title, 
                'URAIAN_PROGRESS'       => $catatan, 
                'STATUS_PROGRESS'       => $status, 
                'FOTO_SELESAI'          => $fileName_done, 
                'USER_ENTRY'            => $user,
                'TGL_ENTRY'             => $tgl
            ]);

        return $q;
    }

    public static function void_data($kd_unit,$no_dokumen,$kd_layanan,$kd_tujuan,$kd_jenis,$catatan,$user,$tgl){

        $q = DB::table('TR_TRX_HUB')
            ->where('KD_PERUSAHAAN', '=', $kd_unit)
            ->where('NO_DOKUMEN', '=', $no_dokumen)
            ->where('KD_LAYANAN', '=', $kd_layanan)
            ->update(
                [
                    'STATUS'		            => 'V',
                    'TITLE_PROGRESS'		    => '4',
                    'USER_UPDATE'               => $user,
                    'TGL_UPDATE'                => $tgl
                ]
            );

        $get_urut = DB::table('TR_TRX_HUB_DTL')
			->select('NO_URUT')
			->where('KD_PERUSAHAAN', '=', $kd_unit)
			->where('NO_DOKUMEN', '=', $no_dokumen)
			->orderBy('NO_URUT', 'DESC')
			->limit(1)
			->get();

		$no_urut='';
		foreach ($get_urut as $get_urut_row) {
			$no_urut = ($get_urut_row->NO_URUT) + 1;
		}

        $q = DB::table('TR_TRX_HUB_DTL')
            ->insert([
                'KD_PERUSAHAAN'         => $kd_unit, 
                'NO_DOKUMEN'            => $no_dokumen, 
                'KD_LAYANAN'            => $kd_layanan, 
                'KD_TUJUAN'             => $kd_tujuan,
                'KD_JENIS'              => $kd_jenis,
                'NO_URUT'               => $no_urut,
                'TGL_PROGRESS'          => $tgl,
                'NILAI_PROGRESS'        => 0,
                'USER_PROGRESS'         => 'TR', 
                'TITLE_PROGRESS'        => '4', 
                'URAIAN_PROGRESS'       => $catatan, 
                'STATUS_PROGRESS'       => 'V', 
                'FOTO_PEKERJAAN'        => null, 
                'FOTO_SELESAI'          => null, 
                'USER_ENTRY'            => $user,
                'TGL_ENTRY'             => $tgl
            ]);

        return $q;
    }

    public static function history_data($no_dokumen){
        $data = [];
        $hub = DB::table('tr_trx_hub')
            ->select('*')
            ->where('NO_DOKUMEN', '=', $no_dokumen)
            ->get();

        $dtl = DB::table('tr_trx_hub_dtl')
            ->select('*')
            ->where('NO_DOKUMEN', '=', $no_dokumen)
            ->orderby('NO_URUT', 'DESC')
            ->get();
        
        $data = array(
            'hub' => $hub,
            'dtl' => $dtl
        );

        return $data;
    }

    public static function cek_idcard($kd_unit, $no_dokumen){
        $q = DB::table('TRX_HUB_IDCARD')
            ->select('*')
            ->where('KD_PERUSAHAAN', '=', $kd_unit)
            ->where('NO_DOKUMEN', '=', $no_dokumen)
            ->exists();

        return $q;
    }

    public static function get_idcard($kd_unit, $no_dokumen){
        $q = DB::table('TRX_HUB_IDCARD')
            ->select('*')
            ->where('KD_PERUSAHAAN', '=', $kd_unit)
            ->where('NO_DOKUMEN', '=', $no_dokumen)
            ->get();

        return $q;
    }

    public static function generate_idcard($kd_unit, $no_dokumen, $kd_layanan, $kd_tujuan, $kd_jenis, $tipe_card, $kd_user, $dt_periode, $user, $tgl){
        // $prefix = DB::table('TR_MST_LAYANAN')
        //         ->select('KD_PREFIX')
        //         ->where('KD_LAYANAN', '=', $kd_layanan)
        //         ->get();

        // foreach ($prefix as $item) {
        //     $kd_prefix = $item->KD_PREFIX;
        // };
        
        $kd_prefix     = 'TRHUB';
        $lenght        = '3';
        $ls_lastnumber = '';

        $q = DB::statement("EXEC SP_MST_NUMBERING '".$kd_unit."', '".$kd_prefix."', '".$lenght."', '".$tgl."', '".$ls_lastnumber."', '".$user."', '".$tgl."'");

        $q = DB::table('MST_NUMBERING_DTL AS A')
        ->select(
            'A.KD_PERUSAHAAN',
            'A.KD_FUNGSI', 
            'A.FORMATION', 
            DB::raw('LEFT(A.FORMATION,4) AS TAHUN'), 
            DB::raw('RIGHT(A.FORMATION,2) AS BULAN'), 
            'A.SEQUENCE'
        )
        ->where('A.KD_PERUSAHAAN', '=', $kd_unit)
        ->where('A.KD_FUNGSI', '=', $kd_prefix)
        ->get();

        $kd_karyawan = '';
        foreach ($q as $row) {
            $kd_karyawan =  $row->KD_FUNGSI.$row->TAHUN.$row->BULAN.$row->SEQUENCE;
        }		

        $q = DB::table('TRX_HUB_IDCARD')
        ->insert([
            'KD_PERUSAHAAN' => $kd_unit,
            'NO_DOKUMEN'    => $no_dokumen,
            'KD_LAYANAN'    => $kd_layanan,
            'KD_TUJUAN'     => $kd_tujuan,
            'KD_JENIS'      => $kd_jenis,
            'KD_USER'       => $kd_user,
            'TIPE_CARD'     => $tipe_card,
            'KODE_KARYAWAN' => $kd_karyawan,
            'PERIODE'       => $dt_periode,
            'NO_SERIAL'     => $kd_karyawan,
            'USER_STATUS'   => 'A',
            'FLAG_AKTIF'    => 'Y',
            'USER_ENTRY'    => $user,
            'TGL_ENTRY'     => $tgl
        ]);

        return $kd_karyawan;
    } 

    public static function insert_idcard($kd_unit,$no_dokumen,$kd_layanan,$kd_tujuan,$kd_jenis,$catatan,$periode,$tgl_progress,$fileName,$user,$tgl){

        $q = DB::table('TR_TRX_HUB')
            ->where('KD_PERUSAHAAN', '=', $kd_unit)
            ->where('NO_DOKUMEN', '=', $no_dokumen)
            ->where('KD_LAYANAN', '=', $kd_layanan)
            ->update(
                [
                    'STATUS'		            => 'C',
                    'TITLE_PROGRESS'		    => '3',
                    'USER_UPDATE'               => $user,
                    'TGL_UPDATE'                => $tgl
                ]
            );

        $get_urut = DB::table('TR_TRX_HUB_DTL')
			->select('NO_URUT')
			->where('KD_PERUSAHAAN', '=', $kd_unit)
			->where('NO_DOKUMEN', '=', $no_dokumen)
			->orderBy('NO_URUT', 'DESC')
			->limit(1)
			->get();

		$no_urut='';
		foreach ($get_urut as $get_urut_row) {
			$no_urut = ($get_urut_row->NO_URUT) + 1;
		}

        $q = DB::table('TR_TRX_HUB_DTL')
            ->insert([
                'KD_PERUSAHAAN'         => $kd_unit, 
                'NO_DOKUMEN'            => $no_dokumen, 
                'KD_LAYANAN'            => $kd_layanan, 
                'KD_TUJUAN'             => $kd_tujuan,
                'KD_JENIS'              => $kd_jenis,
                'NO_URUT'               => $no_urut,
                'TGL_PROGRESS'          => $tgl_progress,
                'NILAI_PROGRESS'        => 0,
                'USER_PROGRESS'         => 'TR', 
                'TITLE_PROGRESS'        => '3', 
                'URAIAN_PROGRESS'       => $catatan.' '.$periode, 
                'STATUS_PROGRESS'       => 'C', 
                'FOTO_PEKERJAAN'        => $fileName, 
                'USER_ENTRY'            => $user,
                'TGL_ENTRY'             => $tgl
            ]);

        return $q;
    }


    //Firebase
    public static function get_token_device($user_id){
        $token = DB::table('TR_USER_TOKEN')
                ->select('*')
                ->where('KD_USER', '=', $user_id)
                ->get();
        return $token;
    }

    public static function get_key_firebase(){
        $token = DB::table('TR_FCM')
                ->select('SERVER_KEY')
                ->where('PLATFORM', '=', 'android')
                ->get();
        return $token;
    }
}