<?php

namespace App\Models\Sqii;

use Illuminate\Database\Eloquent\Model;
use DB;

class RegClusterM extends Model
{
	public static function show_cluster()
	{
		$q = DB::connection('sqii2')
		->table('SQII_CLUSTER as a')
    	->select(
    		'a.KD_CLUSTER', 'a.NM_CLUSTER', 'b.NM_KAWASAN'
    	)
        ->join('SQII_KAWASAN as b', function($join) {
            $join->on('b.KD_KAWASAN', '=', 'a.KD_KAWASAN');
        })
    	->where('a.FLAG_AKTIF', '=', 'Y')
        ->where('b.FLAG_AKTIF', '=', 'Y')
    	->orderBy('a.KD_CLUSTER', 'asc')
    	->get();

    	return $q;
	}

    public static function sync_dt($user)
    {

        // bersihkan table cloud
        DB::connection('sqii2')
            ->table('TBL_LINK_V_SQII_ALL_CLUSTER')->truncate();

        // call data local
        $q = DB::connection('sqii')
        ->select("
           SELECT --TOP 1    
            TBL_LINK.KD_SEKTOR, TBL_LINK.KD_KAWASAN, TBL_LINK.DESKRIPSI, TBL_LINK.FLAG_AKTIF    
           FROM V_SQII_ALL_CLUSTER TBL_LINK
        ");

        foreach ($q as $row) {
            // insert data ke cloud
            $result = DB::connection('sqii2')
            ->table('TBL_LINK_V_SQII_ALL_CLUSTER')
            ->insert(
            [
                'KD_CLUSTER'    => $row->KD_SEKTOR,
                'KD_KAWASAN'    => $row->KD_KAWASAN,
                'NM_CLUSTER'    => $row->DESKRIPSI,
                'FLAG_AKTIF'    => $row->FLAG_AKTIF,
                'USER_ENTRY'    => $user,
                'TGL_ENTRY'     => now(),
            ]
            );     
        }
        

        $q = DB::connection('sqii2')
        ->table('SQII_CLUSTER as a')
        ->select(
            'a.KD_CLUSTER', 'a.NM_CLUSTER', 'b.NM_KAWASAN'
        )
        ->join('SQII_KAWASAN as b', function($join) {
            $join->on('b.KD_KAWASAN', '=', 'a.KD_KAWASAN');
        })
        ->where('a.FLAG_AKTIF', '=', 'Y')
        ->where('b.FLAG_AKTIF', '=', 'Y')
        ->orderBy('a.KD_CLUSTER', 'asc')
        ->get();

        return $q;
        // 
        // NANTI PAKE YG INI YA
        // 
        // $sql = "EXEC SQII_N_SP_SYNC_CLUSTER ";
        // $sql.= "'".$user_id."'";
        // $q = DB::select($sql);

        // return $q;
    } 

    public static function sync_dt_v2($user)
    {

        // get cluster
        // call data local
        $q = DB::connection('sqii')
        ->select("
        SELECT --TOP 1 V_SQII_ALL_CLUSTER_V2
            TBL_LINK.KD_SEKTOR, TBL_LINK.KD_KAWASAN, TBL_LINK.DESKRIPSI, TBL_LINK.FLAG_AKTIF    
        FROM V_SQII_ALL_CLUSTER_V2 TBL_LINK
        WHERE 1=1
            --AND KD_SEKTOR LIKE '%LNR%'
        ");
        // DD($q);
        foreach ($q as $row) {

            $kd_sektor = $row->KD_SEKTOR;
            $deskripsi = $row->DESKRIPSI;
            $flag_aktif = $row->FLAG_AKTIF;
            $kd_kawasan = $row->KD_KAWASAN;
            
            $flag_aktif_cluster = 'N';
            if($flag_aktif == "A"){
                $flag_aktif_cluster = 'Y';
            }
            // DD($kd_sektor);
            // cek data cloud sudah ada atau blm
            $cek_cluster_doesn_exists = DB::connection('sqii2')
                ->table('SQII_CLUSTER')
                ->where('KD_KAWASAN',  '=', $kd_kawasan)
                ->where('KD_CLUSTER',  '=', $kd_sektor)
                ->doesntExist();
            // DD($cek_cluster_doesn_exists);
            if($cek_cluster_doesn_exists){
                // insert data ke cloud
                $result = DB::connection('sqii2')
                ->table('SQII_CLUSTER')
                ->insert(
                [
                    'KD_CLUSTER'    => $kd_sektor,
                    'KD_KAWASAN'    => $kd_kawasan,
                    'NM_CLUSTER'    => $deskripsi,
                    'FLAG_AKTIF'    => $flag_aktif_cluster,
                    'USER_ENTRY'    => $user,
                    'TGL_ENTRY'     => now(),
                ]
                );
                // DD($result);
            }
        }
    }     
}
