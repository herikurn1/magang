<?php

namespace App\Models\Sqii;

use Illuminate\Database\Eloquent\Model;
use DB;

class InputMasterItemPekerjaanM extends Model
{

    public static function show_data_item_defect($kd_kategori)
    {
        $q = DB::connection('sqii2')
        ->table('SQII_ITEM_DEFECT as a')
        ->select(
            'a.KD_ITEM_DEFECT', 'a.NM_ITEM_DEFECT', 'b.KD_KATEGORI_DEFECT', 'b.NM_KATEGORI_DEFECT'
        )
        ->join('SQII_KATEGORI_DEFECT as b', function($join) {
            $join->on('b.KD_KATEGORI_DEFECT', '=', 'a.KD_KATEGORI_DEFECT');
        })
        ->where('a.FLAG_AKTIF', '=', 'Y')
        ->where('b.KD_KATEGORI_DEFECT', '=', $kd_kategori)
        ->orderBy('a.NM_ITEM_DEFECT', 'asc')
        ->get();

        return $q;
    }

    public static function data_item_pekerjaan_h($jns_pekerjan,$kd_tahapan)
    {
        $q = DB::connection('sqii2')
        ->table('SQII_MST_ITEM_PEKERJAAN as a')
        ->select(
            'a.KD_ITEM_PEKERJAAN', 'a.PARENT_ID', 'a.KD_TAHAP', 'a.JENIS_PEKERJAAN', 'a.FLAG_HEADER', 'a.NM_PEKERJAAN', 'a.URUT_HEADER', 'a.URUT_DETAIL'
        )
        ->where('a.FLAG_AKTIF', '=', 'A')
        ->where('a.JENIS_PEKERJAAN', '=', $jns_pekerjan)
        ->where('a.KD_TAHAP', '=', $kd_tahapan)
        ->orderBy('a.URUT_HEADER', 'asc')
        ->orderBy('a.URUT_DETAIL', 'asc')
        ->orderBy('a.KD_ITEM_PEKERJAAN', 'asc')
        ->get();

        return $q;
    }

    public static function data_tahapan($kd_kawasan='SPTK',$kd_cluster='RNG')
    {
        $q = DB::connection('sqii2')
        ->table('SQII_MST_TAHAP_PEKERJAAN as a')
        ->select( 
            'a.KD_TAHAP', 'a.NM_TAHAP'
        )
        ->where('a.FLAG_AKTIF', '=', 'A')
        ->orderBy('a.NM_TAHAP', 'asc')
        ->get();

        return $q;
    }

    public static function item_header_detail($tipe_h_d,$jns_pekerjan,$kd_tahapan)
    {

        $q = DB::connection('sqii2')
        ->table('SQII_MST_ITEM_PEKERJAAN as a')
        ->select( 
            'a.KD_ITEM_PEKERJAAN', 'a.PARENT_ID', 'a.KD_TAHAP', 'a.JENIS_PEKERJAAN', 'a.FLAG_HEADER', 'a.NM_PEKERJAAN', 'a.URUT_HEADER', 'a.URUT_DETAIL'
        )
        ->where('a.JENIS_PEKERJAAN', '=', $jns_pekerjan)
        ->where('a.KD_TAHAP', '=', $kd_tahapan)
        ->where('a.FLAG_HEADER', '=', $tipe_h_d)
        ->where('a.FLAG_AKTIF', '=', 'A')
        ->get();

        return $q;
    }

    public static function simpan_dt($parent_id,$kd_tahap,$jenis_pekerjaan,$flag_header,$nm_pekerjaan,$user,$urut){

        if($flag_header == 'H'){
            $urut_header        = $urut;
            $urut_detail        = 0;
        }elseif ($flag_header == 'D') {
            $sql1 = "SELECT URUT_HEADER
                    FROM SQII_MST_ITEM_PEKERJAAN
                    WHERE 1=1
                        AND FLAG_AKTIF = 'A'
                        AND KD_ITEM_PEKERJAAN = '".$parent_id."' ";

            $query1 = DB::connection('sqii2')
            ->select($sql1);        

            $urut_h = $query1[0]->URUT_HEADER;            
            $urut_header        = $urut_h;
            $urut_detail        = $urut;
        }

        $sql = "EXEC SP_KD_ITEM_PEKERJAAN ";
        $sql.= "'', ";
        $sql.= "'New' ";

        $query = DB::connection('sqii2')
        ->select($sql);        

        $kd_item_pekerjaan = $query[0]->ROW_ID;

        $result = DB::connection('sqii2')
        ->table('SQII_MST_ITEM_PEKERJAAN')
        ->insert(
        [
            'KD_ITEM_PEKERJAAN' => $kd_item_pekerjaan,
            'PARENT_ID'         => $parent_id,
            'KD_TAHAP'          => $kd_tahap,
            'JENIS_PEKERJAAN'   => $jenis_pekerjaan,
            'FLAG_HEADER'       => $flag_header,
            'NM_PEKERJAAN'      => $nm_pekerjaan,
            'URUT_HEADER'       => $urut_header,
            'URUT_DETAIL'       => $urut_detail,
            'FLAG_AKTIF'        => 'A',
            'USER_ENTRY'        => $user,
            'TGL_ENTRY'         => now(),
        ]);        
    }

    public static function update_dt($kd_item_pekerjaan,$parent_id,$kd_tahap,$jenis_pekerjaan,$flag_header,$nm_pekerjaan,$user,$urut){       

        if($flag_header == 'H'){
            $urut_header        = $urut;
            $urut_detail        = 0;
        }elseif ($flag_header == 'D') {
            $sql1 = "SELECT URUT_HEADER
                    FROM SQII_MST_ITEM_PEKERJAAN
                    WHERE 1=1
                        AND FLAG_AKTIF = 'A'
                        AND KD_ITEM_PEKERJAAN = '".$parent_id."' ";

            $query1 = DB::connection('sqii2')
            ->select($sql1);        

            $urut_h = $query1[0]->URUT_HEADER;     
            $urut_header        = $urut_h;
            $urut_detail        = $urut;
        }

        $result = DB::connection('sqii2')
        ->table('SQII_MST_ITEM_PEKERJAAN')
        ->where('KD_ITEM_PEKERJAAN','=',$kd_item_pekerjaan)
        ->update(
            [
            'PARENT_ID'         => $parent_id,
            'KD_TAHAP'          => $kd_tahap,
            'JENIS_PEKERJAAN'   => $jenis_pekerjaan,
            'FLAG_HEADER'       => $flag_header,
            'NM_PEKERJAAN'      => $nm_pekerjaan,
            'URUT_HEADER'       => $urut_header,
            'URUT_DETAIL'       => $urut_detail,
            'USER_UPDATE'       => $user,
            'TGL_UPDATE'        => now(),
            ]
        );  

    }

    public static function delete_item_pekerjaan($kd_item_pekerjaan,$user){

        $result = DB::connection('sqii2')
        ->table('SQII_MST_ITEM_PEKERJAAN')
        ->where('KD_ITEM_PEKERJAAN','=',$kd_item_pekerjaan)
        ->update(
            [
            'FLAG_AKTIF'        => 'N',
            'USER_UPDATE'       => $user,
            'TGL_UPDATE'        => now(),
            ]
        );    

        return $result;
    }
}
