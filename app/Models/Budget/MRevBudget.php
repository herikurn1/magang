<?php

namespace App\Models\Budget;

use Illuminate\Database\Eloquent\Model;
use DB;

class MRevBudget extends Model
{
	public static function get_mst_pengajuan($no_pengajuan)
	{
		$q = DB::table('bc_mst_pengajuan')
		->select('status_approval')
		->where('no_pengajuan', '=', $no_pengajuan)
		->get();

		return $q;
	}

	public static function cek_user_input ($thn_anggaran, $kd_departemen, $kd_unit, $kd_lokasi, $user)
	{
		$get_user = DB::table('bc_mst_pengajuan as a')
		->select(DB::raw("COUNT(a.user_entry) as user_entry")) 
		->where('a.thn_anggaran', '=', $thn_anggaran)
		->where('a.kd_departemen', '=', $kd_departemen)
		->where('a.kd_unit', '=', $kd_unit)
		->where('a.kd_lokasi', '=', $kd_lokasi)
		->where('a.user_entry', '=', $user)
		//->where('a.status_approval', '=', 'E')
		->get();

		return $get_user;
	}

	public static function count_no_pengajuan($no_pengajuan, $thn_anggaran, $kd_departemen, $kd_unit, $kd_lokasi, $user)
	{
		$get_count_pengajuan = DB::table('bc_dtl_pengajuan as a')
		->select(DB::raw("COUNT(a.no_pengajuan) as no_pengajuan")) 
		->where('a.no_pengajuan', '=', $no_pengajuan)
		//->where('a.status_approval', '=', 'E')
		->get();

		return $get_count_pengajuan;
	}

	public static function insert_dt($thn_anggaran, $kd_departemen, $kd_unit, $kd_lokasi, $user, $tgl)
	{
		//$no_pengajuan = '';
		//DB::transaction(function () use ($thn_anggaran, $kd_departemen, $kd_unit, $kd_lokasi, $user, $tgl) {
			$get_no_pengajuan = DB::table('bc_penomoran as a')
			->select('a.last_num')
			->where('a.kelompok', '=', 'BC_NOMOR_PENGAJUAN')
			->where('a.kode', '=', $kd_unit.$thn_anggaran)
			->get();

			if(count($get_no_pengajuan) == 0){
				$ins_no_pengajuan = DB::table('bc_penomoran')
				->insert([
					'kelompok'	=> 'BC_NOMOR_PENGAJUAN',
					'kode'		=> $kd_unit.$thn_anggaran,
					'last_num'	=> 1,
					'deskripsi'	=> 'Seq. Nomor Pengajuan Budget'
				]);

				$last_num = 1;
			}else{
				foreach ($get_no_pengajuan as $get_no_pengajuan_row) {
					$last_num = $get_no_pengajuan_row->last_num;
				}
			}

			$upd_no_pengajuan = DB::table('bc_penomoran')
			->where('kelompok', '=', 'BC_NOMOR_PENGAJUAN')
			->where('kode', '=', $kd_unit.$thn_anggaran)
			->update([
				'last_num'	=> ($last_num + 1)
			]);

			$no_pengajuan = $kd_unit.$thn_anggaran.sprintf("%04d", $last_num);

			$save = DB::table('bc_mst_pengajuan')
			->insert([
				'no_pengajuan' 		=> $no_pengajuan,
				'thn_anggaran'		=> $thn_anggaran,
				'kd_departemen'		=> $kd_departemen,
				'kd_unit'			=> $kd_unit,
				'kd_lokasi'			=> $kd_lokasi,
				'status_approval'	=> 'E',
				'user_entry'		=> $user,
				'tgl_entry'			=> $tgl,
				'user_update'		=> $user,
				'tgl_update'		=> $tgl
			]);

			//echo $no_pengajuan;
			//return $no_pengajuan;
		//});
		
		return $no_pengajuan;
	}

	public static function update_dt($no_pengajuan, $user, $tgl)
	{
		$q = DB::table('bc_mst_pengajuan')
		->where('no_pengajuan', '=', $no_pengajuan)
		->where('status_approval', '=', 'E')
		->update([
			'user_update'	=> $user,
			'tgl_update'	=> $tgl
		]);
	}

	public static function search_dt($user, $kd_unit, $kd_lokasi, $keyword)
	{
		$q = DB::table('bc_mst_pengajuan as a')
		->select(
			'a.no_pengajuan', 'a.thn_anggaran', 'a.kd_departemen', 'a.kd_unit', 
			'a.kd_lokasi', 'a.status_approval', 'b.label as nm_status_approval', 'a.tgl_entry'
		)
		->join('bc_penomoran as b', function($join) {
			$join->on('b.kode', '=', 'a.status_approval')
			->where('b.kelompok', '=', 'BC_STATUS_APPROVAL');
		})
		->where('a.user_entry', '=', $user)
		->where('a.kd_unit', '=', $kd_unit)
		->where('a.kd_lokasi', '=', $kd_lokasi)
		->where('a.no_pengajuan', 'like', '%'.$keyword.'%')
		->orderBy('a.no_pengajuan', 'desc')
		->get();

		return $q;
	}

	public static function search_barang($keyword, $kd_kategori, $kd_jenis)
	{
		$q = DB::connection('inventory')
		->table('mst_barang as a')
		->select('a.kd_barang', 'a.nm_barang', 'a.kd_jenis', 'b.nm_jenis', 'a.kd_kategori', 'c.nm_kategori')
		->join('mst_jenis_barang as b', 'b.kd_jenis', '=', 'a.kd_jenis')
		->join('mst_kategori_barang as c', 'c.kd_kategori', '=', 'a.kd_kategori')
		->where('a.kd_kategori', '=', $kd_kategori)
		->where('a.kd_jenis', '=', $kd_jenis)
		->where(function($wh) use ($keyword){
			$wh->where('a.kd_barang', 'like', '%'.$keyword.'%');
			$wh->orWhere('a.nm_barang', 'like', '%'.$keyword.'%');
		})
		->paginate(20);

		return $q;
	}

	public static function insert_barang($no_pengajuan, $no_budget, $kd_barang, $nm_barang, $kd_kategori_budget, $nm_kategori_budget, $kd_kategori, $nm_kategori, $kd_jenis, $nm_jenis, $qty, $harga, $jumlah_harga, $urgency, $catatan, $user, $tgl)
	{
		try
		{
		$q = DB::table('bc_dtl_pengajuan')
		->insert([
			'no_pengajuan'			=> $no_pengajuan,
			'kd_barang'				=> $no_pengajuan.$no_budget,
			'nm_barang'				=> $nm_barang,
			'kd_kategori_budget'   	=> $kd_kategori_budget,
			'nm_kategori_budget'   	=> $nm_kategori_budget,
			'kd_kategori'			=> $kd_kategori,
			'nm_kategori'			=> $nm_kategori,
			'kd_jenis'				=> $kd_jenis,
			'nm_jenis'				=> $nm_jenis,
			'qty'					=> $qty,
			'qty_finance'			=> $qty,
			'qty_final'				=> $qty,
			'harga'					=> $harga,
			'jumlah_harga'			=> $jumlah_harga,
			'urgency'				=> $urgency,
			'catatan'				=> $catatan,
			'user_entry'			=> $user,
			'tgl_entry'				=> $tgl,
			'user_update'			=> $user,
			'tgl_update'			=> $tgl
		]);
		}
		catch(Exception $e)
		{
		dd($e->getMessage());
		}
		return $q;
	}

	public static function edit_barang($rowid, $qty, $harga, $jumlah_harga, $urgency, $catatan, $user, $tgl)
	{
		$q = DB::table('bc_dtl_pengajuan as a')
		->join('bc_mst_pengajuan as b', 'b.no_pengajuan', '=', 'a.no_pengajuan')
		->where('a.rowid', '=', $rowid)
		->where('b.user_entry', '=', $user)
		->where('b.status_approval', '=', 'E')
		->update([
			'a.qty'				=> $qty,
			'a.qty_finance'		=> $qty,
			'a.qty_final'		=> $qty,
			'a.harga'			=> $harga,
			'a.jumlah_harga'	=> $jumlah_harga,
			'a.urgency'			=> $urgency,
			'a.catatan'			=> $catatan,
			'a.user_update'		=> $user,
			'a.tgl_update'		=> $tgl
		]);

		return $q;
	}

	public static function delete_dt($no_pengajuan, $user, $tgl)
	{
		$q = DB::table('bc_mst_pengajuan')
		->where('no_pengajuan', '=', $no_pengajuan)
		->where('user_entry', '=', $user)
		->where('status_approval', '=', 'E')
		->update([
			'status_approval' 	=> 'C',
			'user_update'		=> $user,
			'tgl_update'		=> $tgl
		]);

		return $q;
	}

	public static function submit_kabag($no_pengajuan, $user, $tgl)
	{
		$q = DB::table('bc_mst_pengajuan')
		->where('no_pengajuan', '=', $no_pengajuan)
		->where('user_entry', '=', $user)
		->where('status_approval', '=', 'E')
		->update([
			'status_approval'   => 'S',
			'user_update'       => $user,
			'tgl_update'        => $tgl
		]);

		return $q;
	}

	public static function delete_barang($rowid)
	{
		$q = DB::table('bc_dtl_pengajuan as a')
		->join('bc_mst_pengajuan as b', 'b.no_pengajuan', '=', 'a.no_pengajuan')
		->where('b.status_approval', '=', 'E')
		->where('a.rowid', '=', $rowid)
		->delete();

		return $q;
	}

	public static function view_history($thn_anggaran, $kd_unit, $kd_departemen, $kd_barang)
	{
		$q = DB::table('bc_dtl_pengajuan as a')
		->select('b.thn_anggaran', 'a.no_pengajuan', 'a.qty', 'a.qty_finance', 'a.qty_final', 'a.harga')
		->join('bc_mst_pengajuan as b', 'b.no_pengajuan', '=', 'a.no_pengajuan')
		->where('b.kd_unit', '=', $kd_unit)
		->where('b.kd_departemen', '=', $kd_departemen)
		// ->where('b.thn_anggaran', '<', $thn_anggaran)
		// ->where('b.status_approval', '=', 'B')
		->where('a.kd_barang', '=', $kd_barang)
		->orderBy('b.thn_anggaran', 'desc')
		->get();

		return $q;
	}
}