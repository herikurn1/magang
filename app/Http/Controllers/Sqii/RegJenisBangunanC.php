<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\RegJenisBangunanM;

class RegJenisBangunanC extends Controller
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

    	$dt = array(
    		'button' 		=> $button,
    		'data_user'		=> $data_user
    	);

    	return view('sqii.RegJenisBangunanV')->with('dt', $dt);
    }

    public function show_jenis_bangunan(Request $r)
	{
		
		$q = RegJenisBangunanM::show_jenis_bangunan();//KD_CLUSTER', 'a.NM_CLUSTER', 'b.NM_KAWASAN
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
		$user_id 		= $r->session()->get('user_id');
		$data;

		$q = RegJenisBangunanM::sync_dt_v2($user_id);
		// foreach ($q as $row) {
		// 	$data[] = array(
		// 		'kd_jenis'	=> $row->KD_JENIS,
		// 		'nm_jenis'	=> $row->NM_JENIS
		// 	);
		// }

		if(isset($data)){
			return $data;
		}
	}
}
