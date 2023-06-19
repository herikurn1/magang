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
          <h3 class="card-title">Laporan Grafik Item Defect</h3>
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
            <div class="row">
              <div class="col-md-6">
                <div id="piechart" style="width: 600px; height: 500px;"></div>
              </div>
              <div class="col-md-6">
                <div id="piechart2" style="width: 600px; height: 500px;"></div>
              </div>
            </div> 
            <div class="row">
              <div class="col-md-6">
                <div id="piechart3" style="width: 600px; height: 500px;"></div>
              </div>                
            </div>
        </div>            
      </div>
    </div>
  </div>  
@endsection
