<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\InputLantaiM;
use DataTables;

class InputLantaiC extends Controller
{
	private $sysController;
	private $master;

	public function __construct()
	{
		$this->sysController = new SysController();
		$this->master = new CMaster();
	}

    public function index(Request $r)
    {
    	$user_id 		= $r->session()->get('user_id');
    	$button 		= $this->sysController->get_button($r);

    	$data_user 		= $this->master->get_data_user($user_id);

    	$dt = array(
    		'button' 		=> $button,
    		'data_user'		=> $data_user
    	);

    	return view('sqii.InputLantaiv')->with('dt', $dt);
    }

    public function show_lantai(Request $r)
	{
		
		$q = InputLantaiM::show_lantai();//KD_CLUSTER', 'a.NM_CLUSTER', 'b.NM_KAWASAN
		//return Datatables::of($q)->make(true);
		foreach ($q as $row) {
			$data[] = array(
				'kd_lantai'	=> $row->KD_LANTAI,
				'nm_lantai'	=> $row->NM_LANTAI
			);
		}

		if(isset($data)){
			return $data;
		}
	}

	public function search_dt(Request $r)
	{
		
		$keyword    = $r->keyword;
		$user   	= $r->session()->get('user_id');

		$q = InputLantaiM::search_dt($keyword);

		//return response()->json($q);
		return Datatables::of($q)->make(true);
	}

	public function save(Request $r)
	{
		//print_r($r);die;
		$savebtnval    = $r->saveBtnVal;
		$kd_lantai    = $r->kd_lantai;
		$nm_lantai    = $r->nm_lantai;
		$user   	= $r->session()->get('user_id');

		if($savebtnval == 'create'){
			$q = InputLantaiM::simpan_dt($nm_lantai,$user);
		}else{
			$q = InputLantaiM::update_dt($kd_lantai,$nm_lantai,$user);
		}

		//return response()->json($q);
	}

	public function delete_dt(Request $r)
	{
		$kd_lantai    = $r->kd_lantai;
		$user   	= $r->session()->get('user_id');

		$q = InputLantaiM::delete_dt($kd_lantai,$user);

		//return response()->json($q);
	}
}
