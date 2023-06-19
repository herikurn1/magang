<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\InputMasterItemPekerjaanM;

class InputMasterItemPekerjaanC extends Controller
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
    	
    	$q = InputMasterItemPekerjaanM::data_tahapan(); 
		$kd_tahapan = '<select class="form-control col-sm-12" name="kd_tahapan" id="kd_tahapan" onchange="data_blok_no()">';
		foreach ($q as $row) {
			$kd_tahapan .= '<option value="'.$row->KD_TAHAP.'">'.$row->KD_TAHAP.' # '.$row->NM_TAHAP.'</option>';
		}
		$kd_tahapan .= '</select>';

    	$dt = array(
    		'button' 		=> $button,
    		'kd_tahapan' 		=> $kd_tahapan,
    		'data_user'		=> $data_user
    	);

    	return view('sqii.InputMasterItemPekerjaanV')->with('dt', $dt);
    }

	public function data_blok_no(Request $r)
	{
		$jns_pekerjan   = $r->jns_pekerjan;
		$kd_tahapan   = $r->kd_tahapan;
		$q = InputMasterItemPekerjaanM::data_item_pekerjaan_h($jns_pekerjan,$kd_tahapan);
		foreach ($q as $row) {
			$data[] = array(
				'kd_item_pekerjaan'	=> $row->KD_ITEM_PEKERJAAN,
				'parent_id'			=> $row->PARENT_ID,
				'kd_tahap'			=> $row->KD_TAHAP,
				'jenis_pekerjaan'	=> $row->JENIS_PEKERJAAN,
				'flag_header'		=> $row->FLAG_HEADER,
				'nm_pekerjaan'		=> $row->NM_PEKERJAAN,
				'urut_header'		=> $row->URUT_HEADER,
				'urut_detail'		=> $row->URUT_DETAIL
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
		$q = InputMasterItemPekerjaanM::item_header_detail($tipe_h_d,$jns_pekerjan,$kd_tahapan);
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
		$kd_tahap			= $r->kd_tahapan_bwh;
		$jns_pekerjan		= $r->jns_pekerjan_bwh;
		$id_header  		= explode("#", $r->id_header);
		$nm_pekerjaan  		= $r->id_detail;
		$kd_item_pekerjaan  = $r->kd_item_pekerjaan;
		$urut   			= $r->id_urut;
		$parent_id    		= NULL;
		
		if($tipe_h_d == 'H'){
			$flag_header    	= 'H';
		}elseif ($tipe_h_d == 'D') {
			$parent_id    		= $id_header[0];
			$flag_header    	= 'D';
		}
		$user   			= $r->session()->get('user_id');
		$data 				= NULL;

		if($saveBtnStaffVal == 'create'){
			$q = InputMasterItemPekerjaanM::simpan_dt($parent_id,$kd_tahap,$jns_pekerjan,$flag_header,$nm_pekerjaan,$user,$urut);
		}else{
			$q = InputMasterItemPekerjaanM::update_dt($kd_item_pekerjaan,$parent_id,$kd_tahap,$jns_pekerjan,$flag_header,$nm_pekerjaan,$user,$urut);
		}
		//return response()->json($q);
	}

	public function delete_item_pekerjaan(Request $r)
	{
		$kd_item_pekerjaan   	 	= $r->kd_item_pekerjaan;
		$user   			= $r->session()->get('user_id');
		$q = InputMasterItemPekerjaanM::delete_item_pekerjaan($kd_item_pekerjaan,$user);

		//return response()->json($q);
	}	
}
