<?php

namespace App\Http\Controllers\Cmstrhub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Vouchermall\cSysVoucher;
use App\Models\Cmstrhub\m_dashboard;

class dashboard extends Controller
{
    public function index(Request $r){

    	return view('cmstrhub.v_Dashboard');
    }

    public function get_data_grafik(Request $r){
		$kd_unit 			= $r->kd_unit;
		$graph 				= self::show_grafik_pengajuan($kd_unit);
		$data['bulan1'] 	= $graph['bulan1'];
		$data['tahun1'] 	= $graph['tahun1'];
		$data['bulan2'] 	= $graph['bulan2'];
		$data['tahun2'] 	= $graph['tahun2'];
		$data['nilai1'] 	= $graph['nilai1'];
		$data['nilai2'] 	= $graph['nilai2'];

		echo json_encode($data);
	}

	function show_grafik_pengajuan($kd_unit){
		$thn1 = '';
		$thn2 = '';
		$get_data = m_dashboard::show_grafik_pengajuan($kd_unit);
		foreach($get_data as $result){
			$bulan1[] 	= $result->BULAN1;
			$tahun1[] 	= $result->TAHUN1;
			$bulan2[] 	= $result->BULAN2;
			$tahun2[] 	= $result->TAHUN2; 
			$nilai1[] 	= (float) $result->JML_PENGAJUAN1;
			$nilai2[] 	= (float) $result->JML_PENGAJUAN2;
		}

		$thn1 = $tahun1;
		$thn2 = $tahun2;
		
		$data['bulan1'] 	= $bulan1;
		$data['tahun1'] 	= $thn1;
		$data['bulan2'] 	= $bulan2;
		$data['tahun2'] 	= $thn2;
		$data['nilai1'] 	= $nilai1;
		$data['nilai2'] 	= $nilai2;
		
		return $data;
	}

	public function waiting(Request $r){
		$q = self::data_panel($r, '1');

		return $q;
	}

	public function progress(Request $r){
		$q = self::data_panel($r, '2');

		return $q;
	}

	public function done(Request $r){
		$q = self::data_panel($r, '3');

		return $q;
	}

	public function cancel(Request $r){
		$q = self::data_panel($r, '4');

		return $q;
	}

	public function data_panel($r, $title){
		$kd_unit 		= $r->session()->get('kd_unit');
		$q = m_dashboard::get_panel($kd_unit, $title);
		return $q;
	}

	public function data_waiting(Request $r){
		$q = self::get_data($r, '1');

		return response()->json(['data' => $q]);
	}

	public function data_progress(Request $r){
		$q = self::get_data($r, '2');

		return response()->json(['data' => $q]);
	}

	public function data_done(Request $r){
		$q = self::get_data($r, '3');

		return response()->json(['data' => $q]);
	}

	public function data_cancel(Request $r){
		$q = self::get_data($r, '4');

		return response()->json(['data' => $q]);
	}

	public function get_data($r, $title_prog){
        $kd_unit 		= $r->session()->get('kd_unit');

        $q = m_dashboard::get_data($kd_unit, $title_prog);

        return $q;
    }

	public function page_waiting(Request $r){

    	return view('cmstrhub.v_Waiting');
    }

	public function page_progress(Request $r){

    	return view('cmstrhub.v_Progress');
    }

	public function page_done(Request $r){

    	return view('cmstrhub.v_Done');
    }

	public function page_cancel(Request $r){

    	return view('cmstrhub.v_Cancel');
    }
}
