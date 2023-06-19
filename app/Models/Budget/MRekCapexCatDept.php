<?php

namespace App\Models\Budget;

use Illuminate\Database\Eloquent\Model;
use DB;

class MRekCapexCatDept extends Model
{
	public static function search_detail($kd_unit, $kd_departemen, $thn_anggaran)
	{
		$sql = "EXEC BC_REKAP_CAPEX_CTG_DEPT ";
		$sql.= "'".$thn_anggaran."',";
		$sql.= "'".$kd_unit."',";
        $sql.= "'".$kd_departemen."'";
        
        
        $q = DB::connection('sqlsrv')
        ->select($sql);
// die($q);
		return $q;
	}

	public static function search_chart($kd_unit, $kd_departemen, $thn_anggaran)
	{
		$sql = "EXEC BC_REKAP_CAPEX_CTG_DEPT_CHART ";
		$sql.= "'".$thn_anggaran."',";
		$sql.= "'".$kd_unit."',";
        $sql.= "'".$kd_departemen."'";
        
        
        $q = DB::connection('sqlsrv')
        ->select($sql);
  //die($q);
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