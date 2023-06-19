<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Redirector;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\LapRekapProgresM;

class LapDetailProgresKontraktorC extends Controller
{
	private $sysController;
	private $master;

	public function __construct()
	{
		$this->sysController = new SysController();
		$this->master = new CMaster();
	}

	// public function index($kd_kawasan,$kd_cluster,$nm_kawasan,$nm_cluster,$kd_periode,$periode2, Request $r)
	public function index($kd_kawasan,$kd_cluster,$nm_kawasan,$nm_cluster,$kd_periode,$periode2,$user_id,$nama,$session_user_id, Request $r)
	{ 
		// DD($periode2);

    	$button 		= $this->sysController->get_button($r);
    	$data_user 		= $this->master->get_data_user($session_user_id);
    
    	$dt = array(
    		'button' 		=> $button,
    		'data_user'		=> $data_user,
    		'kd_kawasan'	=> $kd_kawasan,
    		'kd_cluster'	=> $kd_cluster,
    		'nm_kawasan'	=> $nm_kawasan,
    		'nm_cluster'	=> $nm_cluster,
    		'kd_periode'	=> $kd_periode,
    		'periode'		=> $periode2,
    		'nama'			=> $nama,
    	);

		$row_tbl 		= '';
		$no 			= 1;
		$tot_progress = $tot_aktual = $tot_deviasi = 0;
		$kd_kategori_defect = 1;

		$q = LapRekapProgresM::lap_defect($kd_kawasan,$kd_cluster,$kd_periode,$user_id);

		foreach ($q as $row) {

            $row_tbl .= '
            <tr onclick="detail_aktual_progress(\''.$kd_kawasan.'\',\''.$kd_cluster.'\',\''.$nm_kawasan.'\',\''.$nm_cluster.'\',\''.$kd_periode.'\',\''.$periode2.'\',\''.$user_id.'\',\''.$nama.'\', \''.$row->BLOK.'\', \''.$row->NOMOR.'\', \''.$row->NM_TIPE.'\')">
            	<td>'.$no++.'</td>
            	<td><div class="link_cursor">'.$row->BLOK.'/'.$row->NOMOR.'</div></td>
            	<td style="text-align: center;">'.$row->NM_TIPE.'</td>
            	<td style="text-align: center;">'.number_format($row->PROGRESS, 2, '.', '').'</td>
            	<td style="text-align: center;">'.number_format($row->BOBOT_AKTUAL, 2, '.', '').'</td>
            	<td style="text-align: center;">'.number_format($row->DEVIASI, 2, '.', '').'</td>
            </tr>';

            $tot_progress = $tot_progress + $row->PROGRESS;
            $tot_aktual = $tot_aktual + $row->BOBOT_AKTUAL;
            $tot_deviasi = $tot_deviasi + $row->DEVIASI;
		}

		$no = $no - 1;
	    $tot_progress = $tot_progress / $no;
        $tot_aktual = $tot_aktual / $no;
        $tot_deviasi = $tot_deviasi / $no;
        $row_tbl .= '
        <tr>
        	<td></td>
        	<td><div class="link_cursor"></div></td>
        	<td style="text-align: center;"></td>
        	<td style="text-align: center;font-weight: bold;">'.number_format($tot_progress, 2, '.', '').'</td>
        	<td style="text-align: center;font-weight: bold;">'.number_format($tot_aktual, 2, '.', '').'</td>
        	<td style="text-align: center;font-weight: bold;">'.number_format($tot_deviasi, 2, '.', '').'</td>
        </tr>';

        $tbl = array(
    		'row_tbl'		=> $row_tbl,
    	);

		   	 // DD($dt);
		if(isset($row_tbl)){// LapDetailKualitasV LapDefectV2
			return view('sqii.LapDetailProgresKontraktorV')
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

		$q = LapRekapProgresM::lap_defect($kd_kawasan,$kd_cluster,$user_id_bawahan,$jml_unit,$per_exp_1,$per_exp_2,$tahap_bangun);

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
		return view('sqii.print.CetakLapDetailKinerjaP3515OrangV')
			->with('dt', $dt)
			->with('tbl', $tbl);
   }

}
