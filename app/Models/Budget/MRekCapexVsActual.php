<?php

namespace App\Models\Budget;

use Illuminate\Database\Eloquent\Model;
use DB;

class MRekCapexVsActual extends Model
{
	public static function search_detail($kd_unit, $thn_anggaran)
	{
		$sql = "EXEC BC_REKAP_CAPEX_VS_ACTUAL ";
		$sql.= "'".$thn_anggaran."',";
		$sql.= "'".$kd_unit."'";
        // $sql.= "'".$kd_departemen."'";
        
        
        $q = DB::connection('sqlsrv')
        ->select($sql);
// die($q);
		return $q;
	}

	public static function summary_budget($kd_unit, $thn_anggaran)
	{
		$sql = "EXEC BC_REKAP_CAPEX_VS_ACTUAL_SUM_BUDGET ";
		$sql.= "'".$thn_anggaran."',";
		$sql.= "'".$kd_unit."'";
        // $sql.= "'".$kd_departemen."'";
        
        
        $q = DB::connection('sqlsrv')
        ->select($sql);
// die($q);
		return $q;
	}

	public static function summary_actual($kd_unit, $thn_anggaran)
	{
		$sql = "EXEC BC_REKAP_CAPEX_VS_ACTUAL_SUM_ACTUAL ";
		$sql.= "'".$thn_anggaran."',";
		$sql.= "'".$kd_unit."'";
        // $sql.= "'".$kd_departemen."'";
        
        
        $q = DB::connection('sqlsrv')
        ->select($sql);
// die($q);
		return $q;
	}

	public static function summary_non_realisasi($kd_unit, $thn_anggaran)
	{
		$sql = "EXEC BC_REKAP_CAPEX_VS_ACTUAL_NON_REALISASI ";
		$sql.= "'".$thn_anggaran."',";
		$sql.= "'".$kd_unit."'";
        // $sql.= "'".$kd_departemen."'";
        
        
        $q = DB::connection('sqlsrv')
        ->select($sql);
// die($q);
		return $q;
	}

	public static function search_chart($kd_unit, $thn_anggaran)
	{
		$sql = "EXEC BC_REKAP_CAPEX_CTG_UNIT_CHART ";
		$sql.= "'".$thn_anggaran."',";
		$sql.= "'".$kd_unit."'";
        // $sql.= "'".$kd_departemen."'";
        
        
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