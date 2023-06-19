<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Redirector;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\LapKinerjaM;

class LapCycleTimeC extends Controller
{
	private $sysController;
	private $master;

	public function __construct()
	{
		$this->sysController = new SysController();
		$this->master = new CMaster();
	}

	public function index($kd_kawasan,$kd_cluster,$nm_kawasan,$nm_cluster,$nm_sm,$periode_1,$periode_2,$user_id,$user_id_bawahan,$nama,$jml_unit,$jml_defect,$total_defect,$tot_unit,$session_user_id, $tahap_bangun, Request $r)
	{ 

    	$button 		= $this->sysController->get_button($r);
    	$data_user 		= $this->master->get_data_user($session_user_id);
    	$periode_1l		= str_replace("-","/",$periode_1);
    	$periode_2l		= str_replace("-","/",$periode_2);
    	$isKunjungan 	= 'false';
    
    	$dt = array(
    		'button' 		=> $button,
    		'data_user'		=> $data_user,
    		'nm_cluster'	=> $nm_cluster,
    		'nama'			=> $nama,
    		'jml_unit'		=> $jml_unit,
            'periode_1'     => $periode_1,
            'periode_2'     => $periode_2,
            'kd_kawasan'     => $kd_kawasan,
            'kd_cluster'     => $kd_cluster,
            'user_id_bawahan'     => $user_id_bawahan,
            'tot_unit'     => $tot_unit,
            'tahap_bangun'     => $tahap_bangun,
    	);

		$periode_1   	= $this->sysController->indokegabung(str_replace("-","/",$periode_1));
		$periode_2   	= $this->sysController->indokegabung(str_replace("-","/",$periode_2));
		$row_tbl 		= $row_tbl_h = $row_tbl_d = '';
		$no 			= 1;
					
		$bulan1 = date('m', strtotime($periode_1));
		$bulan2 = date('m', strtotime($periode_2));

		$bulan1 = $this->bulan_indo($bulan1);
		$bulan2 = $this->bulan_indo($bulan2); 

		$q_cek_kunjungan = LapKinerjaM::data_kunjungan($kd_kawasan,$kd_cluster,$user_id_bawahan,$periode_1,$periode_2,$tahap_bangun);
		foreach ($q_cek_kunjungan as $row_kunjungan) {
			if($row_kunjungan->JML > 0){$isKunjungan = 'true';}
		}

		if($isKunjungan == 'true'){
			$q = LapKinerjaM::lap_cycle_time_v2($kd_kawasan,$kd_cluster,$user_id_bawahan,$tot_unit,$periode_1,$periode_2,'1',$tahap_bangun);

			foreach ($q as $row) { //transform: rotate(-90deg);
	            $row_tbl_h .= '<th style="text-align: center;">'.$row->NOMOR.'</th>';
	            $row_tbl_d .= '<td style="text-align: center;">'.$row->CYCLE_TIME.'</td>';
			}

	        $row_tbl .= '<tr><th style="text-align: center;">Data</th>';
			$row_tbl .= $row_tbl_h;
			$row_tbl .= '</tr>';
			$row_tbl .= '<tr><td onclick="formulasi_cycle_time(\''.$kd_kawasan.'\',\''.$kd_cluster.'\',\''.$nm_kawasan.'\',\''.$nm_cluster.'\',\''.$nm_sm.'\',\''.$periode_1l.'\',\''.$periode_2l.'\',\''.$user_id.'\', \''.$user_id_bawahan.'\', \''.$nama.'\', \''.$jml_unit.'\', \''.$jml_defect.'\', \''.$total_defect.'\', \''.$tot_unit.'\', \''.$tahap_bangun.'\')"><div class="link_cursor">Kumulatif Bulan '.$bulan1.' s.d. '.$bulan2.'</div> </td>';
			$row_tbl .= $row_tbl_d;
			$row_tbl .='</tr>';

	        $tbl = array(
	    		'row_tbl'		=> $row_tbl,
	    	);
		}else{
	        $row_tbl .= '<tr><th style="text-align: center;">Data</th>';
			$row_tbl .= $row_tbl_h;
			$row_tbl .= '</tr>';
			$row_tbl .= '<tr><td style="text-align: center;"><div class="link_cursor">Data tidak ditemukan</div></td>';
			$row_tbl .= $row_tbl_d;
			$row_tbl .='</tr>';
	        $tbl = array(
	    		'row_tbl'		=> $row_tbl,
	    	);
		}
		$isKunjungan = 'false';

		if(isset($row_tbl)){
			return view('sqii.LapCycleTimeV')
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
            'periode_2'     => $periode_2,
    	);

		$periode_1   	= $this->sysController->indokegabung(str_replace("-","/",$periode_1));
		$periode_2   	= $this->sysController->indokegabung(str_replace("-","/",$periode_2));
		$row_tbl 		= $row_tbl_h = $row_tbl_d = '';
		$no 			= 1;
					
		$bulan1 = date('m', strtotime($periode_1));
		$bulan2 = date('m', strtotime($periode_2));

		$bulan1 = $this->bulan_indo($bulan1);
		$bulan2 = $this->bulan_indo($bulan2); 

		$q = LapKinerjaM::lap_cycle_time_v2($kd_kawasan,$kd_cluster,$user_id_bawahan,$tot_unit,$periode_1,$periode_2,'1',$tahap_bangun);

		foreach ($q as $row) { 
            $row_tbl_h .= '<th style="text-align: center;">'.$row->NOMOR.'</th>';
            $row_tbl_d .= '<td style="text-align: center;">'.$row->CYCLE_TIME.'</td>';
		}

        $row_tbl .= '<tr><th style="text-align: center;">Data</th>';
		$row_tbl .= $row_tbl_h;
		$row_tbl .= '</tr>';
		$row_tbl .= '<tr><td>Kumulatif Bulan '.$bulan1.' s.d. '.$bulan2.'</td>';
		$row_tbl .= $row_tbl_d;
		$row_tbl .='</tr>';

   		$tbl = array(
    		'row_tbl'		=> $row_tbl,
    	);
		return view('sqii.print.CetakLapCycleTimeV')
			->with('dt', $dt)
			->with('tbl', $tbl);
   }
}
