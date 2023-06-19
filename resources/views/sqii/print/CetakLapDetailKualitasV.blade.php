@extends('layouts.template_print')

@section('js')
  <script type="text/javascript">
    $(function() {
      // print();
    });
  </script>
@endsection
@section('content')
    <div class="col-sm-12">
      <div id="card_rekap_defect" class="invoice p-3 mb-3">
        <div class="card-header">
          <h3 class="card-title">Detail Laporan Kualitas</h3>
          <small class="float-right">Report Date : {{date("d/m/Y")}}</small>
        </div>
        <div class="card-body">
            <dl class="row">
              <dt class="col-sm-2">Periode Temuan</dt>
              <dd class="col-sm-4" id="periode_detail_kualitas">{{ $dt['periode_1'] }} sampai {{ $dt['periode_2'] }}</dd>
              <dt class="col-sm-2">Kawasan</dt>
              <dd class="col-sm-4" id="kawasan_detail_kualitas">{{ $dt['kd_kawasan'] }}</dd>
              <dt class="col-sm-2">Cluster</dt>
              <dd class="col-sm-4" id="cluster_detail_kualitas">{{ $dt['nm_cluster'] }}</dd>
              <dt class="col-sm-2">Site Manager</dt>
              <dd class="col-sm-4" id="sm_detail_kualitas">{{ $dt['nm_sm'] }}</dd>
              <dt class="col-sm-2">Building Inspector</dt>
              <dd class="col-sm-4" id="bi_detail_kualitas">{{ $dt['nama'] }}</dd>
              <dt class="col-sm-2">Jumlah Unit</dt>
              <dd class="col-sm-4" id="jml_detail_kualitas">{{ $dt['jml_unit'] }}</dd>
              <dt class="col-sm-2">Total Unit</dt>
              <dd class="col-sm-4" id="tot_detail_kualitas">{{ $dt['tot_unit'] }}</dd>
            </dl>
            <table id="tbl_rekap_defect" class="table table-bordered table-hover" onload="print()">
              <thead style="vertical-align: middle;">                  
                <tr>
                  <th style="width: 10px">#</th>
                  <th style="text-align: center;">BLOK/UNIT</th>
                  <th style="text-align: center;">NO FORMULIR</th>
                  <th style="text-align: center;">JENIS PEKERJAAN</th>
                  <th style="text-align: center;">ITEM DEFECT</th>
                  <th style="text-align: center;">DESKRIPSI DEFECT</th>
                  <th style="text-align: center;">KATEGORI DEFECT</th>
                  <th style="text-align: center;">AEGING(HARI)</th>
                  <th style="text-align: center;">LOGIN</th>
                </tr>
              </thead>
              <tbody>{!! $tbl['row_tbl'] !!}</tbody>
            </table>
        </div>            
      </div>
    </div>
  </div>  
@endsection
