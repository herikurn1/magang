@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit 		= "{{ session('kd_unit') }}";
		var kd_lokasi 	= "{{ session('kd_lokasi') }}";		

    $(function() {
    });

    function data_blok_no() {
      var nik_petugas = $('#nik_petugas').val();

      $.ajax({
        type  : 'POST',
        url   : 'set_jabatan_struktur_org_c/data_blok_no',
        data  : {
          "kd_unit"   : kd_unit,
          "kd_lokasi" : kd_lokasi,
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
                '<td>'+y.nama+'</td>'+
                '<td>'+y.nm_jabatan+'</td>'+
                '<td style="text-align: center;">'+
                  '<button type="button" class="btn btn-info btn-sm" onclick="delete_dt2(\''+y.user_id+'\',\''+y.user_id_bawahan+'\')">Hapus</button></td>'+
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
        url   : 'set_jabatan_struktur_org_c/sync_dt',
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

    function clear_tbl(){
      $("#tbl_kawasan").find("tr:gt(0)").remove(); // CLEAR TABLE
    }

    function add_dt() {
      //available_stok();
      $('#saveBtnVal').val("create");
      $('#kd_kawasan_add').val('');
      $('#kd_cluster_add').val('');
      $('#kd_jenis_add').val('');
      $('#kd_tipe_add').val('');
      $('#PetugasForm').trigger("reset");
      $("#tbl_kawasan").find("tr:gt(0)").remove(); // CLEAR TABLE
      $('#modelHeading').html("Staff / Bawahan");
      //$('#ajaxModel').modal('show');   

      return false;
    }        

    function get_staff() {
      var nik_petugas = $('#nik_petugas').val();
        var row;
        
        $.ajax({
            type: 'POST',
            url: 'set_jabatan_struktur_org_c/get_staff',
            cache: false,
            data: {
              "_token"  : '{{ csrf_token() }}',
              "kd_unit" : kd_unit,
              "kd_lokasi" : kd_lokasi,
              "nik_petugas" : nik_petugas            
            },
            success: function(msg){

                row = '<select class="form-control col-sm-12" name="id_bawahan" id="id_bawahan" onchange="set_jabatan_bawahan()">';
                $.each(msg, function(x, y) {
                  row += '<option value="'+ y.user_id +'#'+ y.kd_jabatan +'#'+ y.nm_jabatan +'">'+ y.nama +'</option>';
                });
                row += '</select>';

                $('#div_add_staff').html(row);
                
            },
            error: function(){
                $('#v_loading').hide();
                alert('Proses Get Staff Gagal');
            }
        });
        
        return false;
    }

    function set_jabatan_bawahan() {
      var nm_jabatan = $('#id_bawahan').val().split('#');
      $('#jabatan_bawahan').val(nm_jabatan[2]);
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
      //   $.ajax({
      //     data  : {
      //       "kd_kawasan"    : kd_kawasan,
      //       "kd_cluster"    : kd_cluster,
      //       "blok"          : blok,
      //       "nomor"         : nomor,
      //       "user_id"         : user_id,
      //       "_token"        : '{{ csrf_token() }}'
      //     },
      //     url: "set_jabatan_struktur_org_c/delete_dt",
      //     type: "POST",

      //     success: function (data) {
      //       //data_blok_no();
      //     },
      //     error: function (data) {
      //         console.log('Error:', data);
      //     }
      // });

        return false;
    } 

    function save_process() {
      var data_all      = $('#PetugasForm').serializeArray();

      $.ajax({
        data: data_all,
        url: "set_jabatan_struktur_org_c/save",
        type: "POST",

        success: function (data) {
            $('#ajaxModel').modal('hide');
            $('#CustomerForm').trigger("reset");
            //data_blok_no();
        },
        error: function (data) {
            console.log('Error:', data);
            $('#saveBtn').html('Save Changes');
        }
      });
        return false;
    }

    function delete_dt2(user_id, user_id_bawahan) {
        $.ajax({
          data  : {
            "user_id_bawahan" : user_id_bawahan,
            "user_id"         : user_id,
            "_token"          : '{{ csrf_token() }}'
          },
          url: "set_jabatan_struktur_org_c/delete_bawahan",
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

    function save_process2() {
      var nik_petugas = $('#nik_petugas').val();
      var id_bawahan = $('#id_bawahan').val().split('#');

      $.ajax({
          data  : {
            "id_bawahan"    : id_bawahan[0],
            "nm_jabatan"    : id_bawahan[2],
            "nik_petugas"   : nik_petugas,
            "_token"        : '{{ csrf_token() }}'
          },
        url: "set_jabatan_struktur_org_c/save_bawahan",
        type: "POST",

        success: function (data) {
            $('#ajaxModel').modal('hide');
            $('#BawahanForm').trigger("reset");
            data_blok_no();
        },
        error: function (data) {
            console.log('Error:', data);
            $('#saveBtnStaff').html('Save Changes');
        }
      });
        return false;
    }

    function add_staff() {
      get_staff();
      $('#saveBtnVal').val("create");
      $('#kd_kawasan_add').val('');
      $('#kd_cluster_add').val('');
      $('#kd_jenis_add').val('');
      $('#kd_tipe_add').val('');
      $('#CustomerForm').trigger("reset");
      $('#modelHeading').html("Staff / Bawahan");
      $('#ajaxModel').modal('show');   

      return false;
    }  

  var sys_search_unit_page = 1;

  function nik_petugas() {
    var keyword = $('#sys-modal-default-keyword').val();
    var kd_kawasan  = $('#kd_kawasan_add').val();

    $.ajax({
      type  : 'POST',
      url   : 'set_jabatan_struktur_org_c/nik_petugas?page='+sys_search_unit_page,
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
                        '<th>Nama</th>'+
                        '<th>Jabatan</th>'+
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
                        '<td><button type="button" class="btn btn-info btn-sm" onclick="nik_petugas_set(\''+y.USER_ID+'\', \''+y.NAMA+'\', \''+y.KD_JABATAN+'\', \''+y.KD_KAWASAN+'\', \''+y.FLAG_AKTIF+'\')">Pilih</button></td>'+
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

  function nik_petugas_set(USER_ID, NAMA, KD_JABATAN, KD_KAWASAN, FLAG_AKTIF) {
    $('#nik_petugas').val(USER_ID);
    $('#nm_petugas').val(NAMA);
    $('#kd_jabatan').val(KD_JABATAN);
    $('#kd_kawasan').val(KD_KAWASAN);
    $('#flag_aktif').val(FLAG_AKTIF);
    $('#sys-modal-default-keyword').val('');
    $('#sys-modal-default').modal('hide');
    data_blok_no();


  }

  var sys_search_kry_page = 1;

  function nik_karyawan() {
    var keyword = $('#sys-modal-default-keyword').val();
    var kd_kawasan  = $('#kd_kawasan_add').val();

    $.ajax({
      type  : 'POST',
      url   : 'set_jabatan_struktur_org_c/nik_karyawan?page='+sys_search_kry_page,
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
                        '<th>NIK</th>'+
                        '<th>Nama</th>'+
                        '<th>Jabatan</th>'+
                        '<th>#</th>'+
                      '</tr>'+
                    '</thead>'+
                    '<tbody>'+
        '';
        $.each(msg['data'], function(x, y){
          tbl += ''+
                      '<tr>'+
                        '<td>'+y.USER_ID+'</td>'+
                        '<td>'+y.NAMA+'</td>'+
                        '<td>'+y.NM_JABATAN+'</td>'+
                        '<td><button type="button" class="btn btn-info btn-sm" onclick="nik_karyawan_set(\''+y.USER_ID+'\', \''+y.NAMA+'\', \''+y.KD_JABATAN+'\')">Pilih</button></td>'+
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
                            '<button type="button" class="btn btn-info btn-flat btn-sm" onclick="sys_search_kry_page--; nik_karyawan();"><<</button>'+
                          '</span>'+
                          '<input type="number" class="form-control form-control-sm rounded-0" min="1" id="sys_search_kry_page_input" style="text-align: center;" value="'+sys_search_kry_page+'">'+
                          '<span class="input-group-append">'+
                            '<button type="button" class="btn btn-info btn-flat btn-sm" onclick="sys_search_kry_page++; nik_karyawan();">>></button>'+
                            '<button type="button" class="btn btn-default btn-flat btn-sm" onclick="sys_search_kry_page = $(\'#sys_search_kry_page_input\').val(); nik_karyawan();">Go!</button>'+
                          '</span>'+
                      '</div>'+
            '</div>'+
          '</div>'+
        '';

        $('#sys-modal-default-body').html(tbl);
        $('#sys-modal-default-btn-search').attr('onclick', 'nik_karyawan()');
        $('#sys-modal-default').modal("show");
      },
      error   : function(xhr){
        read_error(xhr);
      }
    });
  }

  function nik_karyawan_set(USER_ID, NAMA, KD_JABATAN) {
    $('#nik_petugas').val(USER_ID);
    $('#nm_petugas').val(NAMA);
    $('#kd_jabatan').val(KD_JABATAN);
    $('#sys-modal-default-keyword').val('');
    $('#sys-modal-default').modal('hide');
    data_blok_no();
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
                <h3 class="card-title">Setting Jabatan & Struktur organisasi user</h3>
              </a>
              <a class="navbar-inline">
                {!! $dt['button'] !!}
                <!-- <button type="button" class="btn btn-default btn-flat" onclick="nik_petugas();">
                  <i class="fas fa-search"></i>
                </button> -->
              </a>
            </nav>
          </div>
          <div class="card-body">
            <form id="PetugasForm" name="PetugasForm" class="form-horizontal" autocomplete="off" onsubmit="return save_process()">
            <div class="form-group">
              <label for="nik_petugas" class="col-sm-2 control-label">No Induk</label>
              <div class="col-sm-12">
                <div class="row">
                  <div class="col-6">
                    <input type="text" class="form-control" id="nik_petugas" name="nik_petugas" placeholder="User ID" value="" maxlength="50" readonly="readonly">
                  </div>
                  <div class="col-5">
                    <input type="text" class="form-control" id="nm_petugas" name="nm_petugas" placeholder="Nama" value="" maxlength="50" readonly="readonly">
                  </div>
                  <div class="col">
                    <button type="button" class="btn btn-default btn-flat" onclick="nik_karyawan()">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="kd_kawasan" class="col-sm-12">Jabatan</label>
              <div class="col-sm-12">{!! $dt['kd_jabatan'] !!}</div>
            </div>
            <div class="form-group" style="display: none;">
              <label for="kd_kawasan" class="col-sm-12">Kawasan</label>
              <div class="col-sm-12">{!! $dt['kd_kawasan'] !!}</div>
            </div>
            <div class="form-group">
              <label for="kd_cluser" class="col-sm-12">Flag Aktif</label>
              <div class="col-sm-12">
              <select class="form-control col-sm-12" name="flag_aktif" id="flag_aktif" >
                <option value="A">Aktif</option>
                <option value="T">Tidak Aktif</option>
              </select>                  
              </div>
            </div>
            <button type="submit" style="display: none;" class="btn btn-primary" id="saveBtn" value="create">Save</button>
            <input type="hidden" name="saveBtnVal" id="saveBtnVal" value="create">
            @csrf
            </form>

            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Staff / Bawahan</a>
              </li>
            </ul>
            <div class="tab-content" id="custom-content-below-tabContent">
              <div class="tab-pane fade active show" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
              <div class="form-group">
                <label class="col-sm-12"></label>
                <div class="col-sm-12"><button type="button" class="btn btn-info btn-sm" onclick="add_staff()">Tambah</button></div>
              </div>                
              <table id="tbl_kawasan" class="table table-bordered table-hover">
                <thead>                  
                  <tr >
                    <th style="width: 10px">#</th>
                    <th style="text-align: center;">Nama Petugas</th>
                    <th style="text-align: center;">Jabatan</th>
                    <th style="text-align: center; width: 130px;">Action</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>  
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="BawahanForm" name="BawahanForm" class="form-horizontal" autocomplete="off" onsubmit="return save_process2()">
                   <input type="hidden" name="saveBtnStaffVal" id="saveBtnStaffVal" value="create">
                   <input type="hidden" name="kd_lantai" id="kd_lantai" value="">
                    <div class="form-group">
                        <label for="stok_add" class="col-sm-2 control-label">Nama</label>
                        <div class="col-sm-12">
                          <div class="row">
                            <div class="col-6">
                              <div class="" id="div_add_staff"></div>
                            </div>
                            <div class="col-6">
                              <input type="text" class="form-control" id="jabatan_bawahan" name="jabatan_bawahan" placeholder="Jabatan" value="" maxlength="50" readonly="readonly">
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