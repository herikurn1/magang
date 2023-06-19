<?php

namespace App\Http\Controllers\Cmstrhub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Vouchermall\cSysVoucher;
use App\Models\Cmstrhub\m_salestoday;

class sales extends Controller
{
    private $sysController;
	private $master;

	public function __construct()
	{
		$this->sysController = new SysController();
		$this->master = new cSysVoucher();
	}

    public function index(Request $r){
        $user_id 		= $r->session()->get('user_id');
		$kd_unit 		= $r->session()->get('kd_unit');
    	$button 		= $this->sysController->get_button($r);

    	$dt = array(
    		'button' 		=> $button,
    	);

    	return view('cmstrhub.v_Sales')->with('dt', $dt);
    }

    public function get_data(Request $r){
        $kd_unit 		= $r->session()->get('kd_unit');
        $date_from      = $r->date_from;
        $date_to        = $r->date_to;
        $tgl_now        = date('Y-m-d');

        $data = m_salestoday::get_data($kd_unit, $date_from, $date_to, $tgl_now);

        return response()->json(['data' => $data]);
    }
}
