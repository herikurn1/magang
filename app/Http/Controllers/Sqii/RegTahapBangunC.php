<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\RegTahapBangunM;

class RegTahapBangunC extends Controller
{
	private $sysController;
	private $master;

	public function __construct()
	{
		$this->sysController = new SysController();
		$this->master = new CMaster();
	}

    public function index(Request $r)
    {
    	$user_id 		= $r->session()->get('user_id');
    	$button 		= $this->sysController->get_button($r);

    	$data_user 		= $this->master->get_data_user($user_id);

    	$q = RegTahapBangunM::data_kawasan(); 
		$kd_kawasan = '<select class="form-control col-sm-12" name="kd_kawasan" id="kd_kawasan" onchange="data_cluster()">';
		foreach ($q as $row) {
			$kd_kawasan .= '<option value="'.$row->KD_KAWASAN.'">'.$row->NM_KAWASAN.'</option>';
		}
		$kd_kawasan .= '</select>';

		$q = RegTahapBangunM::data_kawasan(); 
		$kd_kawasan_add = '<select class="form-control col-sm-12" name="kd_kawasan_add" id="kd_kawasan_add" onchange="data_cluster_add(); ">';
		foreach ($q as $row) {
			$kd_kawasan_add .= '<option value="'.$row->KD_KAWASAN.'">'.$row->NM_KAWASAN.'</option>';
		}
		$kd_kawasan_add .= '</select>';

		$q = RegTahapBangunM::data_jenis_bangunan(); 
		$kd_jenis_add = '<select class="form-control col-sm-12" name="kd_jenis_add" id="kd_jenis_add" >';
		foreach ($q as $row) {
			$kd_jenis_add .= '<option value="'.$row->KD_JENIS.'">'.$row->NM_JENIS.'</option>';
		}
		$kd_jenis_add .= '</select>';

    	$dt = array(
    		'button' 		=> $button,
    		'kd_kawasan' 	=> $kd_kawasan,
    		'kd_kawasan_add'=> $kd_kawasan_add,
    		'kd_jenis_add'=> $kd_jenis_add,
    		'data_user'		=> $data_user
    	);

    	return view('sqii.RegTahapBangunV')->with('dt', $dt);
    }

    public function show_jenis_bangunan(Request $r)
	{
		
		$q = RegTahapBangunM::show_jenis_bangunan();//KD_CLUSTER', 'a.NM_CLUSTER', 'b.NM_KAWASAN
		foreach ($q as $row) {
			$data[] = array(
				'kd_jenis'	=> $row->KD_JENIS,
				'nm_jenis'	=> $row->NM_JENIS
			);
		}

		if(isset($data)){
			return $data;
		}
	}

    public function data_jenis_bangunan(Request $r)
	{

		$q = RegTahapBangunM::sync_dt();
		foreach ($q as $row) {
			$data[] = array(
				'kd_jenis'	=> $row->KD_JENIS,
				'nm_jenis'	=> $row->NM_JENIS
			);
		}

		if(isset($data)){
			return $data;
		}
	}

	public function data_cluster(Request $r)
	{

		$kd_kawasan   = $r->kd_kawasan;
		$q = RegTahapBangunM::data_cluster($kd_kawasan);
		foreach ($q as $row) {
			$data[] = array(
				'kd_cluster'	=> $row->KD_CLUSTER,
				'nm_cluster'	=> $row->NM_CLUSTER
			);
		}

		if(isset($data)){
			return $data;
		}
	}

	public function tipe_rumah(Request $r)
	{

		$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;
		$kd_jenis     = $r->kd_jenis;
		$q = RegTahapBangunM::tipe_rumah($kd_kawasan);
		foreach ($q as $row) {
			$data[] = array(
				'kd_tipe'	=> $row->KD_TIPE,
				'nm_tipe'	=> $row->NM_TIPE
			);
		}

		if(isset($data)){
			return $data;
		}
	}

	public function data_blok_no(Request $r)
	{

		$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;
		$tahap_bangun = $r->tahap_bangun;
		$no_awal 	  = $r->no_awal;
		$no_akhir 	  = $r->no_akhir;
		$q = RegTahapBangunM::data_blok_no($kd_kawasan,$kd_cluster,$tahap_bangun,$no_awal,$no_akhir);
		foreach ($q as $row) {
			$dataTipeRumah = '';
			$selected = '';
			$q_tp = RegTahapBangunM::data_tipe_rumah($kd_kawasan,$kd_cluster);
			$dataTipeRumah .= '<select class="form-control col-sm-12" name="tp_rmh_edit[]" id="tp_rmh_edit[]" >';
			foreach ($q_tp as $r_tp) {
				if($r_tp->KD_TIPE == $row->KD_TIPE){$selected = 'selected';}
				$dataTipeRumah .= '<option value="'.$row->KD_KAWASAN.'-'.$row->KD_CLUSTER.'-'.$row->BLOK.'-'.$row->NOMOR.'-'.$row->KD_TIPE.'-'.$r_tp->KD_TIPE.'" '.$selected.'>'.$r_tp->NM_TIPE.'</option>';
				$selected = '';
			}
			$dataTipeRumah .= '</select>';
			$data[] = array(
				'kd_kawasan'	=> $row->KD_KAWASAN,
				'kd_cluster'	=> $row->KD_CLUSTER,
				'blok'			=> $row->BLOK,
				'nomor'			=> $row->NOMOR,
				'kd_tipe'		=> $row->KD_TIPE,
				'nm_tipe'		=> $row->NM_TIPE,
				'tahap_bangun' 	=> $row->TAHAP_BANGUN,
				'dataTipeRumah' => $dataTipeRumah,
				'flag_st' 		=> $row->FLAG_ST
			);
		}

		if(isset($data)){
			return $data;
		}
	}

	public function save(Request $r)
	{
		$kd_kawasan    		= $r->kd_kawasan;
		$kd_cluster    		= $r->kd_cluster;
		$tahap_bangun_edit  = $r->tahap_bangun_edit;
		$flag_st_edit  		= $r->flag_st_edit;
		$tp_rmh_edit  		= $r->tp_rmh_edit;
		$user   			= $r->session()->get('user_id');
		$data 				= NULL;
		$jum_brs 			= count($tahap_bangun_edit);
		if($jum_brs > 0){
			for ($i=0; $i<=$jum_brs-1; $i++){
				$tahap_bangun = explode('#',$tahap_bangun_edit[$i]);
				$flag_st = explode('#',$flag_st_edit[$i]);
				$tp_rmh = explode('-',$tp_rmh_edit[$i]);
				$set_tahap_bangun = $set_tp_rmh = 'N';
				$set_flag_st = 'T';
				// DD($tahap_bangun);
				if($tahap_bangun[4] != $tahap_bangun[5]){ $set_tahap_bangun = 'Y'; }
				if($flag_st[4] != $flag_st[5]){ $set_flag_st = 'Y'; }
				if($tp_rmh[4] != $tp_rmh[5]){ $set_tp_rmh = 'Y'; }

				if($set_tahap_bangun == 'Y' || $set_flag_st == 'Y' || $set_tp_rmh == 'Y'){
					$q = RegTahapBangunM::update_dt($kd_kawasan,$kd_cluster,$tahap_bangun[2],$tahap_bangun[3],$tahap_bangun[5],$flag_st[5],$tp_rmh[5], $user);	
				}
			}
		}
		return response()->json($q);
	}	

	public function pop_tipe(Request $r)
    {
    	$user_id = $r->session()->get('user_id');
    	$keyword = $r->keyword;
    	$kd_kawasan   = $r->kd_kawasan;

    	$q = RegTahapBangunM::tipe_rumah($kd_kawasan, $keyword);

    	return $q;
    }

    public function search_blok_no(Request $r)
    {
    	$user_id = $r->session()->get('user_id');
    	$keyword = $r->keyword;
    	$kd_kawasan   = $r->kd_kawasan;
    	$kd_cluster   = $r->kd_cluster;

    	$q = RegTahapBangunM::search_blok_no($kd_kawasan,$kd_cluster, $keyword);

    	return $q;
    }

	public function sync_dt_tipe_rumah(Request $r)
	{	
		$user   	  = $r->session()->get('user_id');
    	$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;

		$get_tipe_rumah = RegTahapBangunM::get_tipe_rumah_view($kd_kawasan,$kd_cluster);
		foreach($get_tipe_rumah as $get_tipe_rumah_row){
			$kd_jenis 		= $get_tipe_rumah_row->KD_JENIS;
			$kd_tipe 		= $get_tipe_rumah_row->KD_TIPE;
			$deskripsi 		= $get_tipe_rumah_row->DESKRIPSI;
			$kd_sektor 		= $get_tipe_rumah_row->KD_SEKTOR;
			$kd_kawasan 	= $get_tipe_rumah_row->KD_KAWASAN;
			$flag_aktif 	= $get_tipe_rumah_row->FLAG_AKTIF;
			
			$flag_aktif_tipe_rumah = 'N';
			if($flag_aktif == "A"){
				$flag_aktif_tipe_rumah = 'Y';
			}
			
			//$kd_tipe 	= trim($kd_sektor).'#'.trim($kd_tipe);
			
			$cek_tipe_rumah_exists = RegTahapBangunM::cek_tipe_rumah_exists($kd_jenis, $kd_tipe, $kd_kawasan);

			if($cek_tipe_rumah_exists){
				$insert_tipe_rumah = RegTahapBangunM::insert_tipe_rumah($kd_jenis, $kd_tipe, $deskripsi, $kd_sektor, $kd_kawasan, $flag_aktif_tipe_rumah, $user);
				
				// if(gettype($insert_tipe_rumah) == 'integer' && $insert_tipe_rumah > 0){
				// 	$return = 1;
				// }else{
				// 	dd($insert_tipe_rumah);
				// 	$error = 1; // error detected             
				// 	$error_data = $insert_tipe_rumah; // last error msg 
				// }
			}
		}
	}

	// public function sync_dt_tipe_rumah(Request $r)
	// {	
	// 	$user   	  = $r->session()->get('user_id');
 //    	$kd_kawasan   = $r->kd_kawasan;
	// 	$kd_cluster   = $r->kd_cluster;

	// 	$get_tipe_rumah = RegTahapBangunM::get_tipe_rumah_view($kd_kawasan,$kd_cluster);
	// 	foreach($get_tipe_rumah as $get_tipe_rumah_row){
	// 		$kd_jenis 		= $get_tipe_rumah_row->KD_JENIS;
	// 		$kd_tipe 		= $get_tipe_rumah_row->KD_TIPE;
	// 		$deskripsi 		= $get_tipe_rumah_row->DESKRIPSI;
	// 		$kd_sektor 		= $get_tipe_rumah_row->KD_SEKTOR;
	// 		$kd_kawasan 	= $get_tipe_rumah_row->KD_KAWASAN;
	// 		$flag_aktif 	= $get_tipe_rumah_row->FLAG_AKTIF;
			
	// 		$flag_aktif_tipe_rumah = 'N';
	// 		if($flag_aktif == "A"){
	// 			$flag_aktif_tipe_rumah = 'Y';
	// 		}
			
	// 		$kd_tipe 	= trim($kd_sektor).'#'.trim($kd_tipe);
			
	// 		$cek_tipe_rumah_exists = RegTahapBangunM::cek_tipe_rumah_exists($kd_jenis, $kd_tipe, $kd_kawasan);
	// 		if($cek_tipe_rumah_exists){
	// 			$insert_tipe_rumah = RegTahapBangunM::insert_tipe_rumah($kd_jenis, $kd_tipe, $deskripsi, $kd_sektor, $kd_kawasan, $flag_aktif_tipe_rumah, $user);
	// 		}
	// 	}
	// }	

	public function sync_dt2(Request $r)
	{	
		$user   	  = $r->session()->get('user_id');
    	$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;

		$get_tipe_rumah = RegTahapBangunM::get_lantai_tipe_rumah($kd_kawasan,$kd_cluster);
		foreach($get_tipe_rumah as $get_tipe_rumah_row){

			$kd_lantai 				= $get_tipe_rumah_row->KD_LANTAI;
			$kd_jenis 				= $get_tipe_rumah_row->KD_JENIS;
			$kd_tipe 				= $get_tipe_rumah_row->KD_TIPE;
			$kd_kawasan 			= $get_tipe_rumah_row->KD_KAWASAN;
			$path_foto_denah 		= $get_tipe_rumah_row->PATH_FOTO_DENAH;
			$src_foto_denah 		= $get_tipe_rumah_row->SRC_FOTO_DENAH;
			$path_foto_denah_2 		= $get_tipe_rumah_row->PATH_FOTO_DENAH_2;
			$src_foto_denah_2 		= $get_tipe_rumah_row->SRC_FOTO_DENAH_2;
			$keterangan 			= $get_tipe_rumah_row->KETERANGAN;
			

			// $flag_aktif_tipe_rumah = 'N';
			// if($flag_aktif == "A"){
			// 	$flag_aktif_tipe_rumah = 'Y';
			// }
			
			// $kd_tipe 	= trim($kd_sektor).'#'.trim($kd_tipe);
			
			$cek_tipe_rumah_exists = RegTahapBangunM::cek_lantai_tipe_rumah_exists($kd_lantai,$kd_jenis, $kd_tipe, $kd_kawasan);
			//dd($cek_tipe_rumah_exists);
			if($cek_tipe_rumah_exists){
				$insert_tipe_rumah = RegTahapBangunM::ins_lantai_tipe_rumah($kd_lantai,$kd_jenis, $kd_tipe, $kd_kawasan, $path_foto_denah, $src_foto_denah, $path_foto_denah_2, $src_foto_denah_2, $keterangan, $user);
				
				// if(gettype($insert_tipe_rumah) == 'integer' && $insert_tipe_rumah > 0){
				// 	$return = 1;
				// }else{
				// 	dd($insert_tipe_rumah);
				// 	$error = 1; // error detected             
				// 	$error_data = $insert_tipe_rumah; // last error msg 
				// }
				//dd($insert_tipe_rumah);
			}
		}
	}		

    public function sync_dt(Request $r)
	{
		$user   	  = $r->session()->get('user_id');
		$kd_kawasan   = $r->kd_kawasan;
    	$kd_cluster   = $r->kd_cluster;
		$TGL 		  = date('Y-m-d H:i:s');
	    $return       = 0;
	    $error        = 0;
	    $error_data   = 0;

		$get_stok_kr = RegTahapBangunM::get_stok_kr($kd_kawasan,$kd_cluster);
		foreach ($get_stok_kr as $get_stok_kr_row) {
			$KD_KAWASAN = $get_stok_kr_row->KD_KAWASAN;
			$BLOK 		= $get_stok_kr_row->BLOK;
			$NOMOR 		= $get_stok_kr_row->NOMOR;
			$KD_SEKTOR 	= $get_stok_kr_row->KD_SEKTOR;
			$STOK_ID 	= $get_stok_kr_row->STOK_ID;
			$KD_JENIS 	= $get_stok_kr_row->KD_JENIS;
			$KD_TIPE 	= $get_stok_kr_row->KD_TIPE;
			$FLAG_AKTIF	= $get_stok_kr_row->FLAG_AKTIF;
			
			$KD_TIPE_X 	= trim($KD_SEKTOR).'#'.trim($KD_TIPE);
			
			$FLAG_AKTIF_X = $FLAG_AKTIF;
			if(trim($FLAG_AKTIF) == 'A'){
				$FLAG_AKTIF_X = 'Y';
			}
			$FLAG_AKTIF_X = str_replace("A","Y",$FLAG_AKTIF_X);

			$cek_stok = RegTahapBangunM::cek_stok_sync($STOK_ID,$KD_KAWASAN);
			//if($cek_stok->num_rows() == 0){	
			// dd($cek_stok);
			if($cek_stok){		
				$insert_stok 	= RegTahapBangunM::insert_stok($KD_KAWASAN, $BLOK, $NOMOR, $KD_SEKTOR, $STOK_ID, $KD_JENIS, $KD_TIPE_X,$FLAG_AKTIF_X, $user);

				// if(gettype($insert_stok) == 'integer' && $insert_stok > 0){
				// 	$return = 1;
				// }else{
				// 	$error = 1; // error detected             
				// 	$error_data = $insert_stok; // last error msg 
				// }
			}
		}

	    // if($return == 1 && $error == 0 ){
	    //     $response['value']          = "1";
	    //     $response['message']        = 'Insert Berhasil';
	    // }else{
	    //     $response['value']          = "0";
	    //     $response['message']        = 'Insert Gagal';
	    //     $response['error']          = $error_data;
	    // }

	    // return response()->json($response);
	}

	public function data_tahap(Request $r)
	{
		$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;
		$q = RegTahapBangunM::data_tahap($kd_kawasan,$kd_cluster);
		foreach ($q as $row) {
			$data[] = array(
				'tahap_bangun'	=> $row->TAHAP_BANGUN
			);
		}

		if(isset($data)){
			return $data;
		}
	}
}
