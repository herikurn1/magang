<?php

namespace App\Models\Sqii;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;

class TipeRumahImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new SQII_TIPE_RUMAH([
            'KD_KAWASAN'        => $row[kd_kawasan],
            'KD_CLUSTER'        => $row[kd_cluster],
            'NM_TIPE'           => $row[nm_tipe],
            'KD_JENIS'          => $row[kd_jenis],
            'KD_TIPE'           => $row[kd_tipe],
        ]);      
    }
}