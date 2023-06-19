<?php

namespace App\Models\Cmstrhub;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use DB;

class m_salestoday extends Model
{
    public static function get_data($kd_unit, $date_from, $date_to, $tgl_now){

		if($date_from != ""){
			$from = $date_from;
		} else {
			$from = $tgl_now;
		}

		if($date_to != ""){
			$to = $date_to;
		} else {
			$to = $tgl_now;
		}

		// $from = '2023-01-01';
		// $to	  = '2023-01-20';

        // $q = DB::table('tr_trx_salestoday as a')
        //     ->select('a.*', 'b.NAME')
		// 	->leftjoin('tr_sers as b', 'a.kd_user', '=', 'b.kd_user')
        //     ->orderby('rowid', 'asc')
		// 	->whereBetween('tgl_sales', [$from, $to])
        //     ->get();

		$q = DB::select("
			SELECT A.*, B.NAME
			FROM TR_TRX_SALESTODAY AS A
			LEFT JOIN TR_USERS AS B ON A.KD_USER = B.kd_user
			WHERE CONVERT(VARCHAR(10),A.TGL_SALES,126) BETWEEN '".$from."' AND '".$to."' 
			AND A.KD_PERUSAHAAN = '".$kd_unit."'
			ORDER BY A.ROWID DESC
		");

        return $q;
    }
}