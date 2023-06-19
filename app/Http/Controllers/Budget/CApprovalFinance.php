<?php

namespace App\Http\Controllers\Budget;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Budget\MApprovalFinance;

class CApprovalFinance extends Controller
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

		$thn_anggaran 	= $this->master->list_thn_anggaran();
		$data_user 		= $this->master->get_data_user($user_id);

		$dt = array(
			'button' 		=> $button,
			'thn_anggaran'	=> $thn_anggaran,
			'data_user'		=> $data_user
		);

		return view('budget.vApprovalFinance')->with('dt', $dt);
	}

	public function show_finance(Request $r)
	{
		$q = self::show_pengajuan($r, 'K');

		return $q;
	}

	public function show_bod(Request $r)
	{
		$q = self::show_pengajuan($r, 'F');

		return $q;
	}

	public function show_complete(Request $r)
	{
		$q = self::show_pengajuan($r, 'B');

		return $q;
	}

	public function show_pengajuan($r, $status_approval)
	{
		$thn_anggaran   = $r->thn_anggaran;
		$kd_unit 		= $r->kd_unit;

		$q = MApprovalFinance::show_pengajuan($thn_anggaran, $kd_unit, $status_approval);
		foreach ($q as $row) {
			$no_pengajuan 	= $row->no_pengajuan;
			$user_entry 	= $row->user_entry;
			$get_data_user 	= $this->master->get_data_user($user_entry);
			foreach ($get_data_user as $get_data_user_row) {
				$nm_entry = $get_data_user_row->nama;
			}
			$tgl_entry 		= $row->tgl_entry;

			$kd_departemen 	= $row->kd_departemen;
			$nm_departemen 	= '';
			$get_nm_departemen = $this->master->get_nm_departemen($kd_departemen);
			foreach ($get_nm_departemen as $get_nm_departemen_row) {
				$nm_departemen = $get_nm_departemen_row->nm_departemen;
			}

			$data[] = array(
				'no_pengajuan'	=> $no_pengajuan,
				'user_entry'	=> $user_entry,
				'nm_entry'		=> $nm_entry,
				'tgl_entry'		=> $tgl_entry,
				'kd_departemen' => $kd_departemen,
				'nm_departemen' => $nm_departemen
			);
		}

		if(isset($data)){
			return $data;
		}
	}

	public function show_barang(Request $r)
	{
		$no_pengajuan 	= $r->no_pengajuan;

		$q = $this->master->show_dtl_pengajuan($no_pengajuan);

		return $q;
	}

	public function save(Request $r)
	{
		$no_pengajuan 	= $r->no_pengajuan;
		$user        	= $r->session()->get('user_id');
		$tgl 			= date('Y-m-d H:i:s');

		if($no_pengajuan != null){
			$total = count($no_pengajuan);

			for ($i=0; $i < $total; $i++) { 
				$q = MApprovalFinance::approve_finance($no_pengajuan[$i], $user, $tgl);
			}
		}
	}

	public function save_barang(Request $r)
	{
		$no_pengajuan 	= $r->no_pengajuan;
		$rowid 			= $r->rowid;
		$qty_finance 	= $r->qty_finance;

		$user        	= $r->session()->get('user_id');
		$tgl 			= date('Y-m-d H:i:s');

		if($rowid != null){
			$total = count($rowid);

			for ($i=0; $i < $total; $i++) { 
				$get_dtl_before = $this->master->get_dtl_pengajuan($rowid[$i]);
    			foreach ($get_dtl_before as $get_dtl_before_row) {
    				$qty_finance_before = $get_dtl_before_row->qty_finance;

    				if($qty_finance[$i] != $qty_finance_before){
    					$q = MApprovalFinance::save_barang($no_pengajuan, $rowid[$i], $qty_finance[$i], $user, $tgl);
    				}
    			}
			}
		}

		return;
	}
}