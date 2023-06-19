<?php

namespace App\Http\Controllers\Cmstrhub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Vouchermall\cSysVoucher;

class masterpromo extends Controller
{
    private $sysController;
	private $master;

	public function __construct()
	{
		$this->sysController = new SysController();
		$this->master = new cSysVoucher();
	}

    public function index(Request $r)
    {
        $user_id 		= $r->session()->get('user_id');
		$kd_unit 		= $r->session()->get('kd_unit');
    	$button 		= $this->sysController->get_button($r);

    	$dt = array(
    		'button' 		=> $button,
    	);

    	return view('cmstrhub.v_Masterpromo')->with('dt', $dt);
    }
}