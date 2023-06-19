<?php

namespace App\Http\Controllers\Cmstrhub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Vouchermall\cSysVoucher;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Validator;
use Image;

use App\Models\Cmstrhub\m_regis;

class regis extends Controller
{
    private $sysController;
	private $master;

	public function __construct()
	{
		$this->sysController = new SysController();
		$this->master = new cSysVoucher();
	}

    public function index(Request $r)
    {
        $user_id 		= $r->session()->get('user_id');
		$kd_unit 		= $r->session()->get('kd_unit');
    	$button 		= $this->sysController->get_button($r);

    	$dt = array(
    		'button' 		=> $button,
    	);

    	return view('cmstrhub.v_Regis')->with('dt', $dt);
    }

    public function check_code(Request $r){
        $lot_number	= $r->lot_number;
		$code	    = $r->code;
        $user		= $r->session()->get('user_id');
		$kd_unit 	= $r->session()->get('kd_unit');
		$tgl 		= date('Y-m-d H:m:s');

        $q = m_regis::check_code($lot_number, $code);

        if ($q) {
            return response()->json(['code' => 200]);
        }else{
            return response()->json(['code' => 400]);
        }
    }

    public function check_tenant(Request $r){
        $lot	    = $r->lot;
		$unique	    = $r->unique;

        $q = m_regis::check_tenant($lot, $unique);

        return response()->json($q);
    }

    public function form_regis(Request $r){
        $encrypt = Crypt::decryptString($r->data);
        $exp     = explode("/", $encrypt);

        $number = $exp[0];
        $code   = $exp[1];

        $dt = array(
    		'number' => $number,
            'code'   => $code,
    	);
        return view('cmstrhub.v_Regis2')->with('dt', $dt);
    }

    public function regis(Request $r){
		$data	        = Crypt::encryptString($r->lot_number.'/'.$r->code);
    	$button 		= $this->sysController->get_button($r);

        return response($data);
    }

    public function save(Request $r){
        $validator = Validator::make($r->all(), [
            'pic'                   => 'required',
            'email'                 => 'required|email',
            'phone'                 => 'required',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
            'photo'                 => 'image:jpeg,png,jpg',
        ]);

        if($validator->fails()){
            return response()->json(['data' => $validator->errors(), 'code' => 400]);          
        }

        $user                  = $r->session()->get('user_id');
        $tgl                   = date('Y-m-d H:m:s');
        $kd_perusahaan         = $r->kd_perusahaan;
        $nama                  = $r->pic;
        $email                 = $r->email;
        $phone                 = $r->phone;
        $password              = $r->password;
        $password_confirmation = $r->password_confirmation;

        $zona       = $r->kd_zona;
        $blok       = $r->blok;
        $no_pjs     = $r->no_pjs;
        $nomor      = $r->lot_number;
        $stok_id    = $r->stok_id;
        $id_nasabah = $r->id_nasabah;

        $file     = $r->photo;
        $file_dir = 'trhub/profile';
        $fileUpload = NULL;
        if ($file != NULL) {
            $img      = Image::make($file->path())->orientate()->resize(null, 800, function ($constraint) {
                $constraint->aspectRatio();
            });
            $fileUpload = $email.'.'.$file->getClientOriginalExtension();
            //store file into document folder
            $img->save($file_dir.'/'.$fileUpload);
        }


        $check = m_regis::check_user($email);
        if (!$check) {
            m_regis::insert($nama, $email, $phone, $password, $fileUpload, $user, $tgl);
        } 

        $check_unit = m_regis::check_unit($email, $kd_perusahaan);
        if (!$check_unit) {
            m_regis::insert_unit($kd_perusahaan, $email, $tgl);
        }

        $check_zona = m_regis::check_zona($email, $kd_perusahaan, $stok_id, $id_nasabah);
        if (!$check_zona) {
            m_regis::insert_zona($kd_perusahaan, $email, $zona, $blok, $nomor, $no_pjs, $id_nasabah, $stok_id, $user, $tgl);
        }

        return response()->json(['code' => 200]);
    }

}