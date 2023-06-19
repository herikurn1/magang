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
      <div id="card_rekap_defect" >
        <div class="card-header">
          <h3 class="card-title">Patern Cycle Time</h3>
          <small class="float-right">Report Date : {{date('m/d/Y')}}</small>
        </div>
        <div class="card-body">
            <dl class="row">
              <dt class="col-sm-2">Nama</dt>
              <dd class="col-sm-10" id="bi_cycle_time">{{ $dt['nama'] }}</dd>
              <dt class="col-sm-2">Cluster</dt>
              <dd class="col-sm-10" id="cluster_cycle_time">{{ $dt['nm_cluster'] }}</dd>
              <dt class="col-sm-2">Bulan</dt>
              <dd class="col-sm-10" id="bulan_cycle_time">{{ $dt['periode_1'] }} sampai {{ $dt['periode_2'] }}</dd>
              <dt class="col-sm-2">Jumlah Unit</dt>
              <dd class="col-sm-10" id="jml_cycle_time">{{ $dt['jml_unit'] }}</dd>
            </dl>
            <table id="tbl_cycle_time" class="table table-bordered table-hover">{!! $tbl['row_tbl'] !!}</table>
        </div>            
      </div>
    </div>
  </div>  
@endsection
