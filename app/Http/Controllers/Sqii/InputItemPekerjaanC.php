<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\InputItemPekerjaanM;

class InputItemPekerjaanC extends Controller
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

    	$q = InputItemPekerjaanM::data_kawasan(); 
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

    	return view('sqii.InputItemPekerjaanV')->with('dt', $dt);
    }

    public function show_jenis_bangunan(Request $r)
	{
		
		$q = InputItemPekerjaanM::show_jenis_bangunan();//KD_CLUSTER', 'a.NM_CLUSTER', 'b.NM_KAWASAN
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

    public function sync_dt(Request $r)
	{

		$q = InputItemPekerjaanM::sync_dt();
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
		$q = InputItemPekerjaanM::data_cluster($kd_kawasan);
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

	public function data_blok_no2(Request $r)
	{

		$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;
		$q = InputItemPekerjaanM::data_blok_no($kd_kawasan,$kd_cluster);
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

	public function data_blok_no(Request $r)
	{

		$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;
		$jns_pekerjan   = $r->jns_pekerjan;
		$kd_tahapan   = $r->kd_tahapan;
		$total_bobot = InputItemPekerjaanM::total_bobot($kd_kawasan,$kd_cluster,$jns_pekerjan,$kd_tahapan);

		$q = InputItemPekerjaanM::data_item_pekerjaan_h($kd_kawasan,$kd_cluster,$jns_pekerjan,$kd_tahapan);
		foreach ($q as $row) {
			$data[] = array(
				'kd_item_pekerjaan'	=> $row->KD_ITEM_PEKERJAAN,
				'kd_kawasan'		=> $row->KD_KAWASAN,
				'kd_cluster'		=> $row->KD_CLUSTER,
				'kd_tahap'			=> $row->KD_TAHAP,
				'jenis_pekerjaan'	=> $row->JENIS_PEKERJAAN,
				'flag_header'		=> $row->FLAG_HEADER,
				'nm_pekerjaan'		=> $row->NM_PEKERJAAN,
				'bobot'				=> number_format($row->BOBOT, 2, '.', ''),
				'total_bobot'		=> number_format($total_bobot, 2, '.', ''),
				'kd_tahapan'		=> $kd_tahapan
			);
		}

		if(isset($data)){
			return $data;
		}
	}

	public function data_tahapan(Request $r)
	{

		$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;
		$q = InputItemPekerjaanM::data_tahapan($kd_kawasan,$kd_cluster);
		foreach ($q as $row) {
			$data[] = array(
				'kd_tahap'	=> $row->KD_TAHAP,
				'nm_tahap'	=> $row->NM_TAHAP
			);
		}

		if(isset($data)){
			return $data;
		}
	}

    public function item_header_detail(Request $r)
	{

		$jns_pekerjan   = $r->jns_pekerjan;
		$kd_tahapan   	= $r->kd_tahapan;
		$tipe_h_d   	= $r->tipe_h_d;
		$id_header  	= explode("#", $r->id_header);

		if($tipe_h_d == 'H'){
			$parent_id    		= NULL;
		}elseif ($tipe_h_d == 'D') {
			$parent_id  		= $id_header[0];
		}		
		$q = InputItemPekerjaanM::item_header_detail($tipe_h_d,$jns_pekerjan,$kd_tahapan,$parent_id);
		foreach ($q as $row) {
			$data[] = array(
				'kd_item_pekerjaan'		=> $row->KD_ITEM_PEKERJAAN,
				'parent_id'				=> $row->PARENT_ID,
				'kd_tahap'				=> $row->KD_TAHAP,
				'jenis_pekerjaan'		=> $row->JENIS_PEKERJAAN,
				'flag_header'			=> $row->FLAG_HEADER,
				'nm_pekerjaan'			=> $row->NM_PEKERJAAN,
				'urut_header'			=> $row->URUT_HEADER,
				'urut_detail'			=> $row->URUT_DETAIL
			);
		}
		//DD(data);
		if(isset($data)){
			return $data;
		}
	}

	public function save(Request $r)
	{
		//print_r($r);die;saveBtnStaffVal
		$saveBtnStaffVal  	= $r->saveBtnStaffVal;
		$tipe_h_d  			= $r->tipe_h_d;
		$id_header  		= explode("#", $r->id_header);
		$id_detail  		= explode("#", $r->id_detail);
		if($tipe_h_d == 'H'){
			$kd_item_pekerjaan  = $id_header[0];
			$parent_id    		= $id_header[1];
			$kd_tahap    		= $id_header[2];
			$jenis_pekerjaan   	= $id_header[3];
			$flag_header    	= $id_header[4];
			$nm_pekerjaan   	= $id_header[5];
			$urut_header   		= $id_header[6];
			$urut_detail   		= $id_header[7];
		}elseif ($tipe_h_d == 'D') {
			$kd_item_pekerjaan  = $id_detail[0];
			$parent_id    		= $id_detail[1];
			$kd_tahap    		= $id_detail[2];
			$jenis_pekerjaan   	= $id_detail[3];
			$flag_header    	= $id_detail[4];
			$nm_pekerjaan   	= $id_detail[5];
			$urut_header   		= $id_header[6];
			$urut_detail   		= $id_header[7];
		}
		$kd_kawasan    		= $r->kd_kawasan_bwh;
		$kd_cluster    		= $r->kd_cluster_bwh;
		$user   			= $r->session()->get('user_id');
		$data 				= NULL;

		if($saveBtnStaffVal == 'create'){
			$q = InputItemPekerjaanM::simpan_dt($kd_item_pekerjaan,$parent_id,$kd_kawasan,$kd_cluster,$kd_tahap,$jenis_pekerjaan,$flag_header,$nm_pekerjaan,$urut_header,$urut_detail,$user);
		}else{
		}
		//return response()->json($q);
	}

	public function delete_item_pekerjaan(Request $r)
	{
		$kd_item_pekerjaan   	 	= $r->kd_item_pekerjaan;
		$kd_kawasan   	 			= $r->kd_kawasan;
		$kd_cluster   	 			= $r->kd_cluster;
		$q = InputItemPekerjaanM::delete_item_pekerjaan($kd_item_pekerjaan,$kd_kawasan,$kd_cluster);

		//return response()->json($q);
	}

	public function update_bobot(Request $r)
	{
		$kd_item_pekerjaan   	 	= $r->kd_item_pekerjaan;
		$bobot   	 				= $r->bobot;
		$kd_kawasan   	 			= $r->kd_kawasan;
		$kd_cluster   	 			= $r->kd_cluster;
		$user   					= $r->session()->get('user_id');
		$q = InputItemPekerjaanM::update_bobot($bobot,$kd_item_pekerjaan,$kd_kawasan,$kd_cluster,$user);

		//return response()->json($q);
	}	

    public function cek_total_bobot(Request $r)
	{

		$kd_kawasan   	= $r->kd_kawasan;
		$kd_cluster   	= $r->kd_cluster;
		$kd_item_pekerjaan = $r->kd_item_pekerjaan;
		$bobot   		= $r->bobot;	
		
		$total_bobot = InputItemPekerjaanM::cek_total_bobot($kd_kawasan,$kd_cluster,$kd_item_pekerjaan,$bobot);

		$data[] = array(
			'cek_total_bobot' => number_format($total_bobot, 2, '.', '')
		);

		//DD(data);
		if(isset($data)){
			return $data;
		}
	}
}
