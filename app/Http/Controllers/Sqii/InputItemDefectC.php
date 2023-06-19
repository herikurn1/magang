<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\InputItemDefectM;

class InputItemDefectC extends Controller
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

    	$q = InputItemDefectM::show_data_kategori_defect(); 
		$kd_kategori = '<select class="form-control col-sm-12" name="kd_kategori" id="kd_kategori" onchange="item_defect()" required>
							<option value=""> - </option>';
		foreach ($q as $row) {
			$kd_kategori .= '<option value="'.$row->KD_KATEGORI_DEFECT.'">'.$row->NM_KATEGORI_DEFECT.'</option>';
		}
		$kd_kategori .= '</select>';
    	
    	$dt = array(
    		'button' 		=> $button,
    		'kd_kategori'	=> $kd_kategori,
    		'data_user'		=> $data_user
    	);

    	return view('sqii.InputItemDefectV')->with('dt', $dt);
    }

    public function show_data_item_defect(Request $r)
	{
		$kd_kategori    		= $r->kd_kategori;
		
		$q = InputItemDefectM::show_data_item_defect($kd_kategori); 
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
			$q = InputItemDefectM::simpan_dt($nm_item_defect,$kd_kategori_defect,$user);
		}else{
			$q = InputItemDefectM::update_dt($kd_item_defect,$nm_item_defect,$user);
		}

		//return response()->json($q);
	}

	public function delete_dt(Request $r)
	{
		$kd_item_defect    = $r->kd_item_defect;
		$user   	= $r->session()->get('user_id');

		$q = InputItemDefectM::delete_dt($kd_item_defect,$user);

		//return response()->json($q);
	}
}
