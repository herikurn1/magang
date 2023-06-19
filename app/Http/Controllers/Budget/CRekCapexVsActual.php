<?php

namespace App\Http\Controllers\Budget;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Budget\MRekCapexVsActual;

class CRekCapexVsActual extends Controller
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
		$thn_anggaran 	= $this->master->list_thn_anggaran();
		$data_user 		= $this->master->get_data_user($user_id);

		$dt = array(
			'thn_anggaran'	=> $thn_anggaran,
			'data_user'		=> $data_user
		);

		return view('budget.vRekCapexVsActual')->with('dt', $dt);
	}

	public function check_role_budget(Request $r)
	{
		$func 		= $r->func;
		$user_id 	= $r->session()->get('user_id');

		$return 	= 'N';
		$q = MRekCapexVsActual::check_role_budget($user_id);
		foreach ($q as $row) {
			$fg_finance 	= $row->fg_finance;
			$fg_bod 		= $row->fg_bod;

			if($func == "search_unit_budget" || $func == "clear_unit_budget"){
				$return = $fg_bod;
			}

			if($func == "search_departemen_budget" || $func == "clear_departemen_budget"){
				$return = $fg_finance;

				if($fg_bod == "Y") $return = 'Y';
			}
		}

		return $return;
	}

	public function search_unit_budget(Request $r)
	{
		$keyword = $r->keyword;

		$q = $this->master->search_unit_budget($keyword);

		return $q;
	}

	public function search_detail(Request $r)
	{
		$budget				= $r->budget;
		$kd_unit 			= $r->kd_unit;
		$nm_unit 			= $r->nm_unit;
		$kd_departemen 		= $r->kd_departemen;
		$nm_departemen 		= $r->nm_departemen;
		$thn_anggaran 		= $r->thn_anggaran;

		if($kd_unit == "") $nm_unit = "All";
		// if($kd_departemen == "") $nm_departemen = "All";

		$data = array(
			'thn_anggaran' 			=> $thn_anggaran,
			'kd_unit'				=> $kd_unit,
			'nm_unit'				=> $nm_unit
			// 'nm_departemen' 		=> $nm_departemen
		);

		$search_detail = MRekCapexVsActual::search_detail($kd_unit, $thn_anggaran);
		$detail = array(
			'detail' => $search_detail
		);
		
		// ////////********Summary All***********///////////
		// $TOTAL= array();
		// $CAPEX_CURR = 0;
		// $BANGUNAN = 0;
		// $KENDARAAN = 0;
		// $PERALATAN = 0;
		// $KOMPUTER = 0;
		// $MESIN = 0;
		// $LAIN2 = 0;

		// foreach ($search_detail as $row){
		// 	$CAPEX_CURR += $row->CAPEX_CURR;
		// 	$BANGUNAN += $row->BANGUNAN;
		// 	$KENDARAAN += $row->KENDARAAN;
		// 	$PERALATAN += $row->PERALATAN;
		// 	$KOMPUTER += $row->KOMPUTER;
		// 	$MESIN += $row->MESIN;
		// 	$LAIN2 += $row->LAIN2;
		// }

		
		// $TOTAL[]= array('capex_curr' => $CAPEX_CURR, 
		// 			'bangunan'=>$BANGUNAN, 
		// 			'kendaraan'=>$KENDARAAN, 
		// 			'peralatan'=>$PERALATAN, 
		// 			'komputer'=>$KOMPUTER, 
		// 			'mesin'=>$MESIN, 
		// 			'lain2'=>$LAIN2);
		// $summary= array('summary' => $TOTAL);

		///**************Summary Budget*****************//////////////
		$sum_budget = MRekCapexVsActual::summary_budget($kd_unit, $thn_anggaran);
		$TOTAL_BUDGET= array();
		$CAPEX_BUDGET = 0;
		$BANGUNAN_BUDGET = 0;
		$KENDARAAN_BUDGET = 0;
		$PERALATAN_BUDGET = 0;
		$KOMPUTER_BUDGET = 0;
		$MESIN_BUDGET = 0;
		$LAIN2_BUDGET = 0;

		foreach ($sum_budget as $row1){
			$CAPEX_BUDGET += $row1->CAPEX_CURR;
			$BANGUNAN_BUDGET += $row1->BANGUNAN;
			$KENDARAAN_BUDGET += $row1->KENDARAAN;
			$PERALATAN_BUDGET += $row1->PERALATAN;
			$KOMPUTER_BUDGET += $row1->KOMPUTER;
			$MESIN_BUDGET += $row1->MESIN;
			$LAIN2_BUDGET += $row1->LAIN2;
		}

		
		$TOTAL_BUDGET[]= array('capex_curr' => $CAPEX_BUDGET, 
					'bangunan'=>$BANGUNAN_BUDGET, 
					'kendaraan'=>$KENDARAAN_BUDGET, 
					'peralatan'=>$PERALATAN_BUDGET, 
					'komputer'=>$KOMPUTER_BUDGET, 
					'mesin'=>$MESIN_BUDGET, 
					'lain2'=>$LAIN2_BUDGET);
		$summary_budget= array('summary_budget' => $TOTAL_BUDGET);

		///**************Summary Actual*****************//////////////
		$sum_actual = MRekCapexVsActual::summary_actual($kd_unit, $thn_anggaran);
		$TOTAL_ACTUAL= array();
		$CAPEX_ACTUAL = 0;
		$BANGUNAN_ACTUAL = 0;
		$KENDARAAN_ACTUAL = 0;
		$PERALATAN_ACTUAL = 0;
		$KOMPUTER_ACTUAL = 0;
		$MESIN_ACTUAL = 0;
		$LAIN2_ACTUAL = 0;

		foreach ($sum_actual as $row2){
			$CAPEX_ACTUAL += $row2->CAPEX_CURR;
			$BANGUNAN_ACTUAL += $row2->BANGUNAN;
			$KENDARAAN_ACTUAL += $row2->KENDARAAN;
			$PERALATAN_ACTUAL += $row2->PERALATAN;
			$KOMPUTER_ACTUAL += $row2->KOMPUTER;
			$MESIN_ACTUAL += $row2->MESIN;
			$LAIN2_ACTUAL += $row2->LAIN2;
		}

		
		$TOTAL_ACTUAL[]= array('capex_curr' => $CAPEX_ACTUAL, 
					'bangunan'=>$BANGUNAN_ACTUAL, 
					'kendaraan'=>$KENDARAAN_ACTUAL, 
					'peralatan'=>$PERALATAN_ACTUAL, 
					'komputer'=>$KOMPUTER_ACTUAL, 
					'mesin'=>$MESIN_ACTUAL, 
					'lain2'=>$LAIN2_ACTUAL);
		$summary_actual= array('summary_actual' => $TOTAL_ACTUAL);

		///**************Summary Non Realisasi*****************//////////////
		$sum_non_realisasi = MRekCapexVsActual::summary_non_realisasi($kd_unit, $thn_anggaran);
		$TOTAL_NREALISASI= array();
		$CAPEX_NREALISASI = 0;
		$BANGUNAN_NREALISASI = 0;
		$KENDARAAN_NREALISASI = 0;
		$PERALATAN_NREALISASI = 0;
		$KOMPUTER_NREALISASI = 0;
		$MESIN_NREALISASI = 0;
		$LAIN2_NREALISASI = 0;

		foreach ($sum_non_realisasi as $row3){
			$CAPEX_NREALISASI += $row3->CAPEX_CURR;
			$BANGUNAN_NREALISASI += $row3->BANGUNAN;
			$KENDARAAN_NREALISASI += $row3->KENDARAAN;
			$PERALATAN_NREALISASI += $row3->PERALATAN;
			$KOMPUTER_NREALISASI += $row3->KOMPUTER;
			$MESIN_NREALISASI += $row3->MESIN;
			$LAIN2_NREALISASI += $row3->LAIN2;
		}

		
		$TOTAL_NREALISASI[]= array('capex_curr' => $CAPEX_NREALISASI, 
					'bangunan'=>$BANGUNAN_NREALISASI, 
					'kendaraan'=>$KENDARAAN_NREALISASI, 
					'peralatan'=>$PERALATAN_NREALISASI, 
					'komputer'=>$KOMPUTER_NREALISASI, 
					'mesin'=>$MESIN_NREALISASI, 
					'lain2'=>$LAIN2_NREALISASI);
		$summary_nonrealisasi= array('summary_nonrealisasi' => $TOTAL_NREALISASI);

		array_push($data, $detail, $summary_budget, $summary_actual, $summary_nonrealisasi);

		return $data;
	}

	public function search_chart(Request $r)
	{
		$kd_unit 			= $r->kd_unit;
		$nm_unit 			= $r->nm_unit;
		$budget				= $r->budget;
		// $kd_departemen 		= $r->kd_departemen;
		// $nm_departemen 		= $r->nm_departemen;
		$thn_anggaran 		= $r->thn_anggaran;

		if($kd_unit == "") $nm_unit = "All";
		// if($kd_departemen == "") $nm_departemen = "All";


		$result = MRekCapexVsActual::search_chart($kd_unit, $thn_anggaran);
		$data = array();
		//  $data_arr = json_encode($result, true);
		//  $string = str_replace(array('{','}'),array('[',']'),$data_arr);
		 foreach ($result as $row){
		  	$data[]= array($row->NM_KATEGORI_BUDGET, $row->PERSEN);

		 }
 print json_encode($data, JSON_NUMERIC_CHECK);

		// $data = array();
		// foreach ($string->result_array() as $row){
		//  	$data[] = array($row['NM_KATEGORI_BUDGET'],$row['PERSEN']);
		// }
		// print json_encode($data, JSON_NUMERIC_CHECK);


	}
}