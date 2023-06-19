<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Redirect;
use Illuminate\Routing\Redirector;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\LapKinerjaQCP35M;

ini_set('max_execution_time', 0); 
set_time_limit(0);

class LapKinerjaQCP35C extends Controller
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

    	$q = LapKinerjaQCP35M::data_kawasan(); 
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

    	return view('sqii.LapKinerjaQCP35V')->with('dt', $dt);
    }

    public function show_data_item_defect(Request $r)
	{
		$kd_kategori    		= $r->kd_kategori;
		
		$q = LapKinerjaQCP35M::show_data_item_defect($kd_kategori); 
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
			$q = LapKinerjaQCP35M::simpan_dt($nm_item_defect,$kd_kategori_defect,$user);
		}else{
			$q = LapKinerjaQCP35M::update_dt($kd_item_defect,$nm_item_defect,$user);
		}

		//return response()->json($q);
	}

	public function delete_dt(Request $r)
	{
		$kd_item_defect    = $r->kd_item_defect;
		$user   	= $r->session()->get('user_id');

		$q = LapKinerjaQCP35M::delete_dt($kd_item_defect,$user);

		//return response()->json($q);
	}

	public function search_dt(Request $r)
	{

		$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;
		$nik_petugas   = $r->nik_petugas;
		$periode_1   = $this->sysController->indokegabung($r->periode_1);
		$periode_2   = $this->sysController->indokegabung($r->periode_2);
		$tahap_bangun   = $r->tahap_bangun;
		$isKunjungan = 'false';
		$jml_unit_st = 0;
		$q = LapKinerjaQCP35M::search_dt($kd_kawasan,$kd_cluster,$nik_petugas,$periode_1,$periode_2,$tahap_bangun);

		foreach ($q as $row) {
			$sum_f = 0;
			$data[] = array(
				'user_id'			=> $row->USER_ID,
				'user_id_bawahan'	=> $row->USER_ID_BAWAHAN,
				'nama'				=> $row->NAMA,
				'nm_jabatan'		=> $row->NM_JABATAN,
				'jml_unit'			=> $row->JML_UNIT,
				'jml_tdk_sesuai'	=> $row->JML_TIDAK_SESUAI,
				'nilai_tdk_sesuai'	=> number_format($row->NILAI_TIDAK_SESUAI, 2, '.', '')
			);
		}

		if(isset($data)){
			return $data;
		}
	}

	public function print_dt(Request $r)
    {
		$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;
		$nm_kawasan   = $r->nm_kawasan;
		$nm_cluster   = $r->nm_cluster;
		$nik_petugas   = $r->nik_petugas;
		$nm_sm   = $r->nm_sm;
		$periode_1   = $this->sysController->indokegabung($r->periode_1);
		$periode_2   = $this->sysController->indokegabung($r->periode_2);
		$tahap_bangun   = $r->tahap_bangun;
		$q = LapKinerjaQCP35M::search_dt($kd_kawasan,$kd_cluster,$nik_petugas,$periode_1,$periode_2,$tahap_bangun);
		$no = 1;
		$tot_unit = 0;	
		$tot_defect = 0;
		$tot_c = $tot_a1 = $tot_a2 = $tot_a3 = 0;
		$row_tbl 	= '';
		foreach ($q as $row) {

			$q_formula = LapKinerjaQCP35M::lap_cycle_time_v2($kd_kawasan,$kd_cluster,$row->USER_ID_BAWAHAN,$row->JML_UNIT,$periode_1,$periode_2,'2',$tahap_bangun);
			$sum_c = 0;
			$sum_f = 0;
			$sum_e = 0;
			$n 	   = 1;
			foreach ($q_formula as $row_formula) {
				$sum_c = $sum_c + $row_formula->JML_HR;
				if($row_formula->SISA_STOK > 0){
					$c = 0;
					$d = 0;
					$e = 0;
				}else{
					$c = $row_formula->JML_HR;
					$d = $sum_c / $row_formula->CYCLE_COUNT;
					$e = $row->JML_UNIT / $c; 
				}
				$sum_e = ($sum_e + $e);
				$sum_f = ($sum_e)/$n;

				$n++;
			}

            $tot_unit = $tot_unit + $row->JML_UNIT;
            $tot_defect = $tot_defect + $row->JML_DEFECT;
            $tot_c = $tot_c + $row->TOTAL_DEFECT;
            $tot_a1 = $tot_a1 + $row->A1;
            $tot_a2 = $tot_a2 + $row->A2;
            $tot_a3 = $tot_a3 + $row->A3;

            $row_tbl .= '<tr style=""><td>'.$no++.'</td><td>'.$row->NAMA.'</td><td style="text-align: center;">'.$row->JML_UNIT.'</td><td style="text-align: center;">'.$row->JML_DEFECT.'</td><td style="text-align: center;">'.number_format($row->TOTAL_DEFECT, 2, '.', '').'</td><td style="text-align: center;">'.$row->A1.'</td><td style="text-align: center;">'.$row->A2.'</td><td style="text-align: center;">'.$row->A3.'</td><td style="text-align: center;">'.number_format($sum_f, 2, '.', '').'</td></tr>';
		}

        $row_tbl .= '<tr style="font-weight: bold;"><td></td><td style="text-align: Right;">Total</td><td style="text-align: center;">'.$tot_unit.'</td><td style="text-align: center;">'.$tot_defect.'</td><td style="text-align: center;">'.$tot_c.'</td><td style="text-align: center;">'.$tot_a1.'</td><td style="text-align: center;">'.$tot_a2.'</td><td style="text-align: center;">'.$tot_a3.'</td><td style="text-align: center;"></td></tr><tr><td></td><td></td><td style="text-align: center; font-weight: bold;">(a)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';

        $dt = array(
    		'kd_kawasan'	=> $kd_kawasan,
    		'nm_cluster'	=> $nm_cluster,
    		'nm_sm'	=> $nm_sm,
    		'periode_1'	=> $r->periode_1,
    		'periode_2'	=> $r->periode_2
    	);

	   	$tbl = array(
	    		'row_tbl'		=> $row_tbl,
	    	);
		return view('sqii.print.CetakLapKinerjaV')
			->with('dt', $dt)
			->with('tbl', $tbl);
   }

	public function nik_petugas(Request $r)
    {
    	$user_id 	= $r->session()->get('user_id');
    	$keyword 	= $r->keyword;
    	$kd_kawasan = $r->kd_kawasan;
    	$kd_cluster = $r->kd_cluster;

    	$q = LapKinerjaQCP35M::nik_petugas($keyword,$kd_kawasan,$kd_cluster);

    	return $q;
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
		$q = LapKinerjaQCP35M::lap_defect($kd_kawasan,$kd_cluster,$user_id_bawahan,$tot_unit,$periode_1,$periode_2);
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
		$q = LapKinerjaQCP35M::lap_detail_kualitas($kd_kawasan,$kd_cluster,$user_id_bawahan,$kd_kategori_defect,$periode_1,$periode_2);
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
		$q = LapKinerjaQCP35M::lap_formulir_kualitas_bangunan($kd_kawasan,$kd_cluster,$user_id_bawahan,$kd_kategori_defect,$periode_1,$periode_2);
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

	public function data_tahap(Request $r)
	{
		$kd_kawasan   = $r->kd_kawasan;
		$kd_cluster   = $r->kd_cluster;
		$q = LapKinerjaQCP35M::data_tahap($kd_kawasan,$kd_cluster);
		foreach ($q as $row) {
			$data[] = array(
				'tahap_bangun'	=> $row->TAHAP_BANGUN
			);
		}

		if(isset($data)){
			return $data;
		}
	}

}
