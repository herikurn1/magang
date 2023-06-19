<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Redirector;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\LapKinerjaM;

class LapFormulasiCycleTimeC extends Controller
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
    	$periode_1l		= str_replace("-","/",$periode_1x);
    	$periode_2l		= str_replace("-","/",$periode_2x);
    	
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
            'user_id_bawahan'     => $user_id_bawahan,
            'tot_unit'     => $tot_unit,
            'tahap_bangun'     => $tahap_bangun,
    	);

		$periode_1   	= $this->sysController->indokegabung(str_replace("-","/",$periode_1x));
		$periode_2   	= $this->sysController->indokegabung(str_replace("-","/",$periode_2x));
		$row_tbl 		= $row_tbl_h = $row_tbl_d = '';
		$no 			= 1;
					
		$bulan1 = date('m', strtotime($periode_1));
		$bulan2 = date('m', strtotime($periode_2));

		$bulan1 = $this->bulan_indo($bulan1);
		$bulan2 = $this->bulan_indo($bulan2);

        $q = LapKinerjaM::lap_cycle_time_v2($kd_kawasan,$kd_cluster,$user_id_bawahan,$tot_unit,$periode_1,$periode_2,'2',$tahap_bangun);

		$row_tbl_h .= '<tr><th style="text-align: center;">Jml Cycle</th>';
		$row_tbl_h .= '<th style="text-align: center;">Jml Unit</th>';
		$row_tbl_h .= '<th style="text-align: center;">Jml Hari</th>';
		$row_tbl_h .= '<th style="text-align: center;">Rata-rata hari/Cycle</th>';
		$row_tbl_h .= '<th style="text-align: center;">Jml Unit/Jml hari</th>';
		$row_tbl_h .= '<th style="text-align: center;">Rata-rata unit/hari (jika belum 1 cycle maka a=0, shg f blm valid)</th></tr>';

		$row_tbl_h .= '<tr><th style="text-align: center;">a<sub><i>n</i></sub></th>';
		$row_tbl_h .= '<th style="text-align: center;">b</th>';
		$row_tbl_h .= '<th style="text-align: center;">c</th>';
		$row_tbl_h .= '<th style="text-align: center;">d=akumulasi c/a<sub><i>n</i></sub></th>';
		$row_tbl_h .= '<th style="text-align: center;">e=b/c</th>';
		$row_tbl_h .= '<th style="text-align: center;">f = akumulasi e/a<sub><i>n</i></sub></th></tr>';

		$sum_c = 0;
		$sum_f = 0;
		$sum_e = 0;
		$n 	   = 1;
		//e=b/c jml unit/jml hari
		foreach ($q as $row) {
			$sum_c = $sum_c + $row->JML_HR;
			if($row->SISA_STOK > 0){
				$cycle_count = 0;
				$c = 0;
				$d = 0;
				$e = 0;//$jml_unit / $row->JML_HR;
			}else{
				$cycle_count = $row->CYCLE_COUNT;
				$c = $row->JML_HR;
				$d = $sum_c / $row->CYCLE_COUNT;
				// $e = $jml_unit / $d;
				$e = $jml_unit / $c; //$jml_unit / $row->JML_HR;
			}
			// $d = $sum_c / $row->CYCLE_COUNT;
			// $e = $jml_unit / $d;
			// $sum_f = ($sum_f + $e)/$n;
			$sum_e = ($sum_e + $e);
			$sum_f = ($sum_e)/$n;
            //$row_tbl_h .= '<th style="text-align: center;">'.$row->NOMOR.'</th>';
            $row_tbl_d .= '</tr><td onclick="patern_cycle_time(\''.$kd_kawasan.'\',\''.$kd_cluster.'\',\''.$nm_kawasan.'\',\''.$nm_clusterx.'\',\''.$nm_sm.'\',\''.$periode_1l.'\',\''.$periode_2l.'\',\''.$user_id.'\', \''.$user_id_bawahan.'\', \''.$nama.'\', \''.$jml_unit.'\', \''.$jml_defect.'\', \''.$total_defect.'\', \''.$tot_unit.'\', \''.$tahap_bangun.'\')" style="text-align: center;"><div class="link_cursor">'.$cycle_count.'</div></td>';
            $row_tbl_d .= '<td style="text-align: center;">'.$jml_unit.'</td>';
            $row_tbl_d .= '<td style="text-align: center;">'.$c.'</td>';
            $row_tbl_d .= '<td style="text-align: center;">'.number_format($d, 2, '.', '').'</td>';
            $row_tbl_d .= '<td style="text-align: center;">'.number_format($e, 2, '.', '').'</td>';
            $row_tbl_d .= '<td style="text-align: center;">'.number_format($sum_f, 2, '.', '').'</td></tr>';

            $n++;
		}

        //$row_tbl .= '<tr>';
		$row_tbl .= $row_tbl_h;
		//$row_tbl .= '</tr>';
		// $row_tbl .= '<tr><td onclick="formulasi_cycle_time(\''.$kd_kawasan.'\',\''.$kd_cluster.'\',\''.$nm_kawasan.'\',\''.$nm_clusterx.'\',\''.$nm_sm.'\',\''.$periode_2x.'\',\''.$periode_2x.'\',\''.$user_id.'\', \''.$user_id_bawahan.'\', \''.$nama.'\', \''.$jml_unit.'\', \''.$jml_defect.'\', \''.$total_defect.'\', \''.$tot_unit.'\')"><div class="link_cursor">Kumulatif Bulan '.$bulan1.' s.d. '.$bulan2.'</div> </td>';
		$row_tbl .= $row_tbl_d;
		// $row_tbl .='</tr>';

        $tbl = array(
    		'row_tbl'		=> $row_tbl,
    	);

		if(isset($row_tbl)){
			return view('sqii.LapFormulasiCycleTimeV')
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
		$tahap_bangun   	= $r->tahap_bangun;

    	$dt = array(
    		'nm_cluster'	=> $nm_cluster,
    		'nama'			=> $nama,
    		'jml_unit'		=> $jml_unit,
            'periode_1'     => $periode_1,
            'periode_2'     => $periode_1,
    	);

		$periode_1   	= $this->sysController->indokegabung(str_replace("-","/",$periode_1));
		$periode_2   	= $this->sysController->indokegabung(str_replace("-","/",$periode_2));
		$row_tbl 		= $row_tbl_h = $row_tbl_d = '';
		$no 			= 1;
					
		$bulan1 = date('m', strtotime($periode_1));
		$bulan2 = date('m', strtotime($periode_2));

		$bulan1 = $this->bulan_indo($bulan1);
		$bulan2 = $this->bulan_indo($bulan2);

        $q = LapKinerjaM::lap_cycle_time_v2($kd_kawasan,$kd_cluster,$user_id_bawahan,$tot_unit,$periode_1,$periode_2,'2',$tahap_bangun);

		$row_tbl_h .= '<tr><th style="text-align: center;">Jml Cycle</th>';
		$row_tbl_h .= '<th style="text-align: center;">Jml Unit</th>';
		$row_tbl_h .= '<th style="text-align: center;">Jml Hari</th>';
		$row_tbl_h .= '<th style="text-align: center;">Rata-rata hari/Cycle</th>';
		$row_tbl_h .= '<th style="text-align: center;">Jml Unit/Jml hari</th>';
		$row_tbl_h .= '<th style="text-align: center;">Rata-rata unit/hari (jika belum 1 cycle maka a=0, shg f blm valid)</th></tr>';

		$row_tbl_h .= '<tr><th style="text-align: center;">a<sub><i>n</i></sub></th>';
		$row_tbl_h .= '<th style="text-align: center;">b</th>';
		$row_tbl_h .= '<th style="text-align: center;">c</th>';
		$row_tbl_h .= '<th style="text-align: center;">d=akumulasi c/a<sub><i>n</i></sub></th>';
		$row_tbl_h .= '<th style="text-align: center;">e=b/c</th>';
		$row_tbl_h .= '<th style="text-align: center;">f = akumulasi e/a<sub><i>n</i></sub></th></tr>';

		$sum_c = 0;
		$sum_f = 0;
		$sum_e = 0;
		$n 	   = 1;
		foreach ($q as $row) {
			$sum_c = $sum_c + $row->JML_HR;
			if($row->SISA_STOK > 0){
				$cycle_count = 0;
				$c = 0;
				$d = 0;
				$e = 0;
			}else{
				$cycle_count = $row->CYCLE_COUNT;
				$c = $row->JML_HR;
				$d = $sum_c / $row->CYCLE_COUNT;
				$e = $jml_unit / $c; 
			}
			$sum_e = ($sum_e + $e);
			$sum_f = ($sum_e)/$n;
            $row_tbl_d .= '</tr><td style="text-align: center;">'.$cycle_count.'</td>';
            $row_tbl_d .= '<td style="text-align: center;">'.$jml_unit.'</td>';
            $row_tbl_d .= '<td style="text-align: center;">'.$c.'</td>';
            $row_tbl_d .= '<td style="text-align: center;">'.number_format($d, 2, '.', '').'</td>';
            $row_tbl_d .= '<td style="text-align: center;">'.number_format($e, 2, '.', '').'</td>';
            $row_tbl_d .= '<td style="text-align: center;">'.number_format($sum_f, 2, '.', '').'</td></tr>';

            $n++;
		}

		$row_tbl .= $row_tbl_h;

		$row_tbl .= $row_tbl_d;

   		$tbl = array(
    		'row_tbl'		=> $row_tbl,
    	);
		return view('sqii.print.CetakLapFormulasiCycleTimeV')
			->with('dt', $dt)
			->with('tbl', $tbl);
   }
}
