<?php

namespace App\Models\Sqii;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;

class StokImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new SQII_STOK([
            'KD_KAWASAN'        => $row[kd_kawasan],
            'KD_CLUSTER'        => $row[kd_cluster],
            'BLOK'              => $row[blok],
            'NOMOR'             => $row[nomor],
            'STOK_ID'           => $row[stok_id],
            'KD_JENIS'          => $row[kd_jenis],
            'KD_TIPE'           => $row[kd_tipe],
        ]);      
    }
}