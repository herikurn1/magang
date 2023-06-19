<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\InputTahapPekerjaanM;
use DataTables;

class InputTahapPekerjaanC extends Controller
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

    	$q = InputTahapPekerjaanM::data_kawasan(); 
		$kd_kawasan = '<select class="form-control col-sm-12" name="kd_kawasan" id="kd_kawasan" onchange="data_cluster()">';
		foreach ($q as $row) {
			$kd_kawasan .= '<option value="'.$row->KD_KAWASAN.'">'.$row->NM_KAWASAN.'</option>';
		}
		$kd_kawasan .= '</select>';

    	$dt = array(
    		'button' 		=> $button,
    		'kd_kawasan' 		=> $kd_kawasan,
    		'data_user'		=> $data_user
    	);

    	return view('sqii.InputTahapPekerjaanV')->with('dt', $dt);
    }

    public function show_tahap_pekerjaan(Request $r)
	{
		$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;
		
		$q = InputTahapPekerjaanM::show_tahap_pekerjaan($kd_kawasan,$kd_cluster);
		foreach ($q as $row) {
			$data[] = array(
				'kd_tahap'		=> $row->KD_TAHAP,
				'nm_tahap'		=> $row->NM_TAHAP,
				'fg_aktif'		=> $row->FLAG_AKTIF
			);
		}

		if(isset($data)){
			return $data;
		}
	}

	public function search_dt(Request $r)
	{
		
		$keyword    = $r->keyword;
		$user   	= $r->session()->get('user_id');

		$q = InputTahapPekerjaanM::search_dt($keyword);

		//return response()->json($q);
		return Datatables::of($q)->make(true);
	}

	public function save(Request $r)
	{
		//print_r($r);die;
		$savebtnval    = $r->saveBtnVal;
		$id_tahap      = explode("#", $r->id_tahap_pekerjaan);
		$kd_tahap      = $id_tahap[0];
		$nm_tahap      = $id_tahap[1];
		$kd_kawasan    = $r->kd_kawasan_l;
		$kd_cluster    = $r->kd_cluster_l;
		$user   	= $r->session()->get('user_id');

		if($savebtnval == 'create'){
			$q = InputTahapPekerjaanM::simpan_dt($kd_kawasan,$kd_cluster,$kd_tahap,$nm_tahap,$user);
		}else{
			$q = InputTahapPekerjaanM::update_dt($kd_kawasan,$kd_cluster,$kd_tahap,$nm_tahap,$user);
		}

		//return response()->json($q);
	}

	public function delete_dt(Request $r)
	{
		$kd_tahap    = $r->kd_tahap;
		$user   	= $r->session()->get('user_id');

		$q = InputTahapPekerjaanM::delete_dt($kd_tahap,$user);

		//return response()->json($q);
	}

	public function data_cluster(Request $r)
	{

		$kd_kawasan   = $r->kd_kawasan;
		$q = InputTahapPekerjaanM::data_cluster($kd_kawasan);
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

    public function mst_tahap_pekerjaan(Request $r)
	{
		$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;
		$q = InputTahapPekerjaanM::mst_tahap_pekerjaan($kd_kawasan,$kd_cluster);
		foreach ($q as $row) {
			$data[] = array(
				'kd_tahap'			=> $row->KD_TAHAP,
				'nm_tahap'			=> $row->NM_TAHAP
			);
		}
		//DD(data);
		if(isset($data)){
			return $data;
		}
	}

	public function upd_fg(Request $r)
	{
		$kd_kawasan   	= $r->kd_kawasan;
		$kd_cluster   	= $r->kd_cluster;
		$kd_tahap    	= $r->kd_tahap;
		$fg_aktif    	= $r->fg_aktif;
		$user   		= $r->session()->get('user_id');

		$q = InputTahapPekerjaanM::upd_fg($kd_kawasan,$kd_cluster,$kd_tahap,$fg_aktif,$user);

		//return response()->json($q);
	}	
}
