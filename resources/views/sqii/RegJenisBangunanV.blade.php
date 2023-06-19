@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit 		= "{{ session('kd_unit') }}";
		var kd_lokasi 	= "{{ session('kd_lokasi') }}";		

    $(function() {
      show_jenis_bangunan();
    });

    function show_jenis_bangunan() {

      $.ajax({
        type  : 'POST',
        url   : 'register_data_jns_bangunan_c/show_jenis_bangunan',
        data  : {
          "kd_unit"   : kd_unit,
          "kd_lokasi" : kd_lokasi,
          "_token"    : '{{ csrf_token() }}'
        },
        success : function(msg) {          
          var row;
          var no=1;

          $("#tbl_kawasan").find("tr:gt(0)").remove(); // CLEAR TABLE

          $.each(msg, function(x, y) {
            row += ''+
              '<tr>'+
                '<td>'+ no++ +'</td>'+
                '<td>'+y.kd_jenis+'</td>'+
                '<td>'+y.nm_jenis+'</td>'+
              '</tr>'+
            '';
          });

          // $('#tbl_kawasan').html(row);
          $('#tbl_kawasan').append(row);
        },
        error   : function(xhr) {
          read_error(xhr);
        }
      });
    }

    function sync_dt() {
      $('#v_loading').show();

      $.ajax({
        type  : 'POST',
        url   : 'register_data_jns_bangunan_c/sync_dt',
        data  : {
          "_token"  : '{{ csrf_token() }}',
          "kd_unit" : kd_unit,
          "kd_lokasi" : kd_lokasi
        },
        success : function(msg) {
          show_jenis_bangunan()
          $('#v_loading').hide();
        },
        error   : function(xhr) {
          $('#v_loading').hide();
          read_error(xhr);
        }
      });

      return false;
    }      
	</script>
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-12">
			<div class="card card-primary card-outline">
      	<div class="card-header">
          <nav class="navbar justify-content-between">
            <a class="form-brand">
              <h3 class="card-title">Register Jenis Bangunan</h3>
            </a>
            <a class="navbar-inline">
              <!-- {!! $dt['button'] !!} -->
              <button type="button" class="btn btn-default btn-flat" onclick="sync_dt()">
                <i class="fas fa-sync"></i>
              </button>
              <button type="button" class="btn btn-default btn-flat" onclick="search_dt()">
                <i class="fas fa-search"></i>
              </button>
            </a>
          </nav>
        </div>
        <!-- <section class="content-header"></section> -->
        <div class="card-body">
          <table id="tbl_kawasan" class="table table-bordered table-hover">
            <thead>                  
              <tr>
                <th style="width: 10px">#</th>
                <th style="width: 40px; text-align: center;">Kode</th>
                <th style="text-align: center;">Nama Jenis</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>            
        <div class="card-footer">
        </div>
        <!-- /.card-footer-->
        <div id="v_loading" class="overlay" style="display: none;">
          <i class="fas fa-2x fa-sync-alt fa-spin"></i>
        </div>            
      </div>
      <!-- /.card -->
	</div>
@endsection