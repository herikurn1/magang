@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit 		= "{{ session('kd_unit') }}";
		var kd_lokasi 	= "{{ session('kd_lokasi') }}";		

    $(function() {
      show_cluster();
    });

    function show_cluster() {

      $.ajax({
        type  : 'POST',
        url   : 'input_data_jns_pekerjaan_c/show_jenis_pekerjaan',
        data  : {
          "kd_unit"   : kd_unit,
          "kd_lokasi" : kd_lokasi,
          "_token"    : '{{ csrf_token() }}'
        },
        success : function(msg) {          
          var row;
          var no=1;
          var tp_denah_ket ;

          $('#tbl_kawasan tbody').empty();

          $.each(msg, function(x, y) {
            if(y.tipe_denah == 'A'){
              tp_denah_ket = 'Arsitektur';
            }else if(y.tipe_denah == 'S'){
              tp_denah_ket = 'Struktur';
            }else{
              tp_denah_ket = '-';
            }
            row += ''+
              '<tr>'+
                '<td>'+ no++ +'</td>'+
                '<td>'+y.nm_kategori_defect+'</td>'+
                '<td>'+y.deskripsi+'</td>'+
                '<td>'+tp_denah_ket+'</td>'+
                '<td style="text-align: center;">'+
                  '<button type="button" class="btn btn-info btn-sm" onclick="edit_dt(\''+y.kd_kategori_defect+'\',\''+y.nm_kategori_defect+'\',\''+y.deskripsi+'\',\''+y.tipe_denah+'\')">Edit</button>&nbsp'+
                  '<button type="button" class="btn btn-info btn-sm" onclick="delete_dt(\''+y.kd_kategori_defect+'\')">Hapus</button></td>'+
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
        $('#modelHeading').html("Tambah Jenis Pekerjaan");
        $('#CustomerForm').trigger("reset");
        $('#ajaxModel').modal('show');
      return false;
    }        

    function save_dt() {
      $('#saveBtn').click();
    }

    function edit_dt(kd_kategori_defect,nm_kategori_defect,deskripsi,tipe_denah) {
      $('#modelHeading').html("Edit Jenis Pekerjaan");
      $('#saveBtnVal').val("edit");
      $('#ajaxModel').modal('show');
      $('#kd_kategori_defect').val(kd_kategori_defect);
      $('#nm_kategori_defect').val(nm_kategori_defect);
      $('#deskripsi').val(deskripsi);
      $('#tipe_denah').val(tipe_denah);
    }

    function delete_dt(kd_kategori_defect) {
        $.ajax({
          data  : {
            "kd_kategori_defect"   : kd_kategori_defect,
            "_token"    : '{{ csrf_token() }}'
          },
          url: "input_data_jns_pekerjaan_c/delete_dt",
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
          url: "input_data_jns_pekerjaan_c/save",
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
                  <h3 class="card-title">Input Data Jenis Pekerjaan</h3>
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
                <table id="tbl_kawasan" class="table table-bordered table-hover">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th style="width: 150px; text-align: center;">Jenis Pekerjaan</th>
                      <th style="text-align: center;">Deskripsi</th>
                      <th style="width: 150px; text-align: center;">Tipe Denah</th>
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
                <form role="form" id="CustomerForm" name="CustomerForm" class="form-horizontal" autocomplete="off" onsubmit="return save_process()">
                   <input type="hidden" name="saveBtnVal" id="saveBtnVal" value="create">
                   <input type="hidden" name="kd_kategori_defect" id="kd_kategori_defect" value="">
                    <div class="form-group">
                        <label for="nm_kategori_defect" class="col-sm-6 control-label">Jenis Pekerjaan</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="nm_kategori_defect" name="nm_kategori_defect" placeholder="Enter Name" value="" maxlength="50" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi" class="col-sm-6 control-label">Deskripsi</label>
                        <div class="col-sm-12">
                            <textarea id="deskripsi" name="deskripsi" required="" placeholder="Enter Details" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tipe_denah" class="col-sm-6 control-label">Tipe Denah</label>
                        <select class="form-control col-sm-12" name="tipe_denah" id="tipe_denah">
                          <option value="A">Arsitekur</option>
                          <option value="S">Struktur</option>
                        </select>
                      </div>      
                    <div class="col-sm-offset-6 col-sm-10">
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