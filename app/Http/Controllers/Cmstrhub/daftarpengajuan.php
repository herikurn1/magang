<?php

namespace App\Http\Controllers\Cmstrhub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Vouchermall\cSysVoucher;

use App\Models\Cmstrhub\m_daftarpengajuan;

class daftarpengajuan extends Controller
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

    	return view('cmstrhub.v_Daftarpengajuan')->with('dt', $dt);
    }

    public function get_data(Request $r){
        // $kd_unit 		= $r->session()->get('kd_unit');
        $title_prog     = $r->title_prog;
        $kd_layanan     = $r->kd_layanan;
        $kd_tujuan      = $r->kd_tujuan;
        $kd_jenis       = $r->kd_jenis;

        $q = m_daftarpengajuan::get_data($title_prog, $kd_layanan, $kd_tujuan, $kd_jenis);

        return response()->json(['data' => $q]);
    }

    public function get_dtl_data(Request $r){
        
        $no_dokumen     = $r->no_dokumen;

        $q = m_daftarpengajuan::get_dtl_data($no_dokumen);

        return response()->json($q);
    }


    public function get_layanan(Request $r){
        $q = m_daftarpengajuan::layanan();

        return $q;
    }

    public function get_layanan_dtl(Request $r){
        $kd_unit 		= $r->session()->get('kd_unit');
        $kd_layanan     = $r->kd_layanan;

        $q = m_daftarpengajuan::layanan_dtl($kd_unit, $kd_layanan);

        return $q;
    }

    public function get_layanan_item(Request $r){
        $kd_unit 		= $r->session()->get('kd_unit');
        $kd_layanan     = $r->kd_layanan;
        $kd_tujuan      = $r->kd_tujuan;

        $q = m_daftarpengajuan::layanan_item($kd_unit, $kd_layanan, $kd_tujuan);

        return $q;
    }

    public function get_status(Request $r){
        $q = m_daftarpengajuan::status();

        return $q;
    }
}
