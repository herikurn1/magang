<?php

namespace App\Models\Sqii;

use Illuminate\Database\Eloquent\Model;
use DB;

class RegKawasanM extends Model
{
	public static function show_kawasan()
	{
		$q = DB::connection('sqii2')
		->table('SQII_KAWASAN as a')
    	->select(
    		'a.KD_KAWASAN', 'a.NM_KAWASAN'
    	)
    	->where('a.FLAG_AKTIF', '=', 'Y')
    	->orderBy('a.KD_KAWASAN', 'asc')
    	->get();

    	return $q;
	}
}
