<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\ListAlokasiPetugasM;

class ListAlokasiPetugasC extends Controller
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

    	$q = ListAlokasiPetugasM::data_kawasan(); 
		$kd_kawasan = '<select class="form-control col-sm-12" name="kd_kawasan" id="kd_kawasan" onchange="data_cluster(); data_tipe(); data_blok();">';
		foreach ($q as $row) {
			$kd_kawasan .= '<option value="'.$row->KD_KAWASAN.'">'.$row->NM_KAWASAN.'</option>';
		}
		$kd_kawasan .= '</select>';

    	$dt = array(
    		'button' 		=> $button,
    		'kd_kawasan' 	=> $kd_kawasan,
    		'data_user'		=> $data_user
    	);

    	return view('sqii.ListAlokasiPetugasV')->with('dt', $dt);
    }

	public function data_cluster(Request $r)
	{

		$kd_kawasan   = $r->kd_kawasan;
		$q = ListAlokasiPetugasM::data_cluster($kd_kawasan);
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
		$kd_tipe   = $r->kd_tipe;
		$blok   = $r->blok;
		$q = ListAlokasiPetugasM::data_blok_no($kd_kawasan,$kd_cluster,$kd_tipe,$blok);
		foreach ($q as $row) {
			$data[] = array(
				'kd_kawasan'	=> $row->KD_KAWASAN,
				'kd_cluster'	=> $row->KD_CLUSTER,
				'blok'			=> $row->BLOK,
				'nomor'			=> $row->NOMOR,
				'kd_tipe'		=> $row->KD_TIPE,
				'nm_tipe'		=> $row->NM_TIPE,
				'nm_jenis'		=> $row->NM_JENIS,
				'sm'			=> $row->SM,
				'bi'			=> $row->BI,
				'qc'			=> $row->QC,
				'ktt'			=> $row->KTT
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

		$q = ListAlokasiPetugasM::cek_stok($kd_kawasan_add,$kd_cluster_add,$blok,$nomor,$kd_jenis_add,$kd_tipe_add);
		foreach ($q as $row) {
			$data = $row->NOMOR;
		}

		if(!isset($data)){
			if($savebtnval == 'create'){
				$q = ListAlokasiPetugasM::simpan_dt($kd_kawasan_add,$kd_cluster_add,$blok,$nomor,$kd_jenis_add,$kd_tipe_add,$user);
			}else{
				$q = ListAlokasiPetugasM::update_dt($kd_kawasan_add,$kd_cluster_add,$blok,$nomor,$kd_jenis_add,$kd_tipe_add,$stok_id,$user);
			}
		}
		

		//return response()->json($q);
	}	

	public function data_tipe(Request $r)
    {
    	$user_id = $r->session()->get('user_id');
    	$keyword = $r->keyword;
    	$kd_kawasan   = $r->kd_kawasan;
    	$kd_cluster   = $r->kd_cluster;

    	$q = ListAlokasiPetugasM::tipe_rumah($kd_kawasan,$kd_cluster, $keyword);
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

    public function data_blok(Request $r)
    {
    	$user_id = $r->session()->get('user_id');
    	$keyword = $r->keyword;
    	$kd_kawasan   = $r->kd_kawasan;
    	$kd_cluster   = $r->kd_cluster;
    	$kd_tipe   = $r->kd_tipe;

    	$q = ListAlokasiPetugasM::data_blok($kd_kawasan,$kd_cluster,$kd_tipe);
		foreach ($q as $row) {
			$data[] = array(
				'blok'	=> $row->BLOK
			);
		}

		if(isset($data)){
			return $data;
		}
    }
}
