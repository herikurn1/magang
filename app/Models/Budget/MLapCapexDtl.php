<?php

namespace App\Models\Budget;

use Illuminate\Database\Eloquent\Model;
use DB;

class MLapCapexDtl extends Model
{
	public static function search_detail($kd_unit, $kd_departemen, $kd_kategori_budget, $kd_jenis, $thn_anggaran, $sts_approve)
	{
		$q = DB::table('bc_mst_pengajuan as a')
		->select(
			'b.kd_barang', 'b.nm_barang', 'c.label as nm_urgency', 'b.catatan', 
			'b.qty_final', 'b.harga'
		)
		->join('bc_dtl_pengajuan as b', 'b.no_pengajuan', '=', 'a.no_pengajuan')
		->join('bc_penomoran as c', function($join) {
			$join->on('c.kode', '=', 'b.urgency')
			->where('c.kelompok', '=', 'BC_URGENCY');
		})
		->where('a.kd_unit', 'like', '%'.$kd_unit.'%')
		->where('a.kd_departemen', 'like', '%'.$kd_departemen.'%')
		->where('a.thn_anggaran', '=', $thn_anggaran)
		->where('a.status_approval', 'like', '%'.$sts_approve.'%')
		->where('b.kd_kategori_budget', 'like', '%'.$kd_kategori_budget.'%')
		->where('b.kd_jenis', 'like', '%'.$kd_jenis.'%')
		//->whereNotIn('a.status_approval', ['E','S'])
		->get();

		return $q;
	}

	public static function check_role_budget($user_id)
	{
		$q = DB::table('bc_user_role as a')
		->select('a.fg_finance', 'a.fg_bod')
		->where('a.nik', '=', $user_id)
		->get();

		return $q;
	}
}