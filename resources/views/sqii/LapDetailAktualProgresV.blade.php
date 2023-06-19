@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit 		 = "{{ session('kd_unit') }}";
		var kd_lokasi 	 = "{{ session('kd_lokasi') }}";	
    var kd_kawasan   = "{{ $dt['kd_kawasan'] }}";  
    var kd_cluster   = "{{ $dt['kd_cluster'] }}"; 
    var nm_kawasan   = "{{ $dt['nm_kawasan'] }}"; 
    var nm_cluster   = "{{ $dt['nm_cluster'] }}"; 
    var periode        = "{{ $dt['periode'] }}"; 
    var nama         = "{{ $dt['nama'] }}";
    var blok    = "{{ $dt['blok'] }}";
    var nomor    = "{{ $dt['nomor'] }}";
    var nm_tipe     = "{{ $dt['nm_tipe'] }}";

    $(function() {
      $('#periode_detail_kualitas').html(periode);
      $('#kawasan_detail_kualitas').html(nm_kawasan);
      $('#cluster_detail_kualitas').html(nm_cluster);
      $('#sm_detail_kualitas').html(nama);
      $('#bi_detail_kualitas').html(blok+' / '+nomor);
      $('#jml_detail_kualitas').html(nm_tipe);
    });

    function lap_formulir_kualitas_bangunan(kd_kawasan1, kd_cluster1, nm_kawasan1, nm_cluster1, nm_sm1, periode_11, periode_21, user_id_bawahan, kd_kategori_defect, no_formulir, nama1, session_user_id,tahap_bangun){

      window.open('{{ url("sqii/lap_form_kualitas_bgn_c/") }}'+'/'+kd_kawasan1+'/'+kd_cluster1+'/'+nm_kawasan1+'/'+nm_cluster1+'/'+nm_sm1+'/'+periode_11+'/'+periode_21+'/'+user_id_bawahan+'/'+kd_kategori_defect+'/'+no_formulir+'/'+nama1+'/'+session_user_id+'/'+tahap_bangun);
    }  

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
            "kd_kategori_defect" : kd_kategori_defect,
            "tahap_bangun" : tahap_bangun
          },
          url: '{{ url("sqii/lap_detail_aktual_progres_c/") }}/print_dt',
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

@section('content')
    <div class="col-sm-12">
      <div id="card_detail_kualitas" class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Detail Aktual Progres</h3>
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
              <dt class="col-sm-2">Periode Temuan</dt>
              <dd class="col-sm-4" id="periode_detail_kualitas"></dd>
              <dt class="col-sm-2">Kawasan</dt>
              <dd class="col-sm-4" id="kawasan_detail_kualitas"></dd>
              <dt class="col-sm-2">Cluster</dt>
              <dd class="col-sm-4" id="cluster_detail_kualitas"></dd>
              <dt class="col-sm-2">Kontraktor</dt>
              <dd class="col-sm-4" id="sm_detail_kualitas"></dd>
              <dt class="col-sm-2">Blok / Nomor</dt>
              <dd class="col-sm-4" id="bi_detail_kualitas"></dd>
              <dt class="col-sm-2">Type</dt>
              <dd class="col-sm-4" id="jml_detail_kualitas"></dd>
            </dl>

            <table id="tbl_detail_kualitas" class="table table-bordered table-hover">
              <thead style="vertical-align: middle;">                  
                <tr>
                  <th style="width: 10px">#</th>
                  <th style="text-align: center;">URAIAN PEKERJAAN</th>
                  <th style="text-align: center;">BOBOT</th>
                  <th style="text-align: center;">PROGRES / UNIT</th>
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