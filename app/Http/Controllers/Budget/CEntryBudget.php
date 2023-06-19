<?php

namespace App\Http\Controllers\Budget;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Budget\MEntryBudget;
use Illuminate\Support\Facades\Route;
class CEntryBudget extends Controller
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
		$data_urgency   = $this->master->get_master_code('BC_URGENCY');

		$dt = array(
			'button' 		=> $button,
			'thn_anggaran'	=> $thn_anggaran,
			'data_user'		=> $data_user,
			'data_urgency'  => $data_urgency
		);

		return view('budget.vEntryBudget')->with('dt', $dt);
	}

	public function save(Request $r)
	{
		$no_pengajuan 	= $r->no_pengajuan;
		$thn_anggaran 	= $r->thn_anggaran;
		$kd_departemen 	= $r->kd_departemen;
		$kd_unit 		= $r->kd_unit;
		$kd_lokasi 		= $r->kd_lokasi;
		$act 			= $r->act;
		$user 			= $r->session()->get('user_id');
		$tgl 			= date('Y-m-d H:i:s');
		$nm_user		= $r->session()->get('nama');
		

		$x = MEntryBudget::cek_user_input($thn_anggaran, $kd_departemen, $kd_unit, $kd_lokasi, $user);
		// $cek_user = $x;
		$cek_user= json_encode($x[0]);
		$xx = json_decode($cek_user, true);
		// var_dump($xx['user_entry']);exit();
		if($xx['user_entry'] == "0"){

			if($act == "add"){
				$q = MEntryBudget::insert_dt($thn_anggaran, $kd_departemen, $kd_unit, $kd_lokasi, $user, $tgl);
				$no_pengajuan = $q;
				echo $no_pengajuan;
			}else{
				$q = MEntryBudget::update_dt($no_pengajuan, $user, $tgl);
			}

			/* === Detail === */
			$add_kd_barang 			= $r->add_kd_barang;
			$add_nm_barang 			= $r->add_nm_barang;
			$add_kd_kategori_budget = $r->add_kd_kategori_budget;
			$add_nm_kategori_budget = $r->add_nm_kategori_budget;
			$add_kd_kategori 		= $r->add_kd_kategori;
			$add_nm_kategori 		= $r->add_nm_kategori;
			$add_kd_jenis 			= $r->add_kd_jenis;
			$add_nm_jenis 			= $r->add_nm_jenis;
			$add_qty 				= $r->add_qty;
			$add_harga 				= $r->add_harga;
			$add_jumlah_harga 		= $r->add_jumlah_harga;
			$add_urgency			= $r->add_urgency;
			$add_catatan 			= $r->add_catatan;

			if($add_nm_barang != null){
				$add_total 	= count($add_nm_barang);
					
				for ($i=0; $i < $add_total; $i++) { 
					
					$y = MEntryBudget::count_no_pengajuan($no_pengajuan, $thn_anggaran, $kd_departemen, $kd_unit, $kd_lokasi, $user);
					$count_pengajuan= json_encode($y[0]);
					$yy = json_decode($count_pengajuan, true);
					$z = $yy['no_pengajuan'];

					$no_budget= sprintf("%04d", $z++);

					$err = 0;
					$err_msg = '';
					if(trim($no_pengajuan) != ""){
						if($add_nm_barang[$i] == ""){
							$err++;
							$err_msg .= 'Kolom Nama Capex harus di isi...\n';
						}
						else if($add_qty[$i] == ""){
							$err++;
							$err_msg .= 'Kolom Qty harus di isi...\n';
						}
						else if($add_harga[$i] == ""){
							$err++;
							$err_msg .= 'Kolom Harga harus di isi...\n';
						}
						else {
							$insert_barang = MEntryBudget::insert_barang($no_pengajuan, $no_budget, $add_kd_barang[$i], $add_nm_barang[$i], $add_kd_kategori_budget[$i], $add_nm_kategori_budget[$i], $add_kd_kategori[$i], $add_nm_kategori[$i], $add_kd_jenis[$i], $add_nm_jenis[$i], $add_qty[$i], $add_harga[$i], $add_jumlah_harga[$i], $add_urgency[$i], $add_catatan[$i], $user, $tgl);
						}
						if($err > 0){
							http_response_code(401);
							exit(json_encode(['message' => $err_msg]));
						}
					} 
				}
			} else {echo ('Data tidak dapat diproses, karena tidak ada barang yang diinput... '); }

			$edt_rowid 			= $r->edt_rowid;
			$edt_qty 			= $r->edt_qty;
			$edt_harga 			= $r->edt_harga;
			$edt_jumlah_harga 	= $r->edt_jumlah_harga;
			$edt_urgency		= $r->edt_urgency;
			$edt_catatan 		= $r->edt_catatan;

			if($edt_rowid != null){
				$edt_total 			= count($edt_rowid);

				$status_approval = '';
				$get_mst_pengajuan = MEntryBudget::get_mst_pengajuan($no_pengajuan);
				foreach ($get_mst_pengajuan as $get_mst_pengajuan_row) {
					$status_approval = $get_mst_pengajuan_row->status_approval;
				}

				if($status_approval == "E"){
					for ($i=0; $i < $edt_total; $i++) {
						if(trim($no_pengajuan) != ""){
							$get_dtl_before = $this->master->get_dtl_pengajuan($edt_rowid[$i]);
							foreach ($get_dtl_before as $get_dtl_before_row) {
								$qty 			= $get_dtl_before_row->qty;
								$harga 			= $get_dtl_before_row->harga;
								$jumlah_harga 	= $get_dtl_before_row->jumlah_harga;
								$urgency 		= $get_dtl_before_row->urgency;
								$catatan 		= $get_dtl_before_row->catatan;
							}

							if(($edt_qty[$i] != $qty) || ($edt_harga[$i] != $harga) || ($edt_jumlah_harga[$i] != $jumlah_harga) || ($edt_urgency[$i] != $urgency) || ($edt_catatan[$i] != $catatan)){
								$edit_barang = MEntryBudget::edit_barang($edt_rowid[$i], $edt_qty[$i], $edt_harga[$i], $edt_jumlah_harga[$i], $edt_urgency[$i], $edt_catatan[$i], $user, $tgl);
							}
						}
					}
				}
			}
		}else{ echo ('Anggaran tahun '.$thn_anggaran.' sudah pernah input oleh '.$nm_user.' ');}
	}

	public function search_dt(Request $r)
	{
		$keyword 	= $r->keyword;
		$kd_unit 	= $r->kd_unit;
		$kd_lokasi 	= $r->kd_lokasi;
		$user 		= $r->session()->get('user_id');

		$q = MEntryBudget::search_dt($user, $kd_unit, $kd_lokasi, $keyword);
		foreach ($q as $row) {
			$no_pengajuan 	= $row->no_pengajuan;
			$thn_anggaran 	= $row->thn_anggaran;

			$kd_departemen 	= $row->kd_departemen;
			$nm_departemen 	= '';
			$get_data_user 	= $this->master->get_data_user($user);
			foreach ($get_data_user as $get_data_user_row) {
				$nm_departemen 	= $get_data_user_row->nm_departemen;
			}

			$status_approval 		= $row->status_approval;
			$nm_status_approval 	= $row->nm_status_approval;
			$tgl_entry_db 			= $row->tgl_entry;
			$tgl_entry 				= $this->sysController->date_id($tgl_entry_db);

			$data[] = array(
				'no_pengajuan'			=> $no_pengajuan,
				'thn_anggaran'			=> $thn_anggaran,
				'kd_departemen' 		=> $kd_departemen,
				'nm_departemen'			=> $nm_departemen,
				'status_approval' 		=> $status_approval,
				'nm_status_approval'	=> $nm_status_approval,
				'tgl_entry'				=> $tgl_entry
			);
		}

		if(isset($data)){
			return response()->json($data);
		}
	}

	public function search_kategori_budget(Request $r)
	{
		$keyword    = $r->keyword;
		$helper 	= $r->helper;

		$q = $this->master->search_kategori_budget($keyword);

		return $q;
	}

	public function search_jenis(Request $r)
	{
		$keyword            = $r->keyword;
		$kd_kategori_budget = $r->kd_kategori_budget;

		$q = $this->master->search_jenis($keyword, $kd_kategori_budget);

		return $q;
	}

	public function search_barang(Request $r)
	{
		$helper 		= $r->helper;
		$keyword 	 	= $r->keyword;
		$kd_kategori 	= $r->kd_kategori;
		$kd_jenis 		= $r->kd_jenis;

		$q = MEntryBudget::search_barang($keyword, $kd_kategori, $kd_jenis);

		return $q;
	}

	public function show_dtl(Request $r)
	{
		$no_pengajuan 	= $r->no_pengajuan;

		$q = $this->master->show_dtl_pengajuan($no_pengajuan);
		return $q;
	}

	// public function count_data(Request $r)
	// {
	// 	$user 			= $r->session()->get('user_id');
	// 	$thn_anggaran 	= $r->thn_anggaran;
	// 	$kd_unit		= $r->kd_unit;
	// 	$kd_lokasi  	= $r->kd_lokasi;

	// 	$q = MEntryBudget::count_data($user, $thn_anggaran, $kd_unit, $kd_lokasi);
	// 	return $q;
	// }

	public function show_yearprev(Request $r)
	{
		$user 		= $r->session()->get('user_id');
		$yearprev 	= $r->thn_anggaran;
		$kd_unit	= $r->kd_unit;
		$kd_lokasi  = $r->kd_lokasi;

		$q = $this->master->show_dtl_yearprev($user, $yearprev, $kd_unit, $kd_lokasi);
		return $q;
	}

	public function salin_data(Request $r)
	{
		$user 		= $r->session()->get('user_id');
		$yearprev 	= $r->thn_anggaran;
		$kd_unit	= $r->kd_unit;
		$kd_lokasi  = $r->kd_lokasi;

		$q = $this->master->salin_data($user, $yearprev, $kd_unit, $kd_lokasi);
		return $q;
	}

	public function delete_dt(Request $r)
	{
		$no_pengajuan 	= $r->no_pengajuan;
		$user 			= $r->session()->get('user_id');
		$tgl 			= date('Y-m-d H:i:s');

		$q = MEntryBudget::delete_dt($no_pengajuan, $user, $tgl);

		return $q;
	}

	public function delete_barang(Request $r)
	{
		$no_pengajuan 	= $r->no_pengajuan;
		$rowid 			= $r->rowid;
		$user 			= $r->session()->get('user_id');

		$status_approval = '';
		$get_mst_pengajuan = MEntryBudget::get_mst_pengajuan($no_pengajuan);
		foreach ($get_mst_pengajuan as $get_mst_pengajuan_row) {
			$status_approval = $get_mst_pengajuan_row->status_approval;
		}

		if($status_approval == "E"){
			$q = MEntryBudget::delete_barang($rowid);

			return $q;
		}
	}

	public function submit_kabag(Request $r)
	{
		$no_pengajuan   = $r->no_pengajuan;
		$user           = $r->session()->get('user_id');
		$tgl            = date('Y-m-d H:i:s');

		$q = MEntryBudget::submit_kabag($no_pengajuan, $user, $tgl);
	}

	public function view_history(Request $r)
	{
		$thn_anggaran 	= $r->thn_anggaran;
		$kd_unit 		= $r->kd_unit;
		$kd_departemen 	= $r->kd_departemen;
		$kd_barang 		= $r->kd_barang;

		$q = MEntryBudget::view_history($thn_anggaran, $kd_unit, $kd_departemen, $kd_barang);

		return $q;
	}
}