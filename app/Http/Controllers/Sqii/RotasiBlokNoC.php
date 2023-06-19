<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\RotasiBlokNoM;

class RotasiBlokNoC extends Controller
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

    	$q = RotasiBlokNoM::data_kawasan(); 
		$kd_kawasan = '<select class="form-control col-sm-12" name="kd_kawasan" id="kd_kawasan" onchange="data_cluster()">';
		foreach ($q as $row) {
			$kd_kawasan .= '<option value="'.$row->KD_KAWASAN.'">'.$row->NM_KAWASAN.'</option>';
		}
		$kd_kawasan .= '</select>';

    	$dt = array(
    		'button' 		=> $button,
    		'kd_kawasan' 	=> $kd_kawasan,
    		'data_user'		=> $data_user
    	);

    	return view('sqii.RotasiBlokNoV')->with('dt', $dt);
    }

	public function data_cluster(Request $r)
	{

		$kd_kawasan   = $r->kd_kawasan;
		$q = RotasiBlokNoM::data_cluster($kd_kawasan);
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

	public function data_blok_no(Request $r)
	{

		$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;
		$nik_petugas   = $r->nik_petugas;
		$q = RotasiBlokNoM::data_blok_no($kd_kawasan,$kd_cluster,$nik_petugas);
		foreach ($q as $row) {
			$data[] = array(
				'kd_kawasan'	=> $row->KD_KAWASAN,
				'kd_cluster'	=> $row->KD_CLUSTER,
				'blok'			=> $row->BLOK,
				'nomor'			=> $row->NOMOR,
				'nm_tipe'		=> $row->NM_TIPE,
				'user_id'		=> $row->USER_ID
			);
		}

		if(isset($data)){
			return $data;
		}
	}

	public function save(Request $r)
	{
		
		$data_penugasan = $r->data_penugasan;
		$user        	= $r->session()->get('user_id');
		$tgl 			= date('Y-m-d H:i:s');

		if($data_penugasan != null){
			$total = count($data_penugasan);

			for ($i=0; $i < $total; $i++) { 	
				$data = explode('#', $data_penugasan[$i]);
				$q = RotasiBlokNoM::simpan_dt($data[0], $data[1], $data[2], $data[3], $data[4], $user);
			}
		}		

		//return response()->json($q);
	}	

	public function delete_dt(Request $r)
	{
		$savebtnval    		= $r->savebtnval;
		$kd_kawasan    		= $r->kd_kawasan;
		$kd_cluster    		= $r->kd_cluster;
		$blok    			= $r->blok;
		$nomor   	 		= $r->nomor;
		$nik_petugas	 	= $r->user_id;
		$user   			= $r->session()->get('user_id');
		$data 				= NULL;

		$q = RotasiBlokNoM::delete_dt($kd_kawasan,$kd_cluster,$blok,$nomor,$nik_petugas,$user);

		//return response()->json($q);
	}	

	public function nik_petugas(Request $r)
    {
    	$user_id = $r->session()->get('user_id');
    	$keyword = $r->keyword;

    	$q = RotasiBlokNoM::nik_petugas($keyword);

    	return $q;
    }


    public function available_stok(Request $r)
	{

		$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;
		$nik_petugas   = $r->nik_petugas;
		$q = RotasiBlokNoM::available_stok($kd_kawasan,$kd_cluster,$nik_petugas);
		foreach ($q as $row) {
			$data[] = array(
				'kd_kawasan'	=> $row->KD_KAWASAN,
				'kd_cluster'	=> $row->KD_CLUSTER,
				'blok'			=> $row->BLOK,
				'nomor'			=> $row->NOMOR,
				'nm_tipe'		=> $row->NM_TIPE,
				'user_id'		=> $row->USER_ID
			);
		}

		if(isset($data)){
			return $data;
		}
	}

    public function get_staff(Request $r)
	{

		$user_lama   	= $r->user_lama;
		$kd_jabatan   	= $r->kd_jabatan;
		$q = RotasiBlokNoM::user_baru($user_lama,$kd_jabatan);
		foreach ($q as $row) {
			$data[] = array(
				'user_id'		=> $row->USER_ID,
				'nama'			=> $row->NAMA,
				'nm_jabatan'	=> $row->NM_JABATAN
			);
		}

		if(isset($data)){
			return $data;
		}
	}

	public function save_rotasi(Request $r)
	{
		//print_r($r);die;
		$user_baru   	 	= $r->user_baru;
		$user_lama   	 	= $r->user_lama;
		$kd_kawasan   	 	= $r->kd_kawasan;
		$kd_cluster   	 	= $r->kd_cluster;
		$blok   	 		= $r->blok;
		$nomor   	 		= $r->nomor;
		$user   			= $r->session()->get('user_id');
		$data 				= NULL;

		$q = RotasiBlokNoM::save_rotasi($kd_kawasan,$kd_cluster,$blok,$nomor,$user_lama,$user_baru);

		//return response()->json($q);
	}	
}
