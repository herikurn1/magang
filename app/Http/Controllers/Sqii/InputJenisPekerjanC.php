<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\InputJenisPekerjanM;

class InputJenisPekerjanC extends Controller
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

    	return view('sqii.InputJenisPekerjanV')->with('dt', $dt);
    }

    public function show_jenis_pekerjaan(Request $r)
	{
		
		$q = InputJenisPekerjanM::show_jenis_pekerjaan();
		foreach ($q as $row) {
			$data[] = array(
				'kd_kategori_defect'	=> $row->KD_KATEGORI_DEFECT,
				'nm_kategori_defect'	=> $row->NM_KATEGORI_DEFECT,
				'deskripsi'				=> $row->DESKRIPSI,
				'tipe_denah'			=> $row->TIPE_DENAH
			);
		}

		if(isset($data)){
			return $data;
		}
	}

	public function save(Request $r)
	{
		//print_r($r);die;
		$savebtnval    = $r->saveBtnVal;
		$kd_kategori_defect    = $r->kd_kategori_defect;
		$nm_kategori_defect    = $r->nm_kategori_defect;
		$deskripsi    = $r->deskripsi;
		$tipe_denah    = $r->tipe_denah;
		$user   	= $r->session()->get('user_id');

		if($savebtnval == 'create'){
			$q = InputJenisPekerjanM::simpan_dt($nm_kategori_defect,$deskripsi,$tipe_denah,$user);
		}else{
			$q = InputJenisPekerjanM::update_dt($kd_kategori_defect,$nm_kategori_defect,$deskripsi,$tipe_denah,$user);
		}

		//return response()->json($q);
	}

	public function delete_dt(Request $r)
	{
		$kd_kategori_defect    = $r->kd_kategori_defect;
		$user   	= $r->session()->get('user_id');

		$q = InputJenisPekerjanM::delete_dt($kd_kategori_defect,$user);

		//return response()->json($q);
	}
}
