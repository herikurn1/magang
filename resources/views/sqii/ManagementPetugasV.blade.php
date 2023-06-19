@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit 		= "{{ session('kd_unit') }}";
		var kd_lokasi 	= "{{ session('kd_lokasi') }}";		

    $(function() {
    });

    function add_dt() {
      mst_jabatan();
      $('#saveBtnVal').val("create");
      $('#kd_kawasan_add').val('');
      $('#kd_cluster_add').val('');
      $('#kd_jenis_add').val('');
      $('#kd_tipe_add').val('');
      $('#CustomerForm').trigger("reset");
      $('#modelHeading').html("Entry User");
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

    function delete_dt() {
      var nik_petugas = $('#nik_petugas').val();

        $.ajax({
          data  : {
            "nik_petugas"    : nik_petugas,
            "_token"        : '{{ csrf_token() }}'
          },
          url: "user_management_c/delete_dt",
          type: "POST",

          success: function (data) {
            $('#PetugasForm').trigger("reset");
            //data_blok_no();
          },
          error: function (data) {
              console.log('Error:', data);
          }
      });

        return false;
    } 

    function save_process() {
      var data_all      = $('#PetugasForm').serializeArray();

      $.ajax({
        data: data_all,
        url: "user_management_c/save",
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
          url: "user_management_c/delete_bawahan",
          type: "POST",

          success: function (data) {
            //data_blok_no();
          },
          error: function (data) {
              console.log('Error:', data);
          }
      });

        return false;
    } 

    function save_process2() {
      //var nik_petugas = $('#nik_petugas').val();
      var user_email = $('#user_email').val();
      var user_nama = $('#user_nama').val();
      var id_jabatan = $('#id_jabatan').val();
      // var id_bawahan = $('#id_bawahan').val().split('#');

      $.ajax({
          data  : {
            "user_email"    : user_email,
            "user_nama"     : user_nama,
            "id_jabatan"    : id_jabatan,
            "_token"        : '{{ csrf_token() }}'
          },
        url: "user_management_c/save_bawahan",
        type: "POST",

        success: function (data) {
            $('#ajaxModel').modal('hide');
            $('#BawahanForm').trigger("reset");
            //data_blok_no();
        },
        error: function (data) {
            read_error(data);
            //console.log('Error:', data);
            $('#saveBtnStaff').html('Save Changes');
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
      url   : 'user_management_c/nik_petugas?page='+sys_search_unit_page,
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
        $('#sys-modal-default-btn-search').attr('onclick', 'sys_search_unit()');
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
    $('#sys-modal-default').modal('hide');
    //data_blok_no();


  }

  var sys_search_kry_page = 1;

  function nik_karyawan() {
    var keyword = $('#sys-modal-default-keyword').val();
    var kd_kawasan  = $('#kd_kawasan_add').val();

    $.ajax({
      type  : 'POST',
      url   : 'user_management_c/nik_karyawan?page='+sys_search_kry_page,
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
                        '<td><button type="button" class="btn btn-info btn-sm" onclick="nik_karyawan_set(\''+y.USER_ID+'\', \''+y.NAMA+'\', \''+y.KD_JABATAN+'\', \''+y.FLAG_AKTIF+'\')">Pilih</button></td>'+
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

  function nik_karyawan_set(USER_ID, NAMA, KD_JABATAN, FLAG_AKTIF) {
    $('#nik_petugas').val(USER_ID);
    $('#nm_petugas').val(NAMA);
    $('#kd_jabatan').val(KD_JABATAN);
    $('#flag_aktif').val(FLAG_AKTIF);
    $('#sys-modal-default').modal('hide');
  }  

    function mst_jabatan() {
      var nik_petugas = $('#nik_petugas').val();
        var row;
        
        $.ajax({
            type: 'POST',
            url: 'user_management_c/mst_jabatan',
            cache: false,
            data: {
              "_token"  : '{{ csrf_token() }}',
              "kd_unit" : kd_unit,
              "kd_lokasi" : kd_lokasi,
              "nik_petugas" : nik_petugas            
            },
            success: function(msg){

                row = '<select class="form-control col-sm-12" name="id_jabatan" id="id_jabatan">';
                $.each(msg, function(x, y) {
                  row += '<option value="'+ y.kd_jabatan +'">'+ y.nm_jabatan +'</option>';
                });
                row += '</select>';

                $('#div_jabatan').html(row);
                
            },
            error: function(){
                $('#v_loading').hide();
                alert('Proses Get Cluster Gagal');
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
                <h3 class="card-title">User Management</h3>
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
                    <input type="text" class="form-control" id="nm_petugas" name="nm_petugas" placeholder="Nama" value="" maxlength="50" >
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
              <label for="kd_jabatan" class="col-sm-12">Jabatan</label>
              <div class="col-sm-12">{!! $dt['kd_jabatan'] !!}</div>
            </div>
            <div class="form-group">
              <label for="flag_aktif" class="col-sm-12">Flag Aktif</label>
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
              <form id="BawahanForm" name="BawahanForm" class="form-horizontal" autocomplete="off" onsubmit="return save_process2()">
                 <!-- <input type="hidden" name="saveBtnStaffVal" id="saveBtnStaffVal" value="create">
                 <input type="hidden" name="kd_lantai" id="kd_lantai" value=""> -->
                  <div class="form-group">
                      <div class="col-sm-12">
                        <div class="row">
                          <div class="col-4">
                            <label for="stok_add" class="col-sm control-label">User Email</label>
                            <input type="text" class="form-control" id="user_email" name="user_email" placeholder="User Email" value="" maxlength="50">
                          </div>
                          <div class="col-4">
                            <label for="stok_add" class="col-sm control-label">Nama</label>
                            <input type="text" class="form-control" id="user_nama" name="user_nama" placeholder="Nama" value="" maxlength="50" >
                          </div>                            
                          <div class="col-4">
                            <label for="stok_add" class="col-sm control-label">Jabatan</label>
                            <div class="" id="div_jabatan"></div>
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