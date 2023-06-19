<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Redirector;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\LapKinerjaM;

class LapDefectC extends Controller
{
	private $sysController;
	private $master;

	public function __construct()
	{
		$this->sysController = new SysController();
		$this->master = new CMaster();
	}

	public function index($kd_kawasan,$kd_cluster,$nm_kawasan,$nm_cluster,$nm_sm,$periode_1,$periode_2,$user_id,$user_id_bawahan,$nama,$jml_unit,$jml_defect,$total_defect,$tot_unit,$session_user_id,$tahap_bangun, Request $r)
	{ 

    	$button 		= $this->sysController->get_button($r);
    	$data_user 		= $this->master->get_data_user($session_user_id);
    	$periode_1l 	= $periode_1;
    	$periode_2l 	= $periode_2;
    
    	$dt = array(
    		'button' 		=> $button,
    		'data_user'		=> $data_user,
    		'kd_kawasan'	=> $kd_kawasan,
    		'kd_cluster'	=> $kd_cluster,
    		'nm_kawasan'	=> $nm_kawasan,
    		'nm_cluster'	=> $nm_cluster,
    		'nm_sm'			=> $nm_sm,
    		'periode_1'		=> $periode_1,
    		'periode_2'		=> $periode_2,
    		'user_id'		=> $user_id,
    		'user_id_bawahan'	=> $user_id_bawahan,
    		'nama'			=> $nama,
    		'jml_unit'		=> $jml_unit,
    		'jml_defect'	=> $jml_defect,
    		'total_defect'	=> $total_defect,
    		'tot_unit'		=> $tot_unit,
    		'tahap_bangun'	=> $tahap_bangun,

    	);

		$periode_1   	= $this->sysController->indokegabung(str_replace("-","/",$periode_1));
		$periode_2   	= $this->sysController->indokegabung(str_replace("-","/",$periode_2));
		$row_tbl 		= '';
		$no 			= 1;
		$tot_sedang = $tot_b_a = $tot_berat = $tot_c_a = $tot_defect = $tot_d_a = 0;
		$kd_kategori_defect = 1;

		$q = LapKinerjaM::lap_defect($kd_kawasan,$kd_cluster,$user_id_bawahan,$jml_unit,$periode_1,$periode_2,$tahap_bangun);

		foreach ($q as $row) {
            $tot_sedang 	= $tot_sedang + $row->SEDANG;
            $tot_b_a 		= $tot_b_a + $row->B_A;
            $tot_berat 		= $tot_berat + $row->BERAT;
            $tot_c_a 		= $tot_c_a + $row->C_A;
            $tot_defect 	= $tot_defect + $row->TOT_DEFECT;
            $tot_d_a 		= $tot_d_a + $row->D_A;

            $row_tbl .= '<tr onclick="lap_detail_kualitas(\''.$kd_kawasan.'\',\''.$kd_cluster.'\',\''.$nm_kawasan.'\',\''.$nm_cluster.'\',\''.$nm_sm.'\',\''.$periode_1l.'\',\''.$periode_2l.'\',\''.$user_id.'\', \''.$user_id_bawahan.'\', \''.$nama.'\', \''.$jml_unit.'\', \''.$jml_defect.'\', \''.$total_defect.'\', \''.$tot_unit.'\', \''.$row->KD_KATEGORI_DEFECT.'\', \''.$tahap_bangun.'\')"><td>'.$no++.'</td><td><div class="link_cursor">'.$row->NM_KATEGORI_DEFECT.'</div></td><td style="text-align: center;">'.number_format($row->SEDANG, 0, '.', '').'</td><td style="text-align: center;">'.number_format($row->B_A, 2, '.', '').'</td><td style="text-align: center;">'.number_format($row->BERAT, 0, '.', '').'</td><td style="text-align: center;">'.number_format($row->C_A, 2, '.', '').'</td><td style="text-align: center;font-weight: bold;">'.number_format($row->TOT_DEFECT, 0, '.', '').'</td><td style="text-align: center;font-weight: bold;">'.number_format($row->D_A, 2, '.', '').'</td></tr>';
		}

          $row_tbl .= '<tr style="font-weight: bold;"><td></td><td style="text-align: Right;">Total</td><td style="text-align: center;">'.$tot_sedang.'</td><td style="text-align: center;">'.$tot_b_a.'</td><td style="text-align: center;">'.$tot_berat.'</td><td style="text-align: center;">'.$tot_c_a.'</td><td style="text-align: center;font-weight: bold;">'.$tot_defect.'</td><td style="text-align: center;font-weight: bold;">'.$tot_d_a.'</td></tr>';

        $tbl = array(
    		'row_tbl'		=> $row_tbl,
    	);

		   	 // DD($dt);
		if(isset($row_tbl)){// LapDetailKualitasV LapDefectV2
			return view('sqii.LapDefectV2')
				->with('dt', $dt)
				->with('tbl', $tbl);
		}
	}

	public function print_dt(Request $r)
   {
		$kd_kawasan   	= $r->kd_kawasan;
		$kd_cluster   	= $r->kd_cluster;
		$nm_kawasan   	= $r->nm_kawasan;  
		$nm_cluster   	= $r->nm_cluster;
		$nm_sm   		= $r->nm_sm;
		$periode_1   	= $r->periode_1;  
		$periode_2   	= $r->periode_2;
		$user_id   		= $r->user_id;
		$user_id_bawahan = $r->user_id_bawahan;  
		$nama   			= $r->nama;
		$jml_unit   	= $r->jml_unit;
		$jml_defect   	= $r->jml_defect;  
		$total_defect  = $r->total_defect;
		$tot_unit   	= $r->tot_unit;
		$tahap_bangun  = $r->tahap_bangun;

		$periode1 = explode('-', $periode_1);
		$periode2 = explode('-', $periode_2);
		$per_exp_1 = $periode1[2].'-'.$periode1[1].'-'.$periode1[0];
		$per_exp_2 = $periode2[2].'-'.$periode2[1].'-'.$periode2[0];

    	$dt = array(
    		'kd_kawasan'	=> $kd_kawasan,
    		'kd_cluster'	=> $kd_cluster,
    		'nm_kawasan'	=> $nm_kawasan,
    		'nm_cluster'	=> $nm_cluster,
    		'nm_sm'			=> $nm_sm,
    		'periode_1'		=> $periode_1,
    		'periode_2'		=> $periode_2,
    		'user_id'		=> $user_id,
    		'user_id_bawahan'	=> $user_id_bawahan,
    		'nama'			=> $nama,
    		'jml_unit'		=> $jml_unit,
    		'jml_defect'	=> $jml_defect,
    		'total_defect'	=> $total_defect,
    		'tot_unit'		=> $tot_unit,
    	);

		$row_tbl 	= '';
		$no 			= 1;
		$tot_sedang = $tot_b_a = $tot_berat = $tot_c_a = $tot_defect = $tot_d_a = 0;
		$kd_kategori_defect = 1;

		$q = LapKinerjaM::lap_defect($kd_kawasan,$kd_cluster,$user_id_bawahan,$jml_unit,$per_exp_1,$per_exp_2,$tahap_bangun);

		foreach ($q as $row) {
         $tot_sedang 	= $tot_sedang + $row->SEDANG;
         $tot_b_a 		= $tot_b_a + $row->B_A;
         $tot_berat 		= $tot_berat + $row->BERAT;
         $tot_c_a 		= $tot_c_a + $row->C_A;
         $tot_defect 	= $tot_defect + $row->TOT_DEFECT;
         $tot_d_a 		= $tot_d_a + $row->D_A;

         $row_tbl .= '<tr><td>'.$no++.'</td><td>'.$row->NM_KATEGORI_DEFECT.'</td><td style="text-align: center;">'.number_format($row->SEDANG, 0, '.', '').'</td><td style="text-align: center;">'.number_format($row->B_A, 2, '.', '').'</td><td style="text-align: center;">'.number_format($row->BERAT, 0, '.', '').'</td><td style="text-align: center;">'.number_format($row->C_A, 2, '.', '').'</td><td style="text-align: center;font-weight: bold;">'.number_format($row->TOT_DEFECT, 0, '.', '').'</td><td style="text-align: center;font-weight: bold;">'.number_format($row->D_A, 2, '.', '').'</td></tr>';
		}

         $row_tbl .= '<tr style="font-weight: bold;"><td></td><td style="text-align: Right;">Total</td><td style="text-align: center;">'.$tot_sedang.'</td><td style="text-align: center;">'.$tot_b_a.'</td><td style="text-align: center;">'.$tot_berat.'</td><td style="text-align: center;">'.$tot_c_a.'</td><td style="text-align: center;font-weight: bold;">'.$tot_defect.'</td><td style="text-align: center;font-weight: bold;">'.$tot_d_a.'</td></tr>';

   	$tbl = array(
    		'row_tbl'		=> $row_tbl,
    	);
		return view('sqii.print.CetakLapDefectV2')
			->with('dt', $dt)
			->with('tbl', $tbl);
   }

}
