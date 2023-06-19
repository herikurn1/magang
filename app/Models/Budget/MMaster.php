<?php

namespace App\Models\Budget;

use Illuminate\Database\Eloquent\Model;
use DB;

class MMaster extends Model
{
    public static function list_thn_anggaran()
    {
    	$q = DB::table('bc_thn_anggaran as a')
    	->select('a.thn_anggaran')
    	->where('a.fg_aktif', '=', 'Y')
    	->orderBy('a.thn_anggaran', 'asc')
    	->get();

    	return $q;
    }

    public static function get_master_code($kelompok)
    {
        $q = DB::table('bc_penomoran as a')
        ->select('a.kode', 'a.label')
        ->where('a.kelompok', '=', $kelompok)
        ->orderBy('a.urut', 'asc')
        ->get();

        return $q;
    }

    public static function get_data_user($user_id)
    {
    	$q = DB::connection('hrms')
    	->table('mst_karyawan as a')
    	->select('a.nama', 'b.kd_bagian as kd_departemen', 'b.nama_bagian as nm_departemen')
    	->join('tbl_bagian as b', 'b.kd_bagian', '=', 'a.kd_bagian')
    	->where('a.no_induk', '=', $user_id)
    	->get();

    	return $q;
    }

    public static function get_staff($user_id)
    {
    	$q = DB::connection('hrms')
    	->table('mst_karyawan as a')
    	->select('a.no_induk', 'a.nama', 'b.kd_bagian as kd_departemen', 'b.nama_bagian as nm_departemen')
    	->join('tbl_bagian as b', 'b.kd_bagian', '=', 'a.kd_bagian')
    	->where('a.no_induk_app_cuti', '=', $user_id)
    	->get();

    	return $q;
    }

    public static function get_nm_unit($kd_unit)
    {
    	$q = DB::table('t_unit')
    	->select('nama as nm_unit')
    	->where('unit_id', '=', $kd_unit)
    	->get();

    	return $q;
    }

    public static function get_nm_departemen($kd_departemen, $keyword = '')
    {
    	$q = DB::connection('hrms')
    	->table('tbl_bagian')
    	->select('nama_bagian as nm_departemen')
    	->where('kd_bagian', '=', $kd_departemen)
        ->where(function($wh) use ($keyword){
            $wh->where('kd_bagian', 'like', '%'.$keyword.'%');
            $wh->orWhere('nama_bagian', 'like', '%'.$keyword.'%');
        })
    	->get();

    	return $q;
    }

    public static function get_dtl_pengajuan($rowid)
    {
        $q = DB::table('bc_dtl_pengajuan as a')
        ->select('a.qty', 'a.qty_finance', 'a.qty_final', 'a.harga', 'a.jumlah_harga', 'a.urgency', 'a.catatan')
        ->where('a.rowid', '=', $rowid)
        ->get();

        return $q;
    }

    public static function show_dtl_pengajuan($no_pengajuan)
    {
        $q = DB::table('bc_dtl_pengajuan as a')
        ->select(
            'a.kd_barang', 
            DB::raw("UPPER(a.nm_barang) nm_barang"), 
            'a.kd_kategori_budget', 'a.nm_kategori_budget', 
            'a.kd_kategori', 'a.nm_kategori', 'a.kd_jenis', 'a.nm_jenis', 
            'a.qty', 'a.qty_finance', 'a.qty_final', 'a.harga', 
            DB::raw("SUM(a.qty_final*a.harga) as jumlah_harga"), 'a.urgency', 
            'b.label as nm_urgency', 'a.catatan', 'a.rowid'
        )
        ->join('bc_penomoran as b', 'b.kode', '=', 'a.urgency')
        ->where('a.no_pengajuan', '=', $no_pengajuan)
        ->where('b.kelompok', '=', 'BC_URGENCY')
        ->GroupBy('a.kd_barang', 'a.nm_barang', 'a.kd_kategori_budget', 'a.nm_kategori_budget', 
        'a.kd_kategori', 'a.nm_kategori', 'a.kd_jenis', 'a.nm_jenis', 
        'a.qty', 'a.qty_finance', 'a.qty_final', 'a.harga', 'a.urgency', 
        'b.label', 'a.catatan', 'a.rowid')
        ->orderBy('a.rowid', 'asc')
        ->get();

        return $q;
    }

    public static function show_dtl_yearprev($user, $yearprev, $kd_unit, $kd_lokasi)
    {
        $q = DB::select("select
                            a.kd_barang, a.nm_barang, a.kd_kategori_budget, a.nm_kategori_budget, 
                            a.kd_kategori, a.nm_kategori, a.kd_jenis, a.nm_jenis, 
                            a.qty, a.qty_finance, a.qty_final, a.harga, a.jumlah_harga, a.urgency, 
                            b.label as nm_urgency, a.catatan, a.rowid
                        from bc_dtl_pengajuan as a       
                        inner join bc_penomoran as b 
                            on b.kode = a.urgency
                        inner join bc_mst_pengajuan c
							on c.no_pengajuan = a.no_pengajuan
                        where a.user_entry= '".$user."'
                            and c.thn_anggaran = '".$yearprev."'
                            and b.kelompok = 'BC_URGENCY'
                            and c.kd_unit = '".$kd_unit."'
							and c.kd_lokasi = '".$kd_lokasi."'
                        order By a.rowid asc");

        return $q;
    }

    public static function salin_data($user, $yearprev, $kd_unit, $kd_lokasi)
    {
        $q = DB::select("select
                            a.kd_barang as add_kd_barang, a.nm_barang as add_nm_barang, 
                            a.kd_kategori_budget as add_kd_kategori_budget, a.nm_kategori_budget as add_nm_kategori_budget, 
                            a.kd_kategori as add_kd_kategori, a.nm_kategori as add_nm_kategori, 
                            a.kd_jenis as add_kd_jenis, a.nm_jenis as add_nm_jenis, 
                            a.qty as add_qty, a.qty_finance, a.qty_final, 
                            a.harga as add_harga, a.jumlah_harga as add_jumlah_harga, a.urgency as add_urgency, 
                            b.label as nm_urgency, 
                            a.catatan as add_catatan, 
                            a.rowid
                        from bc_dtl_pengajuan as a       
                        inner join bc_penomoran as b 
                            on b.kode = a.urgency
                        inner join bc_mst_pengajuan c
							on c.no_pengajuan = a.no_pengajuan
                        where a.user_entry= '".$user."'
                            and c.thn_anggaran = '".$yearprev."'
                            and b.kelompok = 'BC_URGENCY'
                            and c.kd_unit = '".$kd_unit."'
							and c.kd_lokasi = '".$kd_lokasi."'
                        order By a.rowid asc");

        return $q;
    }

    public static function search_kategori_budget($keyword)
    {
        $q = DB::connection('sqlsrv')
        ->table('bc_kategori as a')
        ->select('a.kd_kategori', 'a.nm_kategori')
        ->where(function($wh) use ($keyword) {
            $wh->where('a.kd_kategori', 'like', '%'.$keyword.'%');
            $wh->orWhere('a.nm_kategori', 'like', '%'.$keyword.'%');
        })
        ->get();

        return $q;
    }

    public static function search_jenis($keyword, $kd_kategori_budget)
    {
        $q = DB::connection('sqlsrv')
        ->table('bc_jenis_barang as a')
        ->select('a.kd_jenis', 'a.nm_jenis', 'a.kd_kategori')
        // ->join('bc_kategori_jenis_barang as b', function($join){
        //     $join->on('b.kd_jenis', '=', 'a.kd_jenis');
        //     $join->on('b.kd_kategori', '=', 'a.kd_kategori');
        // })
        ->where('a.kd_kategori', '=', $kd_kategori_budget)
        ->where(function($wh) use ($keyword) {
            $wh->where('a.kd_jenis', 'like', '%'.$keyword.'%');
            $wh->orWhere('a.nm_jenis', 'like', '%'.$keyword.'%');
        })
        ->get();

        return $q;
    }

    public static function search_unit_budget($keyword)
    {
        $q = DB::table('t_unit as a')
        ->select('a.unit_id as kd_unit', 'a.nama as nm_unit')
        ->join('bc_mst_pengajuan as b', 'b.kd_unit', '=', 'a.unit_id')
        ->where(function($wh) use ($keyword) {
            $wh->where('a.unit_id', 'like', '%'.$keyword.'%');
            $wh->orWhere('a.nama', 'like', '%'.$keyword.'%');
        })
        ->groupBy('a.unit_id', 'a.nama')
        ->get();

        return $q;
    }

    public static function group_departemen_budget()
    {
        $q = DB::table('bc_mst_pengajuan as a')
        ->select('a.kd_departemen')
        ->groupBy('a.kd_departemen')
        ->get();

        return $q;
    }

    public static function search_departemen_budget($keyword)
    {
        $q = DB::connection('hrms')
        ->table('tbl_bagian as a')
        ->select('a.kd_bagian', 'a.nm_bagian')
        ->join('bc_mst_pengajuan as b', 'b.kd_departemen', '=', 'a.kd_departemen')
        ->where(function($wh) use ($keyword){
            $wh->where('a.kd_bagian', 'like', '%'.$keyword.'%');
            $wh->orWhere('a.nm_bagian', 'like', '%'.$keyword.'%');
        })
        ->get();

        return $q;
    }
}