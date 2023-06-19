@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit 		= "{{ session('kd_unit') }}";
		var kd_lokasi 	= "{{ session('kd_lokasi') }}";		

    $(function() {
      show_cluster();
    });

    function show_cluster() {

      // $('#tbl_kawasan').DataTable({
      //     processing: true,
      //     serverSide: true,
      //     ajax: {
      //         url   : 'input_data_lantai_c/show_lantai',
      //         method: 'POST',
      //         headers : { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
      //     },
      //     columns: [
      //         {data: 'KD_LANTAI', name: 'KD_LANTAI'},
      //         {data: 'NM_LANTAI', name: 'NM_LANTAI'}
      //     ]
      // });

      $.ajax({
        type  : 'POST',
        url   : 'input_data_lantai_c/show_lantai',
        data  : {
          "kd_unit"   : kd_unit,
          "kd_lokasi" : kd_lokasi,
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
                '<td>'+y.nm_lantai+'</td>'+
                '<td style="text-align: center;">'+
                  '<button type="button" class="btn btn-info btn-sm" onclick="edit_dt(\''+y.kd_lantai+'\',\''+y.nm_lantai+'\')">Edit</button>&nbsp'+
                  '<button type="button" class="btn btn-info btn-sm" onclick="delete_dt(\''+y.kd_lantai+'\')">Hapus</button></td>'+
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

    function search_dt() {
      $('#v_loading').show();

      var tbl = '<table class="table table-bordered table-striped" id="example1">';
      tbl += '<thead><tr><th>Kode Lantai</th><th>Nama Lantai</th></tr></thead><tbody>';
      tbl += '</tbody></table>';

      $('#modal-body').html(tbl);

      $('#example1').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
              url   : 'input_data_lantai_c/search_dt',
              method: 'POST',
              headers : { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
          },
          columns: [
              {data: 'KD_LANTAI', name: 'KD_LANTAI'},
              {data: 'NM_LANTAI', name: 'NM_LANTAI'}
          ]
      });

      $('#v_loading').hide();
      $('#myModal').modal("show");
    }   

    function add_dt() {
        $('#saveBtnVal').val("create");
        $('#kd_lantai').val('');
        $('#CustomerForm').trigger("reset");
        $('#modelHeading').html("Tambah Lantai");
        $('#ajaxModel').modal('show');
      return false;
    }        

    function save_dt() {
      $('#saveBtn').click();
    }

    function edit_dt(kd_lantai,nm_lantai) {
      $('#modelHeading').html("Edit Lantai");
      $('#saveBtnVal').val("edit");
      $('#ajaxModel').modal('show');
      $('#kd_lantai').val(kd_lantai);
      $('#nm_lantai').val(nm_lantai);
    }

    function delete_dt(kd_lantai) {
        $.ajax({
          data  : {
            "kd_lantai"   : kd_lantai,
            "_token"    : '{{ csrf_token() }}'
          },
          url: "input_data_lantai_c/delete_dt",
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

        $.ajax({
          data: data_all,
          url: "input_data_lantai_c/save",
          type: "POST",

          success: function (data) {

              $('#CustomerForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              show_cluster();
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
                  <h3 class="card-title">Input Data Lantai</h3>
                </a>
                <a class="navbar-inline">
                  {!! $dt['button'] !!}
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
                      <th style="text-align: center;">Nama Lantai</th>
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

<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="CustomerForm" name="CustomerForm" class="form-horizontal" autocomplete="off" onsubmit="return save_process()">
                   <input type="hidden" name="saveBtnVal" id="saveBtnVal" value="create">
                   <input type="hidden" name="kd_lantai" id="kd_lantai" value="">
                    <div class="form-group">
                        <label for="nm_lantai" class="col-sm-2 control-label">Nama</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="nm_lantai" name="nm_lantai" placeholder="Enter Name" value="" maxlength="50" required="">
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