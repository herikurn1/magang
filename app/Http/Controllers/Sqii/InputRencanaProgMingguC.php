<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\InputRencanaProgMingguM;

class InputRencanaProgMingguC extends Controller
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

    	$q = InputRencanaProgMingguM::data_kawasan(); 
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

    	return view('sqii.InputRencanaProgMingguV')->with('dt', $dt);
    }

    public function show_jenis_bangunan(Request $r)
	{
		
		$q = InputRencanaProgMingguM::show_jenis_bangunan();//KD_CLUSTER', 'a.NM_CLUSTER', 'b.NM_KAWASAN
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

		$q = InputRencanaProgMingguM::sync_dt();
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
		$q = InputRencanaProgMingguM::data_cluster($kd_kawasan);
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
		$q = InputRencanaProgMingguM::data_blok_no($kd_kawasan,$kd_cluster);
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
		$kd_tahapan   = $r->kd_tahapan;
		$q = InputRencanaProgMingguM::data_master_rencana_progres($kd_kawasan,$kd_cluster,$kd_tahapan);
		foreach ($q as $row) {
			$data[] = array(
				'kd_kawasan'		=> $row->KD_KAWASAN,
				'kd_cluster'		=> $row->KD_CLUSTER,
				'kd_tahap'			=> $row->KD_TAHAP,
				'kd_periode'		=> $row->KD_PERIODE,
				'nm_periode'		=> $row->NM_PERIODE,
				'tgl_awal'			=> $row->TGL_AWAL,
				'tgl_akhir'			=> $row->TGL_AKHIR,
				'progres'			=> number_format($row->PROGRESS, 2, '.', '')
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
		$q = InputRencanaProgMingguM::data_tahapan($kd_kawasan,$kd_cluster);
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
		$q = InputRencanaProgMingguM::item_header_detail($tipe_h_d,$jns_pekerjan,$kd_tahapan,$parent_id);
		foreach ($q as $row) {
			$data[] = array(
				'kd_item_pekerjaan'		=> $row->KD_ITEM_PEKERJAAN,
				'parent_id'				=> $row->PARENT_ID,
				'kd_tahap'				=> $row->KD_TAHAP,
				'jenis_pekerjaan'		=> $row->JENIS_PEKERJAAN,
				'flag_header'			=> $row->FLAG_HEADER,
				'nm_pekerjaan'			=> $row->NM_PEKERJAAN
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
		$kd_kawasan 		= $r->kd_kawasan_bwh;
		$kd_cluster  		= $r->kd_cluster_bwh;
		$kd_tahap  			= $r->kd_tahap_bwh;
		$nm_periode  		= $r->nm_periode;
		$periode1  			= $r->periode1; 
		$periode2  			= $r->periode2;
		$progress  			= $r->progress;
		$user   			= $r->session()->get('user_id');

        $dt = explode("/", $periode1);
        $dt2 = explode("/", $periode2); 

		$periode_1   	= $dt[2].'-'.$dt[1].'-'.$dt[0];
		$periode_2   	= $dt2[2].'-'.$dt2[1].'-'.$dt2[0];

		if($saveBtnStaffVal == 'create'){
			$q = InputRencanaProgMingguM::simpan_dt($kd_kawasan,$kd_cluster,$kd_tahap,$nm_periode,$periode_1,$periode_2,$progress,$user);
		}else{
		}
		//return response()->json($q);
	}

	public function delete_item_pekerjaan(Request $r)
	{
		$kd_tahap   	 			= $r->kd_tahap;
		$kd_kawasan   	 			= $r->kd_kawasan;
		$kd_cluster   	 			= $r->kd_cluster;
		$kd_periode   	 			= $r->kd_periode;
		$q = InputRencanaProgMingguM::delete_periode($kd_tahap,$kd_kawasan,$kd_cluster,$kd_periode);

		//return response()->json($q);
	}

	public function update_bobot(Request $r)
	{
		$kd_item_pekerjaan   	 	= $r->kd_item_pekerjaan;
		$bobot   	 				= $r->bobot;
		$kd_kawasan   	 			= $r->kd_kawasan;
		$kd_cluster   	 			= $r->kd_cluster;
		$user   					= $r->session()->get('user_id');
		$q = InputRencanaProgMingguM::update_bobot($bobot,$kd_item_pekerjaan,$kd_kawasan,$kd_cluster,$user);

		//return response()->json($q);
	}	

	public function cek_minggu_pertama(Request $r)
	{

		$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;
		$q = InputRencanaProgMingguM::cek_minggu_pertama($kd_kawasan,$kd_cluster);
		foreach ($q as $row) {
			$data[] = array(
				'tgl_awal'	=> $row->TGL_AWAL
			);
		}

		if(isset($data)){
			return $data;
		}
	}
}
