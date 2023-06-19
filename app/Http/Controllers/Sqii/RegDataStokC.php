<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\RegDataStokM;
use Excel;
use App\Models\Sqii\StokImport;
use App\Models\Sqii\TipeRumahImport;

class RegDataStokC extends Controller
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

    	$q = RegDataStokM::data_kawasan(); 
		$kd_kawasan = '<select class="form-control col-sm-12" name="kd_kawasan" id="kd_kawasan" onchange="data_cluster()">';
		foreach ($q as $row) {
			$kd_kawasan .= '<option value="'.$row->KD_KAWASAN.'">'.$row->NM_KAWASAN.'</option>';
		}
		$kd_kawasan .= '</select>';

		$q = RegDataStokM::data_kawasan(); 
		$kd_kawasan_add = '<select class="form-control col-sm-12" name="kd_kawasan_add" id="kd_kawasan_add" onchange="data_cluster_add(); ">';
		foreach ($q as $row) {
			$kd_kawasan_add .= '<option value="'.$row->KD_KAWASAN.'">'.$row->NM_KAWASAN.'</option>';
		}
		$kd_kawasan_add .= '</select>';

		$q = RegDataStokM::data_jenis_bangunan(); 
		$kd_jenis_add = '<select class="form-control col-sm-12" name="kd_jenis_add" id="kd_jenis_add" >';
		foreach ($q as $row) {
			$kd_jenis_add .= '<option value="'.$row->KD_JENIS.'">'.$row->NM_JENIS.'</option>';
		}
		$kd_jenis_add .= '</select>';

		$q = RegDataStokM::data_kawasan(); 
		$kd_kawasan_tp_rmh = '<select class="form-control col-sm-12" name="kd_kawasan_tp_rmh" id="kd_kawasan_tp_rmh" onchange="data_cluster_tp_rmh(); ">';
		foreach ($q as $row) {
			$kd_kawasan_tp_rmh .= '<option value="'.$row->KD_KAWASAN.'">'.$row->NM_KAWASAN.'</option>';
		}
		$kd_kawasan_tp_rmh .= '</select>';

		$q = RegDataStokM::data_jenis_bangunan(); 
		$kd_jenis_tp_rmh = '<select class="form-control col-sm-12" name="kd_jenis_tp_rmh" id="kd_jenis_tp_rmh" >';
		foreach ($q as $row) {
			$kd_jenis_tp_rmh .= '<option value="'.$row->KD_JENIS.'">'.$row->NM_JENIS.'</option>';
		}
		$kd_jenis_tp_rmh .= '</select>';

    	$dt = array(
    		'button' 		=> $button,
    		'kd_kawasan' 	=> $kd_kawasan,
    		'kd_kawasan_add'=> $kd_kawasan_add,
    		'kd_jenis_add'=> $kd_jenis_add,
    		'kd_kawasan_tp_rmh'=> $kd_kawasan_tp_rmh,
    		'kd_jenis_tp_rmh'=> $kd_jenis_tp_rmh,
    		'data_user'		=> $data_user
    	);

    	return view('sqii.RegDataStokV')->with('dt', $dt);
    }

    public function show_jenis_bangunan(Request $r)
	{
		
		$q = RegDataStokM::show_jenis_bangunan();//KD_CLUSTER', 'a.NM_CLUSTER', 'b.NM_KAWASAN
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

		$q = RegDataStokM::sync_dt();
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
		// DD($r);
		$kd_kawasan   = $r->kd_kawasan;
		$q = RegDataStokM::data_cluster($kd_kawasan);
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
		$q = RegDataStokM::tipe_rumah($kd_kawasan);
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
		$q = RegDataStokM::data_blok_no($kd_kawasan,$kd_cluster,$tahap_bangun);
		foreach ($q as $row) {
			$data[] = array(
				'kd_kawasan'	=> $row->KD_KAWASAN,
				'kd_cluster'	=> $row->KD_CLUSTER,
				'blok'			=> $row->BLOK,
				'nomor'			=> $row->NOMOR,
				'kd_tipe'		=> $row->KD_TIPE,
				'nm_tipe'		=> $row->NM_TIPE
			);
		}

		if(isset($data)){
			return $data;
		}
	}

	public function save(Request $r)
	{
		//print_r($r);die;
		$savebtnval    		= $r->saveBtnVal;
		$kd_kawasan_add    	= $r->kd_kawasan_add;
		$kd_cluster_add    	= $r->kd_cluster_add;
		$kd_jenis_add    	= $r->kd_jenis_add;
		$kd_tipe_add   	 	= $r->kd_tipe_add;
		$blok    			= $r->blok;
		$nomor    			= $r->nomor;
		$stok_id    		= $r->stok_id;
		$user   			= $r->session()->get('user_id');
		$data 				= NULL;

		$q = RegDataStokM::cek_stok($kd_kawasan_add,$kd_cluster_add,$blok,$nomor,$kd_jenis_add,$kd_tipe_add);
		foreach ($q as $row) {
			$data = $row->NOMOR;
		}

		if(!isset($data)){
			if($savebtnval == 'create'){
				$q = RegDataStokM::simpan_dt($kd_kawasan_add,$kd_cluster_add,$blok,$nomor,$kd_jenis_add,$kd_tipe_add,$user);
			}else{
				$q = RegDataStokM::update_dt($kd_kawasan_add,$kd_cluster_add,$blok,$nomor,$kd_jenis_add,$kd_tipe_add,$stok_id,$user);
			}
		}
		

		//return response()->json($q);
	}	

	public function pop_tipe(Request $r)
    {
    	$user_id = $r->session()->get('user_id');
    	$keyword = $r->keyword;
    	$kd_kawasan   = $r->kd_kawasan;

    	$q = RegDataStokM::tipe_rumah($kd_kawasan, $keyword);

    	return $q;
    }

    public function search_blok_no(Request $r)
    {
    	$user_id = $r->session()->get('user_id');
    	$keyword = $r->keyword;
    	$kd_kawasan   = $r->kd_kawasan;
    	$kd_cluster   = $r->kd_cluster;

    	$q = RegDataStokM::search_blok_no($kd_kawasan,$kd_cluster, $keyword);

    	return $q;
    }

	public function sync_dt_tipe_rumah(Request $r)
	{	
		$user   	  = $r->session()->get('user_id');
    	$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;

		$get_tipe_rumah = RegDataStokM::get_tipe_rumah_view($kd_kawasan,$kd_cluster);
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
			
			$cek_tipe_rumah_exists = RegDataStokM::cek_tipe_rumah_exists($kd_jenis, $kd_tipe, $kd_kawasan);

			if($cek_tipe_rumah_exists){
				$insert_tipe_rumah = RegDataStokM::insert_tipe_rumah($kd_jenis, $kd_tipe, $deskripsi, $kd_sektor, $kd_kawasan, $flag_aktif_tipe_rumah, $user);
				
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

	// 	$get_tipe_rumah = RegDataStokM::get_tipe_rumah_view($kd_kawasan,$kd_cluster);
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
			
	// 		$cek_tipe_rumah_exists = RegDataStokM::cek_tipe_rumah_exists($kd_jenis, $kd_tipe, $kd_kawasan);
	// 		if($cek_tipe_rumah_exists){
	// 			$insert_tipe_rumah = RegDataStokM::insert_tipe_rumah($kd_jenis, $kd_tipe, $deskripsi, $kd_sektor, $kd_kawasan, $flag_aktif_tipe_rumah, $user);
	// 		}
	// 	}
	// }	

	public function sync_dt2(Request $r)
	{	
		$user   	  = $r->session()->get('user_id');
    	$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;

		$get_tipe_rumah = RegDataStokM::get_lantai_tipe_rumah($kd_kawasan,$kd_cluster);
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
			
			$cek_tipe_rumah_exists = RegDataStokM::cek_lantai_tipe_rumah_exists($kd_lantai,$kd_jenis, $kd_tipe, $kd_kawasan);
			//dd($cek_tipe_rumah_exists);
			if($cek_tipe_rumah_exists){
				$insert_tipe_rumah = RegDataStokM::ins_lantai_tipe_rumah($kd_lantai,$kd_jenis, $kd_tipe, $kd_kawasan, $path_foto_denah, $src_foto_denah, $path_foto_denah_2, $src_foto_denah_2, $keterangan, $user);
				
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

		$get_stok_kr = RegDataStokM::get_stok_kr($kd_kawasan,$kd_cluster);
		foreach ($get_stok_kr as $get_stok_kr_row) {
			$KD_KAWASAN = $get_stok_kr_row->KD_KAWASAN;
			$BLOK 		= $get_stok_kr_row->BLOK;
			$NOMOR 		= $get_stok_kr_row->NOMOR;
			$KD_SEKTOR 	= $get_stok_kr_row->KD_SEKTOR;
			$STOK_ID 	= $get_stok_kr_row->STOK_ID;
			$KD_JENIS 	= $get_stok_kr_row->KD_JENIS;
			$KD_TIPE 	= $get_stok_kr_row->KD_TIPE;
			$FLAG_AKTIF	= $get_stok_kr_row->FLAG_AKTIF;
			$KD_SEKTOR_SRIS	= $get_stok_kr_row->KD_SEKTOR_SRIS;
			
			$KD_TIPE_X 	= trim($KD_SEKTOR).'#'.trim($KD_TIPE);
			
			$FLAG_AKTIF_X = $FLAG_AKTIF;
			if(trim($FLAG_AKTIF) == 'A'){
				$FLAG_AKTIF_X = 'Y';
			}
			$FLAG_AKTIF_X = str_replace("A","Y",$FLAG_AKTIF_X);

			$cek_stok = RegDataStokM::cek_stok_sync($STOK_ID,$KD_KAWASAN);
			//if($cek_stok->num_rows() == 0){	
			// dd($cek_stok);
			if($cek_stok){		
				$insert_stok 	= RegDataStokM::insert_stok($KD_KAWASAN, $BLOK, $NOMOR, $KD_SEKTOR, $STOK_ID, $KD_JENIS, $KD_TIPE_X,$FLAG_AKTIF_X, $user, $KD_SEKTOR_SRIS);
				// DD($insert_stok);
				// if(gettype($insert_stok) == 'integer' && $insert_stok > 0){
				// 	$return = 1;
				// }else{
				// 	$error = 1; // error detected             
				// 	$error_data = $insert_stok; // last error msg 
				// }
			}
			// dd($cek_stok);
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
		$q = RegDataStokM::data_tahap($kd_kawasan,$kd_cluster);
		foreach ($q as $row) {
			$data[] = array(
				'tahap_bangun'	=> $row->TAHAP_BANGUN
			);
		}

		if(isset($data)){
			return $data;
		}
	}

	public function save_tipe_rumah(Request $r)
	{
		//DD($r);die;
		$savebtnval    		= $r->saveBtnTpRmhVal;
		$kd_kawasan    		= $r->kd_kawasan_tp_rmh;
		$kd_cluster    		= $r->kd_cluster_tp_rmh;
		$kd_jenis    		= $r->kd_jenis_tp_rmh;
		$kd_tipe   	 		= $r->kd_tipe;
		$nm_tipe   	 		= $r->nm_tipe;
		$user   			= $r->session()->get('user_id');
		$data 				= NULL;

		$q = RegDataStokM::cek_tp_rmh($kd_kawasan,$kd_cluster,$kd_tipe,$kd_jenis);
		foreach ($q as $row) {
			$data = $row->KD_TIPE;
		}

		if(!isset($data)){
			if($savebtnval == 'create'){
				$q = RegDataStokM::simpan_tp_rmh($kd_kawasan,$kd_cluster,$kd_jenis,$kd_tipe,$nm_tipe,$user);
			}else{
				$q = RegDataStokM::update_tp_rmh($kd_kawasan,$kd_cluster,$kd_jenis,$kd_tipe,$nm_tipe,$user);
			}
		}	
		return response()->json($q);
	}

    function save_process_upload(Request $request)
    {
		$this->validate($request, [
			'berkas2'  => 'required|mimes:xls,xlsx'
		]);
		$user   = $request->session()->get('user_id');
        // $yyyymmdd = date('Ymd');
        // $file_name = $yyyymmdd.sprintf("%07d", rand(0, 1000000));

        // $extFile2 = strtoupper($request->berkas2->getClientOriginalExtension());
        // $namaFile2 = 'EXL_'.$file_name.'.'.$extFile2;
        // $path2 = $request->berkas2->move('excel',$namaFile2);
        // $pathBaru2 = asset('excel/'.$namaFile2);
        // DD($pathBaru2);die;

		$path = $request->file('berkas2')->getRealPath();
		$import = Excel::toArray(new StokImport(), $request->file('berkas2'));
		// DD($import);die;

        foreach($import[0] as $row) {
			if(!empty($row)){
				RegDataStokM::simpan_dt($row['kd_kawasan'],$row['kd_cluster'],$row['blok'],$row['nomor'],$row['kd_jenis'],$row['kd_tipe'],$user);
			}
        }
		
		return 'Excel Data Imported successfully.';
    }

	function save_process_upload_tp_rmh(Request $request)
    {
		$this->validate($request, [
			'berkas'  => 'required|mimes:xls,xlsx'
		]);
		$user   = $request->session()->get('user_id');
        // $yyyymmdd = date('Ymd');
        // $file_name = $yyyymmdd.sprintf("%07d", rand(0, 1000000));

        // $extFile2 = strtoupper($request->berkas2->getClientOriginalExtension());
        // $namaFile2 = 'EXL_'.$file_name.'.'.$extFile2;
        // $path2 = $request->berkas2->move('excel',$namaFile2);
        // $pathBaru2 = asset('excel/'.$namaFile2);
        // DD($pathBaru2);die;

		$path = $request->file('berkas')->getRealPath();
		$import = Excel::toArray(new TipeRumahImport(), $request->file('berkas'));
		// DD($import);die;

        foreach($import[0] as $row) {
			if(!empty($row)){
				RegDataStokM::simpan_tp_rmh($row['kd_kawasan'],$row['kd_cluster'],$row['kd_jenis'],$row['kd_tipe'],$row['nm_tipe'],$user);
			}
        }
		
		return 'Excel Data Imported successfully.';
    }
}
