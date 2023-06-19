<?php

namespace App\Http\Controllers\Budget;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Budget\MRekCapexCatUnit;

class CRekCapexCatUnit extends Controller
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

		return view('budget.vRekCapexCatUnit')->with('dt', $dt);
	}

	public function check_role_budget(Request $r)
	{
		$func 		= $r->func;
		$user_id 	= $r->session()->get('user_id');

		$return 	= 'N';
		$q = MRekCapexCatUnit::check_role_budget($user_id);
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
		$kd_unit 			= $r->kd_unit;
		$nm_unit 			= $r->nm_unit;
		$kd_departemen 		= $r->kd_departemen;
		$nm_departemen 		= $r->nm_departemen;
		$thn_anggaran 		= $r->thn_anggaran;

		if($kd_unit == "") $nm_unit = "All";
		// if($kd_departemen == "") $nm_departemen = "All";

		$data = array(
			'thn_anggaran' 			=> $thn_anggaran,
			'nm_unit'				=> $nm_unit
			// 'nm_departemen' 		=> $nm_departemen
		);

		$search_detail = MRekCapexCatUnit::search_detail($kd_unit, $thn_anggaran);
		$detail = array(
			'detail' => $search_detail
		);
		
		$TOTAL= array();
		$CAPEX_BEFORE = 0;
		$CAPEX_CURR = 0;
		$BANGUNAN = 0;
		$KENDARAAN = 0;
		$PERALATAN = 0;
		$KOMPUTER = 0;
		$MESIN = 0;
		$LAIN2 = 0;
		$VS_CAPEX = 0;
		$PERSEN = 0;

		foreach ($search_detail as $row){
			$CAPEX_BEFORE += $row->CAPEX_BEFORE;
			$CAPEX_CURR += $row->CAPEX_CURR;
			$BANGUNAN += $row->BANGUNAN;
			$KENDARAAN += $row->KENDARAAN;
			$PERALATAN += $row->PERALATAN;
			$KOMPUTER += $row->KOMPUTER;
			$MESIN += $row->MESIN;
			$LAIN2 += $row->LAIN2;
			$VS_CAPEX += $row->VS_CAPEX;
			$PERSEN += $row->PERSEN;
		}

		
		$TOTAL[]= array('capex_before' => $CAPEX_BEFORE,
					'capex_curr' => $CAPEX_CURR, 
					'bangunan'=>$BANGUNAN, 
					'kendaraan'=>$KENDARAAN, 
					'peralatan'=>$PERALATAN, 
					'komputer'=>$KOMPUTER, 
					'mesin'=>$MESIN, 
					'lain2'=>$LAIN2, 
					'vs_capex'=>$VS_CAPEX, 
					'persen'=>$PERSEN);
		$summary= array('summary' => $TOTAL);
		array_push($data, $detail, $summary);

		return $data;
	}

	public function search_chart(Request $r)
	{
		$kd_unit 			= $r->kd_unit;
		$nm_unit 			= $r->nm_unit;
		// $kd_departemen 		= $r->kd_departemen;
		// $nm_departemen 		= $r->nm_departemen;
		$thn_anggaran 		= $r->thn_anggaran;

		if($kd_unit == "") $nm_unit = "All";
		// if($kd_departemen == "") $nm_departemen = "All";


		$result = MRekCapexCatUnit::search_chart($kd_unit, $thn_anggaran);
		$data = array();
		//  $data_arr = json_encode($result, true);
		//  $string = str_replace(array('{','}'),array('[',']'),$data_arr);
		 foreach ($result as $row){
		  	$data[]= array($row->kd_unit, $row->PERSEN);

		 }
 print json_encode($data, JSON_NUMERIC_CHECK);

		// $data = array();
		// foreach ($string->result_array() as $row){
		//  	$data[] = array($row['NM_KATEGORI_BUDGET'],$row['PERSEN']);
		// }
		// print json_encode($data, JSON_NUMERIC_CHECK);


	}
}