<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\RegKawasanM;

class RegKawasanC extends Controller
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

    	return view('sqii.RegKawasanV')->with('dt', $dt);
    }

    public function show_kawasan(Request $r)
	{
		//echo 'haloo';die;
		$q = RegKawasanM::show_kawasan();
		foreach ($q as $row) {
			$data[] = array(
				'kd_kawasan'	=> $row->KD_KAWASAN,
				'nm_kawasan'	=> $row->NM_KAWASAN
			);
		}

		if(isset($data)){
			return $data;
		}
	}
}
