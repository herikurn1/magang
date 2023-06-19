@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit 		= "{{ session('kd_unit') }}";
		var kd_lokasi 	= "{{ session('kd_lokasi') }}";		

    $(function() {
      data_cluster();
    });

    function data_blok_no() {
      var kd_kawasan = $('#kd_kawasan').val();
      var kd_cluster = $('#kd_cluster').val();
      var nik_petugas = $('#nik_petugas').val();

      $.ajax({
        type  : 'POST',
        url   : 'alokasi_petugas_blok_nomor_c/data_blok_no',
        data  : {
          "kd_unit"   : kd_unit,
          "kd_lokasi" : kd_lokasi,
          "kd_kawasan" : kd_kawasan,
          "kd_cluster" : kd_cluster, 
          "nik_petugas" : nik_petugas,            
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
                '<td>'+y.blok+' / '+y.nomor+'</td>'+
                '<td>'+y.nm_tipe+'</td>'+
                '<td style="text-align: center;">'+
                  '<button type="button" class="btn btn-info btn-sm" onclick="delete_dt(\''+y.kd_kawasan+'\',\''+y.kd_cluster+'\',\''+y.blok+'\',\''+y.nomor+'\',\''+y.user_id+'\')">Hapus</button></td>'+
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
        url   : 'alokasi_petugas_blok_nomor_c/sync_dt',
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
            url: 'alokasi_petugas_blok_nomor_c/data_cluster',
            cache: false,
            data: {
              "_token"  : '{{ csrf_token() }}',
              "kd_unit" : kd_unit,
              "kd_lokasi" : kd_lokasi,
              "kd_kawasan" : kd_kawasan                
            },
            success: function(msg){

                row = '<select class="form-control col-sm-12" name="kd_cluster" id="kd_cluster" onchange="clear_tbl()">';
                $.each(msg, function(x, y) {
                  row += '<option value="'+ y.kd_cluster +'">'+ y.nm_cluster +' # <p style="color:red">'+ y.kd_cluster.toLowerCase() +'</p></option>';
                });
                row += '</select>';

                $('#div_cluster').html(row);
                
                clear_tbl();
            },
            error: function(){
                $('#v_loading').hide();
                alert('Proses Get Cluster Gagal');
            }
        });
        
        return false;
    }

    function clear_tbl(){
      $("#tbl_kawasan").find("tr:gt(0)").remove(); // CLEAR TABLE
    }

    function add_dt() {
      available_stok();
      $('#saveBtnVal').val("create");
      $('#kd_kawasan_add').val('');
      $('#kd_cluster_add').val('');
      $('#kd_jenis_add').val('');
      $('#kd_tipe_add').val('');
      $('#CustomerForm').trigger("reset");
      // $('#modelHeading').html("Alokasi Blok / Nomor");
      $('#ajaxModel').modal('show');   

      return false;
    }        

    function available_stok() {
      var kd_kawasan = $('#kd_kawasan').val();
      var kd_cluster = $('#kd_cluster').val();
      var nik_petugas = $('#nik_petugas').val();

      $.ajax({
        type  : 'POST',
        url   : 'alokasi_petugas_blok_nomor_c/available_stok',
        data  : {
        "kd_kawasan" : kd_kawasan,
        "kd_cluster" : kd_cluster, 
        "nik_petugas" : nik_petugas,
          "_token"    : '{{ csrf_token() }}'
        },
        success : function(msg) {          
          var row;
          var no=1;

          $('#tbl_available_stok tbody').empty();

          $.each(msg, function(x, y) {
            row += ''+
                      '<tr>'+
                        '<td>'+ no++ +'</td>'+
                        '<td>'+y.blok+' / '+y.nomor+'</td>'+
                        '<td>'+y.nm_tipe+'</td>'+
                        '<td style="text-align: center;"><input type="checkbox" name="data_penugasan[]" value="'+kd_kawasan+'#'+kd_cluster+'#'+y.blok+'#'+y.nomor+'#'+nik_petugas+'" /></td>'+
                      '</tr>'+
            '';
          });

          $('#tbl_available_stok').append(row);
        },
        error   : function(xhr) {
          read_error(xhr);
        }
      });
    }

    function set_tipe_rumah() {
      var nm_tipe = $('#stok_add').val().split('#');
      $('#tipe').val(nm_tipe[2]);
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

    function delete_dt(kd_kawasan,kd_cluster,blok,nomor,user_id) {
        $.ajax({
          data  : {
            "kd_kawasan"    : kd_kawasan,
            "kd_cluster"    : kd_cluster,
            "blok"          : blok,
            "nomor"         : nomor,
            "user_id"         : user_id,
            "_token"        : '{{ csrf_token() }}'
          },
          url: "alokasi_petugas_blok_nomor_c/delete_dt",
          type: "POST",

          success: function (data) {
            data_blok_no();
          },
          error: function (data) {
              console.log('Error:', data);
          }
      });

        return false;
    } 

    function save_process() {
      var data_all = $('#CustomerForm').serializeArray();

      $.ajax({
        data  : data_all,
        url: "alokasi_petugas_blok_nomor_c/save",
        type: "POST",

        success: function (data) {
            $('#ajaxModel').modal('hide');
            $('#CustomerForm').trigger("reset");
            data_blok_no();
        },
        error: function (data) {
            console.log('Error:', data);
            $('#saveBtn').html('Save Changes');
        }
      });
        return false;
    }

  var sys_search_unit_page = 1;

  function nik_petugas() {
    var keyword = $('#sys-modal-default-keyword').val();
    var kd_kawasan  = $('#kd_kawasan_add').val();

    $.ajax({
      type  : 'POST',
      url   : 'alokasi_petugas_blok_nomor_c/nik_petugas?page='+sys_search_unit_page,
      data  : {
        "_token"  : '{{ csrf_token() }}',
        "keyword" : keyword,
        "kd_kawasan" : kd_kawasan

      },
      success : function(msg){
        var tbl = ''+
          '<div class="row">'+
                  '<div class="col-12">'+
                    '<div class="card">'+
                        '<div class="card-header">'+
                          '<h3 class="card-title">Nama Petugas</h3>'+
                        '</div>'+

                        '<div class="card-body table-responsive p-0" style="height: 300px;">'+
                  '<table class="table table-sm table-head-fixed text-nowrap">'+
                    '<thead>'+
                      '<tr>'+
                        '<th>Kode Tipe</th>'+
                        '<th>Nama Tipe</th>'+
                        '<th>#</th>'+
                      '</tr>'+
                    '</thead>'+
                    '<tbody>'+
        '';
        $.each(msg['data'], function(x, y){
          tbl += ''+
                      '<tr>'+
                        '<td>'+y.NAMA+'</td>'+
                        '<td>'+y.NM_JABATAN+'</td>'+
                        '<td><button type="button" class="btn btn-info btn-sm" onclick="nik_petugas_set(\''+y.USER_ID+'\', \''+y.NAMA+'\', \''+y.NM_JABATAN+'\')">Pilih</button></td>'+
                      '</tr>'+
          '';
        });

        tbl += ''+
                    '</tbody>'+
                  '</table>'+
                '</div>'+
              '</div>'+
            '</div>'+
          '</div>'+
          
          '<div class="row">'+
            '<label class="col-sm-1 col-form-label">Halaman</label>'+
            '<div class="col-sm-3">'+
              '<div class="input-group">'+
                '<span class="input-group-preppend">'+
                            '<button type="button" class="btn btn-info btn-flat btn-sm" onclick="sys_search_unit_page--; nik_petugas();"><<</button>'+
                          '</span>'+
                          '<input type="number" class="form-control form-control-sm rounded-0" min="1" id="sys_search_unit_page_input" style="text-align: center;" value="'+sys_search_unit_page+'">'+
                          '<span class="input-group-append">'+
                            '<button type="button" class="btn btn-info btn-flat btn-sm" onclick="sys_search_unit_page++; nik_petugas();">>></button>'+
                            '<button type="button" class="btn btn-default btn-flat btn-sm" onclick="sys_search_unit_page = $(\'#sys_search_unit_page_input\').val(); nik_petugas();">Go!</button>'+
                          '</span>'+
                      '</div>'+
            '</div>'+
          '</div>'+
        '';

        $('#sys-modal-default-body').html(tbl);
        $('#sys-modal-default-btn-search').attr('onclick', 'nik_petugas()');
        $('#sys-modal-default').modal("show");
      },
      error   : function(xhr){
        read_error(xhr);
      }
    });
  }

  function nik_petugas_set(USER_ID, NAMA, NM_JABATAN) {
    $('#nik_petugas').val(USER_ID);
    $('#nm_petugas').val(NAMA);
    $('#jabatan_petugas').val(NM_JABATAN);
    $('#sys-modal-default').modal('hide');
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
                <h3 class="card-title">Alokasi Petugas terhadap Lokasi Blok/Nomor</h3>
              </a>
              <a class="navbar-inline">
                {!! $dt['button'] !!}
                <button type="button" class="btn btn-default btn-flat" onclick="data_blok_no();">
                  <i class="fas fa-search"></i>
                </button>
              </a>
            </nav>
          </div>
          <div class="card-body">
            <table id="tbl_kawasan" class="table table-bordered table-hover">
              <div class="form-group">
                <label for="nik_petugas" class="col-sm-2 control-label">Nama Petugas</label>
                <div class="col-sm-12">
                  <div class="row">
                    <div class="col-6">
                      <input type="text" class="form-control" id="nik_petugas" name="nik_petugas" placeholder="User ID" value="" maxlength="50" readonly="readonly">
                    </div>
                    <div class="col-5">
                      <input type="text" class="form-control" id="nm_petugas" name="nm_petugas" placeholder="Nama" value="" maxlength="50" readonly="readonly">
                    </div>
                    <div class="col">
                      <button type="button" class="btn btn-default btn-flat" onclick="nik_petugas()">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12">
                  <input type="text" class="form-control" id="jabatan_petugas" name="jabatan_petugas" placeholder="Jabatan" value="" maxlength="50" readonly="readonly">
                </div>
              </div>
              <div class="form-group">
                <label for="kd_kawasan" class="col-sm-12">Kawasan</label>
                <div class="col-sm-12">{!! $dt['kd_kawasan'] !!}</div>
              </div>
              <div class="form-group">
                <label for="kd_cluser" class="col-sm-12">Cluster</label>
                <div id="div_cluster" class="col-sm-12"></div>
              </div>
              <thead >                  
                <tr >
                  <th style="width: 10px">#</th>
                  <th style="width: 130px; text-align: center;">Blok / Nomor</th>
                  <th style="text-align: center;">Tipe</th>
                  <th style="text-align: center; width: 130px;">Action</th>
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

<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading">Alokasi Blok / Nomor</h4>
            </div>
            <div class="modal-body">
                <form id="CustomerForm" name="CustomerForm" class="form-horizontal" autocomplete="off" onsubmit="return save_process()">
                   <input type="hidden" name="saveBtnVal" id="saveBtnVal" value="create">
                   <input type="hidden" name="kd_lantai" id="kd_lantai" value="">
                    <div class="form-group table-responsive p-0" style="height: 300px;">
                      <table id="tbl_available_stok" class="table table-bordered table-head-fixed table-hover">
                        <thead>                  
                          <tr>
                            <th style="text-align: center; width: 10px">No</th>
                            <th style="text-align: center; width: 200px;">Blok / Nomor</th>
                            <th style="text-align: center; width: 200px;">Tipe</th>
                            <th style="text-align: center; width: 20px;">#</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                      </table>                        
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