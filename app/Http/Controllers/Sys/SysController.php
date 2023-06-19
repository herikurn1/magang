<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Models\Costing\PcMstItemM;
use Illuminate\Http\Request;
use App\Services\BookmarkService;
use App\Models\Sys\SysModel;

class SysController extends Controller
{
	public function autologin(Request $r)
    {
    	$encryption = str_replace("|", "/", $r->encryption);

		// Store the cipher method
		$ciphering = "AES-128-CTR";

		// Use OpenSSl Encryption method
		$options = 0;

		// Non-NULL Initialization Vector for decryption
		$decryption_iv = '1234567891011121';

		// Store the decryption key
		$decryption_key = "5Umm4r3c0nK3y";

		// Use openssl_decrypt() function to decrypt the data
		$decryption=openssl_decrypt ($encryption, $ciphering, $decryption_key, $options, $decryption_iv);

		// Display the decrypted string
		// echo "Decrypted String: " . $decryption."<br>";

		$exp = explode("|", $decryption);

		$user_id 	= $exp[0];
		$nama 		= $exp[1];
		$expired 	= $exp[2];

		if(strtotime("now") > $expired){
			abort(419);
		}else{
			$r->session()->put('user_id', $user_id);
			$r->session()->put('nama', $nama);
			$r->session()->put('login', true);

			$ctrl 	= $r->ctrl.'/'.$r->ctrl2;
			if($ctrl == "/"){
				$ctrl = 'budget';
			}

			return redirect($ctrl);
		}
    }

    public static function menu($user_id)
    {
    	$tbl = '';
    	$get_parent = SysModel::get_parent($user_id);

		BookmarkService::print_bookmarks($user_id);

    	foreach ($get_parent as $get_parent_row) {
    		$modul_id 		= $get_parent_row->modul_id;
    		$nama_modul 	= $get_parent_row->nama_modul;

    		echo '
				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<i class="nav-icon far fa-plus-square"></i>
						<p>
							'.$nama_modul.'
							<i class="right fas fa-angle-left"></i>
						</p>
					</a>
    		';

    		self::get_child($modul_id, $user_id);

    		echo '
				</li>
    		';
    	}
    }

    public static function get_child($parent_id, $user_id)
    {
    	$get_child = SysModel::get_child($parent_id, $user_id);
    	if(count($get_child) > 0){
    		echo '
				<ul class="nav nav-treeview">
    		';

    		foreach ($get_child as $get_child_row) {
	    		$modul_id 		= $get_child_row->modul_id;
	    		$nama_modul 	= $get_child_row->nama_modul;
	    		$controller 	= $get_child_row->controller;

	    		if($controller == "#"){
	    			echo '
						<li class="nav-item has-treeview">
							<a href="#" class="nav-link">
								<i class="nav-icon far fa-circle"></i>
								<p>
									'.$nama_modul.'
									<i class="right fas fa-angle-left"></i>
								</p>
							</a>
		    		';

		    		self::get_child($modul_id, $user_id);

		    		echo '
						</li>
		    		';
	    		}else{
	    			echo '
						<li class="nav-item">
							<a href="/'.$controller.'" class="nav-link">
								<i class="far fa-dot-circle nav-icon"></i>
								<p>'.$nama_modul.'</p>
							</a>
						</li>
	    			';
	    		}
	    	}

    		echo '
				</ul>
    		';
    	}
    }

    public function portal_auth_view($user_id, $controller)
    {
    	$q = SysModel::hak_akses($user_id, $controller);

    	if(count($q) > 0){
    		return true;
    	}else{
    		return false;
    	}
    }

    public function portal_auth_save($user_id, $controller)
    {
    	$m_save = 0;
    	$q = SysModel::hak_akses($user_id, $controller);
    	foreach ($q as $row) {
    		$m_save = $row->m_save;
    	}

    	if($m_save == 1){
    		return true;
    	}else{
    		return false;
    	}
    }

    public function portal_auth_delete($user_id, $controller)
    {
    	$m_delete = 0;
    	$q = SysModel::hak_akses($user_id, $controller);
    	foreach ($q as $row) {
    		$m_delete = $row->m_delete;
    	}

    	if($m_delete == 1){
    		return true;
    	}else{
    		return false;
    	}
    }

    public static function title($controller)
    {
    	$nama_modul = 'Summarecon Portal';

    	$q = SysModel::title($controller);
    	foreach ($q as $row) {
    		$nama_modul = $row->nama_modul;
    	}

    	return $nama_modul;
    }

    public function get_button(Request $r)
    {
    	$user_id 	= $r->session()->get('user_id');
    	$controller = $r->segment(1).'/'.$r->segment(2);

    	$button 	= '';
    	$m_save 	= '';
    	$m_delete 	= '';

    	$q = SysModel::hak_akses($user_id, $controller);
    	foreach ($q as $row) {
    		$m_save 	= $row->m_save;
    		$m_delete 	= $row->m_delete;
    	}

    	if(count($q) > 0){
    		$button .= '
    			<div class="btn-group">
    		';

    		if($m_save == 1){
                $button .= '
					<button type="button" id="btn_mst_add" class="btn btn-default btn-flat" onclick="add_dt()"><i class="fas fa-plus"></i></button>
                    <button type="button" id="btn_mst_save" class="btn btn-default btn-flat" onclick="save_dt(); $(this).attr(\'disabled\', true); setTimeout(function(){$(\'#btn_mst_save\').attr(\'disabled\', false)}, 10000);"><i class="fas fa-save"></i></button>
                ';
            }

    		if($m_delete == 1){
    			$button .= '
					<button type="button" id="btn_mst_delete" class="btn btn-default btn-flat" onclick="delete_dt()"><i class="fas fa-times"></i></button>
    			';
    		}

    		$button .= '
    			</div>
    		';
    	}

    	return $button;
    }

    public function set_first_unit($r)
    {
    	$user_id = $r->session()->get('user_id');

    	$q = SysModel::set_first_unit($user_id);
    	foreach ($q as $row) {
    		$kd_unit 	= $row->kd_unit;
    		$nm_unit 	= $row->nm_unit;
    		$kd_lokasi 	= $row->kd_lokasi;
    		$nm_lokasi 	= $row->nm_lokasi;

    		$r->session()->put('kd_unit', $kd_unit);
    		$r->session()->put('nm_unit', $nm_unit);
    		$r->session()->put('kd_lokasi', $kd_lokasi);
    		$r->session()->put('nm_lokasi', $nm_lokasi);
    	}

    	return;
    }

    public function search_unit(Request $r)
    {
    	$user_id = $r->session()->get('user_id');
    	$keyword = $r->keyword;
		$controller = $r->controller;

    	$q = SysModel::search_unit_location_by_controller($user_id, $controller, $keyword);

    	return $q;
    }

    public function ganti_unit(Request $r)
    {
    	$kd_unit 	= $r->kd_unit;
    	$nm_unit 	= $r->nm_unit;
    	$kd_lokasi 	= $r->kd_lokasi;
    	$nm_lokasi 	= $r->nm_lokasi;

    	$r->session()->put('kd_unit', $kd_unit);
    	$r->session()->put('nm_unit', $nm_unit);
    	$r->session()->put('kd_lokasi', $kd_lokasi);
    	$r->session()->put('nm_lokasi', $nm_lokasi);

    	return;
    }

    public function date_db($tgl)
	{
		if(trim($tgl) != ""){
			$year   = substr($tgl, 6, 4);
			$month  = substr($tgl, 3, 2);
			$day    = substr($tgl, 0, 2);

			$new_tgl = $year.'-'.$month.'-'.$day;

			return ($new_tgl);
		}else{
			return NULL;
		}
	}

	public function date_id($tgl)
	{
		if(trim($tgl) != ""){
			$year   = substr($tgl, 0, 4);
			$month  = substr($tgl, 5, 2);
			$day    = substr($tgl, 8, 2);

			$new_tgl = $day.'/'.$month.'/'.$year;

			return ($new_tgl);
		}else{
			return NULL;
		}
	}

	public function date_ym($tgl)
	{

		if(trim($tgl) != ""){
			$year   = substr($tgl, 6, 4);
			$month  = substr($tgl, 3, 2);
			$day    = substr($tgl, 0, 2);

			$new_tgl = $year.$month;


			return ($new_tgl); // YYYYMM
		}else{
			return NULL;
		}
	}

	public function time_id($time)
	{
		if(trim($time) != ""){
			return(substr($time, 0, 5));
		}else{
			return NULL;
		}
	}

    // ganti format tanggal dd/mm/yyyy ke yyyymmdd // 103 to 112
    public function indokegabung($tgl){
        if ($tgl){
            $dt = explode("/", $tgl);
            $hari = $dt[0];
            $bln = $dt[1];
            $thn = $dt[2];
            $formattgl = $thn.$bln.$hari;
            return $formattgl;
        } else {
            //return "0000-00-00";
            return NULL;
        }
    }

    public function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }

	    return $randomString;
	}

    public function signin()
    {
    	return view('auth.signin');
    }

    public function signin_process(Request $r)
    {
    	$user_id 	= $r->user_id;
    	$password 	= md5($r->password);

    	$q = SysModel::signin_process($user_id, $password);
    	if(count($q) > 0) {
    		foreach ($q as $row) {
	    		$nama = $row->nama;

	    		$r->session()->put('user_id', $user_id);
				$r->session()->put('nama', $nama);
				$r->session()->put('login', true);

				return redirect('home');
	    	}
    	} else {
    		return redirect('signin')->withErrors(['user_id' => ' ', 'password' => 'Username / Password tidak ditemukan']);
    	}
    }

    public function signout(Request $r)
    {
    	$r->session()->put('login', false);

    	return redirect('signin');
    }

	public function get_akses_all($user_id, $controller)
	{
    	$hakAkses = SysModel::hak_akses($user_id, $controller);
    	$m_insert = '';
    	$m_edit   = '';
    	$m_save   = '';
    	$m_delete = '';

    	foreach ($hakAkses as $row) {
			$m_insert = $row->m_insert;
			$m_edit   = $row->m_save;
			$m_save   = $row->m_save;
			$m_delete = $row->m_delete;
			break;
    	}

		return [
			'm_insert' => $m_insert,
			'm_edit'   => $m_edit,
			'm_save'   => $m_save,
			'm_delete' => $m_delete
		];
	}

	public function get_button_edit($valid)
	{
		$class    = '';
		$disabled = 'disabled';

		if ($valid == 1) {
			$class    = 'btn-edit';
			$disabled = '';
		}

		return '<button type="button" class="btn btn-info btn-xs '.$class.'" '.$disabled.'>Edit</button>';
	}

	public function get_button_delete($valid)
	{
		$class    = '';
		$disabled = 'disabled';

		if ($valid == 1) {
			$class    = 'btn-delete';
			$disabled = '';
		}

		return '<button type="button" class="btn btn-info btn-xs '.$class.'" '.$disabled.'>Hapus</button>';
	}

    public function get_button_print($valid)
	{
		$class    = '';
		$disabled = 'disabled';

		if ($valid == 1) {
			$class    = 'btn-print';
			$disabled = '';
		}

		return '<button type="button" class="btn btn-info btn-xs '.$class.'" '.$disabled.'>Print</button>';
	}

	public function get_button_approval_proposed($class = '', $disabled = 'disabled', $label)
	{
		return '
		<div class="form-check">
			<label class="form-check-label">
				<input class="form-check-input '.$class.'" type="radio" name="button_approval" value="Y" checked '.$disabled.'>
				'.$label.'
			</label>
		</div>
		';
	}

	public function get_button_approval_approved($class = '', $disabled = 'disabled', $label)
	{
		return '
		<div class="form-check">
			<label class="form-check-label">
				<input class="form-check-input '.$class.'" type="radio" name="button_approval" value="Y" '.$disabled.'>
				'.$label.'
			</label>
		</div>
		';
	}

	public function get_button_approval_canceled($class = '', $disabled = 'disabled', $label)
	{
		return '
		<div class="form-check">
				<label class="form-check-label">
					<input class="form-check-input '.$class.'" type="radio" name="button_approval" value="Y" '.$disabled.'>
					'.$label.'
				</label>
			</div>
		';
	}

	public function get_button_approval($valid, $labels = [])
	{
		$classProposed = '';
		$classApproved = '';
		$classCanceled = '';
		$labelProposed = 'Ready to Approved';
		$labelApproved = 'Approved/Locked';
		$labelCanceled = 'Canceled';
		$disabled      = 'disabled';

		if ($valid == 1) {
			$classProposed = 'btn-approval-proposed';
			$classApproved = 'btn-approval-approved';
			$classCanceled = 'btn-approval-canceled';
			$disabled      = '';
		}

		if (!empty($labels)) {
			if (isset($labels['proposed'])) $labelProposed = $labels['proposed'];
			if (isset($labels['approved'])) $labelApproved = $labels['approved'];
			if (isset($labels['canceled'])) $labelCanceled = $labels['canceled'];
		}

		return [
			'button_proposed' => $this->get_button_approval_proposed($classProposed, $disabled, $labelProposed),
			'button_approved' => $this->get_button_approval_approved($classApproved, $disabled, $labelApproved),
			'button_canceled' => $this->get_button_approval_canceled($classCanceled, $disabled, $labelCanceled),
		];
	}

	public function get_button_proposed_pembatalan_fa($class = '', $disabled = 'disabled', $label)
	{
		return '
		<div class="form-check">
			<label class="form-check-label">
				<input class="form-check-input '.$class.'" type="radio" name="button_approval" value="Y" checked '.$disabled.'>
				'.$label.'
			</label>
		</div>
		';
	}

	public function get_button_proposed_headqs_pembatalan_fa($class = '', $disabled = 'disabled', $label)
	{
		return '
		<div class="form-check">
			<label class="form-check-label">
				<input class="form-check-input '.$class.'" type="radio" name="button_approval" value="Y" '.$disabled.'>
				'.$label.'
			</label>
		</div>
		';
	}

	public function get_button_pembatalan_fa($valid, $labels = [])
	{
		$classProposed       = '';
		$classProposedHeadqs = '';
		$labelProposed       = 'Siap Diajukan';
		$labelProposedHeadqs = 'Pengajuan Pembatalan';
		$disabled            = 'disabled';

		if ($valid == 1) {
			$classProposed       = 'btn-pembatalan-fa-proposed';
			$classProposedHeadqs = 'btn-pembatalan-fa-proposed-headqs';
			$disabled            = '';
		}

		if (!empty($labels)) {
			if (isset($labels['proposed'])) $labelProposed = $labels['proposed'];
			if (isset($labels['proposed_headqs'])) $labelProposedHeadqs = $labels['proposed_headqs'];
		}

		return [
			'button_proposed'        => $this->get_button_proposed_pembatalan_fa($classProposed, $disabled, $labelProposed),
			'button_proposed_headqs' => $this->get_button_proposed_headqs_pembatalan_fa($classProposedHeadqs, $disabled, $labelProposedHeadqs)
		];
	}

	public function item_jenis()
	{
        $itemJenis = PcMstItemM::get_item_jenis();

		return $itemJenis;
	}

	public function item_sumber()
	{
        $itemSumber = PcMstItemM::get_item_sumber();

		return $itemSumber;
	}

	public function item_departemen()
	{
        $itemDepartemen = PcMstItemM::get_item_departemen();

		return $itemDepartemen;
	}

	public function item_beban()
	{
        $itemBeban = PcMstItemM::get_item_beban();

		return $itemBeban;
	}

	public function trn_anggaran_urut()
	{
		$data = [
			['key' => '0', 'value' => '1'],
			['key' => '1', 'value' => '2'],
			['key' => '2', 'value' => '3'],
			['key' => '3', 'value' => '4'],
			['key' => '4', 'value' => '5'],
			['key' => '5', 'value' => '6'],
			['key' => '6', 'value' => '7'],
			['key' => '7', 'value' => '8'],
			['key' => '8', 'value' => '9'],
			['key' => '9', 'value' => '10']
		];

		return $data;
	}

	public function check_session_location(Request $r) {
		$user_id = $r->session()->get('user_id');
		$kode_unit = $r->session()->get('kd_unit');
    	$keyword = $r->keyword;
		$controller = $r->controller;
		$q = SysModel::get_kode_unit_by_controller($user_id, $controller, $keyword);
		$is_code_unit_exist = true;

		if ($q->isNotEmpty()) {
			$q = $q->map(function ($item) {
				return trim($item->unit_id);
			})->toArray();

			$is_code_unit_exist = in_array($kode_unit, $q);
		}

		return [
			'data' => $is_code_unit_exist
		];
	}

	public function bookmark_page(Request $r) {
		BookmarkService::toggle_bookmarks(
			session('user_id'),
			$r->input('controller'),
			$r->input('module_name')
		);

		return response('', 200);
	}
}
