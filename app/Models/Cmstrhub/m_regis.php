<?php

namespace App\Models\Cmstrhub;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use DB;

class m_regis extends Model
{ 
    public static function check_code($nomor, $code){
        $q = DB::table('TR_MST_TENANT')
            ->select('*')
            ->where('NOMOR', '=', $nomor)
            ->where('UNIQUE_CODE', '=', $code)
            ->exists();

        return $q;
    }

    public static function check_tenant($nomor, $code){
        $q = DB::table('TR_MST_TENANT')
            ->select('*')
            ->where('NOMOR', '=', $nomor)
            ->where('UNIQUE_CODE', '=', $code)
            ->get();

        return $q;
    }

    public static function check_user($email){
        $q = DB::table('TR_USERS')
            ->select('*')
            ->where('KD_USER', '=', $email)
            ->exists();

        return $q;
    }

    public static function check_unit($email, $kd_perusahaan){
        $q = DB::table('TR_USER_UNIT')
            ->select('*')
            ->where('KD_USER', '=', $email)
            ->where('KD_PERUSAHAAN', '=', $kd_perusahaan)
            ->exists();

        return $q;
    }

    public static function check_zona($email, $kd_perusahaan, $stok_id, $id_nasabah){
        $q = DB::table('TR_USER_ZONA')
            ->select('*')
            ->where('KD_USER', '=', $email)
            ->where('KD_PERUSAHAAN', '=', $kd_perusahaan)
            ->where('ID_NASABAH', '=', $id_nasabah)
            ->where('STOK_ID', '=', $stok_id)
            ->exists();

        return $q;
    }

    public static function insert($nama, $email, $phone, $password, $profile, $user, $tgl){
        $q = DB::table('TR_USERS')
            ->insert([
                'KD_USER'     => $email,
                'NAME'        => $nama,
                'EMAIL'       => $email,
                'PASSWORD'    => md5($password),
                'NO_HP'       => $phone,
                'FOTO_PROFIL' => $profile,
                'FLAG_AKTIF'  => 'Y',
                'CREATED_AT'  => $tgl,
                'CREATED_BY'  => 'TENANT',
            ]);

            return $q;
    }

    public static function insert_zona($kd_perusahaan, $email, $zona, $blok, $nomor, $no_pjs, $id_nasabah, $stok_id, $user, $tgl){
        $q = DB::table('TR_USER_ZONA')
            ->insert([
                'KD_PERUSAHAAN' => $kd_perusahaan,
                'KD_USER'       => $email,
                'KD_ZONA'       => $zona,
                'BLOK'          => $blok,
                'NOMOR'         => $nomor,
                'BLOKNO'        => $nomor,
                'NO_PJS'        => $no_pjs,
                'ID_NASABAH'    => $id_nasabah,
                'STOK_ID'       => $stok_id,
                'FLAG_PEMILIK'  => 'N',
                'FLAG_DEFAULT'  => 'N',
                'TGL_ENTRY'     => $tgl,
                'USER_ENTRY'    => 'TENANT',
            ]);

            return $q;

    }

    public static function insert_unit($kd_perusahaan, $email, $tgl){
        $q = DB::table('TR_USER_UNIT')
            ->insert([
                'KD_USER'       => $email,
                'KD_PERUSAHAAN' => $kd_perusahaan,
                'USER_ENTRY'    => 'TENANT',
                'TGL_ENTRY'     => $tgl
            ]);

            return $q;
    }

}