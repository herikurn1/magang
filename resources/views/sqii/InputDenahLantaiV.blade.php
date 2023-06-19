@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit 		= "{{ session('kd_unit') }}";
		var kd_lokasi 	= "{{ session('kd_lokasi') }}";		
    var kd_jenis_live = '';
    var kd_tipe_live = '';
    var kd_kawasan_l  = '';
    var kd_jenis_l    = '';
    var kd_tipe_l     = '';  

    $(function() {
      //show_jenis_bangunan();
      data_cluster();
    });

    function tipe_rumah() {
        var kd_kawasan = $('#kd_kawasan').val();
        var kd_cluster = $('#kd_cluster').val();
        var nm_kawasan = $("#kd_kawasan option:selected" ).text();
        var nm_cluster = $("#kd_cluster option:selected" ).text();

      $.ajax({
        type  : 'POST',
        url   : 'input_denah_tipe_lantai_c/tipe_rumah',
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

          //$('#tbl_kawasan tbody').empty();

          $("#tbl_kawasan").find("tr:gt(0)").remove(); // CLEAR TABLE
          $.each(msg, function(x, y) {
            row += ''+
              '<tr>'+
                '<td>'+ no++ +'</td>'+
                '<td>'+y.nm_jenis+'</td>'+
                '<td onclick="denah_lantai(\''+kd_kawasan+'\',\''+y.kd_jenis+'\',\''+y.kd_tipe+'\',\''+y.nm_jenis+'\',\''+y.nm_tipe+'\',\''+nm_kawasan+'\',\''+nm_cluster+'\')"><div class="link_cursor">'+y.kd_tipe+'</div></td>'+
                '<td>'+y.nm_tipe+'</td>'+
                '<td>'+y.jml_lantai+'</td>'+
              '</tr>'+
            '';
          });

          // $('#tbl_kawasan').html(row);
          $('#tbl_kawasan').append(row);

          $('#tbl_denah_lantai tbody').empty();
          $("#tbl_denah_lantai").find("tr:gt(0)").remove(); // CLEAR TABLE LANTAI

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
        url   : 'input_denah_tipe_lantai_c/sync_dt',
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
            url: 'input_denah_tipe_lantai_c/data_cluster',
            cache: false,
            data: {
              "_token"  : '{{ csrf_token() }}',
              "kd_unit" : kd_unit,
              "kd_lokasi" : kd_lokasi,
              "kd_kawasan" : kd_kawasan                
            },
            success: function(msg){

                row = '<select class="form-control col-sm-12" name="kd_cluster" id="kd_cluster" onchange="tipe_rumah()">';
                $.each(msg, function(x, y) {
                  row += '<option value="'+ y.kd_cluster +'">'+ y.nm_cluster +'</option>';
                });
                row += '</select>';

                $('#div_cluster').html(row);
                
                tipe_rumah();
            },
            error: function(){
                $('#v_loading').hide();
                alert('Proses Get Cluster Gagal');
            }
        });
        
        return false;
    }

    function add_dt() {
        data_lantai();
        $('#saveBtnVal').val("create");
        $('#kd_lantai').val('');
        $('#CustomerForm').trigger("reset");
        $('#modelHeading').html("Tambah Denah Lantai");
        $('#ajaxModel').modal('show');
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

    function delete_dt(kd_jenis,kd_tipe,kd_lantai) {
      var kd_kawasan    = $('#kd_kawasan').val();
        $.ajax({
          data  : {
            "kd_kawasan"    : kd_kawasan,
            "kd_jenis"      : kd_jenis,
            "kd_tipe"       : kd_tipe,
            "kd_lantai"     : kd_lantai,
            "_token"        : '{{ csrf_token() }}'
          },
          url: "input_denah_tipe_lantai_c/delete_dt",
          type: "POST",

          success: function (data) {
             denah_lantai(kd_jenis,kd_tipe);
          },
          error: function (data) {
              console.log('Error:', data);
          }
      });

        return false;
    } 

    function save_process() {
      var data_all      = $('#CustomerForm').serializeArray();
      var kd_kawasan    = $('#kd_kawasan').val();
      var kd_cluster    = $('#kd_cluster').val();

      data_all.push({name : "kd_kawasan", value : kd_kawasan});
      data_all.push({name : "kd_cluster", value : kd_cluster});
      data_all.push({name : "kd_jenis", value : kd_jenis_live});
      data_all.push({name : "kd_tipe", value : kd_tipe_live});

        $.ajax({
          data: data_all,
          url: "input_denah_tipe_lantai_c/save",
          type: "POST",

          success: function (data) {

              $('#CustomerForm').trigger("reset");
              denah_lantai(kd_jenis_live,kd_tipe_live);
              
              //table.draw();

          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }
      });

        return false;
    }

    function denah_lantai(kd_kawasan,kd_jenis,kd_tipe,nm_jenis,nm_tipe,nm_kawasan,nm_cluster) {
      kd_kawasan_l = kd_kawasan;
      kd_jenis_l = kd_jenis;
      kd_tipe_l = kd_tipe;      

      $.ajax({
        type  : 'POST',
        url   : 'input_denah_tipe_lantai_c/denah_lantai',
        data  : {
          "kd_unit"   : kd_unit,
          "kd_lokasi" : kd_lokasi,
          "kd_kawasan" : kd_kawasan,
          "kd_jenis" : kd_jenis, 
          "kd_tipe" : kd_tipe,            
          "_token"    : '{{ csrf_token() }}'
        },
        success : function(msg) {          
          var row;
          var no=1;

          $('#div_denah_title').html(nm_kawasan+" - "+nm_cluster+" # "+nm_jenis+" - "+nm_tipe+" ("+kd_tipe+")" );
          $('#div_denah').show();
          $('#tbl_denah_lantai tbody').empty();

          $("#tbl_denah_lantai").find("tr:gt(0)").remove(); // CLEAR TABLE
          $.each(msg, function(x, y) {
            row += ''+
              '<tr>'+
                '<td>'+ no++ +'</td>'+
                '<td>'+y.nm_lantai+'</td>'+
                '<td><div class="row"><div style="width: 150px;">'+
                      '<div class="color-palette-set">'+
                        '<div class="bg-warning color-palette text-center"><strong>Denah Struktur</strong></div>'+
                        '<div class="bg-light color-palette"><img class="img-fluid" src="https://sqii.gadingemerald.com/public/image/denah/'+y.src_foto_denah+'" id="formulir_denah" alt="Photo Denah"></div>'+
                      '</div>'+
                    '</div></div>'+
                '</td>'+
                '<td><div class="row"><div style="width: 150px;">'+
                      '<div class="color-palette-set">'+
                        '<div class="bg-warning color-palette text-center"><strong>Denah Arsitektur</strong></div>'+
                        '<div class="bg-light color-palette"><img class="img-fluid" src="https://sqii.gadingemerald.com/public/image/denah_2/'+y.src_foto_denah_2+'" id="formulir_denah" alt="Photo Denah"></div>'+
                      '</div>'+
                    '</div></div>'+
                '</td>'+
                '<td style="text-align: center;">'+
                  '<button type="button" class="btn btn-info btn-sm" onclick="delete_dt2(\''+y.kd_lantai+'\',\''+y.kd_kawasan+'\',\''+y.kd_jenis+'\',\''+y.kd_tipe+'\')">Hapus</button></td>'+
              '</tr>'+
            '';
          });

          kd_jenis_live = kd_jenis;
          kd_tipe_live = kd_tipe;

          // $('#tbl_kawasan').html(row);
          $('#tbl_denah_lantai').append(row);
        },
        error   : function(xhr) {
          read_error(xhr);
        }
      });
    }

    function data_lantai() {
        var row;

        $.ajax({
            type: 'POST',
            url: 'input_denah_tipe_lantai_c/data_lantai',
            cache: false,
            data: {
              "_token"  : '{{ csrf_token() }}',
              "kd_unit" : kd_unit,
              "kd_lokasi" : kd_lokasi              
            },
            success: function(msg){

                row = '<select class="form-control col-sm-12" name="kd_lantai" id="kd_lantai" onchange="data_blok_no()">';
                $.each(msg, function(x, y) {
                  row += '<option value="'+ y.kd_lantai +'">'+ y.nm_lantai +'</option>';
                });
                row += '</select>';

                $('#div_data_lantai').html(row);
                
            },
            error: function(){
                $('#v_loading').hide();
                alert('Proses Tipe Rumah Gagal');
            }
        });
        
        return false;
    }

    function add_staff() {
      available_stok();
      $('#saveBtnVal').val("create");
      $('#kd_kawasan_add').val('');
      $('#kd_cluster_add').val('');
      $('#kd_jenis_add').val('');
      $('#kd_tipe_add').val('');
      $('#CustomerForm').trigger("reset");
      $('#modelHeading').html("Tambah denah"); 
      $('#ajaxModel').modal('show');   

      return false;
    }

    function available_stok() {
      var row;
        
        $.ajax({
            type: 'POST',
            url: 'input_denah_tipe_lantai_c/available_stok',
            cache: false,
            data: {
              "_token"  : '{{ csrf_token() }}',
              "kd_unit" : kd_unit,
              "kd_lokasi" : kd_lokasi,
              "kd_kawasan" : kd_kawasan_l,
              "kd_jenis" : kd_jenis_l, 
              "kd_tipe" : kd_tipe_l        
            },
            success: function(msg){

                row = '<select class="form-control col-sm-12" name="kd_lantai" id="kd_lantai">';
                $.each(msg, function(x, y) {
                  row += '<option value="'+ y.kd_lantai+'">'+ y.nm_lantai +'</option>';
                });
                row += '</select>';

                $('#div_available_stok').html(row);
                
            },
            error: function(){
                $('#v_loading').hide();
                alert('Proses Get Cluster Gagal');
            }
        });
        
        return false;
    }    

    function file_upload(dataall) {

      $('#kd_jenis_l').val(kd_jenis_l);
      $('#kd_tipe_l').val(kd_tipe_l);
      $('#kd_kawasan_l').val(kd_kawasan_l);

      let formData      = new FormData($('#BawahanForm')[0]);

      $.ajaxSetup({
          headers: {
              "X-CSRF-TOKEN": '{{ csrf_token() }}'
          }
      });
      
      $.ajax({
        data: formData,
        url: "input_denah_tipe_lantai_c/file_upload",
        type: "POST",
        cache:false,
        dataType: false,
        processData: false,
        contentType: false,
        success: function (data) {
            console.log(data);
            $('#ajaxModel').modal('hide');
            $('#BawahanForm').trigger("reset");
            denah_lantai(kd_kawasan_l,kd_jenis_l,kd_tipe_l);
        },
        error: function (data) {
            console.log('Error:', data);
            $('#saveBtnStaff').html('Save Changes');
        }
      });
        return false;
    }

    function delete_dt2(kd_lantai,kd_kawasan,kd_jenis,kd_tipe) {
        $.ajax({
          data  : {
            "kd_lantai"       : kd_lantai,
            "kd_kawasan"      : kd_kawasan,
            "kd_jenis"        : kd_jenis,
            "kd_tipe"         : kd_tipe,
            "_token"          : '{{ csrf_token() }}'
          },
          url: "input_denah_tipe_lantai_c/delete_denah",
          type: "POST",

          success: function (data) {
            denah_lantai(kd_kawasan,kd_jenis,kd_tipe);
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
<div class="row">
  <div class="col-sm-12">
  <div class="card card-primary card-outline">
          <div class="card-header">
            <nav class="navbar justify-content-between">
              <a class="form-brand">
                <h3 class="card-title">Input Denah per Tipe per Lantai</h3>
              </a>
              <a class="navbar-inline">
                <!-- {!! $dt['button'] !!} -->
                <button type="button" class="btn btn-default btn-flat" onclick="sync_dt()">
                  <i class="fas fa-sync"></i>
                </button>
                <button type="button" class="btn btn-default btn-flat" onclick="tipe_rumah()">
                  <i class="fas fa-search"></i>
                </button>
              </a>
            </nav>
          </div>
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
              <label for="kd_cluser" class="col-sm-12">Tipe Rumah</label>
              <div class="col-sm-12 card-body table-responsive p-0" style="height: 200px;">
                <table id="tbl_kawasan" class="table table-bordered table-hover table-head-fixed text-nowrap">
                  <thead >                  
                    <tr >
                      <th style="width: 10px">#</th>
                      <th style="width: 130px; text-align: center;">Jenis</th>
                      <th style="text-align: center;">Kode Tipe</th>
                      <th style="text-align: center;">Nama Tipe</th>
                      <th style="text-align: center;">Jml Lantai</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>

            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Denah Lantai</a>
              </li>
            </ul>
            <div id="div_denah" style="display: none;">  
            <div class="tab-content" id="custom-content-below-tabContent">
              <div class="tab-pane fade active show" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                <div class="form-group">
                  <div class="card-header">
                    <nav class="navbar justify-content-between">
                      <a class="form-brand">
                        <button type="button" class="btn btn-info btn-sm" onclick="add_staff()">Tambah</button>
                      </a>
                      <a class="navbar-inline">
                        <h3 id="div_denah_title" class="card-title"></h3>
                      </a>
                    </nav>
                  </div>

                </div>  
                <table id="tbl_denah_lantai" class="table table-bordered table-hover">
                  <thead >                  
                    <tr >
                      <th style="width: 10px">#</th>
                      <th style="width: 130px; text-align: center;">Lantai</th>
                      <th style="text-align: center;">Denah Struktur</th>
                      <th style="text-align: center;">Denah Arsitektur</th>
                      <th style="text-align: center;">Action</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>                
            </div>
            </div>
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

<div class="modal fade" id="ajaxModel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modelHeading"></h4>
      </div>
      <div class="modal-body">     
        <form id="BawahanForm" name="BawahanForm" class="form-horizontal" method="POST" enctype="multipart/form-data" onsubmit="return file_upload(this)">
         <input type="hidden" name="saveBtnStaffVal" id="saveBtnStaffVal" value="create">
         <input type="hidden" name="kd_jenis_l" id="kd_jenis_l" value="">
         <input type="hidden" name="kd_tipe_l" id="kd_tipe_l" value="">
         <input type="hidden" name="kd_kawasan_l" id="kd_kawasan_l" value="">
          <div class="form-group">
            <div class="col-sm-12">
              <div class="row">
                <div class="col-4">
                  <label for="stok_add" class="col-sm-12 control-label">Lantai</label>
                  <div class="" id="div_available_stok"></div>                              
                </div>
                <div class="col-4">
                  <label for="stok_add" class="col-sm-12 control-label">Struktur</label>
                  <input type="file" class="form-control-file" id="berkas" name="berkas">
                </div>
                <div class="col-4">
                  <label for="stok_add" class="col-sm-12 control-label">Arsitektur</label>
                  <input type="file" class="form-control-file" id="berkas2" name="berkas2">
                </div>
              </div>
            </div>                        
          </div>
          <div class="col-sm-offset-2 col-sm-10">
           <button type="submit" class="btn btn-primary" id="saveBtnStaff" value="create">Save</button>
           <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
          </div>
          @csrf
        </form>
      </div>
    </div>
  </div>
</div> 
@endsection