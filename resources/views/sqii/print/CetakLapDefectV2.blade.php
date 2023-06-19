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
          <h3 class="card-title">Rekap Defect</h3>
          <small class="float-right">Report Date : {{date("d/m/Y")}}</small>
        </div>
        <div class="card-body">
            <dl class="row">
              <dt class="col-sm-2">Kawasan</dt>
              <dd class="col-sm-4" id="kawasan_rekap_defect">{{ $dt['kd_kawasan'] }}</dd>
              <dt class="col-sm-2">Cluster</dt>
              <dd class="col-sm-4" id="cluster_rekap_defect">{{ $dt['nm_cluster'] }}</dd>
              <dt class="col-sm-2">Site Manager</dt>
              <dd class="col-sm-4" id="sm_rekap_defect">{{ $dt['nm_sm'] }}</dd>
              <dt class="col-sm-2">Building Inspector</dt>
              <dd class="col-sm-4" id="bi_rekap_defect">{{ $dt['nama'] }}</dd>
              <dt class="col-sm-2">Jumlah Unit(a)</dt>
              <dd class="col-sm-4" id="jml_unit_rekap_defect">{{ $dt['jml_unit'] }}</dd>
              <dt class="col-sm-2">Total Unit</dt>
              <dd class="col-sm-4" id="tot_unit_rekap_defect">{{ $dt['tot_unit'] }}</dd>
            </dl>
            <table id="tbl_rekap_defect" class="table table-bordered table-hover" onload="print()">
              <thead style="text-align: center;font-weight: bold;">                  
                <tr>
                  <th rowspan="2" style="width: 10px;vertical-align: middle;">#</th>
                  <th rowspan="2" style="vertical-align: middle;">JENIS PEKERJAAN</th>
                  <th colspan="2">DEFECT SEDANG</th>
                  <th colspan="2">DEFECT BERAT</th>
                  <th colspan="2">TOTAL DEFECT (d)</th>
                </tr>
                <tr>
                  <td>JUMLAH(b)</td>
                  <td>(b)/(a)</td>
                  <td>JUMLAH(c)</td>
                  <td>(c)/(a)</td>
                  <td>JUMLAH</td>
                  <td>(d)/(a)</td>
                </tr>
              </thead>
              <tbody>{!! $tbl['row_tbl'] !!}</tbody>
            </table>
        </div>            
      </div>
    </div>
  </div>  
@endsection
