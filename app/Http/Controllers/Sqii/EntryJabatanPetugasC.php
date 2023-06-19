<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\EntryJabatanPetugasM;

class EntryJabatanPetugasC extends Controller
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

    	$q = EntryJabatanPetugasM::mst_jabatan(); 
		$kd_jabatan = '<select class="form-control col-sm-12" name="kd_jabatan" id="kd_jabatan" onchange="clear_tbl()" disabled>';
		foreach ($q as $row) {
			$kd_jabatan .= '<option value="'.$row->KD_JABATAN.'">'.$row->NM_JABATAN.'</option>';
		}
		$kd_jabatan .= '</select>';

		$q = EntryJabatanPetugasM::data_kawasan(); 
		$kd_kawasan = '<select class="form-control col-sm-12" name="kd_kawasan" id="kd_kawasan" onchange="clear_tbl()">';
		foreach ($q as $row) {
			$kd_kawasan .= '<option value="'.$row->KD_KAWASAN.'">'.$row->NM_KAWASAN.'</option>';
		}
		$kd_kawasan .= '</select>';

    	$dt = array(
    		'button' 		=> $button,
    		'kd_jabatan' 	=> $kd_jabatan,
    		'kd_kawasan' 	=> $kd_kawasan,
    		'data_user'		=> $data_user
    	);

    	return view('sqii.EntryJabatanPetugasV')->with('dt', $dt);
    }

	public function data_blok_no(Request $r)
	{

		$nik_petugas   = $r->nik_petugas;
		$q = EntryJabatanPetugasM::data_blok_no($nik_petugas);
		foreach ($q as $row) { 
			$data[] = array(
				'user_id'			=> $row->USER_ID,
				'user_id_bawahan'	=> $row->USER_ID_BAWAHAN,
				'nama'				=> $row->NAMA,
				'nm_jabatan'		=> $row->NM_JABATAN
			);
		}

		if(isset($data)){
			return $data;
		}
	}

	public function save(Request $r)
	{
		// //print_r($r);die;
		// $savebtnval    		= $r->saveBtnVal;
		// $kd_kawasan    		= $r->kd_kawasan;
		// $kd_jabatan    		= $r->kd_jabatan;
		// $nik_petugas   	 	= $r->nik_petugas;
		// $nm_petugas   	 	= $r->nm_petugas;
		// $flag_aktif   	 	= $r->flag_aktif;
		// $user   			= $r->session()->get('user_id');
		// $data 				= NULL;

		// if($savebtnval == 'create'){
		// 	$q = EntryJabatanPetugasM::simpan_dt($kd_kawasan, $kd_jabatan, $nik_petugas, $nm_petugas, $flag_aktif, $user);
		// }else{
		// 	$q = EntryJabatanPetugasM::update_dt($kd_kawasan, $kd_jabatan, $nik_petugas, $flag_aktif, $user);
		// }

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

		$q = EntryJabatanPetugasM::delete_dt($kd_kawasan,$kd_cluster,$blok,$nomor,$nik_petugas,$user);

		//return response()->json($q);
	}	

	public function save_bawahan(Request $r)
	{
		//print_r($r);die;
		$id_bawahan   	 	= $r->id_bawahan;
		$id_petugas   	 	= $r->nik_petugas;
		$nm_jabatan   	 	= $r->nm_jabatan;
		$user   			= $r->session()->get('user_id');
		$data 				= NULL;

		$q = EntryJabatanPetugasM::simpan_bawahan($id_petugas, $id_bawahan, $nm_jabatan, $user);

		//return response()->json($q);
	}	

	public function delete_bawahan(Request $r)
	{
		$id_bawahan   	 	= $r->user_id_bawahan;
		$id_petugas   	 	= $r->user_id;
		$user   			= $r->session()->get('user_id');

		$q = EntryJabatanPetugasM::delete_bawahan($id_petugas, $id_bawahan, $user);

		//return response()->json($q);
	}	

	public function nik_petugas(Request $r)
    {
    	$user_id = $r->session()->get('user_id');
    	$keyword = $r->keyword;

    	$q = EntryJabatanPetugasM::nik_petugas($keyword);

    	return $q;
    }

    public function nik_karyawan(Request $r)
    {
    	$user_id = $r->session()->get('user_id');
    	$keyword = $r->keyword;

    	$q = EntryJabatanPetugasM::nik_petugas($keyword);

    	return $q;
    }

    public function get_staff(Request $r)
	{

		$nik_petugas   = $r->nik_petugas;
		$q = EntryJabatanPetugasM::get_staff($nik_petugas);
		foreach ($q as $row) {
			$data[] = array(
				'user_id'		=> $row->USER_ID,
				'nama'			=> $row->NAMA,
				'kd_jabatan'	=> $row->KD_JABATAN,
				'nm_jabatan'	=> $row->NM_JABATAN
			);
		}

		if(isset($data)){
			return $data;
		}
	}

}
