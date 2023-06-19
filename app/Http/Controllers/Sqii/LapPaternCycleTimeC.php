<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Redirector;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\LapKinerjaM;

class LapPaternCycleTimeC extends Controller
{
	private $sysController;
	private $master;

	public function __construct()
	{
		$this->sysController = new SysController();
		$this->master = new CMaster();
	}

	public function index($kd_kawasan,$kd_cluster,$nm_kawasan,$nm_clusterx,$nm_sm,$periode_1x,$periode_2x,$user_id,$user_id_bawahan,$nama,$jml_unit,$jml_defect,$total_defect,$tot_unit,$session_user_id,$tahap_bangun, Request $r)
	{ 

    	$button 		= $this->sysController->get_button($r);
    	$data_user 		= $this->master->get_data_user($session_user_id);
    
    	$dt = array(
    		'button' 		=> $button,
    		'data_user'		=> $data_user,
    		'nm_cluster'	=> $nm_clusterx,
    		'nama'			=> $nama,
    		'jml_unit'		=> $jml_unit,
            'periode_1'     => $periode_1x,
            'periode_2'     => $periode_2x,
            'kd_kawasan'     => $kd_kawasan,
            'kd_cluster'     => $kd_cluster,
            'user_id_bawahan' => $user_id_bawahan,
            'tot_unit' => $tot_unit,
            'tahap_bangun' => $tahap_bangun,
    	);

		$periode_1   	= $this->sysController->indokegabung(str_replace("-","/",$periode_1x));
		$periode_2   	= $this->sysController->indokegabung(str_replace("-","/",$periode_2x));
		$row_tbl 		= $row_tbl_h = $row_tbl_d = '';
		$no 			= 1;
		$first 			= 'x';

        $qc = LapKinerjaM::lap_cycle_time_col_v2($kd_kawasan,$kd_cluster,$user_id_bawahan,$tahap_bangun);

		foreach ($qc as $rowc) {
            $row_tbl_h .= '<th style="text-align: center;">'.$rowc->KOLOM.'</th>';
		}

		$q = LapKinerjaM::lap_cycle_time_v2($kd_kawasan,$kd_cluster,$user_id_bawahan,$tot_unit,$periode_1,$periode_2,'3',$tahap_bangun);

		foreach ($q as $row) {
			$data_arr = explode("|",$row->DATA);
			$max = count($data_arr);

			// kolom patern
			if($first == 'x'){
				$row_tbl_d .= '<tr><td style="text-align: center;" rowspan="'.$data_arr[$max-1].'">'.$data_arr[$max-2].'</td>';
				$cycle_old = $data_arr[$max-2];
				$first = 'y';
			}else{
				if($cycle_old == $data_arr[$max-2]){
					//
				}else{
					$row_tbl_d .= '<tr><td style="text-align: center;" rowspan="'.$data_arr[$max-1].'">'.$data_arr[$max-2].'</td>';
					$cycle_old = $data_arr[$max-2];
				}
			}
            //$row_tbl_d .= '<tr><td style="text-align: center;">'.$data_arr[$max-2].'</td>';
            // data tgl kunjungan
            $row_tbl_d .= '<td style="text-align: center;">'.$row->TGL_KUNJUNGAN.'</td>';
			
			// split data unit dari kolom jadi row
			for ($x = 0; $x < $max-2; $x++) {
				if($data_arr[$x] == 1){
					$chek = '<i class="fas fa-check"></i>';
				}elseif($data_arr[$x] == 2){
					$chek = '<i class="fa fa-check-circle"></i>';
				}else{
					$chek = '';
				}
				$row_tbl_d .= '<td style="text-align: center;">'.$chek.'</td>';
			}

            $row_tbl_d .= '</tr>';
		}

        $row_tbl .= '<tr><th style="text-align: center;">Patern</th>';
        $row_tbl .= '<th style="text-align: center;">Tgl Kunjungan</th>';
		$row_tbl .= $row_tbl_h;
		$row_tbl .= '</tr>';
		$row_tbl .= $row_tbl_d;

        $tbl = array(
    		'row_tbl'		=> $row_tbl,
    	);

		if(isset($row_tbl)){
			return view('sqii.LapPaternCycleTimeV')
				->with('dt', $dt)
				->with('tbl', $tbl);
		}

	}   

	public function bulan_indo($bulan){
		switch($bulan){      
			case 1 : {	$bulan = 'Januari'; 	}break;
			case 2 : {	$bulan = 'Februari';	}break;
			case 3 : {	$bulan = 'Maret';		}break;
			case 4 : {	$bulan = 'April';		}break;
			case 5 : {	$bulan = 'Mei';			}break;
			case 6 : {	$bulan = "Juni";		}break;
			case 7 : {	$bulan = 'Juli';		}break;
			case 8 : {	$bulan = 'Agustus';		}break;
			case 9 : {	$bulan = 'September';	}break;
			case 10 : {	$bulan = 'Oktober';		}break;    
			case 11 : {	$bulan = 'November';	}break;
			case 12 : {	$bulan = 'Desember';	}break;
			default: {	$bulan = 'UnKnown';		}break;
			}

		return $bulan;		
	}

	public function print_dt(Request $r)
   {
		$kd_kawasan   	= $r->kd_kawasan;
		$kd_cluster   	= $r->kd_cluster;
		$nm_cluster   	= $r->nm_cluster;
		$periode_1   	= $r->periode_1;  
		$periode_2   	= $r->periode_2;
		$user_id_bawahan = $r->user_id_bawahan;  
		$nama   			= $r->nama;
		$jml_unit   	= $r->jml_unit;
		$tot_unit   	= $r->tot_unit;
		$tahap_bangun   = $r->tahap_bangun;

   		$dt = array(
    		'nm_cluster'	=> $nm_cluster,
    		'nama'			=> $nama,
    		'jml_unit'		=> $jml_unit,
            'periode_1'     => $periode_1,
            'periode_2'     => $periode_2,
    	);

		$periode_1   	= $this->sysController->indokegabung(str_replace("-","/",$periode_1));
		$periode_2   	= $this->sysController->indokegabung(str_replace("-","/",$periode_2));
		$row_tbl 		= $row_tbl_h = $row_tbl_d = '';
		$no 			= 1;
		$first 			= 'x';

        $qc = LapKinerjaM::lap_cycle_time_col_v2($kd_kawasan,$kd_cluster,$user_id_bawahan,$tahap_bangun);

		foreach ($qc as $rowc) {
            $row_tbl_h .= '<th style="text-align: center;">'.$rowc->KOLOM.'</th>';
		}

		$q = LapKinerjaM::lap_cycle_time_v2($kd_kawasan,$kd_cluster,$user_id_bawahan,$tot_unit,$periode_1,$periode_2,'3',$tahap_bangun);

		foreach ($q as $row) {
			$data_arr = explode("|",$row->DATA);
			$max = count($data_arr);

			// kolom patern
			if($first == 'x'){
				$row_tbl_d .= '<tr><td style="text-align: center;" rowspan="'.$data_arr[$max-1].'">'.$data_arr[$max-2].'</td>';
				$cycle_old = $data_arr[$max-2];
				$first = 'y';
			}else{
				if($cycle_old == $data_arr[$max-2]){
					//
				}else{
					$row_tbl_d .= '<tr><td style="text-align: center;" rowspan="'.$data_arr[$max-1].'">'.$data_arr[$max-2].'</td>';
					$cycle_old = $data_arr[$max-2];
				}
			}
            // data tgl kunjungan
            $row_tbl_d .= '<td style="text-align: center;">'.$row->TGL_KUNJUNGAN.'</td>';			
			// split data unit dari kolom jadi row
			for ($x = 0; $x < $max-2; $x++) {
				if($data_arr[$x] == 1){
					$chek = '<i class="fas fa-check"></i>';
				}else{
					$chek = '';
				}
				$row_tbl_d .= '<td style="text-align: center;">'.$chek.'</td>';
			}

            $row_tbl_d .= '</tr>';
		}

        $row_tbl .= '<tr><th style="text-align: center;">Patern</th>';
        $row_tbl .= '<th style="text-align: center;">Tgl Kunjungan</th>';
		$row_tbl .= $row_tbl_h;
		$row_tbl .= '</tr>';
		$row_tbl .= $row_tbl_d;

   	$tbl = array(
    		'row_tbl'		=> $row_tbl,
    	);
		return view('sqii.print.CetakLapPaternCycleTimeV')
			->with('dt', $dt)
			->with('tbl', $tbl);
   }
}
