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
          <h3 class="card-title">Laporan kinerja</h3>
          <small class="float-right">Report Date : {{date("d/m/Y")}}</small>
        </div>
        <div class="card-body">
            <dl class="row">
              <dt class="col-sm-2">Kawasan</dt>
              <dd class="col-sm-10" id="kawasan_kualitas_bangunan">{{ $dt['kd_kawasan'] }}</dd>
              <dt class="col-sm-2">Cluster</dt>
              <dd class="col-sm-10" id="cluster_kualitas_bangunan">{{ $dt['nm_cluster'] }}</dd>
              <dt class="col-sm-2">Site Manager</dt>
              <dd class="col-sm-10" id="sm_kualitas_bangunan">{{ $dt['nm_sm'] }}</dd>
              <dt class="col-sm-2">Periode</dt>
              <dd class="col-sm-10" id="periode_kualitas_bangunan">{{ $dt['periode_1'] }} sampai {{ $dt['periode_2'] }}</dd>
            </dl>
            <table id="tbl_rekap_defect" class="table table-bordered table-hover" onload="print()">
                <thead style="text-align: center;font-weight: bold;">                  
                  <tr>
                    <td style="width: 10px;vertical-align: middle;" rowspan="3">#</td>
                    <td style="vertical-align: middle;" rowspan="3">NAMA BUILDING INSPECTOR</td>
                    <td style="vertical-align: middle;" rowspan="2">JUMLAH UNIT</td>
                    <td colspan="2">TOTAL DEFECT</td>
                    <td colspan="3">AGEING</td>
                    <td >CYCLE TIME</td>
                  </tr>
                  <tr>
                    <td>JML DEFECT</td>
                    <td>DEFECT / UNIT</td>
                    <td colspan="3">SUDAH LEWAT</td>
                    <td >RATA-RATA UNIT/HARI (Min. 8 unit)</td>
                  </tr>
                  <tr>
                    <td>&nbsp;(a)</td>
                    <td>(b)</td>
                    <td>(c) = (b) / (a)</td>
                    <td>1-7</td>
                    <td>8-13</td>
                    <td>&gt;14</td>
                    <td>&nbsp;</td>
                  </tr>                  
                </thead>
              <tbody>{!! $tbl['row_tbl'] !!}</tbody>
            </table>
        </div>            
      </div>
    </div>
  </div>  
@endsection
