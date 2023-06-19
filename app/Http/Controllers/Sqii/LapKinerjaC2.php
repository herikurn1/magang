<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Redirector;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\LapKinerjaM;

class LapKinerjaC2 extends Controller
{
	private $sysController;
	private $master;

	public function __construct()
	{
		$this->sysController = new SysController();
		$this->master = new CMaster();
	}

	public function index($kd_kawasan, $kd_cluster, $nm_kawasan, $nm_cluster, $nik_petugas, $nm_sm, $periode_1, $periode_2, $kd_kategori, $chart,$session_user_id, $tahap_bangun, Request $r)
	{ 

    	$user_id 		= $session_user_id;
    	$button 		= $this->sysController->get_button($r);
    	$data_user 		= $this->master->get_data_user($user_id);
    
    	$dt = array(
    		'button' 		=> $button,
    		'data_user'		=> $data_user,
    		'kd_kawasan'	=> $kd_kawasan,
    		'kd_cluster'	=> $kd_cluster,
    		'nm_kawasan'	=> $nm_kawasan,
    		'nm_cluster'	=> $nm_cluster,
    		'nik_petugas'	=> $nik_petugas,
    		'nm_sm'			=> $nm_sm,
    		'periode_1'		=> $periode_1,
    		'periode_2'		=> $periode_2,
    	);

    	// return view('sqii.LapKinerjaV2')->with('dt', $dt);die;

		$periode_1   	= $this->sysController->indokegabung(str_replace("-","/",$periode_1));
		$periode_2   	= $this->sysController->indokegabung(str_replace("-","/",$periode_2));

		$nm_kategori   	= '';
		for ($i=1; $i <4 ; $i++) { 
			$q = LapKinerjaM::lap_grafik_defect($kd_kawasan,$kd_cluster,$nik_petugas,$periode_1,$periode_2,$i, $tahap_bangun);
			foreach ($q as $row) {
				
				$data[] = array(
					'nm_kategori_defect'	=> $row->NM_KATEGORI_DEFECT, 
					'kd_kategori_defect'	=> $row->KD_KATEGORI_DEFECT,
					'nm_item_defect'		=> $row->NM_ITEM_DEFECT,
					'jml'					=> $row->JML
				);
				$nm_kategori   = $row->NM_KATEGORI_DEFECT;
			}
			if ($i==1) {
				if(isset($data)){
					$data1 			= $data;
					$nm_kategori1 	= $nm_kategori;
					unset($data);
				}else{
					$data1[] = array(
						'nm_kategori_defect'	=> 'Struktur', 
						'kd_kategori_defect'	=> '1',
						'nm_item_defect'		=> 'No data',
						'jml'					=> '0'
					);
					$nm_kategori1   = 'Struktur No data';					
				}
				$chart1 		= 'piechart';
			}elseif ($i==2) {
				if(isset($data)){
					$data2 			= $data;
					$nm_kategori2 	= $nm_kategori;
					unset($data);
				}else{
					$data2[] = array(
						'nm_kategori_defect'	=> 'Arsitektur', 
						'kd_kategori_defect'	=> '2',
						'nm_item_defect'		=> 'No data',
						'jml'					=> '0'
					);
					$nm_kategori2   = 'Arsitektur No data';					
				}
				$chart2 		= 'piechart2';
			}else{
				if(isset($data)){
					$data3 			= $data;
					$nm_kategori3 	= $nm_kategori;
				}else{
					$data3[] = array(
						'nm_kategori_defect'	=> 'MEP', 
						'kd_kategori_defect'	=> '3',
						'nm_item_defect'		=> 'No data',
						'jml'					=> '0'
					);
					$nm_kategori3   = 'MEP No data';					
				}
				$chart3 		= 'piechart3';
			}
		}
		
		// if(isset($data3)){
			return view('sqii.LapKinerjaV2')
				->with('dt', $dt)
				->with('nm_kategori1', $nm_kategori1)
				->with('chart1', $chart1)
				->with('products1', $data1)
				->with('nm_kategori2', $nm_kategori2)
				->with('chart2', $chart2)
				->with('products2', $data2)
				->with('nm_kategori3', $nm_kategori3)
				->with('chart3', $chart3)
				->with('products3', $data3);				
		// }

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

    	$dt = array(
    		'button' 		=> $button,
    		'data_user'		=> $data_user,
    		'kd_kawasan'	=> $kd_kawasan,
    		'kd_cluster'	=> $kd_cluster,
    		'nm_kawasan'	=> $nm_kawasan,
    		'nm_cluster'	=> $nm_cluster,
    		'nik_petugas'	=> $nik_petugas,
    		'nm_sm'			=> $nm_sm,
    		'periode_1'		=> $periode_1,
    		'periode_2'		=> $periode_2,
    	);

		$periode_1   	= $this->sysController->indokegabung(str_replace("-","/",$periode_1));
		$periode_2   	= $this->sysController->indokegabung(str_replace("-","/",$periode_2));

		$nm_kategori   	= '';
		for ($i=1; $i <4 ; $i++) { 
			$q = LapKinerjaM::lap_grafik_defect($kd_kawasan,$kd_cluster,$nik_petugas,$periode_1,$periode_2,$i);
			foreach ($q as $row) {
				
				$data[] = array(
					'nm_kategori_defect'	=> $row->NM_KATEGORI_DEFECT, 
					'kd_kategori_defect'	=> $row->KD_KATEGORI_DEFECT,
					'nm_item_defect'		=> $row->NM_ITEM_DEFECT,
					'jml'					=> $row->JML
				);
				$nm_kategori   = $row->NM_KATEGORI_DEFECT;
			}
			if ($i==1) {
				if(isset($data)){
					$data1 			= $data;
					$nm_kategori1 	= $nm_kategori;
					unset($data);
				}else{
					$data1[] = array(
						'nm_kategori_defect'	=> 'Struktur', 
						'kd_kategori_defect'	=> '1',
						'nm_item_defect'		=> 'No data',
						'jml'					=> '0'
					);
					$nm_kategori1   = 'Struktur No data';					
				}
				$chart1 		= 'piechart';
			}elseif ($i==2) {
				if(isset($data)){
					$data2 			= $data;
					$nm_kategori2 	= $nm_kategori;
					unset($data);
				}else{
					$data2[] = array(
						'nm_kategori_defect'	=> 'Arsitektur', 
						'kd_kategori_defect'	=> '2',
						'nm_item_defect'		=> 'No data',
						'jml'					=> '0'
					);
					$nm_kategori2   = 'Arsitektur No data';					
				}
				$chart2 		= 'piechart2';
			}else{
				if(isset($data)){
					$data3 			= $data;
					$nm_kategori3 	= $nm_kategori;
				}else{
					$data3[] = array(
						'nm_kategori_defect'	=> 'MEP', 
						'kd_kategori_defect'	=> '3',
						'nm_item_defect'		=> 'No data',
						'jml'					=> '0'
					);
					$nm_kategori3   = 'MEP No data';					
				}
				$chart3 		= 'piechart3';
			}
		}
		
			return view('sqii.print.CetakLapKinerjaV2')
				->with('dt', $dt)
				->with('nm_kategori1', $nm_kategori1)
				->with('chart1', $chart1)
				->with('products1', $data1)
				->with('nm_kategori2', $nm_kategori2)
				->with('chart2', $chart2)
				->with('products2', $data2)
				->with('nm_kategori3', $nm_kategori3)
				->with('chart3', $chart3)
				->with('products3', $data3);				
   }
}
