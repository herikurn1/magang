<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Redirector;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\LapKinerjaM;

class LapDetailAegingC extends Controller
{
	private $sysController;
	private $master;

	public function __construct()
	{
		$this->sysController = new SysController();
		$this->master = new CMaster();
	}

	public function index($kd_kawasan,$kd_cluster,$nm_kawasan,$nm_cluster,$nm_sm,$periode_1,$periode_2,$user_id,$user_id_bawahan,$nama,$jml_unit,$jml_defect,$total_defect,$tot_unit,$tipe_ageing,$session_user_id,$tahap_bangun, Request $r)
	{ 

    	$button 		= $this->sysController->get_button($r);
    	$data_user 		= $this->master->get_data_user($session_user_id);
    
    	$dt = array(
    		'button' 		=> $button,
    		'data_user'		=> $data_user,
    		'kd_kawasan'	=> $kd_kawasan,
    		'kd_cluster'	=> $kd_cluster,
    		'nm_kawasan'	=> $nm_kawasan,
    		'nm_cluster'	=> $nm_cluster,
    		'nm_sm'			=> $nm_sm,
    		'nama'			=> $nama,
    		'jml_unit'		=> $jml_unit,
    		'tot_unit'		=> $tot_unit,
            'tipe_ageing'   => $tipe_ageing,
            'periode_1'     => $periode_1,
            'periode_2'     => $periode_2,
            'user_id_bawahan'     => $user_id_bawahan,
            'tahap_bangun'     => $tahap_bangun,
    	);

		$periode_1   	= $this->sysController->indokegabung(str_replace("-","/",$periode_1));
		$periode_2   	= $this->sysController->indokegabung(str_replace("-","/",$periode_2));
		$row_tbl 		= '';
		$no 			= 1;

        $q = LapKinerjaM::lap_detail_ageing_v2($kd_kawasan,$kd_cluster,$user_id_bawahan,$tot_unit,$periode_1,$periode_2,$tipe_ageing,$tahap_bangun);

		foreach ($q as $row) {

             $row_tbl .= '<tr><td>'.$no++.'</td><td>'.$row->BLOK.'/'.$row->NOMOR.'</td><td style="text-align: center;">'.$row->NO_FORMULIR.'</td><td style="text-align: center;">'.$row->NM_KATEGORI_DEFECT.'</td><td style="text-align: center;">'.$row->DESKRIPSI.'</td><td style="text-align: center;">'.$row->NM_ITEM_DEFECT.'</td><td style="text-align: center;">'.$row->STATUS_DEFECT.'</td><td style="text-align: center;">'.$row->AGEING.'</td></tr>';
		}

        $tbl = array(
    		'row_tbl'		=> $row_tbl,
    	);

		if(isset($row_tbl)){
			return view('sqii.LapDetailAegingV')
				->with('dt', $dt)
				->with('tbl', $tbl);
		}

	}   

    public function print_dt(Request $r)
    {

        $kd_kawasan     = $r->kd_kawasan;
        $kd_cluster     = $r->kd_cluster;
        $nm_kawasan     = $r->nm_kawasan;  
        $nm_cluster     = $r->nm_cluster;
        $nm_sm          = $r->nm_sm;
        $periode_1      = $r->periode_1;  
        $periode_2      = $r->periode_2;
        $user_id_bawahan = $r->user_id_bawahan;  
        $nama           = $r->nama;
        $jml_unit       = $r->jml_unit;
        $tot_unit       = $r->tot_unit;
        $tipe_ageing    = $r->tipe_ageing;
        $tahap_bangun   = $r->tahap_bangun;

        $dt = array(
            'kd_kawasan'    => $kd_kawasan,
            'kd_cluster'    => $kd_cluster,
            'nm_kawasan'    => $nm_kawasan,
            'nm_cluster'    => $nm_cluster,
            'nm_sm'         => $nm_sm,
            'nama'          => $nama,
            'jml_unit'      => $jml_unit,
            'tot_unit'      => $tot_unit,
            'tipe_ageing'   => $tipe_ageing,
            'periode_1'     => $periode_1,
            'periode_2'     => $periode_2,
            'user_id_bawahan'     => $user_id_bawahan,
        );

        $periode_1      = $this->sysController->indokegabung(str_replace("-","/",$periode_1));
        $periode_2      = $this->sysController->indokegabung(str_replace("-","/",$periode_2));
        $row_tbl        = '';
        $no             = 1;

        $q = LapKinerjaM::lap_detail_ageing($kd_kawasan,$kd_cluster,$user_id_bawahan,$tot_unit,$periode_1,$periode_2,$tipe_ageing,$tahap_bangun);

        foreach ($q as $row) {

             $row_tbl .= '<tr><td>'.$no++.'</td><td>'.$row->BLOK.'/'.$row->NOMOR.'</td><td style="text-align: center;">'.$row->NO_FORMULIR.'</td><td style="text-align: center;">'.$row->NM_KATEGORI_DEFECT.'</td><td style="text-align: center;">'.$row->DESKRIPSI.'</td><td style="text-align: center;">'.$row->NM_ITEM_DEFECT.'</td><td style="text-align: center;">'.$row->STATUS_DEFECT.'</td><td style="text-align: center;">'.$row->AGEING.'</td></tr>';
        }

        $tbl = array(
            'row_tbl'       => $row_tbl,
        );

        return view('sqii.print.CetakLapDetailAegingV')
            ->with('dt', $dt)
            ->with('tbl', $tbl);
   }
}
