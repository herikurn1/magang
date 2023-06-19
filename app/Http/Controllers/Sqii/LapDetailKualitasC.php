<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Redirector;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\LapKinerjaM;

class LapDetailKualitasC extends Controller
{
	private $sysController;
	private $master;

	public function __construct()
	{
		$this->sysController = new SysController();
		$this->master = new CMaster();
	}

	public function index($kd_kawasan,$kd_cluster,$nm_kawasan,$nm_cluster,$nm_sm,$periode_1,$periode_2,$user_id,$user_id_bawahan,$nama,$jml_unit,$jml_defect,$total_defect,$tot_unit,$kd_kategori_defect,$session_user_id,$tahap_bangun, Request $r)
	{ 

    	$button 		= $this->sysController->get_button($r);
    	$data_user 		= $this->master->get_data_user($session_user_id);
    
        $dt = array(
            'button'        => $button,
            'data_user'     => $data_user,
            'kd_kawasan'    => $kd_kawasan,
            'kd_cluster'    => $kd_cluster,
            'nm_kawasan'    => $nm_kawasan,
            'nm_cluster'    => $nm_cluster,
            'nm_sm'         => $nm_sm,
            'periode_1'     => $periode_1,
            'periode_2'     => $periode_2,
            'user_id'       => $user_id,
            'user_id_bawahan'   => $user_id_bawahan,
            'nama'          => $nama,
            'jml_unit'      => $jml_unit,
            'jml_defect'    => $jml_defect,
            'total_defect'  => $total_defect,
            'tot_unit'      => $tot_unit,
            'kd_kategori_defect' => $kd_kategori_defect,
            'tahap_bangun' => $tahap_bangun,
        );

		$periode_1   	= $this->sysController->indokegabung(str_replace("-","/",$periode_1));
		$periode_2   	= $this->sysController->indokegabung(str_replace("-","/",$periode_2));
		$row_tbl 		= '';
		$no 			= 1;

        $q = LapKinerjaM::lap_detail_kualitas($kd_kawasan,$kd_cluster,$user_id_bawahan,$kd_kategori_defect,$periode_1,$periode_2,$tahap_bangun);

        foreach ($q as $row) {

            $row_tbl .= '<tr onclick="lap_formulir_kualitas_bangunan(\''.$kd_kawasan.'\',\''.$kd_cluster.'\',\''.$nm_kawasan.'\',\''.$nm_cluster.'\',\''.$nm_sm.'\',\''.$periode_1.'\',\''.$periode_2.'\',\''.$user_id_bawahan.'\',\''.$kd_kategori_defect.'\',\''.$row->NO_FORMULIR.'\',\''.$nama.'\',\''.$session_user_id.'\',\''.$tahap_bangun.'\')"><td>'.$no++.'</td><td>'.$row->BLOK.'/'.$row->NOMOR.'</td><td style="text-align: center;"><div class="link_cursor">'.$row->NO_FORMULIR.'</div></td><td style="text-align: center;">'.$row->NM_KATEGORI_DEFECT.'</td><td style="text-align: center;">'.htmlentities($row->DESKRIPSI).'</td><td style="text-align: center;">'.$row->NM_ITEM_DEFECT.'</td><td style="text-align: center;">'.$row->STATUS_DEFECT.'</td><td style="text-align: center;">'.$row->AGEING.'</td><td style="text-align: center;">'.$row->NM_USER_GOSHOW.'</td></tr>';

        }

        $tbl = array(
    		'row_tbl'		=> $row_tbl,
    	);

		if(isset($row_tbl)){
			return view('sqii.LapDetailKualitasV')
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
        $user_id        = $r->user_id;
        $user_id_bawahan = $r->user_id_bawahan;  
        $nama               = $r->nama;
        $jml_unit       = $r->jml_unit;
        $jml_defect     = $r->jml_defect;  
        $total_defect  = $r->total_defect;
        $tot_unit       = $r->tot_unit;
        $kd_kategori_defect = $r->kd_kategori_defect;
        $tahap_bangun = $r->tahap_bangun;

        $periode1 = explode('-', $periode_1);
        $periode2 = explode('-', $periode_2);
        $per_exp_1 = $periode1[2].'-'.$periode1[1].'-'.$periode1[0];
        $per_exp_2 = $periode2[2].'-'.$periode2[1].'-'.$periode2[0];

        $dt = array(
            'kd_kawasan'    => $kd_kawasan,
            'kd_cluster'    => $kd_cluster,
            'nm_kawasan'    => $nm_kawasan,
            'nm_cluster'    => $nm_cluster,
            'nm_sm'         => $nm_sm,
            'periode_1'     => $periode_1,
            'periode_2'     => $periode_2,
            'user_id'       => $user_id,
            'user_id_bawahan'   => $user_id_bawahan,
            'nama'          => $nama,
            'jml_unit'      => $jml_unit,
            'jml_defect'    => $jml_defect,
            'total_defect'  => $total_defect,
            'tot_unit'      => $tot_unit,
        );

        $row_tbl        = '';
        $no             = 1;

        $q = LapKinerjaM::lap_detail_kualitas($kd_kawasan,$kd_cluster,$user_id_bawahan,$kd_kategori_defect,$per_exp_1,$per_exp_2,$tahap_bangun);

        foreach ($q as $row) {

            $row_tbl .= '<tr><td>'.$no++.'</td><td>'.$row->BLOK.'/'.$row->NOMOR.'</td><td style="text-align: center;">'.$row->NO_FORMULIR.'</td><td style="text-align: center;">'.$row->NM_KATEGORI_DEFECT.'</td><td style="text-align: center;">'.htmlentities($row->DESKRIPSI).'</td><td style="text-align: center;">'.$row->NM_ITEM_DEFECT.'</td><td style="text-align: center;">'.$row->STATUS_DEFECT.'</td><td style="text-align: center;">'.$row->AGEING.'</td><td style="text-align: center;">'.$row->NM_USER_GOSHOW.'</td></tr>';
        }

        $tbl = array(
            'row_tbl'       => $row_tbl,
        );
        return view('sqii.print.CetakLapDetailKualitasV')
            ->with('dt', $dt)
            ->with('tbl', $tbl);
   }
}
