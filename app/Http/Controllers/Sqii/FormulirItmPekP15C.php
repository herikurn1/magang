<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Redirect;
use Illuminate\Routing\Redirector;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\FormulirItmPekP15M;

ini_set('max_execution_time', 0); 
set_time_limit(0);

class FormulirItmPekP15C extends Controller
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

    	$q = FormulirItmPekP15M::data_kawasan(); 
		$kd_kawasan = '<select class="form-control col-sm-12" name="kd_kawasan" id="kd_kawasan" onchange="data_cluster()">';
		foreach ($q as $row) {
			$kd_kawasan .= '<option value="'.$row->KD_KAWASAN.'">'.$row->NM_KAWASAN.'</option>';
		}
		$kd_kawasan .= '</select>';
    	
    	$dt = array(
    		'button' 		=> $button,
    		'kd_kawasan' 	=> $kd_kawasan,
    		'data_user'		=> $data_user
    	);

    	return view('sqii.FormulirItmPekP15V')->with('dt', $dt);
    }

    public function show_data_item_defect(Request $r)
	{
		$kd_kategori    		= $r->kd_kategori;
		
		$q = FormulirItmPekP15M::show_data_item_defect($kd_kategori); 
		foreach ($q as $row) {
			$data[] = array(
				'kd_item_defect'		=> $row->KD_ITEM_DEFECT,
				'nm_item_defect'		=> $row->NM_ITEM_DEFECT,
				'kd_kategori_defect'	=> $row->KD_KATEGORI_DEFECT,
				'nm_kategori_defect'	=> $row->NM_KATEGORI_DEFECT
			);
		}

		if(isset($data)){
			return $data;
		}
	}

	public function save(Request $r)
	{
		//print_r($r);die;
		$savebtnval   	 		= $r->saveBtnVal;
		$kd_item_defect    		= $r->kd_item_defect;
		$nm_item_defect    		= $r->nm_item_defect;
		$kd_kategori_defect    	= $r->kd_kategori_defect;
		$user   	= $r->session()->get('user_id');

		if($savebtnval == 'create'){
			$q = FormulirItmPekP15M::simpan_dt($nm_item_defect,$kd_kategori_defect,$user);
		}else{
			$q = FormulirItmPekP15M::update_dt($kd_item_defect,$nm_item_defect,$user);
		}

		//return response()->json($q);
	}

	public function delete_dt(Request $r)
	{
		$kd_item_defect    = $r->kd_item_defect;
		$user   	= $r->session()->get('user_id');

		$q = FormulirItmPekP15M::delete_dt($kd_item_defect,$user);

		//return response()->json($q);
	}

	public function search_dt(Request $r)
	{

		$kd_kawasan   		= $r->kd_kawasan;
		$kd_cluster   		= $r->kd_cluster;
		$kd_tahapan   		= $r->kd_tahap;
		$user_kontraktor  	= $r->user_kontraktor;
		$isKunjungan 		= 'false';
		$jml_unit_st 		= 0;
		$q = FormulirItmPekP15M::search_dtl_temuan($kd_kawasan,$kd_cluster,$kd_tahapan,$user_kontraktor);

		foreach ($q as $row) {
			$data[] = array(
				'user_id'	=> $row->USER_ID,
				'nama'		=> $row->NAMA,
				'blok'		=> $row->BLOK,
				'nomor'		=> $row->NOMOR,
				'nm_tipe'	=> $row->NM_TIPE
			);
		}

		if(isset($data)){
			return $data;
		}
	}

	public function print_dt(Request $r)
    { 
		$kd_kawasan   		= $r->kd_kawasan;
		$kd_cluster   		= $r->kd_cluster;
		$nm_kawasan   		= $r->nm_kawasan;
		$nm_cluster   		= $r->nm_cluster;
		$user_kontraktor   	= $r->user_kontraktor;
		$kd_tahap   		= $r->kd_tahap;
		$nm_tahap   		= $r->nm_tahap;
		$nama   			= $r->nama;
		$blok   			= $r->blok;
		$nomor   			= $r->nomor;
		$nm_tipe   			= $r->nm_tipe;
		$no = 1;
		$row_tbl 	= '';

		$q1 = FormulirItmPekP15M::nama_jabatan($kd_kawasan,$kd_cluster,$blok,$nomor,$kd_jabatan='BI');
		foreach ($q1 as $row1) { $nama_bi = $row1->NAMA; $tgl = $row1->TGL; }
		$q2 = FormulirItmPekP15M::nama_jabatan($kd_kawasan,$kd_cluster,$blok,$nomor,$kd_jabatan='SM');
		foreach ($q2 as $row2) {$nama_sm = $row2->NAMA;}
		$q = FormulirItmPekP15M::cetak_formulir_p15($kd_kawasan,$kd_cluster,$kd_tahap,$user_kontraktor,$blok,$nomor);

		foreach ($q as $row) {

            if($row->FLAG_HEADER == 'H'){
                $row_tbl .= '
				<tr>
					<td style="border-left:2px double #000000;border-right:1px solid #000000; text-align:center; width:33px">'.$no++.'</td>
					<td colspan="7" rowspan="1" style="width:916px">'.$row->NM_PEKERJAAN.'</td>
					<td style="border-left:1px solid #000000;border-right:1px solid #000000;">&nbsp;</td>
					<td style="border-right:2px double #000000;">&nbsp;</td>
				</tr>				
                ';
                // $no         = 1;     
            }else{
	            $row_tbl .= '
				<tr>
					<td style="border-left:2px double #000000;border-right:1px solid #000000; width:33px">&nbsp;</td>
					<td colspan="4" rowspan="1" style="width:781px">'.$row->NM_PEKERJAAN.'</td>
					<td style="text-align:center;">'.$row->V.'</td>
					<td style="text-align:center;">'.$row->O.'</td>
					<td style="text-align:center;">'.$row->P.'</td>
					<td style="border-left:1px solid #000000;border-right:1px solid #000000;">&nbsp;</td>
					<td style="border-right:2px double #000000;">&nbsp;</td>
				</tr>
	            ';
            }
        }

        $dt = array(
    		'nm_kawasan'	=> $nm_kawasan,
    		'nm_cluster'	=> $nm_cluster,
    		'nama'			=> $nama,
    		'blok'			=> $blok,
    		'nomor'			=> $nomor,
    		'nama_bi'		=> $nama_bi,
    		'nama_sm'		=> $nama_sm,
    		'nm_tahap'		=> $nm_tahap,
    		'tgl'			=> $tgl
    	);

	   	$tbl = array(
	    		'row_tbl'		=> $row_tbl,
	    	);
		return view('sqii.print.CetakFrmItmPekP15V')
			->with('dt', $dt)
			->with('tbl', $tbl);
   }

    public function lap_defect(Request $r)
	{
			// DD($r);die;
		$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;
		$user_id_bawahan   = $r->user_id_bawahan;
		$tot_unit   = $r->tot_unit;
		$periode_1   = $this->sysController->indokegabung($r->periode_1);
		$periode_2   = $this->sysController->indokegabung($r->periode_2);
		$q = FormulirItmPekP15M::lap_defect($kd_kawasan,$kd_cluster,$user_id_bawahan,$tot_unit,$periode_1,$periode_2);
		foreach ($q as $row) {
			$data[] = array(
				'nm_kategori_defect'	=> $row->NM_KATEGORI_DEFECT, 
				'kd_kategori_defect'	=> $row->KD_KATEGORI_DEFECT,
				'sedang_f'				=> number_format($row->SEDANG, 0, '.', ''),
				'sedang'				=> $row->SEDANG,
				'b_a_f'					=> number_format($row->B_A, 2, '.', ''),
				'b_a'					=> $row->B_A,
				'berat_f'				=> number_format($row->BERAT, 0, '.', ''),
				'berat'					=> $row->BERAT,
				'c_a_f'					=> number_format($row->C_A, 2, '.', ''),
				'c_a'					=> $row->C_A,
				'tot_defect_f'			=> number_format($row->TOT_DEFECT, 0, '.', ''),
				'tot_defect'			=> $row->TOT_DEFECT,
				'd_a_f'					=> number_format($row->D_A, 2, '.', ''),
				'd_a'					=> $row->D_A
			);
		}

		if(isset($data)){
			return $data;
		}

	}

	public function lap_detail_kualitas(Request $r)
	{

		$kd_kawasan   		= $r->kd_kawasan;
		$kd_cluster   		= $r->kd_cluster;
		$user_id_bawahan   	= $r->user_id_bawahan;
		$kd_kategori_defect	= $r->kd_kategori_defect;
		$periode_1   		= $this->sysController->indokegabung($r->periode_1);
		$periode_2   		= $this->sysController->indokegabung($r->periode_2);
		$q = FormulirItmPekP15M::lap_detail_kualitas($kd_kawasan,$kd_cluster,$user_id_bawahan,$kd_kategori_defect,$periode_1,$periode_2);
		foreach ($q as $row) {
			$data[] = array(
				'blok'					=> $row->BLOK,
				'nomor'					=> $row->NOMOR,
				'no_formulir'			=> $row->NO_FORMULIR,
				'status_defect'			=> $row->STATUS_DEFECT,
				'nm_kategori_defect'	=> $row->NM_KATEGORI_DEFECT,
				'deskripsi'				=> $row->DESKRIPSI,
				'nm_item_defect'		=> $row->NM_ITEM_DEFECT,
				'nm_lantai'				=> $row->NM_LANTAI,
				'path_foto_denah'		=> $row->PATH_FOTO_DENAH,
				'src_foto_denah'		=> $row->SRC_FOTO_DENAH,
				'path_foto_defect'		=> $row->PATH_FOTO_DEFECT,
				'src_foto_defect'		=> $row->SRC_FOTO_DEFECT,
				'path_foto_perbaikan'	=> $row->PATH_FOTO_DEFECT,
				'src_foto_perbaikan'	=> $row->SRC_FOTO_DEFECT,
				'tgl_foto'				=> $row->TGL_FOTO,
				'tgl_jt_perbaikan'		=> $row->TGL_JATUH_TEMPO_PERBAIKAN,
				'tgl_selesai'			=> $row->TGL_SELESAI,
				'ageing'				=> $row->AGEING
			);
		}
		
		if(isset($data)){
			return $data;
		}
	}

	public function lap_formulir_kualitas_bangunan(Request $r)
	{

		$kd_kawasan   		= $r->kd_kawasan;
		$kd_cluster   		= $r->kd_cluster;
		$user_id_bawahan   	= $r->user_id_bawahan;
		$kd_kategori_defect	= $r->kd_kategori_defect;
		$periode_1   		= $r->periode_1;
		$periode_2   		= $r->periode_2;
		$q = FormulirItmPekP15M::lap_formulir_kualitas_bangunan($kd_kawasan,$kd_cluster,$user_id_bawahan,$kd_kategori_defect,$periode_1,$periode_2);
		foreach ($q as $row) {
			$data[] = array(
				'blok'					=> $row->BLOK,
				'nomor'					=> $row->NOMOR,
				'no_formulir'			=> $row->NO_FORMULIR,
				'status_defect'			=> $row->STATUS_DEFECT,
				'nm_kategori_defect'	=> $row->NM_KATEGORI_DEFECT,
				'deskripsi'				=> $row->DESKRIPSI,
				'nm_item_defect'		=> $row->NM_ITEM_DEFECT,
				'ageing'				=> $row->AGEING
			);
		}

		if(isset($data)){
			return $data;
		}
	}

	public function data_kontraktor(Request $r)
	{
		$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;
		$q = FormulirItmPekP15M::data_kontraktor($kd_kawasan,$kd_cluster);
		foreach ($q as $row) {
			$data[] = array(
				'user_ktt'	=> $row->USER_ID,
				'nama'	=> $row->NAMA
			);
		}

		if(isset($data)){
			return $data;
		}
	}

	public function data_tahap_pekerjaan(Request $r)
	{
		$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;
		$q = FormulirItmPekP15M::data_tahap_pekerjaan($kd_kawasan,$kd_cluster);
		foreach ($q as $row) {
			$data[] = array(
				'kd_tahap'	=> $row->KD_TAHAP,
				'nm_tahap'	=> $row->NM_TAHAP
			);
		}

		if(isset($data)){
			return $data;
		}
	}	

	public function nik_petugas(Request $r)
    {
    	$user_id 	= $r->session()->get('user_id');
    	$keyword 	= $r->keyword;
    	$kd_kawasan = $r->kd_kawasan;
    	$kd_cluster = $r->kd_cluster;

    	$q = FormulirItmPekP15M::nik_petugas($keyword,$kd_kawasan,$kd_cluster);

    	return $q;
    }

}
