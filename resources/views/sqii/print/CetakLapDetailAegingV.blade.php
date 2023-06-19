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
          <h3 class="card-title">Laporan Detil Ageing Kualitas </h3>
          <small class="float-right">Report Date : {{date("d/m/Y")}}</small>
        </div>
        <div class="card-body">
            <dl class="row">
              <dt class="col-sm-2">Periode Temuan</dt>
              <dd class="col-sm-4" id="periode_detail_ageing"></dd>
              <dt class="col-sm-2">Kawasan</dt>
              <dd class="col-sm-4" id="kawasan_detail_ageing">{{ $dt['kd_kawasan'] }}<dd>
              <dt class="col-sm-2">Cluster</dt>
              <dd class="col-sm-4" id="cluster_detail_ageing">{{ $dt['nm_cluster'] }}</dd>
              <dt class="col-sm-2">Site Manager</dt>
              <dd class="col-sm-4" id="sm_detail_ageing">{{ $dt['nm_sm'] }}</dd>
              <dt class="col-sm-2">Building Inspector</dt>
              <dd class="col-sm-4" id="bi_detail_ageing">{{ $dt['nama'] }}</dd>
              <dt class="col-sm-2">Jumlah Unit</dt>
              <dd class="col-sm-4" id="jml_detail_ageing">{{ $dt['jml_unit'] }}</dd>
              <dt class="col-sm-2">Total Unit(a)</dt>
              <dd class="col-sm-4" id="tot_detail_ageing">{{ $dt['tot_unit'] }}</dd>
            </dl>
            <table id="tbl_rekap_defect" class="table table-bordered table-hover" onload="print()">
              <thead style="text-align: center;font-weight: bold;">                  
                <tr>
                  <th style="width: 10px">#</th>
                  <th style="text-align: center;">Blok/unit</th>
                  <th style="text-align: center;">No. Formulir</th>
                  <th style="text-align: center;">Jenis Pekerjaan</th>
                  <th style="text-align: center;">Item Defect</th>
                  <th style="text-align: center;">Deskripsi Defect</th>
                  <th style="text-align: center;">Kategori Defect</th>
                  <th style="text-align: center;">Aeging (hari)</th>
                </tr>
              </thead>
              <tbody>{!! $tbl['row_tbl'] !!}</tbody>
            </table>
        </div>            
      </div>
    </div>
  </div>  
@endsection
