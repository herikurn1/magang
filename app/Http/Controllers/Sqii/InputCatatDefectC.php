<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\InputCatatDefectM;

class InputCatatDefectC extends Controller
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

    	$q = InputCatatDefectM::show_data_kategori_defect(); 
		$kd_kategori = '<select class="form-control col-sm-12" name="kd_item_defect" id="kd_item_defect" onchange="item_defect()" required>
							<option value=""> - </option>';
		foreach ($q as $row) {
			$kd_kategori .= '<option value="'.$row->KD_ITEM_DEFECT.'">'.$row->NM_ITEM_DEFECT.'</option>';
		}
		$kd_kategori .= '</select>';
    	
    	$dt = array(
    		'button' 		=> $button,
    		'kd_kategori'	=> $kd_kategori,
    		'data_user'		=> $data_user
    	);

    	return view('sqii.InputCatatDefectV')->with('dt', $dt);
    }

    public function show_data_item_defect(Request $r)
	{
		$kd_item_defect    	= $r->kd_item_defect;
		
		$q = InputCatatDefectM::show_data_item_defect($kd_item_defect); 
		foreach ($q as $row) {
			$data[] = array(
				'kd_catatan'		=> $row->KD_CATATAN,
				'kd_item_defect'	=> $row->KD_ITEM_DEFECT,
				'deskripsi'			=> $row->DESKRIPSI,
				'nm_item_defect'	=> $row->NM_ITEM_DEFECT
			);
		}

		if(isset($data)){
			return $data;
		}
	}

	public function save(Request $r)
	{
		//print_r($r);die;
		$savebtnval   	 	= $r->saveBtnVal;
		$kd_item_defect    	= $r->kd_item_defect;
		$kd_catatan    		= $r->kd_catatan;
		$deskripsi    		= $r->deskripsi;
		$user   			= $r->session()->get('user_id');

		if($savebtnval == 'create'){
			$q = InputCatatDefectM::simpan_dt($kd_item_defect,$deskripsi,$user);
		}else{
			$q = InputCatatDefectM::update_dt($kd_item_defect,$kd_catatan,$deskripsi,$user);
		}

		return response()->json($q);
	}

	public function delete_dt(Request $r)
	{
		$kd_item_defect    	= $r->kd_item_defect;
		$kd_catatan    		= $r->kd_catatan;
		$user   			= $r->session()->get('user_id');

		$q = InputCatatDefectM::delete_dt($kd_item_defect,$kd_catatan,$user);

		//return response()->json($q);
	}
}
