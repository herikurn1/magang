@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit 		 = "{{ session('kd_unit') }}";
		var kd_lokasi 	 = "{{ session('kd_lokasi') }}";	
    var nm_cluster   = "{{ $dt['nm_cluster'] }}"; 
    var nama         = "{{ $dt['nama'] }}";
    var jml_unit     = "{{ $dt['jml_unit'] }}";
    var periode_1    = "{{ $dt['periode_1'] }}";
    var periode_2    = "{{ $dt['periode_2'] }}";
    var kd_kawasan    = "{{ $dt['kd_kawasan'] }}";
    var kd_cluster    = "{{ $dt['kd_cluster'] }}";
    var user_id_bawahan    = "{{ $dt['user_id_bawahan'] }}";
    var tot_unit    = "{{ $dt['tot_unit'] }}";
    var tahap_bangun    = "{{ $dt['tahap_bangun'] }}";

    $(function() {
      $('#bi_cycle_time').html(nama);
      $('#cluster_cycle_time').html(nm_cluster);
      $('#bulan_cycle_time').html(periode_1+' sampai '+periode_2);
      $('#jml_cycle_time').html(jml_unit);
      changeBGColor();
    });

    function changeBGColor() {
      var cols = document.getElementsByClassName('content-wrapper');
      for(i = 0; i < cols.length; i++) {
        cols[i].style.backgroundColor = 'white';
      }
    }
    
    function print_dt(){

        $.ajax({
          data  : {
            "kd_kawasan"    : kd_kawasan,
            "kd_cluster"    : kd_cluster,
            "nm_cluster"    : nm_cluster,
            "periode_1"     : periode_1,
            "periode_2"     : periode_2,
            "user_id_bawahan" : user_id_bawahan,
            "nama"          : nama,
            "jml_unit"      : jml_unit, 
            "tot_unit"      : tot_unit,
            "tahap_bangun"  : tahap_bangun,
          },
          url: '{{ url("sqii/lap_patern_cycle_time_c/") }}/print_dt',
          type: "POST",
          dataType: 'html',
          headers : { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
          success: function (data) {
            var w = window.open();
            w.document.title = 'Daftar Member';
            $(w.document.body).html(data);
            $('#v_loading').hide();
          },
          error: function (data) {
              console.log('Error:', data);
          }
      });
        return false;
    }
	</script>
@endsection
<!-- card card-primary card-outline -->
@section('content')
    <div class="col-sm-12">
      <div id="card_cycle_time" class="">
        <div class="card-header">
          <h3 class="card-title">Patern Cycle Time </h3>
          <div class="card-tools">
            <a class="navbar-inline">
              <button type="button" class="btn btn-default btn-flat" onclick="print_dt()">
                <i class="fas fa-print"></i>
              </button>
            </a>
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
            <dl class="row">
              <dt class="col-sm-2">Nama</dt>
              <dd class="col-sm-10" id="bi_cycle_time"></dd>
              <dt class="col-sm-2">Cluster</dt>
              <dd class="col-sm-10" id="cluster_cycle_time"></dd>
              <dt class="col-sm-2">Bulan</dt>
              <dd class="col-sm-10" id="bulan_cycle_time"></dd>
              <dt class="col-sm-2">Jumlah Unit</dt>
              <dd class="col-sm-10" id="jml_cycle_time"></dd>
            </dl>
            <table id="tbl_cycle_time" class="table table-bordered table-hover">{!! $tbl['row_tbl'] !!}</table>
        </div>            
      </div>
          <!-- /.card -->
    </div>
	</div>

  <div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="CustomerForm" name="CustomerForm" class="form-horizontal" autocomplete="off" onsubmit="return save_process()">
                   <input type="hidden" name="saveBtnVal" id="saveBtnVal" value="create">
                   <input type="hidden" name="kd_item_defect" id="kd_item_defect" value="">
                    <div class="form-group">
                        <label for="nm_item_defect" class="col-sm-2 control-label">Nama</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="nm_item_defect" name="nm_item_defect" placeholder="Enter Name" value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                     <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save</button>
                     <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                    @csrf
                </form>
            </div>
        </div>
    </div>
  </div>    
@endsection