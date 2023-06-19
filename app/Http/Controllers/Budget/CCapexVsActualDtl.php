<?php

namespace App\Http\Controllers\Budget;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Budget\MCapexVsActualDtl;

class CCapexVsActualDtl extends Controller
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

		return view('budget.vCapexVsActualDtl')->with('dt', $dt);
	}

	public function check_role_budget(Request $r)
	{
		$func 		= $r->func;
		$user_id 	= $r->session()->get('user_id');

		$return 	= 'N';
		$q = MCapexVsActualDtl::check_role_budget($user_id);
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

	public function search_kategori_budget(Request $r)
	{
		$keyword    = $r->keyword;

		$q = $this->master->search_kategori_budget($keyword);

		return $q;
	}

	public function search_unit_budget(Request $r)
	{
		$keyword = $r->keyword;

		$q = $this->master->search_unit_budget($keyword);

		return $q;
	}

	public function search_jenis(Request $r)
	{
		$keyword            = $r->keyword;
		$kd_kategori_budget = $r->kd_kategori_budget;

		$q = $this->master->search_jenis($keyword, $kd_kategori_budget);

		return $q;
	}
	
	public function search_departemen_budget(Request $r)
	{
		$keyword = $r->keyword;

		$q = $this->master->search_departemen_budget($keyword);

		return $q;
	}

	public function search_detail(Request $r)
	{
		$kd_unit 			= $r->kd_unit;
		$nm_unit 			= $r->nm_unit;
		$kd_departemen 		= $r->kd_departemen;
		$nm_departemen 		= $r->nm_departemen;
		$kd_kategori 		= $r->kd_kategori;
		$nm_kategori 		= $r->nm_kategori;
		$kd_jns 			= $r->kd_jns;
		$nm_jns 			= $r->nm_jns;
		$thn_anggaran 		= $r->thn_anggaran;

		if($kd_unit == "") $nm_unit = "All";
		if($kd_departemen == "") $nm_departemen = "All";
		if($kd_kategori == "") $nm_kategori = "All";
		if($kd_jns == "") $nm_jns = "All";

		$data = array(
			'thn_anggaran' 	=> $thn_anggaran,
			'nm_unit'		=> $nm_unit,
			'nm_departemen' => $nm_departemen,
			'nm_kategori'	=> $nm_kategori,
			'nm_jns' 		=> $nm_jns
		);

		$search_detail = MCapexVsActualDtl::search_detail($kd_unit, $kd_departemen, $kd_kategori, $kd_jns, $thn_anggaran);
		$detail = array(
			'detail' => $search_detail
		);

		$TOTAL= array();
		$TOT_BUDGET = 0;
		$TOT_REALISASI = 0;
		$SISA = 0;

		foreach ($search_detail as $row){
			$TOT_BUDGET += $row->jml_hrg_budget;
			$TOT_REALISASI += $row->jml_hrg_actual;
			$SISA += $row->sisa;
		}

		
		$TOTAL[]= array('sum_budget' => $TOT_BUDGET,
					'sum_realisasi' => $TOT_REALISASI, 
					'sisa'=>$SISA);
		$summary= array('summary' => $TOTAL);

		array_push($data, $detail, $summary);

		return $data;
	}

	
}