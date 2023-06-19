<?php

namespace App\Models\Budget;

use Illuminate\Database\Eloquent\Model;
use DB;

class MApprovalKabag extends Model
{
	public static function show_pengajuan($thn_anggaran, $list_staff, $status_approval)
	{
		$q = DB::table('bc_mst_pengajuan as a')
    	->select(
    		'a.no_pengajuan', 'a.thn_anggaran', 'a.kd_departemen', 'a.kd_unit', 
    		'a.kd_lokasi', 'a.status_approval', 'b.label as nm_status_approval', 'a.user_entry', 'a.tgl_entry'
    	)
    	->join('bc_penomoran as b', function($join) {
    		$join->on('b.kode', '=', 'a.status_approval')
    		->where('b.kelompok', '=', 'BC_STATUS_APPROVAL');
    	})
    	->where('a.thn_anggaran', '=', $thn_anggaran)
    	->where('a.status_approval', '=', $status_approval)
    	->whereIn('a.user_entry', $list_staff)
    	->orderBy('a.no_pengajuan', 'desc')
    	->get();

    	return $q;
	}

	public static function approve_kabag($no_pengajuan, $user, $tgl)
	{
		$q = DB::table('bc_mst_pengajuan')
		->where('status_approval', '=', 'S')
		->where('no_pengajuan', '=', $no_pengajuan)
		->update([
			'status_approval' 		=> 'K',
			'user_approve_kabag'	=> $user,
			'tgl_approve_kabag'		=> $tgl
		]);

		return $q;
	}

	public static function kembalikan($no_pengajuan, $user, $tgl)
	{
		$q = DB::table('bc_mst_pengajuan')
		->where('status_approval', '=', 'S')
		->where('no_pengajuan', '=', $no_pengajuan)
		->update([
			'status_approval' 		=> 'E'
		]);

		return $q;
	}

	public static function save_barang($no_pengajuan, $rowid, $qty, $harga_before, $user, $tgl)
	{
		$q = DB::table('bc_dtl_pengajuan as a')
		->join('bc_mst_pengajuan as b', 'b.no_pengajuan', '=', 'a.no_pengajuan')
		->where('b.no_pengajuan', '=', $no_pengajuan)
		->where('b.status_approval', '=', 'S')
		->where('a.rowid', '=', $rowid)
		->update([
			'a.qty'				=> $qty,
			'a.qty_finance'		=> $qty,
			'a.qty_final'		=> $qty,
			'a.jumlah_harga'	=> ($qty * $harga_before),
			'a.user_update'		=> $user,
			'a.tgl_update'		=> $tgl
		]);

		return $q;
	}

	public static function delete_barang($no_pengajuan, $rowid)
	{
		$q = DB::table('bc_dtl_pengajuan as a')
		->join('bc_mst_pengajuan as b', 'b.no_pengajuan', '=', 'a.no_pengajuan')
		->where('b.status_approval', '=', 'S')
		->where('b.no_pengajuan', '=', $no_pengajuan)
    	->where('a.rowid', '=', $rowid)
    	->delete();

    	return $q;
	}
}