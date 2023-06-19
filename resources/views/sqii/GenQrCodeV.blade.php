@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit 		= "{{ session('kd_unit') }}";
		var kd_lokasi 	= "{{ session('kd_lokasi') }}";		

    $(function() {
      data_cluster();
    });

    function item_defect() {

      var kd_kategori = $('#kd_kategori').val();

      $.ajax({
        type  : 'POST',
        url   : 'generate_qr_code_c/show_data_item_defect',
        data  : {
          "kd_kategori"   : kd_kategori,
          "_token"    : '{{ csrf_token() }}'
        },
        success : function(msg) {          
          var row;
          var no=1;

          $('#tbl_kawasan tbody').empty();

          $.each(msg, function(x, y) {
            row += ''+
              '<tr>'+
                '<td>'+ no++ +'</td>'+
                '<td>'+y.nm_item_defect+'</td>'+
                '<td style="text-align: center;">'+
                  '<button type="button" class="btn btn-info btn-sm" onclick="edit_dt(\''+y.kd_item_defect+'\',\''+y.nm_item_defect+'\')">Edit</button>&nbsp'+
                  '<button type="button" class="btn btn-info btn-sm" onclick="delete_dt(\''+y.kd_item_defect+'\')">Hapus</button></td>'+
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

    function add_dt() {
        $('#saveBtnVal').val("create");
        $('#kd_lantai').val('');
        $('#CustomerForm').trigger("reset");
        $('#modelHeading').html("Tambah Item Defect");
        $('#ajaxModel').modal('show');
      return false;
    }        

    function save_dt() {
      $('#saveBtn').click();
    }

    function edit_dt(kd_item_defect,nm_item_defect) {
      $('#modelHeading').html("Edit Item Defect");
      $('#saveBtnVal').val("edit");
      $('#ajaxModel').modal('show');
      $('#kd_item_defect').val(kd_item_defect);
      $('#nm_item_defect').val(nm_item_defect);
    }

    function delete_dt(kd_item_defect) {
        $.ajax({
          data  : {
            "kd_item_defect"   : kd_item_defect,
            "_token"    : '{{ csrf_token() }}'
          },
          url: "generate_qr_code_c/delete_dt",
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
      var data_all      = $('#CustomerForm').serializeArray();
      var kd_kategori = $('#kd_kategori').val();
      if (kd_kategori == ''){
        $('#ajaxModel').modal('hide');
        alert('Pilih Kode Kategori');
        return false;
      }

        $.ajax({
          data: data_all,
          url: "generate_qr_code_c/save",
          type: "POST",

          success: function (data) {

              $('#CustomerForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              item_defect();
              //table.draw();

          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
              $('#ajaxModel').modal('hide');
          }
      });

        return false;
    }   

    function data_cluster() {
        var kd_kawasan = $('#kd_kawasan').val();
        var row;
        
        $.ajax({
            type: 'POST',
            url: 'generate_qr_code_c/data_cluster',
            cache: false,
            data: {
              "_token"  : '{{ csrf_token() }}',
              "kd_unit" : kd_unit,
              "kd_lokasi" : kd_lokasi,
              "kd_kawasan" : kd_kawasan                
            },
            success: function(msg){

                row = '<select class="form-control col-sm-12" name="kd_cluster" id="kd_cluster" onchange="clear_data()">';
                $.each(msg, function(x, y) {
                  row += '<option value="'+ y.kd_cluster +'">'+ y.nm_cluster +'</option>';
                });
                row += '</select>';

                $('#div_cluster').html(row);
                
            },
            error: function(){
                $('#v_loading').hide();
                alert('Proses Get Cluster Gagal');
            }
        });
        
        return false;
    }    

    function clear_data(){
      $('#tbl_kawasan tbody').empty();
    }

    function search_dt() {
        var kd_kawasan = $('#kd_kawasan').val();
        var kd_cluster = $('#kd_cluster').val();

      $.ajax({
        type  : 'POST',
        url   : 'generate_qr_code_c/search_dt',
        data  : {
          "kd_unit"   : kd_unit,
          "kd_lokasi" : kd_lokasi,
          "kd_kawasan" : kd_kawasan,
          "kd_cluster" : kd_cluster,            
          "_token"    : '{{ csrf_token() }}'
        },
        success : function(msg) {          
          var row;
          var no=1;

          $('#tbl_kawasan tbody').empty();

          $("#tbl_kawasan").find("tr:gt(0)").remove(); // CLEAR TABLE
          $.each(msg, function(x, y) {
            row += ''+
              '<tr>'+
                '<td>'+ no++ +'</td>'+
                '<td>'+y.blok+'</td>'+
                '<td>'+y.nomor+'</td>'+
                '<td>'+y.nm_tipe+'</td>'+
                '<td>'+y.qr_code+'</td>'+
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

    function generate_qrcode() {
        alert('Generate QRCODE!!');return false;
        var kd_kawasan = $('#kd_kawasan').val();
        var kd_cluster = $('#kd_cluster').val();

      $.ajax({
        type  : 'POST',
        url   : 'generate_qr_code_c/search_dt',
        data  : {
          "kd_unit"   : kd_unit,
          "kd_lokasi" : kd_lokasi,
          "kd_kawasan" : kd_kawasan,
          "kd_cluster" : kd_cluster,            
          "_token"    : '{{ csrf_token() }}'
        },
        success : function(msg) {          
          var row;
          var no=1;

          $('#tbl_kawasan tbody').empty();

          $("#tbl_kawasan").find("tr:gt(0)").remove(); // CLEAR TABLE
          $.each(msg, function(x, y) {
            row += ''+
              '<tr>'+
                '<td>'+ no++ +'</td>'+
                '<td>'+y.blok+'</td>'+
                '<td>'+y.nomor+'</td>'+
                '<td>'+y.nm_tipe+'</td>'+
                '<td>'+y.qr_code+'</td>'+
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

    function cetak_qrcode() {
        alert('Cetak Label QRCODE!!');return false;
        var kd_kawasan = $('#kd_kawasan').val();
        var kd_cluster = $('#kd_cluster').val();

      $.ajax({
        type  : 'POST',
        url   : 'generate_qr_code_c/search_dt',
        data  : {
          "kd_unit"   : kd_unit,
          "kd_lokasi" : kd_lokasi,
          "kd_kawasan" : kd_kawasan,
          "kd_cluster" : kd_cluster,            
          "_token"    : '{{ csrf_token() }}'
        },
        success : function(msg) {          
          var row;
          var no=1;

          $('#tbl_kawasan tbody').empty();

          $("#tbl_kawasan").find("tr:gt(0)").remove(); // CLEAR TABLE
          $.each(msg, function(x, y) {
            row += ''+
              '<tr>'+
                '<td>'+ no++ +'</td>'+
                '<td>'+y.blok+'</td>'+
                '<td>'+y.nomor+'</td>'+
                '<td>'+y.nm_tipe+'</td>'+
                '<td>'+y.qr_code+'</td>'+
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
	</script>
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-12">
			<div class="card card-primary card-outline">
		      	<div class="card-header">
              <nav class="navbar justify-content-between">
                <a class="form-brand">
                  <h3 class="card-title">Generate QR Code Lokasi Blok/No</h3>
                </a>
                <a class="navbar-inline">
                  <!-- {!! $dt['button'] !!} -->
                  <button type="button" class="btn btn-default btn-flat" onclick="cetak_qrcode()">
                    <i class="fas fa-print"></i>
                  </button>
                  <button type="button" class="btn btn-default btn-flat" onclick="generate_qrcode()">
                    <i class="fas fa-qrcode"></i>
                  </button>
                  <button type="button" class="btn btn-default btn-flat" onclick="search_dt()">
                    <i class="fas fa-search"></i>
                  </button>
                </a>
              </nav>
            </div>
            <!-- <section class="content-header"></section> -->
              <div class="card-body">
                <div class="form-group">
                  <label for="kd_kawasan" class="col-sm-12">Kawasan</label>
                  <div class="col-sm-12">{!! $dt['kd_kawasan'] !!}</div>
                </div>
                <div class="form-group">
                  <label for="kd_cluser" class="col-sm-12">Cluster</label>
                  <div id="div_cluster" class="col-sm-12"></div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-3">
                      <label for="blok" class="col-sm-12 control-label">Blok / Nomor</label>
                      <div class="col-sm-12">
                          <input type="text" class="form-control" id="blok" name="blok" placeholder="From" value="" maxlength="50" >
                      </div>
                    </div>
                    <div class="col-md-2">
                      <label for="blok" class="col-sm-2 control-label">&nbsp;</label>
                      <div class="col-sm-5">
                          S/D
                      </div>
                    </div>
                    <div class="col-md-3">
                      <label for="blok" class="col-sm-2 control-label">&nbsp;</label>
                      <div class="col-sm-12">
                          <input type="text" class="form-control" id="blok" name="blok" placeholder="To" value="" maxlength="50" >
                      </div>
                    </div>
                  </div>
                  
                  
                </div>
                
                <table id="tbl_kawasan" class="table table-bordered table-hover">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th style="text-align: center;">Blok</th>
                      <th style="text-align: center;">Nomor</th>
                      <th style="text-align: center;">Tipe</th>
                      <th style="text-align: center;">QR Code</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>            
                <div class="card-footer">
                  Footer
                </div>
                <!-- /.card-footer-->
              </div>
              <!-- /.card -->

		      	<div id="v_loading" class="overlay" style="display: none;">
					    <i class="fas fa-2x fa-sync-alt fa-spin"></i>
				    </div>
	    	</div>
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