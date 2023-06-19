<?php

namespace App\Http\Controllers\Cmstrhub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;

use App\Models\Cmstrhub\m_user;

class usertr extends Controller
{
    private $sysController;
	private $master;

	public function __construct()
	{
		$this->sysController = new SysController();
	}

    public function index(Request $r){
        $user_id 		= $r->session()->get('user_id');
		$kd_unit 		= $r->session()->get('kd_unit');
    	$button 		= $this->sysController->get_button($r);

    	$dt = array(
    		'button' 			=> $button,
    	);

    	return view('cmstrhub.v_User')->with('dt', $dt);
    }

	public function search_dt(Request $r){
		$q = m_user::search_dt();

		return $q;
	}

	public function insert(Request $r){
		$id			= $r->id_user;
		$kd_user	= $r->kd_user;
		$nm_user    = $r->nm_user;
		$email      = $r->email;
		$no_hp      = $r->no_hp;
		$password   = $r->password;
		$flag_aktif	= $r->flag_aktif;
		$act        = $r->act;
		$flag_aktif = ($flag_aktif=="") ? 'N':'Y';
		$tradename	= $r->tradename;

		$user		= $r->session()->get('user_id');
		$kd_unit 	= $r->session()->get('kd_unit');
		$tgl 		= date('Y-m-d H:m:s');

		if ($act == "add") {
			$q = m_user::insert($email, $nm_user, $password, $no_hp, $flag_aktif, $tradename, $user, $tgl);

			$kd_user = $q;
			$data = [];
			$data[] = [
				'code'		=> 'S200',
				'kd_user' 	=> $kd_user, 
				'Message' 	=> 'Data Berhasil di Tambah.'
			];
			echo json_encode($data);
		}else{

			if($password == "123456789abcefghij"){
				$update = m_user::update_dt($id, $email, $nm_user, $no_hp, $flag_aktif, $tradename, $user, $tgl);
			}else{
				$update = m_user::update_dt_with_password($id, $email, $nm_user, $password, $no_hp, $flag_aktif, $tradename, $user, $tgl);
			}

			$data = [];
			$data[] = [
				'code'		=> 'U200',
				'Message' 	=> 'Data Berhasil di Update.'
			];
			echo json_encode($data);
		}
		
		$add_unit = $r->add_unit;
		if($add_unit != null){
			$add_total 	= count($add_unit);
			//m_produkhukum::hapus_produk_dtl($no);
			for ($i=0; $i < $add_total; $i++) { 
				$cek = m_user::cek_unit($email, $add_unit[$i]);
				if (count($cek) == 0) {
					$q = m_user::insert_unit($email, $add_unit[$i], $user, $tgl);
				}
			}

		}

		$add_unit_zona 	= $r->add_unit_zona;
		$add_zona		= $r->add_zona;
		$add_stok		= $r->add_stok;
		$add_blok		= $r->add_blok;
		$add_nomer		= $r->add_nomer;
		$add_pjs		= $r->add_pjs;
		$add_pemilik	= $r->add_pemilik;
		$add_default	= $r->add_default;
		$add_nasabah_id	= $r->add_nasabah_id;
		$add_stok_id	= $r->add_stok_id;

		if($add_unit_zona != null){
			$add_total_zona 	= count($add_unit_zona);
			//m_produkhukum::hapus_produk_dtl($no);
			for ($i=0; $i < $add_total_zona; $i++) { 
				$cek = m_user::cek_zona($add_unit_zona[$i], $email, $add_zona[$i], $add_pjs[$i]);
				if (count($cek) == 0) {
					$q = m_user::insert_zona($add_unit_zona[$i], $email, $add_zona[$i], $add_stok[$i], $add_blok[$i], $add_nomer[$i], $add_pjs[$i], $add_pemilik[$i], $add_default[$i], $add_nasabah_id[$i], $add_stok_id[$i], $user, $tgl);
				}
			}

		}
	}

	public function get_unit(Request $r){
		$q = m_user::get_unit();

		return $q;
	} 

	public function get_zona(Request $r){
		$kd_unit = $r->kd_unit; 

		$q = m_user::get_zona($kd_unit);

		return $q;
	}

	public function get_stok(Request $r){
		$kd_unit = $r->kd_unit; 
		$kd_zona = $r->kd_zona;

		$q = m_user::get_stok($kd_unit, $kd_zona);

		return $q;
	}

	public function get_blok(Request $r){
		$kd_unit = $r->kd_unit; 
		$kd_zona = $r->kd_zona;
		$kd_stok = $r->kd_stok;

		$q = m_user::get_blok($kd_unit, $kd_zona, $kd_stok);

		return $q;
	}

	public function show_unit(Request $r){
		$user_id = $r->user_id;

		$q = m_user::show_unit($user_id);

		return $q;
	}

	public function show_zona(Request $r){
		$user_id = $r->user_id;

		$q = m_user::show_zona($user_id);

		return $q;
	}

	public function delete_unit(Request $r){
		$kd_unit	= $r->kd_unit;
		$user_id 	= $r->user_id;

		$q = m_user::delete_unit($kd_unit, $user_id);

		return $q;
	}

	public function delete_zona(Request $r){
		$kd_unit	= $r->kd_unit;
		$kd_user 	= $r->kd_user;
		$kd_zona	= $r->kd_zona;
		$no_pjs		= $r->no_pjs;

		$q = m_user::delete_zona($kd_unit, $kd_user, $kd_zona, $no_pjs);

		return $q;
	}
}
