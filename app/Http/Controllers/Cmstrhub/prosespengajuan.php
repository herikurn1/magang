<?php

namespace App\Http\Controllers\Cmstrhub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Vouchermall\cSysVoucher;
use Image;
use Validator;
use App\Models\Cmstrhub\m_prosespengajuan;
use Illuminate\Support\Facades\Mail;

class prosespengajuan extends Controller
{
    private $sysController;
	private $master;

	public function __construct(){
		$this->sysController = new SysController();
		$this->master = new cSysVoucher();
	}

    public function index(Request $r){
        $user_id 		= $r->session()->get('user_id');
		$kd_unit 		= $r->session()->get('kd_unit');
    	$button 		= $this->sysController->get_button($r);

    	$dt = array(
    		'button' 		=> $button,
    	);

    	return view('cmstrhub.v_Prosespengajuan')->with('dt', $dt);
    }

    public function get_data(Request $r){
        $kd_unit 		= $r->session()->get('kd_unit');
        $title_prog     = $r->title_prog;
        $kd_layanan     = $r->kd_layanan;
        $kd_tujuan      = $r->kd_tujuan;
        $kd_jenis       = $r->kd_jenis;

        $q = m_prosespengajuan::get_data($kd_unit, $title_prog, $kd_layanan, $kd_tujuan, $kd_jenis);

        return response()->json(['data' => $q]);
    }

    public function get_dtl_data(Request $r){
        $kd_unit 		= $r->session()->get('kd_unit');
        $no_dokumen     = $r->no_dokumen;

        $q = m_prosespengajuan::get_dtl_data($kd_unit, $no_dokumen);

        return response()->json($q);
    }

    public function get_layanan(Request $r){
        $q = m_prosespengajuan::layanan();

        return $q;
    }

    public function get_layanan_dtl(Request $r){
        $kd_unit 		= $r->session()->get('kd_unit');
        $kd_layanan     = $r->kd_layanan;

        $q = m_prosespengajuan::layanan_dtl($kd_unit, $kd_layanan);

        return $q;
    }

    public function get_layanan_item(Request $r){
        $kd_unit 		= $r->session()->get('kd_unit');
        $kd_layanan     = $r->kd_layanan;
        $kd_tujuan      = $r->kd_tujuan;

        $q = m_prosespengajuan::layanan_item($kd_unit, $kd_layanan, $kd_tujuan);

        return $q;
    }

    public function get_status(Request $r){
        $q = m_prosespengajuan::status();

        return $q;
    }

    public function confirm_data(Request $r){

        $kd_unit 		= $r->kd_unit;
		$no_dokumen 	= $r->no_dokumen;
		$kd_layanan 	= $r->kd_layanan;
        $kd_tujuan      = $r->kd_tujuan;
        $kd_jenis       = $r->kd_jenis;
        $user  			= $r->session()->get('user_id');
		$tgl 			= date('Y-m-d H:m:s');

       $q = m_prosespengajuan::confirm_data($kd_unit,$no_dokumen,$kd_layanan,$kd_tujuan,$kd_jenis,$user,$tgl);

		return $tgl;
    }

    public function void_data(Request $r){

        $kd_unit 		= $r->kd_unit;
		$no_dokumen 	= $r->no_dokumen;
		$kd_layanan 	= $r->kd_layanan;
        $kd_tujuan      = $r->kd_tujuan;
        $kd_jenis       = $r->kd_jenis;
        $catatan        = $r->catatan;
        $user  			= $r->session()->get('user_id');
		$tgl 			= date('Y-m-d H:m:s');

        $q = m_prosespengajuan::void_data($kd_unit,$no_dokumen,$kd_layanan,$kd_tujuan,$kd_jenis,$catatan,$user,$tgl);

		return $q;
    }

    public function progress_data(Request $r){
        $kd_unit 		= $r->kd_unit;
		$no_dokumen 	= $r->no_dokumen;
		$kd_layanan 	= $r->kd_layanan;
        $kd_tujuan      = $r->kd_tujuan;
        $kd_jenis       = $r->kd_jenis;
        $status1        = $r->status_progress;

        $arr           = explode("|", $status1);
        $status        = $arr[0];
        $title         = $arr[1];
        $catatan       = $r->catatan_progress;
        $tgl_progress  = $r->date_com;
        $user          = $r->session()->get('user_id');
        $tgl           = date('Y-m-d H:m:s');
        $tgl_img       = date('Ymd_Hms');
        $file          = $r->file('file');
        $fileName      = null;
        $fileName_done = null;

        if ($kd_layanan == 'L0001') {
            $file_dir = 'trhub/keluhan/';
        }else if($kd_layanan == 'L0002'){
            $file_dir = 'trhub/surat-ijin/';
        }else{
            $file_dir = 'trhub/id-karyawan/';
        };
        
        if ($status == "F") {
            if($r->hasFile('file')){
                $img        = Image::make($file->path())->orientate()->resize(null, 800, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $fileUpload       = $user.'_'.uniqid().'.'.$file->getClientOriginalExtension();
                if($file->isValid()) {   
                                 
                    if (!file_exists($file_dir )) {
                        mkdir($file_dir , 0777, true);
                    }
                    //$target_path 	= $file_dir;
                    $img->save($file_dir.'/'.$fileUpload);
                    //$file->move(public_path($target_path), $fileUpload);
                }
                $fileName_done       = $fileUpload;
            }
            $q = m_prosespengajuan::progress_done($kd_unit,$no_dokumen,$kd_layanan,$kd_tujuan,$kd_jenis,$status,$title,$tgl_progress,$catatan,$fileName_done,$user,$tgl);
        }else{
            if($r->hasFile('file')){
                $img        = Image::make($file->path())->orientate()->resize(null, 800, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $fileUpload       = $user.'_'.uniqid().'.'.$file->getClientOriginalExtension();
                if($file->isValid()) {   
                                 
                    if (!file_exists($file_dir )) {
                        mkdir($file_dir , 0777, true);
                    }
                    //$target_path 	= $file_dir;
                    $img->save($file_dir.'/'.$fileUpload);
                    //$file->move(public_path($target_path), $fileUpload);
                }
                $fileName       = $fileUpload;
            }
            $q = m_prosespengajuan::progress_data($kd_unit,$no_dokumen,$kd_layanan,$kd_tujuan,$kd_jenis,$status,$title,$tgl_progress,$catatan,$fileName,$user,$tgl);
        }
	
        return response($q); 
    }

    public function history_data(Request $r){
        $no_dokumen     = $r->no_dokumen;

        $q = m_prosespengajuan::history_data($no_dokumen);

        return response()->json($q);
    }

    public function insert_idcard(Request $r){
        $kd_unit       = $r->kd_unit;
        $no_dokumen    = $r->no_dokumen;
        $kd_layanan    = $r->kd_layanan;
        $kd_tujuan     = $r->kd_tujuan;
        $kd_jenis      = $r->kd_jenis;
        $tgl_progress  = $r->tgl_progress;
        $catatan       = $r->status_idcard;
        $periode       = $r->dt_periode;
        $user          = $r->session()->get('user_id');
        $tgl           = date('Y-m-d H:m:s');
        $fileName      = $r->file_idcard;
        $mail_to       = $r->mail_idcard;
	    
        $mail = array( 
            'TO'			=> $mail_to,
            'NO_DOKUMEN'    => $no_dokumen,
            'UNIT'			=> $kd_unit,
            'LOCAL'         => 'galang_pratama@summarecon.com',
        );

        $files = public_path('trhub/id-karyawan/idcard/'.$fileName);
        
        Mail::send('cmstrhub.email',['data'=> $mail], function($message) use ($mail, $files){
            $message->to($mail['TO'])
                    ->bcc($mail['LOCAL'])
                    ->from('cstrmobile@summarecon.com', 'TR HUB MALL')
                    ->subject('TR Hub Mall - ID Card Karyawan')
                    ->attach($files);

           // foreach ($files as $file){
                //$message->attach($files);
            //}
        });

        $q = m_prosespengajuan::insert_idcard($kd_unit,$no_dokumen,$kd_layanan,$kd_tujuan,$kd_jenis,$catatan,$periode,$tgl_progress,$fileName,$user,$tgl);
        return response()->json([
            'code'        => '200',
            'msg'         => 'Data berhasil diupdate.',
        ]);
    }

    public function resend_mail(Request $r){
        $kd_unit       = $r->kd_unit;
        $no_dokumen    = $r->no_dokumen;
        $mail_to       = $r->email;

        $dt = m_prosespengajuan::get_idcard($kd_unit, $no_dokumen);
        foreach ($dt as $row) {
            $kode = $row->KODE_KARYAWAN;
        }

        $mail = array( 
            'TO'			=> $mail_to,
            'NO_DOKUMEN'    => $no_dokumen,
            'UNIT'			=> $kd_unit,
            'LOCAL'         => 'galang_pratama@summarecon.com',
        );

        $files = public_path('trhub/id-karyawan/idcard/'.$kode.'.png');
        
        Mail::send('cmstrhub.email',['data'=> $mail], function($message) use ($mail, $files){
            $message->to($mail['TO'])
                    ->bcc($mail['LOCAL'])
                    ->from('cstrmobile@summarecon.com', 'TR HUB MALL')
                    ->subject('TR Hub Mall - ID Card Karyawan')
                    ->attach($files);
        });

        return response()->json([
            'code'        => '200',
            'msg'         => 'Email Berhasil dikirim',
        ]);
    }

    //function upload foto from APi
    public function upload_surat_ijin(Request $r){
        $validator = Validator::make($r->all(), [
			'user' => 'required',
			'file' => 'required|image:jpeg,png,jpg,gif,svg',
        ]);
   
        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $user     = $r->user;
        $tgl_img  = date('YmdHms');
        $file     = $r->file;
        $file_dir = 'trhub/surat-ijin';

        $img        = Image::make($file->path())->orientate()->resize(null, 800, function ($constraint) {
            $constraint->aspectRatio();
        });
        $fileUpload = $user.'_'.uniqid().'.'.$file->getClientOriginalExtension();
        //store file into document folder
        $img->save($file_dir.'/'.$fileUpload);
		//$filename     = $file->move(public_path($file_dir), $fileUpload);

        return response()->json($fileUpload);
    }

    public function upload_keluhan(Request $r){
        $validator = Validator::make($r->all(), [
			'user' => 'required',
			'file' => 'required|image:jpeg,png,jpg,gif,svg',
        ]);
   
        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $user     = $r->user;
        $tgl_img  = date('YmdHms');
        $file     = $r->file;
        $file_dir = 'trhub/keluhan';

        $img        = Image::make($file->path())->orientate()->resize(null, 800, function ($constraint) {
            $constraint->aspectRatio();
        });
        $fileUpload = $user.'_'.uniqid().'.'.$file->getClientOriginalExtension();
        //store file into document folder
        $img->save($file_dir.'/'.$fileUpload);
		//$filename = $img->move(public_path($file_dir), $fileUpload);

        return response()->json($fileUpload);
    }

    public function upload_sales_today(Request $r){
        $validator = Validator::make($r->all(), [
			'user' => 'required',
			'file' => 'required|image:jpeg,png,jpg,gif,svg',
        ]);
   
        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $user     = $r->user;
        $tgl_img  = date('YmdHms');
        $file     = $r->file;
        $file_dir = 'trhub/sales-today';

        $img        = Image::make($file->path())->orientate()->resize(null, 800, function ($constraint) {
            $constraint->aspectRatio();
        });
        $fileUpload       = $user.'_'.uniqid().'.'.$file->getClientOriginalExtension();
        //store file into document folder
        $img->save($file_dir.'/'.$fileUpload);
		//$filename     = $file->move(public_path($file_dir), $fileUpload);

        return response()->json($fileUpload);
    }

    public function upload_ik(Request $r){
        $validator = Validator::make($r->all(), [
			'user' => 'required',
			'file' => 'required|image:jpeg,png,jpg,gif,svg',
        ]);
   
        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $user     = $r->user;
        $tgl_img  = date('YmdHms');
        $file     = $r->file;
        $file_dir = 'trhub/id-karyawan';

        $img        = Image::make($file->path())->orientate()->resize(null, 800, function ($constraint) {
            $constraint->aspectRatio();
        });
        $fileUpload = $user.'_'.uniqid().'.'.$file->getClientOriginalExtension();
        //store file into document folder
		$img->save($file_dir.'/'.$fileUpload);
        //$filename     = $file->move(public_path($file_dir), $fileUpload);

        return response()->json($fileUpload);
    }

    public function upload_profile(Request $r){
        $validator = Validator::make($r->all(), [
			'user' => 'required',
			'file' => 'required|image:jpeg,png,jpg,gif,svg',
        ]);
   
        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $user     = $r->user;
        $tgl_img  = date('YmdHms');
        $file     = $r->file;
        $file_dir = 'trhub/profile';

        $img        = Image::make($file->path())->orientate()->resize(null, 800, function ($constraint) {
            $constraint->aspectRatio();
        });
        $fileUpload = $user.'.'.$file->getClientOriginalExtension();
        //store file into document folder
		$img->save($file_dir.'/'.$fileUpload);
        //$filename     = $file->move(public_path($file_dir), $fileUpload);

        return response()->json($fileUpload);
    }

    //ID Karyawan
    public function generate_idcard(Request $r){
        $kd_unit    = $r->kd_unit;
        $no_dokumen = $r->no_dokumen;
        $kd_layanan = $r->kd_layanan;
        $kd_tujuan  = $r->kd_tujuan;
        $kd_jenis   = $r->kd_jenis;
        $tipe_card  = $r->tipe_card;
        $kd_user    = $r->kd_user;
        $dt_periode = $r->dt_periode;
        $user       = $r->session()->get('user_id');
        $tgl        = date('Y-m-d H:m:s');

        $cek = m_prosespengajuan::cek_idcard($kd_unit, $no_dokumen);

        if ($cek) {
            $dt = m_prosespengajuan::get_idcard($kd_unit, $no_dokumen);
            foreach ($dt as $row) {
                $kode = $row->KODE_KARYAWAN;
            }

            return response()->json([
                'code'        => '500',
                'msg'         => 'ID Card berhasil dibuat.',
                'kd_karyawan' => $kode,
            ]);
        } else {
            $kd_karyawan = m_prosespengajuan::generate_idcard($kd_unit, $no_dokumen, $kd_layanan, $kd_tujuan, $kd_jenis, $tipe_card, $kd_user, $dt_periode, $user, $tgl);
            $get_data = m_prosespengajuan::get_dtl_data($kd_unit, $no_dokumen);

            foreach ($get_data as $row) {
                $nm_karyawan = $row->NM_KARYAWAN;
                $periode = $row->NM_PERIODE;
                $tahun = $row->TAHUN;
                $foto = $row->FOTO_DIRI;
            }
            
            //Background
            $img = Image::canvas(400, 600, '#ffffff');
            
            //Header
            $head = Image::make(public_path('trhub/image/header.png'));
            $img->insert($head, 'top', 400, 0);

            //Logo Mall
            $logo = Image::make(public_path('trhub/id-karyawan/idcard/smkg.png'));
            $logo->resize(150, 75);
            $img->insert($logo, 'top', 30, 30);

            //Text Header
            $img->text('KARTU IDENTITAS KARYAWAN TENANT', 50, 150, function($font) {
                $font->file(public_path('trhub/image/font/Poppins-Medium.ttf'));
                $font->color('#000');
                $font->size(15);
            });

            //Foto Karyawan
            $foto = Image::make(public_path('trhub/id-karyawan/'.$foto));
            $foto->resize(150, 200);
            $img->insert($foto, 'center', 0, -30);

            //List Nama
            $img->rectangle(0, 375, 400, 376, function ($draw) {
                $draw->background('#02ca13');
            });

            $img->rectangle(0, 445, 400, 446, function ($draw) {
                $draw->background('#02ca13');
            });
            // $bg = Image::canvas(400, 100, '#ccccb3');
            // $img->insert($bg, 'center', 0, 0);

            //Nama Karyawan
            $img->text('NAMA    :      '.$nm_karyawan, 10, 400, function($font) {
                $font->file(public_path('trhub/image/font/Poppins-Bold.ttf'));
                $font->color('#000');
                //$font->align('center');
                //$font->valign('middle');
                $font->size(16);
            });

            //Periode
            $img->text('Periode  :    '.$periode.' '.$tahun, 10, 430, function($font) {
                $font->file(public_path('trhub/image/font/Poppins-Medium.ttf'));
                $font->color('#000');
                $font->size(14);
            });

            //Footer
            $bott = Image::make(public_path('trhub/image/footer.png'));
            $img->insert($bott, 'bottom', 10, -5);

            //List QR Code
            $list = Image::make(public_path('trhub/image/listqr.png'));
            $list->resize(95, 95);
            $img->insert($list, 'bottom', 30, 47);

            //QR Code
            $qrcode = Image::make('https://api.qrserver.com/v1/create-qr-code/?size=512x512&data='.$kd_karyawan);
            ///$qrcode = Image::make(public_path('trhub/id-karyawan/qrcode/TRHUB202212001.svg'));
            $qrcode->resize(80, 80);
            $img->insert($qrcode, 'bottom', 30, 54);          


            $img->save(public_path('trhub/id-karyawan/idcard/'.$kd_karyawan.'.png'));

            return response()->json([
                'code'        => '200',
                'msg'         => 'ID Card berhasil dibuat.',
                'kd_karyawan' => $kd_karyawan,
            ]);
        }
    }
    
    public function idcard(Request $r){
        // $img  = Image::make('#ffffff');
        // $img->resize(400, 600);

        //Background
        $img = Image::canvas(400, 600, '#000000');
        
        //Header
        $head = Image::make(public_path('trhub/image/bg-idcard.jpg'));
        $head->resize(400, 600);
        $img->insert($head, 'center', 0, 0);

        //Logo Mall
        // $logo = Image::make(public_path('trhub/id-karyawan/idcard/smkg.png'));
        // $logo->resize(150, 75);
        // $img->insert($logo, 'top', 30, 30);

        //Text Header
        // $img->text('KARTU IDENTITAS KARYAWAN TENANT', 50, 150, function($font) {
        //     $font->file(public_path('trhub/image/font/Poppins-Medium.ttf'));
        //     $font->color('#000');
        //     $font->size(15);
        // });

        //Foto Karyawan
        $foto = Image::make(public_path('trhub/id-karyawan/201005469_63a184352a3b5.jpg'));
        $foto->resize(120, 150);
        $img->insert($foto, 'center', 0, -40);

        //List Nama
        // $img->rectangle(0, 375, 400, 376, function ($draw) {
        //     $draw->background('#02ca13');
        // });

        // $img->rectangle(0, 445, 400, 446, function ($draw) {
        //     $draw->background('#02ca13');
        // });
        // $bg = Image::canvas(400, 100, '#ccccb3');
        // $img->insert($bg, 'center', 0, 0);

        //Nama Karyawan
        $img->text('GIANDRA AL HANIF', 120, 375, function($font) {
            $font->file(public_path('trhub/image/font/Poppins-Bold.ttf'));
            $font->color('#000');
            //$font->align('center');
            //$font->valign('middle');
            $font->size(16);
        });

        //Periode
        // $img->text('Periode  :    Jan - Mar 2022', 10, 430, function($font) {
        //     $font->file(public_path('trhub/image/font/Poppins-Medium.ttf'));
        //     $font->color('#000');
        //     $font->size(14);
        // });

        //Footer
        // $bott = Image::make(public_path('trhub/image/footer.png'));
        // $img->insert($bott, 'bottom', 10, -5);

        //List QR Code
        // $list = Image::make(public_path('trhub/image/listqr.png'));
        // $list->resize(95, 95);
        // $img->insert($list, 'bottom', 30, 47);

        //QR Code
        $qrcode = Image::make('https://api.qrserver.com/v1/create-qr-code/?size=512x512&data=TRHUB2022120008');
        ///$qrcode = Image::make(public_path('trhub/id-karyawan/qrcode/TRHUB202212001.svg'));
        $qrcode->resize(90, 90);
        $img->insert($qrcode, 'bottom', 30, 70);       


        //$img->save(public_path('trhub/id-karyawan/qrcode/TRHUB2022120008.png'));
        return $img->response("png");
    }

    public function get_tipe_idkaryawan(Request $r){
        $q = m_prosespengajuan::tipe_idkaryawan();

        return $q;
    }

    //Firebase
    public function get_token_confirm(Request $r){
        $kd_unit    = $r->session()->get('kd_unit');
        $user_id    = $r->user_id;
        $no_dokumen = $r->no_dokumen;

        $data = m_prosespengajuan::get_dtl_data($kd_unit, $no_dokumen);
        $token = m_prosespengajuan::get_token_device($user_id);
        $key = m_prosespengajuan::get_key_firebase();

        return response()->json([
            'token' => $token,
            'data'  => $data,
            'key'   => $key,
        ]); 
    }

    public function get_token_void(Request $r){
        $kd_unit    = $r->session()->get('kd_unit');
        $user_id    = $r->user_id;
        $no_dokumen = $r->no_dokumen;

        $data = m_prosespengajuan::get_dtl_data($kd_unit, $no_dokumen);
        $token = m_prosespengajuan::get_token_device($user_id);
        $key = m_prosespengajuan::get_key_firebase();

        return response()->json([
            'token' => $token,
            'data'  => $data,
            'key'   => $key,
        ]); 
    }

    public function get_token_device(Request $r){
        $kd_unit    = $r->session()->get('kd_unit');
        $user_id    = $r->user_id;
        $no_dokumen = $r->no_dokumen;

        $data = m_prosespengajuan::get_dtl_data($kd_unit, $no_dokumen);
        $token = m_prosespengajuan::get_token_device($user_id);
        $key = m_prosespengajuan::get_key_firebase();

        return response()->json([
            'token' => $token,
            'data'  => $data,
            'key'   => $key,
        ]); 
    }
}
