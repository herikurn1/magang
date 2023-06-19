@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit 		= "{{ session('kd_unit') }}";
		var kd_lokasi 	= "{{ session('kd_lokasi') }}";		

    $(function() {
      data_cluster();
      show_tahap_pekerjaan();
    });

    function show_tahap_pekerjaan() {
      var kd_kawasan = $('#kd_kawasan').val();
      var kd_cluster = $('#kd_cluster').val();

      $.ajax({
        type  : 'POST',
        url   : 'input_tbl_mst_tahap_pekerjaan_c/show_tahap_pekerjaan',
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

          $.each(msg, function(x, y) {
            if(y.fg_aktif == 'A'){ 
              chek_var = '';
              chek_var2 = '<button type="button" class="btn btn-warning  btn-sm" onclick="upd_fg(\''+y.kd_tahap+'\',\''+y.fg_aktif+'\')">Disable</button> ';
              style = '';
            }else{ 
              chek_var = '<button type="button" class="btn btn-success btn-sm" onclick="upd_fg(\''+y.kd_tahap+'\',\''+y.fg_aktif+'\')">Enable</button> ';
              chek_var2 = '';
              style = 'style="background-color:#ececec"';
            }

            row += ''+
              '<tr '+style+'>'+
                '<td>'+ no++ +'</td>'+
                '<td>'+y.kd_tahap+'</td>'+
                '<td>'+y.nm_tahap+'</td>'+
                '<td style="text-align: center;">'+
                  '<button type="button" class="btn btn-info btn-sm" onclick="edit_dt(\''+y.kd_tahap+'\',\''+y.nm_tahap+'\')">Edit</button>&nbsp'+
                  ''+chek_var+
                  ''+chek_var2+'</td>'+
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
      tbl += '<thead><tr><th>Kode Tahapan</th><th>Tahapan Pekerjaan</th></tr></thead><tbody>';
      tbl += '</tbody></table>';

      $('#modal-body').html(tbl);

      $('#example1').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
              url   : 'input_tbl_tahap_pekerjaan_c/search_dt',
              method: 'POST',
              headers : { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
          },
          columns: [
              {data: 'KD_TAHAP', name: 'KD_TAHAP'},
              {data: 'NM_TAHAP', name: 'NM_TAHAP'}
          ]
      });

      $('#v_loading').hide();
      $('#myModal').modal("show");
    }   

    function add_dt() {
        $('#saveBtnVal').val("create");
        $('#kd_lantai').val('');
        $('#CustomerForm').trigger("reset");
        $('#modelHeading').html("Tambah Tahapan Pekerjaan");
        $('#ajaxModel').modal('show');
      return false;
    }        

    function save_dt() {
      $('#saveBtn').click();
    }

    function edit_dt(kd_tahap,nm_tahap) {
      $('#modelHeading').html("Edit Tahapan Pekerjaan");
      $('#saveBtnVal').val("edit");
      $('#ajaxModel').modal('show');
      $('#kd_tahap').val(kd_tahap);
      $('#nm_tahap').val(nm_tahap);
    }

    function delete_dt(kd_tahap) {
        $.ajax({
          data  : {
            "kd_tahap"   : kd_tahap,
            "_token"    : '{{ csrf_token() }}'
          },
          url: "input_tbl_mst_tahap_pekerjaan_c/delete_dt",
          type: "POST",

          success: function (data) {
             show_tahap_pekerjaan();
          },
          error: function (data) {
              console.log('Error:', data);
          }
      });

        return false;
    } 

    function upd_fg(kd_tahap,fg_aktif) {
        console.log(kd_tahap);
        console.log(fg_aktif);
        if(fg_aktif == 'A'){ fg_aktif = 'N';
        }else{ fg_aktif = 'A'; }
        $.ajax({
          data  : {
            "kd_tahap"      : kd_tahap,
            "fg_aktif"      : fg_aktif,
            "_token"        : '{{ csrf_token() }}'
          },
          url: "input_tbl_mst_tahap_pekerjaan_c/upd_fg",
          type: "POST",

          success: function (data) {
             show_tahap_pekerjaan();
          },
          error: function (data) {
              console.log('Error:', data);
          }
      });

        return false;
    }        

    function save_process() {
      var kd_kawasan = $('#kd_kawasan').val();
      var kd_cluster = $('#kd_cluster').val();

      $('#kd_kawasan_l').val(kd_kawasan);
      $('#kd_cluster_l').val(kd_cluster);

      var data_all      = $('#CustomerForm').serializeArray();

        $.ajax({
          data: data_all,
          url: "input_tbl_mst_tahap_pekerjaan_c/save",
          type: "POST",

          success: function (data) {

              $('#CustomerForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              show_tahap_pekerjaan();
          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }
      });

        return false;
    }      

    function data_cluster() {
        var kd_kawasan = $('#kd_kawasan').val();
        var row;
        
        $.ajax({
            type: 'POST',
            url: 'input_tbl_mst_tahap_pekerjaan_c/data_cluster',
            cache: false,
            data: {
              "_token"  : '{{ csrf_token() }}',
              "kd_unit" : kd_unit,
              "kd_lokasi" : kd_lokasi,
              "kd_kawasan" : kd_kawasan                
            },
            success: function(msg){

                row = '<select class="form-control col-sm-12" name="kd_cluster" id="kd_cluster" >';
                $.each(msg, function(x, y) {
                  row += '<option value="'+ y.kd_cluster +'">'+ y.nm_cluster +'</option>';
                });
                row += '</select>';

                $('#div_cluster').html(row);
                
                //data_blok_no();
            },
            error: function(){
                $('#v_loading').hide();
                alert('Proses Get Cluster Gagal');
            }
        });
        $('#v_loading').hide();
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
                  <h3 class="card-title">Input Tabel Master Tahap Pekerjaan</h3>
                </a>
                <a class="navbar-inline">
                  {!! $dt['button'] !!}
                  <!-- <button type="button" class="btn btn-default btn-flat" onclick="sync_dt()">
                    <i class="fas fa-sync"></i>
                  </button> -->
                  <button type="button" class="btn btn-default btn-flat" onclick="show_tahap_pekerjaan()">
                    <i class="fas fa-search"></i>
                  </button>
                </a>
              </nav>
            </div>
              <div class="card-body">
                <table id="tbl_kawasan" class="table table-bordered table-hover">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th style="width: 90px">KD Tahap</th>
                      <th style="text-align: center;">Tahap Pekerjaan</th>
                      <th style="text-align: center; width: 220px;">Action</th>
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
                   <input type="hidden" name="kd_tahap" id="kd_tahap" value="">
                   <input type="hidden" name="kd_kawasan_l" id="kd_kawasan_l" value="">
                   <input type="hidden" name="kd_cluster_l" id="kd_cluster_l" value="">
                    <div class="form-group">
                        <label for="nm_tahap" class="col-sm-2 control-label">Nama</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="nm_tahap" name="nm_tahap" placeholder="Enter Name" value="" maxlength="50" required="">
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