@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit 		 = "{{ session('kd_unit') }}";
		var kd_lokasi 	 = "{{ session('kd_lokasi') }}";	
    var session_user_id   = "{{ session('user_id') }}";
    var kd_kawasan   = "{{ $dt['kd_kawasan'] }}";  
    var kd_cluster   = "{{ $dt['kd_cluster'] }}"; 
    var nm_kawasan   = "{{ $dt['nm_kawasan'] }}"; 
    var nm_cluster   = "{{ $dt['nm_cluster'] }}"; 
    var kd_periode   = "{{ $dt['kd_periode'] }}"; 
    var periode      = "{{ $dt['periode'] }}";
    var nama         = "{{ $dt['nama'] }}";

    var periode1 = periode.replace("-", "#");
    var periode2 = periode1.replaceAll("-", "/");

    $(function() {
      $('#kawasan_rekap_defect').html(nm_kawasan);
      $('#cluster_rekap_defect').html(nm_cluster);
      $('#sm_rekap_defect').html(nama);
      $('#bi_rekap_defect').html(periode2);
    });

    function print_dt(){

        $.ajax({
          data  : {
            "kd_kawasan"    : kd_kawasan,
            "kd_cluster"    : kd_cluster,
            "nm_kawasan"    : nm_kawasan,
            "nm_cluster"    : nm_cluster, 
            "nm_sm"         : nm_sm,    
            "periode_1"     : periode_1,
            "periode_2"     : periode_2,
            "user_id"       : user_id,
            "user_id_bawahan" : user_id_bawahan,
            "nama"          : nama,
            "jml_unit"      : jml_unit, 
            "jml_defect"    : jml_defect,    
            "total_defect"  : total_defect,
            "tot_unit"      : tot_unit,
            "session_user_id" : session_user_id,
            "tahap_bangun"  : tahap_bangun
          },
          url: '{{ url("sqii/lap_detail_progres_kontraktor_c/") }}/print_dt',
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

    function detail_aktual_progress(kd_kawasan1,kd_cluster1,nm_kawasan1,nm_cluster1,kd_periode1,periode21,user_id1,nama1, blok1, nomor1, nm_tipe1){

    
      window.open('{{ url("sqii/lap_detail_aktual_progres_c/") }}'+'/'+kd_kawasan1+'/'+kd_cluster1+'/'+nm_kawasan1+'/'+nm_cluster1+'/'+kd_periode1+'/'+periode21+'/'+user_id1+'/'+nama1+'/'+blok1+'/'+nomor1+'/'+nm_tipe1+'/'+session_user_id);
    }   

	</script>
@endsection

@section('content')
    <div class="col-sm-12">
      <div id="card_rekap_defect" class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Detail Progress per Kontraktor</h3>
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
              <dt class="col-sm-2">Kawasan</dt>
              <dd class="col-sm-4" id="kawasan_rekap_defect"></dd>
              <dt class="col-sm-2">Cluster</dt>
              <dd class="col-sm-4" id="cluster_rekap_defect"></dd>
              <dt class="col-sm-2">Kontraktor</dt>
              <dd class="col-sm-4" id="sm_rekap_defect"></dd>
              <dt class="col-sm-2">Periode</dt>
              <dd class="col-sm-4" id="bi_rekap_defect"></dd>
            </dl>

            <table id="tbl_rekap_defect" class="table table-bordered table-hover" onload="print()">
              <thead style="text-align: center;font-weight: bold;">                  
                <tr>
                  <th style="width: 10px;vertical-align: middle;">#</th>
                  <th style="vertical-align: middle;">BLOK / NOMOR</th>
                  <th >TYPE</th>
                  <th >RENCANA (%)</th>
                  <th >AKTUAL (%)</th>
                  <th >DEVIASI (%)</th>
                </tr>
              </thead>
              <tbody>{!! $tbl['row_tbl'] !!}</tbody>
            </table>
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