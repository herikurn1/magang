<?php

namespace App\Models\Budget;

use Illuminate\Database\Eloquent\Model;
use DB;

class MCapexVsActualDtl extends Model
{
	public static function search_detail($kd_unit, $kd_departemen, $kd_kategori, $kd_jns, $thn_anggaran)
	{
		$sql = "EXEC BC_DETAIL_BUDGET_VS_REALISASI ";
		$sql.= "'".$thn_anggaran."',";
		$sql.= "'".$kd_unit."',";
        $sql.= "'".$kd_departemen."',";
		$sql.= "'".$kd_kategori."',";
		$sql.= "'".$kd_jns."' ";
        
        
        $q = DB::connection('sqlsrv')
        ->select($sql);
 //die($sql);
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