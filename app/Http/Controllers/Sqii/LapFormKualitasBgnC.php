<?php

namespace App\Http\Controllers\Sqii;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Redirector;

use App\Http\Controllers\Sys\SysController;
use App\Http\Controllers\Budget\CMaster;

use App\Models\Sqii\LapKinerjaM;

class LapFormKualitasBgnC extends Controller
{
	private $sysController;
	private $master;

	public function __construct()
	{
		$this->sysController = new SysController();
		$this->master = new CMaster();
	}

	public function index( $kd_kawasan,$kd_cluster,$nm_kawasan,$nm_cluster,$nm_sm,$periode_1,$periode_2,$user_id_bawahan,$kd_kategori_defect,$no_formulir,$nama,$session_user_id,$tahap_bangun, Request $r)
	{ 

    	$button 		= $this->sysController->get_button($r);
    	$data_user 		= $this->master->get_data_user($session_user_id);

        $q = LapKinerjaM::lap_formulir_kualitas_bangunan($kd_kawasan,$kd_cluster,$user_id_bawahan,$kd_kategori_defect,$no_formulir,$periode_1,$periode_2,$tahap_bangun);
        foreach ($q as $row) {
            $blok                   = $row->BLOK;
            $nomor                  = $row->NOMOR;
            $no_formulir            = $row->NO_FORMULIR;
            $nm_kategori_defect     = $row->NM_KATEGORI_DEFECT;
            $deskripsi              = $row->DESKRIPSI;
            $nm_item_defect         = trim(preg_replace('/[^A-Za-z ]/', ' ', $row->NM_ITEM_DEFECT));
            $status_defect          = $row->STATUS_DEFECT;
            $nm_lantai              = $row->NM_LANTAI;
            $path_foto_denah        = $row->PATH_FOTO_DENAH;
            $src_foto_denah         = $row->SRC_FOTO_DENAH;
            $path_foto_defect       = $row->PATH_FOTO_DEFECT;
            $src_foto_defect        = $row->SRC_FOTO_DEFECT;
            $path_foto_perbaikan    = $row->PATH_FOTO_PERBAIKAN;
            $src_foto_perbaikan     = $row->SRC_FOTO_PERBAIKAN;
            $tgl_foto               = $row->TGL_FOTO;
            $tgl_jatuh_tempo_perbaikan = $row->TGL_JATUH_TEMPO_PERBAIKAN;
            $tgl_selesai            = $row->TGL_SELESAI;
            $nama_ktt               = $row->NAMA_KTT;
            $nama_qc                = $row->NAMA_QC;
        }

    
    	$dt = array(
    		'button' 		        => $button,
    		'data_user'		        => $data_user,
            'kd_kawasan'            => $kd_kawasan,
            'kd_cluster'            => $kd_cluster,
            'nm_kawasan'            => $nm_kawasan,
            'nm_cluster'            => $nm_cluster,
            'nm_sm'                 => $nm_sm,
            'periode_1'             => $periode_1,
            'periode_2'             => $periode_2,
            'blok'                  => $blok,
            'nomor'                 => $nomor,
            'no_formulir'           => $no_formulir,
            'nm_kategori_defect'    => $nm_kategori_defect,
            'deskripsi'             => $deskripsi,
            'nm_item_defect'        => $nm_item_defect,
            'status_defect'         => $status_defect,
            'nm_lantai'             => $nm_lantai,
            'path_foto_denah'       => $path_foto_denah,
            'src_foto_denah'        => $src_foto_denah,
            'path_foto_defect'      => $path_foto_defect,
            'src_foto_defect'       => $src_foto_defect,
            'path_foto_perbaikan'   => $path_foto_perbaikan,
            'src_foto_perbaikan'    => $src_foto_perbaikan,
            'tgl_foto'              => $tgl_foto,
            'tgl_jatuh_tempo_perbaikan'   => $tgl_jatuh_tempo_perbaikan,
            'tgl_selesai'           => $tgl_selesai,
            'nama'                  => $nama,
            'nama_ktt'              => $nama_ktt,
            'nama_qc'               => $nama_qc,
            'user_id_bawahan'       => $user_id_bawahan,
            'kd_kategori_defect'    => $kd_kategori_defect,
            'tahap_bangun'          => $tahap_bangun,
    	);
        // DD($dt);
		if(isset($dt)){
			return view('sqii.LapFormKualitasBgnV')
				->with('dt', $dt);
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
        $no_formulir = $r->no_formulir;
        $tahap_bangun = $r->tahap_bangun;

       $q = LapKinerjaM::lap_formulir_kualitas_bangunan($kd_kawasan,$kd_cluster,$user_id_bawahan,$kd_kategori_defect,$no_formulir,$periode_1,$periode_2,$tahap_bangun);
        foreach ($q as $row) {
            $blok                   = $row->BLOK;
            $nomor                  = $row->NOMOR;
            $no_formulir            = $row->NO_FORMULIR;
            $nm_kategori_defect     = $row->NM_KATEGORI_DEFECT;
            $deskripsi              = $row->DESKRIPSI;
            $nm_item_defect         = trim(preg_replace('/\s\s+/', ' ', $row->NM_ITEM_DEFECT));
            $status_defect          = $row->STATUS_DEFECT;
            $nm_lantai              = $row->NM_LANTAI;
            $path_foto_denah        = $row->PATH_FOTO_DENAH;
            $src_foto_denah         = $row->SRC_FOTO_DENAH;
            $path_foto_defect       = $row->PATH_FOTO_DEFECT;
            $src_foto_defect        = $row->SRC_FOTO_DEFECT;
            $path_foto_perbaikan    = $row->PATH_FOTO_PERBAIKAN;
            $src_foto_perbaikan     = $row->SRC_FOTO_PERBAIKAN;
            $tgl_foto               = $row->TGL_FOTO;
            $tgl_jatuh_tempo_perbaikan = $row->TGL_JATUH_TEMPO_PERBAIKAN;
            $tgl_selesai            = $row->TGL_SELESAI;
            $nama_ktt               = $row->NAMA_KTT;
            $nama_qc                = $row->NAMA_QC;
        }

    
        $dt = array(
            'kd_kawasan'            => $kd_kawasan,
            'kd_cluster'            => $kd_cluster,
            'nm_kawasan'            => $nm_kawasan,
            'nm_cluster'            => $nm_cluster,
            'nm_sm'                 => $nm_sm,
            'periode_1'             => $periode_1,
            'periode_2'             => $periode_2,
            'blok'                  => $blok,
            'nomor'                 => $nomor,
            'no_formulir'           => $no_formulir,
            'nm_kategori_defect'    => $nm_kategori_defect,
            'deskripsi'             => $deskripsi,
            'nm_item_defect'        => $nm_item_defect,
            'status_defect'         => $status_defect,
            'nm_lantai'             => $nm_lantai,
            'path_foto_denah'       => $path_foto_denah,
            'src_foto_denah'        => $src_foto_denah,
            'path_foto_defect'      => $path_foto_defect,
            'src_foto_defect'       => $src_foto_defect,
            'path_foto_perbaikan'   => $path_foto_perbaikan,
            'src_foto_perbaikan'    => $src_foto_perbaikan,
            'tgl_foto'              => $tgl_foto,
            'tgl_jatuh_tempo_perbaikan'   => $tgl_jatuh_tempo_perbaikan,
            'tgl_selesai'           => $tgl_selesai,
            'nama'                  => $nama,
            'nama_ktt'              => $nama_ktt,
            'nama_qc'               => $nama_qc,
        );

        return view('sqii.print.CetakLapFormKualitasBgnV')
            ->with('dt', $dt);
   }
}
