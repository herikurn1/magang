@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit 		= "{{ session('kd_unit') }}";
		var kd_lokasi 	= "{{ session('kd_lokasi') }}";		

    $(function() {
      data_cluster();
      data_tipe();
      data_blok();
    });

    function data_blok_no() {
        var kd_kawasan = $('#kd_kawasan').val();
        var kd_cluster = $('#kd_cluster').val();
        var kd_tipe = $('#kd_tipe').val();
        var blok = $('#kd_blok').val();

      $.ajax({
        type  : 'POST',
        url   : 'list_alokasi_petugas_c/data_blok_no',
        data  : {
          "kd_unit"   : kd_unit,
          "kd_lokasi" : kd_lokasi,
          "kd_kawasan" : kd_kawasan,
          "kd_cluster" : kd_cluster,
          "kd_tipe" : kd_tipe,
          "blok" : blok,            
          "_token"    : '{{ csrf_token() }}'
        },
        success : function(msg) {          
          var row;
          var no=1;

          //$('#tbl_kawasan tbody').empty();

          $("#tbl_kawasan").find("tr:gt(0)").remove(); // CLEAR TABLE
          $.each(msg, function(x, y) {
            row += ''+
              '<tr>'+
                '<td>'+ no++ +'</td>'+
                '<td>'+y.blok+'</td>'+
                '<td>'+y.nomor+'</td>'+
                '<td>'+y.nm_tipe+'</td>'+
                '<td>'+y.ktt+'</td>'+
                '<td>'+y.bi+'</td>'+
                '<td>'+y.sm+'</td>'+
                '<td>'+y.qc+'</td>'+
                '<td></td>'+
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
        url   : 'list_alokasi_petugas_c/sync_dt',
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

    function data_cluster() {
        var kd_kawasan = $('#kd_kawasan').val();
        var row;
        
        $.ajax({
            type: 'POST',
            url: 'list_alokasi_petugas_c/data_cluster',
            cache: false,
            data: {
              "_token"  : '{{ csrf_token() }}',
              "kd_unit" : kd_unit,
              "kd_lokasi" : kd_lokasi,
              "kd_kawasan" : kd_kawasan                
            },
            success: function(msg){

                row = '<select class="form-control col-sm-12" name="kd_cluster" id="kd_cluster" onchange="data_tipe(); data_blok(); data_blok_no();">';
                $.each(msg, function(x, y) {
                  row += '<option value="'+ y.kd_cluster +'">'+ y.nm_cluster +'</option>';
                });
                row += '</select>';

                $('#div_cluster').html(row);
                data_tipe(); 
                //data_blok();
                // data_tipe();
                // data_blok_no();
            },
            error: function(){
                $('#v_loading').hide();
                alert('Proses Get Cluster Gagal');
            }
        });
        
        return false;
    }

    function data_tipe() {
        var kd_kawasan = $('#kd_kawasan').val();
        var kd_cluster = $('#kd_cluster').val();
        var row;
        
        $.ajax({
            type: 'POST',
            url: 'list_alokasi_petugas_c/data_tipe',
            cache: false,
            data: {
              "_token"  : '{{ csrf_token() }}',
              "kd_unit" : kd_unit,
              "kd_lokasi" : kd_lokasi,
              "kd_kawasan" : kd_kawasan,
              "kd_cluster" : kd_cluster                
            },
            success: function(msg){

                row = '<select class="form-control col-sm-12" name="kd_tipe" id="kd_tipe" onchange="data_blok(); data_blok_no();">';
                $.each(msg, function(x, y) {
                  row += '<option value="'+ y.kd_tipe +'">'+ y.kd_tipe +' = '+ y.nm_tipe +'</option>';
                });
                row += '</select>';

                $('#div_tipe').html(row);
                data_blok();
                
                //data_blok_no();
            },
            error: function(){
                $('#v_loading').hide();
                alert('Proses Get Cluster Gagal');
            }
        });
        
        return false;
    }

    function data_blok() {
        var kd_kawasan = $('#kd_kawasan').val();
        var kd_cluster = $('#kd_cluster').val();
        var kd_tipe = $('#kd_tipe').val();
        var row;
        
        $.ajax({
            type: 'POST',
            url: 'list_alokasi_petugas_c/data_blok',
            cache: false,
            data: {
              "_token"  : '{{ csrf_token() }}',
              "kd_unit" : kd_unit,
              "kd_lokasi" : kd_lokasi,
              "kd_kawasan" : kd_kawasan,
              "kd_cluster" : kd_cluster,
              "kd_tipe" : kd_tipe                 
            },
            success: function(msg){

                row = '<select class="form-control col-sm-12" name="kd_blok" id="kd_blok" onchange="data_blok_no()">';
                $.each(msg, function(x, y) {
                  row += '<option value="'+ y.blok +'">'+ y.blok +'</option>';
                });
                row += '</select>';

                $('#div_blok').html(row);
                data_blok_no();
            },
            error: function(){
                $('#v_loading').hide();
                alert('Proses Get Cluster Gagal');
            }
        });
        
        return false;
    }

    function add_dt() {
        $('#saveBtnVal').val("create");
        $('#kd_kawasan_add').val('');
        $('#kd_cluster_add').val('');
        $('#kd_jenis_add').val('');
        $('#kd_tipe_add').val('');
        $('#InputForm').trigger("reset");
      return false;
    }        

    function save_dt() {
      $('#saveBtn').click();
    }

    function edit_dt(blok,nomor,kd_tipe) {
      $('#modelHeading').html("Edit Item Defect");
      $('#saveBtnVal').val("edit");
      $('#ajaxModel').modal('show');
      $('#kd_item_defect').val(blok);
      $('#nm_item_defect').val(nomor);
      $('#nm_item_defect').val(kd_tipe);
    }

    function delete_dt(kd_kawasan,kd_cluster,blok,nomor) {
        $.ajax({
          data  : {
            "kd_kawasan"    : kd_kawasan,
            "kd_cluster"    : kd_cluster,
            "blok"          : blok,
            "nomor"         : nomor,
            "_token"        : '{{ csrf_token() }}'
          },
          url: "list_alokasi_petugas_c/delete_dt",
          type: "POST",

          success: function (data) {
             show_cluster();
          },
          error: function (data) {
              console.log('Error:', data);
          }
      });

        return false;
    } 

    function save_process() {
      var data_all      = $('#InputForm').serializeArray();
      var kd_kawasan    = $('#kd_kawasan').val();
      var kd_cluster    = $('#kd_cluster').val();

        $.ajax({
          data: data_all,
          url: "list_alokasi_petugas_c/save",
          type: "POST",

          success: function (data) {

              $('#InputForm').trigger("reset");
              add_dt();
              //table.draw();

          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
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
                <h3 class="card-title">List Alokasi Petugas </h3>
              </a>
              <a class="navbar-inline">
                <!-- {!! $dt['button'] !!} -->
                <!-- <button type="button" class="btn btn-default btn-flat" onclick="sync_dt()">
                  <i class="fas fa-sync"></i>
                </button> -->
                <button type="button" class="btn btn-default btn-flat" onclick="search_dt()">
                  <i class="fas fa-search"></i>
                </button>
              </a>
            </nav>
          </div>
          <div class="card-body">
            <table id="tbl_kawasan" class="table table-bordered table-hover">
              <div class="form-group">
                <label for="kd_kawasan" class="col-sm-12">Kawasan</label>
                <div class="col-sm-12">{!! $dt['kd_kawasan'] !!}</div>
              </div>
              <div class="form-group">
                <label for="kd_cluser" class="col-sm-12">Cluster</label>
                <div id="div_cluster" class="col-sm-12"></div>
              </div>
              <div class="form-group">
                <label for="kd_tipe" class="col-sm-2 control-label">Tipe</label>
                <div id="div_tipe" class="col-sm-12"></div>
              </div>
              <div class="form-group">
                <label for="kd_blok" class="col-sm-2 control-label">Blok</label>
                <div id="div_blok" class="col-sm-12"></div>
              </div>
              <thead>                  
                <tr >
                  <th style="width: 10px">#</th>
                  <th style="width: 130px; text-align: center;">Blok</th>
                  <th style="text-align: center;">Nomor</th>
                  <th style="text-align: center;">Tipe</th>
                  <th style="text-align: center;">Kontraktor</th>
                  <th style="text-align: center;">Pengawas</th>
                  <th style="text-align: center;">Site Manager</th>
                  <th style="text-align: center;">Petugas QC</th>
                  <th style="text-align: center;">Deskripsi</th>
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
          <!-- /.card -->
        </div>
  </div>
</div>
@endsection