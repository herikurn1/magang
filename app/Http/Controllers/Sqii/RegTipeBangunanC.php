<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\RegTipeBangunanM;

class RegTipeBangunanC extends Controller
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

    	$q = RegTipeBangunanM::data_kawasan(); 
		$kd_kawasan = '<select class="form-control col-sm-12" name="kd_kawasan" id="kd_kawasan" onchange="data_cluster()">';
		foreach ($q as $row) {
			$kd_kawasan .= '<option value="'.$row->KD_KAWASAN.'">'.$row->NM_KAWASAN.'</option>';
		}
		$kd_kawasan .= '</select>';

		$q = RegTipeBangunanM::data_kawasan(); 
		$kd_kawasan_add = '<select class="form-control col-sm-12" name="kd_kawasan_add" id="kd_kawasan_add" onchange="data_cluster_add(); ">';
		foreach ($q as $row) {
			$kd_kawasan_add .= '<option value="'.$row->KD_KAWASAN.'">'.$row->NM_KAWASAN.'</option>';
		}
		$kd_kawasan_add .= '</select>';

		$q = RegTipeBangunanM::data_jenis_bangunan(); 
		$kd_jenis_add = '<select class="form-control col-sm-12" name="kd_jenis_add" id="kd_jenis_add" >';
		foreach ($q as $row) {
			$kd_jenis_add .= '<option value="'.$row->KD_JENIS.'">'.$row->NM_JENIS.'</option>';
		}
		$kd_jenis_add .= '</select>';

    	$dt = array(
    		'button' 		=> $button,
    		'kd_kawasan' 	=> $kd_kawasan,
    		'kd_kawasan_add'=> $kd_kawasan_add,
    		'kd_jenis_add'	=> $kd_jenis_add,
    		'data_user'		=> $data_user
    	);

    	return view('sqii.RegTipeBangunanV')->with('dt', $dt);
    }

    public function show_jenis_bangunan(Request $r)
	{
		
		$q = RegTipeBangunanM::show_jenis_bangunan();//KD_CLUSTER', 'a.NM_CLUSTER', 'b.NM_KAWASAN
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

    public function sync_dt_old(Request $r)
	{

		$q = RegTipeBangunanM::sync_dt();
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
		$q = RegTipeBangunanM::data_cluster($kd_kawasan);
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

    public function search_dt(Request $r)
	{
		$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;
		$data;

		$q = RegTipeBangunanM::search_dt($kd_kawasan,$kd_cluster);
		foreach ($q as $row) {
			$data[] = array(
				'kd_jenis'		=> $row->KD_JENIS,
				'nm_jenis'		=> $row->NM_JENIS,
				'jml_lantai'	=> $row->JML_LANTAI,
				'kd_tipe'		=> $row->KD_TIPE,
				'nm_tipe'		=> $row->NM_TIPE
			);
		}

		if(isset($data)){
			return $data;
		}
	}	

	public function sync_dt(Request $r)
	{
		$user   	  = $r->session()->get('user_id');
		$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;
		$data;

		// tipe rumah
		$get_tipe_rumah = RegTipeBangunanM::get_tipe_rumah_view($kd_kawasan,$kd_cluster);
		// DD($get_tipe_rumah);
		foreach($get_tipe_rumah as $get_tipe_rumah_row){
			$kd_jenis 		= $get_tipe_rumah_row->KD_JENIS;
			$kd_tipe 		= $get_tipe_rumah_row->KD_TIPE;
			$deskripsi 		= $get_tipe_rumah_row->DESKRIPSI;
			$kd_sektor 		= $get_tipe_rumah_row->KD_SEKTOR;
			$kd_kawasan 	= $get_tipe_rumah_row->KD_KAWASAN;
			$flag_aktif 	= $get_tipe_rumah_row->FLAG_AKTIF;
			// $kd_model 		= $get_tipe_rumah_row->KD_MODEL;
			// $nm_model 		= $get_tipe_rumah_row->NM_MODEL;
			
			$flag_aktif_tipe_rumah = 'N';
			if($flag_aktif == "A" || $flag_aktif == "Y"){
				$flag_aktif_tipe_rumah = 'Y';
			}

			$kd_tipe 	= trim($kd_sektor).'#'.trim($kd_tipe);
					
			$cek_tipe_rumah_exists = RegTipeBangunanM::cek_tipe_rumah_exists($kd_jenis, $kd_tipe, $kd_kawasan);

			if($cek_tipe_rumah_exists){
				$insert_tipe_rumah = RegTipeBangunanM::insert_tipe_rumah($kd_jenis, $kd_tipe, $deskripsi, $kd_sektor, $kd_kawasan, $flag_aktif_tipe_rumah, $user);
	
			}
		}

		// lantai tipe rumah
		/*
		// FAHMI 20/06/2022 DI REMARK
		// KARENA SUDAH HARUS INPUT DATA DARI NEW PORTAL SUDAK TIDAK PERLU LAGI AMBIL DATA DARI SQII LAMA
		$get_tipe_rumah = RegTipeBangunanM::get_lantai_tipe_rumah($kd_kawasan,$kd_cluster);
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
			
			$cek_tipe_rumah_exists = RegTipeBangunanM::cek_lantai_tipe_rumah_exists($kd_lantai,$kd_jenis, $kd_tipe, $kd_kawasan);
			//dd($cek_tipe_rumah_exists);
			if($cek_tipe_rumah_exists){
				$insert_tipe_rumah = RegTipeBangunanM::ins_lantai_tipe_rumah($kd_lantai,$kd_jenis, $kd_tipe, $kd_kawasan, $path_foto_denah, $src_foto_denah, $path_foto_denah_2, $src_foto_denah_2, $keterangan, $user);
				
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
		*/

		if(isset($data)){
			return $data;
		}
	}

	public function data_denah(Request $r)
	{
		$kd_kawasan = $r->kd_kawasan;
		$kd_jenis   = $r->kd_jenis;
		$kd_tipe   	= $r->kd_tipe;
		
		$q = RegTipeBangunanM::data_denah($kd_kawasan,$kd_jenis,$kd_tipe);
		foreach ($q as $row) {
			$data[] = array(
				'nm_lantai'			=> $row->NM_LANTAI,
				'path_foto_denah'	=> $row->PATH_FOTO_DENAH,
				'src_foto_denah'	=> $row->SRC_FOTO_DENAH,
				'path_foto_denah_2'	=> $row->PATH_FOTO_DENAH_2,
				'src_foto_denah_2'	=> $row->SRC_FOTO_DENAH_2,
				'kd_lantai'			=> $row->KD_LANTAI,
				'kd_kawasan'		=> $kd_kawasan,
				'kd_jenis'			=> $kd_jenis,
				'kd_tipe'			=> $kd_tipe,
			);
		}

		if(isset($data)){
			return $data;
		}
	}	

    public function available_stok(Request $r)
	{

		$kd_kawasan   = $r->kd_kawasan;
		$kd_jenis   = $r->kd_jenis;
		$kd_tipe   = $r->kd_tipe;
		$q = RegTipeBangunanM::available_stok($kd_kawasan,$kd_jenis,$kd_tipe);
		foreach ($q as $row) {
			$data[] = array(
				'kd_lantai'	=> $row->KD_LANTAI,
				'nm_lantai'	=> $row->NM_LANTAI
			);
		}

		if(isset($data)){
			return $data;
		}
	}

    public function file_upload(Request $r){
        $r->validate([
             'berkas'  => 'required|file|image|max:2000',
             'berkas2'  => 'required|file|image|max:2000',
        ]);

        // $ip  = $_SERVER["SERVER_ADDR"];
        // echo $ip;die; ftp_put(D:/Apache24/htdocs\\image\\denah\\DS_202108160425327.JPG): failed to open stream: No such file or directory",
        $yyyymmdd = date('Ymd');
        $file_name = $yyyymmdd.sprintf("%07d", rand(0, 1000000));

        $extFile = strtoupper($r->berkas->getClientOriginalExtension());
        $namaFile = 'DS_'.$file_name.'.'.$extFile;

        $extFile2 = strtoupper($r->berkas2->getClientOriginalExtension());
        $namaFile2 = 'DA_'.$file_name.'.'.$extFile2;

        $path = $r->berkas->move('image/denah/',$namaFile);
        $path2 = $r->berkas2->move('image/denah_2',$namaFile2);

        $pathBaru = asset('image/denah/'.$namaFile);
        $pathBaru2 = asset('image/denah_2/'.$namaFile2);

        $kd_lantai 	   		= $r->kd_lantai;
        $kd_jenis 	   		= $r->kd_jenis_l;
        $kd_tipe 	   		= $r->kd_tipe_l;
        $kd_kawasan 	   	= $r->kd_kawasan_l;
        $path_foto_denah 	= 'ext-upload/sqii/denah/';
        $src_foto_denah 	= $namaFile;
        $path_foto_denah_2 	= 'ext-upload/sqii/denah_2/';
        $src_foto_denah_2 	= $namaFile2;
        $keterangan 	   	= 'NULL';
        $user   	  		= $r->session()->get('user_id');

        // $str = '\DS_'.$file_name.'.'.$extFile;

        // $file_location = $_SERVER['DOCUMENT_ROOT']."\image\denah";
        // Storage::disk('ftp')->put($namaFile, fopen($file_location.''.$str, 'r+'));

		$insert_tipe_rumah = RegTipeBangunanM::ins_lantai_tipe_rumah($kd_lantai,$kd_jenis, $kd_tipe, $kd_kawasan, $path_foto_denah, $src_foto_denah, $path_foto_denah_2, $src_foto_denah_2, $keterangan, $user);
		
		// FTP
		$ftp_server = "35.247.151.208"; // Address of FTP server.
		$ftp_user_name = "summarecongcp2019"; // Username
		$ftp_user_pass = 'Sm4rtc!ty'; // Password
		$destination_file = "/PHP/sqii_api/public/image"; //where you want to throw the file on the webserver (relative to your login dir)
		$file_location = $_SERVER['DOCUMENT_ROOT']."\portalnew\image";
		$conn_id = ftp_connect($ftp_server,'221','90') or die("<span style='color:#FF0000'><h2>Couldn't connect to $ftp_server</h2></span>");        // set up basic connection
		ftp_set_option($conn_id, FTP_USEPASVADDRESS, false);
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("<span style='color:#FF0000'><h2>You do not have access to this ftp server!</h2></span>");   // login with username and password, or give invalid user message
		ftp_pasv($conn_id, true);

		if ((!$conn_id) || (!$login_result)) {  // check connection
		    // wont ever hit this, b/c of the die call on ftp_login
		    echo "<span style='color:#FF0000'><h2>FTP connection has failed! <br />";
		    echo "Attempted to connect to $ftp_server for user $ftp_user_name</h2></span>";
		    exit;
		} else {
		    //echo "Connected to $ftp_server, for user $ftp_user_name <br />";
		}
		// $file_list = ftp_nlist($conn_id, "/PHP/sqii_api/public/image");//strtoupper ($namaFile )
		// print_r($file_list);die;
		ftp_put($conn_id, $destination_file.'/denah/'.$namaFile, $file_location.'\denah\DS_'.$file_name.'.'.$extFile, FTP_BINARY);  // upload the file
		//ftp_put($conn_id, $destination_file.'/denah/'.$namaFile, $file_location.'/'.$namaFile, FTP_BINARY);  // upload the file
		ftp_put($conn_id, $destination_file.'/denah_2/'.$namaFile2, $file_location.'\denah_2\DA_'.$file_name.'.'.$extFile2, FTP_BINARY);  // upload the file
		// ftp_put($conn_id, $destination_file.'/denah/'.$namaFile, $pathBaru, FTP_BINARY);  // upload the file
		// ftp_put($conn_id, $destination_file.'/denah_2/'.$namaFile2, $pathBaru2, FTP_BINARY);  // upload the file
		ftp_close($conn_id); // close the FTP stream 
    }	

	public function delete_denah(Request $r)
	{
		$kd_lantai   	 	= $r->kd_lantai;
		$kd_kawasan   	 	= $r->kd_kawasan;
		$kd_jenis   	 	= $r->kd_jenis;
		$kd_tipe   	 		= $r->kd_tipe;
		$user   			= $r->session()->get('user_id');

		$q = RegTipeBangunanM::delete_denah($kd_lantai, $kd_kawasan, $kd_jenis, $kd_tipe, $user);

		//return response()->json($q);
	}    
	
}
