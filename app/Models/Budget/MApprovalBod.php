<?php

namespace App\Models\Budget;

use Illuminate\Database\Eloquent\Model;
use DB;

class MApprovalBod extends Model
{
	public static function show_pengajuan($thn_anggaran, $kd_unit, $status_approval)
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
    	->where('a.kd_unit', $kd_unit)
    	->orderBy('a.no_pengajuan', 'desc')
    	->get();

    	return $q;
	}

	public static function approve_bod($no_pengajuan, $user, $tgl)
	{
		$q = DB::table('bc_mst_pengajuan')
		->where('status_approval', '=', 'F')
		->where('no_pengajuan', '=', $no_pengajuan)
		->update([
			'status_approval' 	=> 'B',
			'user_approve_bod'	=> $user,
			'tgl_approve_bod'	=> $tgl
		]);

		return $q;
	}

	public static function save_barang($no_pengajuan, $rowid, $qty_final, $user, $tgl)
	{
		$q = DB::table('bc_dtl_pengajuan as a')
		->join('bc_mst_pengajuan as b', 'b.no_pengajuan', '=', 'a.no_pengajuan')
		->where('b.no_pengajuan', '=', $no_pengajuan)
		->where('b.status_approval', '=', 'F')
		->where('a.rowid', '=', $rowid)
		->update([
			'a.qty_final'	=> $qty_final,
			'a.user_update'	=> $user,
			'a.tgl_update'	=> $tgl
		]);

		return $q;
	}
}