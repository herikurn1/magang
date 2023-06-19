<?php

namespace App\Http\Controllers\Budget;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\Budget\MMaster;

class CMaster extends Controller
{
	public function list_thn_anggaran()
	{
		$q = MMaster::list_thn_anggaran();

		return $q;
	}

	public function get_master_code($kelompok)
	{
		$q = MMaster::get_master_code($kelompok);

		return $q;
	}

	public function get_data_user($user_id)
	{
		$q = MMaster::get_data_user($user_id);

		return $q;
	}

	public function get_staff($user_id)
	{
		$q = MMaster::get_staff($user_id);

		return $q;
	}

	public function get_nm_unit($kd_unit)
	{
		$q = MMaster::get_nm_unit($kd_unit);

		return $q;
	}

	public function get_nm_departemen($kd_departemen)
	{
		$q = MMaster::get_nm_departemen($kd_departemen);

		return $q;
	}

	public function get_dtl_pengajuan($rowid)
	{
		$q = MMaster::get_dtl_pengajuan($rowid);

		return $q;
	}

	public function show_dtl_pengajuan($no_pengajuan)
	{
		$q = MMaster::show_dtl_pengajuan($no_pengajuan);

		return $q;
	}

	public function show_dtl_yearprev($user, $yearprev, $kd_unit, $kd_lokasi)
	{
		$q = MMaster::show_dtl_yearprev($user, $yearprev, $kd_unit, $kd_lokasi);

		return $q;
	}

	public function salin_data($user, $yearprev, $kd_unit, $kd_lokasi)
	{
		$q = MMaster::salin_data($user, $yearprev, $kd_unit, $kd_lokasi);

		return $q;
	}

	public function search_kategori_budget($keyword)
	{
		$q = MMaster::search_kategori_budget($keyword);

		return $q;
	}

	public function search_jenis($keyword, $kd_kategori_budget)
	{
		$q = MMaster::search_jenis($keyword, $kd_kategori_budget);

		return $q;
	}

	public function search_unit_budget($keyword)
	{
		$q = MMaster::search_unit_budget($keyword);

		return $q;
	}

	public function search_departemen_budget($keyword)
	{
		$q1 = MMaster::group_departemen_budget();
		foreach ($q1 as $q1_row) {
			$kd_departemen = $q1_row->kd_departemen;

			$nm_departemen = '';
			$q2 = MMaster::get_nm_departemen($kd_departemen, $keyword);

			foreach ($q2 as $q2_row) {
				$data[] = array(
					'kd_departemen' => $kd_departemen,
					'nm_departemen'	=> $q2_row->nm_departemen
				);
			}
		}

		if(isset($data)){
			return $data;
		}
	}
}