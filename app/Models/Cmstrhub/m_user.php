<?php

namespace App\Models\Cmstrhub;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use DB;

class m_user extends Model
{ 
    public static function search_dt(){
        $q = DB::table('TR_USERS')
            ->select('*')
            ->get();

        return $q;
    }

    public static function insert($email, $nm_user, $password, $no_hp, $flag_aktif, $tradename, $user, $tgl){
        $q = DB::table('TR_USERS')
            ->insert([
                'KD_USER'      => $email, 
                'NAME'         => $nm_user, 
                'EMAIL'        => $email, 
                'PASSWORD'     => md5($password),
                'NO_HP'        => $no_hp,
                'FLAG_AKTIF'   => $flag_aktif,
                'TRADENAME'    => $tradename,
                'CREATED_AT'   => $tgl,
                'CREATED_BY'   => $user
            ]);

        return $email;
    }

    public static function update_dt($id, $email, $nm_user, $no_hp, $flag_aktif, $tradename, $user, $tgl){
        $q = DB::table('TR_USERS')
            ->where('ID', '=', $id)
            ->update([
                'NAME'         => $nm_user, 
                'EMAIL'        => $email, 
                'NO_HP'        => $no_hp,
                'FLAG_AKTIF'   => $flag_aktif,
                'TRADENAME'    => $tradename,
                'CREATED_AT'   => $tgl,
                'CREATED_BY'   => $user
            ]);

        return $q;
    }

    public static function update_dt_with_password($id, $email, $nm_user, $password, $no_hp, $flag_aktif, $tradename, $user, $tgl){
        $q = DB::table('TR_USERS')
            ->where('ID', '=', $id)
            ->update([
                'NAME'         => $nm_user, 
                'EMAIL'        => $email, 
                'PASSWORD'     => md5($password),
                'NO_HP'        => $no_hp,
                'FLAG_AKTIF'   => $flag_aktif,
                'TRADENAME'    => $tradename,
                'CREATED_AT'   => $tgl,
                'CREATED_BY'   => $user
            ]);

        return $q;
    }

    public static function cek_unit($email, $kd_unit){
        $q = DB::table('TR_USER_UNIT')
            ->select('KD_USER')
            ->where('KD_USER', '=', $email)
            ->where('KD_PERUSAHAAN', '=', $kd_unit)
            ->get();

        return $q;
    }

    public static function insert_unit($email, $add_unit, $user, $tgl){
        $q = DB::table('TR_USER_UNIT')
            ->insert([
                'KD_USER'       => $email, 
                'KD_PERUSAHAAN' => $add_unit, 
                'USER_ENTRY'    => $user,
                'TGL_ENTRY'     => $tgl
            ]);

        return $q;
    }

    public static function cek_zona($add_unit_zona, $email, $add_zona, $add_pjs){
        $q = DB::table('TR_USER_ZONA')
            ->select('KD_PERUSAHAAN')
            ->where('KD_PERUSAHAAN', '=', $add_unit_zona)
            ->where('KD_USER', '=', $email)
            ->where('KD_ZONA', '=', $add_zona)
            ->where('NO_PJS', '=', $add_pjs)
            ->get();

        return $q;
    }

    public static function insert_zona($add_unit_zona, $email, $add_zona, $add_stok, $add_blok, $add_nomer, $add_pjs, $add_pemilik, $add_default, $add_nasabah_id, $add_stok_id, $user, $tgl){
        $q = DB::table('TR_USER_ZONA')
            ->insert([
                'KD_PERUSAHAAN' => $add_unit_zona,
                'KD_USER'       => $email, 
                'KD_ZONA'       => $add_zona, 
                'BLOK'          => $add_blok,
                'NOMOR'         => $add_nomer, 
                'BLOKNO'        => $add_stok,
                'NO_PJS'        => $add_pjs,
                'ID_NASABAH'    => $add_nasabah_id,
                'STOK_ID'       => $add_stok_id,
                'FLAG_PEMILIK'  => $add_pemilik,
                'FLAG_DEFAULT'  => $add_default,
                'USER_ENTRY'    => $user,
                'TGL_ENTRY'     => $tgl
            ]);

        return $q;
    }

    public static function get_unit(){
        $q = DB::table('TR_MST_TENANT')
            ->select('KD_PERUSAHAAN AS UNIT_ID')
            ->groupBy('KD_PERUSAHAAN')
            ->get();

        return $q;
    } 

    public static function get_zona($kd_unit){
        $q = DB::table('TR_MST_TENANT AS A')
            ->select('A.ZONE_CD', 'B.NM_ZONA')
            ->leftJoin('TR_MST_ZONA AS B', 'A.ZONE_CD', '=', 'B.KD_ZONA')
            ->where('A.KD_PERUSAHAAN', '=', $kd_unit)
            ->where('A.FLAG_AKTIF', '=', 'A')
            ->groupBy('A.ZONE_CD', 'B.NM_ZONA')
            ->get();

        return $q;
    }

    public static function get_stok($kd_unit, $kd_zona){
        $q = DB::table('TR_MST_TENANT')
            ->select('NO_PJS', 'NOMOR')
            ->where('KD_PERUSAHAAN', '=', $kd_unit)
            ->where('ZONE_CD', '=', $kd_zona)
            ->get();

        return $q;
    }

    public static function get_blok($kd_unit, $kd_zona, $kd_stok){
        $q = DB::table('TR_MST_TENANT')
            ->select('*')
            ->where('KD_PERUSAHAAN', '=', $kd_unit)
            ->where('ZONE_CD', '=', $kd_zona)
            ->where('NOMOR', '=', $kd_stok)
            ->get();

        return $q;
    }

    public static function show_unit($user_id){
        $q = DB::table('TR_USER_UNIT AS A')
            ->select('UNIT_ID', 'NAMA')
            ->leftjoin('T_UNIT AS B', 'A.KD_PERUSAHAAN', '=', 'B.UNIT_ID')
            ->where('KD_USER', '=', $user_id)
            ->get();

        return $q;
    }

    public static function show_zona($user_id){
        $q = DB::table('TR_USER_ZONA AS A')
            ->select('A.*', 'B.NM_ZONA')
            ->leftJoin('TR_MST_ZONA AS B', 'A.KD_ZONA', '=', 'B.KD_ZONA')
            ->where('KD_USER', '=', $user_id)
            ->get();

        return $q;
    }

    public static function delete_unit($kd_unit, $user_id){
        $q = DB::table('TR_USER_UNIT')
            ->where('KD_USER', '=', $user_id)
            ->where('KD_PERUSAHAAN', '=', $kd_unit)
            ->delete();

        return $q;
    }

    public static function delete_zona($kd_unit, $kd_user, $kd_zona, $no_pjs){
        $q = DB::table('TR_USER_ZONA')
            ->where('KD_USER', '=', $kd_user)
            ->where('KD_PERUSAHAAN', '=', $kd_unit)
            ->where('KD_ZONA', '=', $kd_zona)
            ->where('NO_PJS', '=', $no_pjs)
            ->delete();

        return $q;
    }
}
