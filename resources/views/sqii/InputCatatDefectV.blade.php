@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit 		= "{{ session('kd_unit') }}";
		var kd_lokasi 	= "{{ session('kd_lokasi') }}";		

    $(function() {
      //show_cluster();
    });

    function item_defect() {

      var kd_item_defect = $('#kd_item_defect').val();

      $.ajax({
        type  : 'POST',
        url   : 'input_tbl_catatan_defect_c/show_data_item_defect',
        data  : {
          "kd_item_defect"  : kd_item_defect,
          "_token"          : '{{ csrf_token() }}'
        },
        success : function(msg) {          
          var row;
          var no=1;

          $('#tbl_kawasan tbody').empty();

          $.each(msg, function(x, y) {
            row += ''+
              '<tr>'+
                '<td>'+ no++ +'</td>'+
                '<td>'+y.deskripsi+'</td>'+
                '<td style="text-align: center;">'+
                  '<button type="button" class="btn btn-info btn-sm" onclick="edit_dt(\''+y.kd_catatan+'\',\''+y.kd_item_defect+'\',\''+y.deskripsi+'\')">Edit</button>&nbsp'+
                  '<button type="button" class="btn btn-info btn-sm" onclick="delete_dt(\''+y.kd_catatan+'\',\''+y.kd_item_defect+'\')">Hapus</button></td>'+
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
        $('#modelHeading').html("Tambah Catatan Item Defect");
        $('#ajaxModel').modal('show');
      return false;
    }        

    function save_dt() {
      $('#saveBtn').click();
    }

    function edit_dt(kd_catatan,kd_item_defect,deskripsi) {
      $('#modelHeading').html("Edit Catatan Item Defect");
      $('#saveBtnVal').val("edit");
      $('#ajaxModel').modal('show');
      $('#kd_catatan').val(kd_catatan);
      $('#kd_item_defect').val(kd_item_defect);
      $('#deskripsi').val(deskripsi);
    }

    function delete_dt(kd_catatan,kd_item_defect) {
        $.ajax({
          data  : {
            "kd_catatan"      : kd_catatan,
            "kd_item_defect"  : kd_item_defect,
            "_token"          : '{{ csrf_token() }}'
          },
          url: "input_tbl_catatan_defect_c/delete_dt",
          type: "POST",

          success: function (data) {
             item_defect();
          },
          error: function (data) {
              console.log('Error:', data);
          }
      });

        return false;
    } 

    function save_process() {
      var data_all        = $('#CustomerForm').serializeArray();
      var kd_item_defect  = $('#kd_item_defect').val();

      data_all.push({name : "kd_item_defect", value : kd_item_defect});

        $.ajax({
          data    : data_all,
          url     : "input_tbl_catatan_defect_c/save",
          type    : "POST",

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
	</script>
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-12">
			<div class="card card-primary card-outline">
		      	<div class="card-header">
              <nav class="navbar justify-content-between">
                <a class="form-brand">
                  <h3 class="card-title">Input Tabel Catatan per Item Defect</h3>
                </a>
                <a class="navbar-inline">
                  {!! $dt['button'] !!}
                  <button type="button" class="btn btn-default btn-flat" onclick="search_dt()">
                    <i class="fas fa-search"></i>
                  </button>
                </a>
              </nav>
            </div>
            <!-- <section class="content-header"></section> -->
              <div class="card-body">
                <div class="form-group">
                  <label for="kd_kategori">Jenis Pekerjaan</label>
                  {!! $dt['kd_kategori'] !!}
                </div>
                <table id="tbl_kawasan" class="table table-bordered table-hover">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th style="text-align: center;">Nama Item Defect</th>
                      <th style="text-align: center; width: 130px;">Action</th>
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
                   <input type="hidden" name="kd_catatan" id="kd_catatan" value="">
                    <div class="form-group">
                        <label for="deskripsi" class="col-sm-2 control-label">Catatan</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="deskripsi" name="deskripsi" placeholder="Enter Name" value="" maxlength="50" required="">
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