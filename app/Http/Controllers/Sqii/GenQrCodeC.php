<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\GenQrcodeM;

class GenQrcodeC extends Controller
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

    	$q = GenQrcodeM::data_kawasan(); 
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

    	return view('sqii.GenQrcodeV')->with('dt', $dt);
    }

    public function show_data_item_defect(Request $r)
	{
		$kd_kategori    		= $r->kd_kategori;
		
		$q = GenQrcodeM::show_data_item_defect($kd_kategori); 
		foreach ($q as $row) {
			$data[] = array(
				'kd_item_defect'		=> $row->KD_ITEM_DEFECT,
				'nm_item_defect'		=> $row->NM_ITEM_DEFECT,
				'kd_kategori_defect'	=> $row->KD_KATEGORI_DEFECT,
				'nm_kategori_defect'	=> $row->NM_KATEGORI_DEFECT
			);
		}

		if(isset($data)){
			return $data;
		}
	}

	public function save(Request $r)
	{
		//print_r($r);die;
		$savebtnval   	 		= $r->saveBtnVal;
		$kd_item_defect    		= $r->kd_item_defect;
		$nm_item_defect    		= $r->nm_item_defect;
		$kd_kategori_defect    	= $r->kd_kategori_defect;
		$user   	= $r->session()->get('user_id');

		if($savebtnval == 'create'){
			$q = GenQrcodeM::simpan_dt($nm_item_defect,$kd_kategori_defect,$user);
		}else{
			$q = GenQrcodeM::update_dt($kd_item_defect,$nm_item_defect,$user);
		}

		//return response()->json($q);
	}

	public function delete_dt(Request $r)
	{
		$kd_item_defect    = $r->kd_item_defect;
		$user   	= $r->session()->get('user_id');

		$q = GenQrcodeM::delete_dt($kd_item_defect,$user);

		//return response()->json($q);
	}

	public function search_dt(Request $r)
	{

		$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;
		$q = GenQrcodeM::search_dt($kd_kawasan,$kd_cluster);
		foreach ($q as $row) {
			$data[] = array(
				'kd_kawasan'	=> $row->KD_KAWASAN,
				'kd_cluster'	=> $row->KD_CLUSTER,
				'blok'			=> $row->BLOK,
				'nomor'			=> $row->NOMOR,
				'kd_tipe'		=> $row->KD_TIPE,
				'nm_tipe'		=> $row->NM_TIPE,
				'qr_code'		=> $row->KD_QRCODE
			);
		}

		if(isset($data)){
			return $data;
		}
	}
}
