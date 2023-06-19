@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit 		= "{{ session('kd_unit') }}";
		var kd_lokasi 	= "{{ session('kd_lokasi') }}";		

    $(function() {
      //show_jenis_bangunan();
      data_cluster();
      data_cluster_add();
      //tipe_rumah();
    });

    function data_blok_no() {
      $('#v_loading').show();

      var kd_kawasan = $('#kd_kawasan').val();
      var kd_cluster = $('#kd_cluster').val();
      var tahap_bangun = $('#tahap_bangun').val();
      var no_awal = $('#no_awal').val();
      var no_akhir = $('#no_akhir').val();

      $.ajax({
        type  : 'POST',
        url   : 'register_tahap_bangun_c/data_blok_no',
        data  : {
          "kd_unit"   : kd_unit,
          "kd_lokasi" : kd_lokasi,
          "kd_kawasan" : kd_kawasan,
          "kd_cluster" : kd_cluster,  
          "tahap_bangun" : tahap_bangun,
          "no_awal" : no_awal,
          "no_akhir" : no_akhir,
          "_token"    : '{{ csrf_token() }}'
        },
        success : function(msg) {          
          var row;
          var no=1;

          //$('#tbl_kawasan tbody').empty();

          $("#tbl_kawasan").find("tr:gt(0)").remove(); // CLEAR TABLE
          $.each(msg, function(x, y) {
            var isSelected5 = isSelected4 = isSelected3 = isSelected2 = isSelected1 = isSelected6 = isSelected7 ='';
            switch (y.tahap_bangun) {
              case '5': isSelected5 = 'selected'; break;
              case '4': isSelected4 = 'selected'; break;
              case '3': isSelected3 = 'selected'; break;
              case '2': isSelected2 = 'selected'; break;
              default:
                isSelected1 = 'selected';
            }
            switch (y.flag_st) {
              case 'Y': isSelected6 = 'selected'; break;
              default:
                isSelected7 = 'selected';
            }
            row += ''+
              '<tr>'+
                '<td>'+ no++ +'</td>'+
                '<td>'+y.blok+'</td>'+
                '<td>'+y.nomor+'</td>'+
                '<td>'+y.nm_tipe+'</td>'+
                '<td><select class="form-control col-sm-12" name="tahap_bangun_edit[]" id="tahap_bangun_edit[]" >'+
                  '<option value="'+kd_kawasan+'#'+kd_cluster+'#'+y.blok+'#'+y.nomor+'#'+y.tahap_bangun+'#1" '+isSelected1+'>1</option>'+
                  '<option value="'+kd_kawasan+'#'+kd_cluster+'#'+y.blok+'#'+y.nomor+'#'+y.tahap_bangun+'#2" '+isSelected2+'>2</option>'+
                  '<option value="'+kd_kawasan+'#'+kd_cluster+'#'+y.blok+'#'+y.nomor+'#'+y.tahap_bangun+'#3" '+isSelected3+'>3</option>'+
                  '<option value="'+kd_kawasan+'#'+kd_cluster+'#'+y.blok+'#'+y.nomor+'#'+y.tahap_bangun+'#4" '+isSelected4+'>4</option>'+
                  '<option value="'+kd_kawasan+'#'+kd_cluster+'#'+y.blok+'#'+y.nomor+'#'+y.tahap_bangun+'#5" '+isSelected5+'>5</option>'+
                '</select></td>'+
                '<td>'+y.dataTipeRumah+'</td>'+
                '<td><select class="form-control col-sm-12" name="flag_st_edit[]" id="flag_st_edit[]" >'+
                  '<option value="'+kd_kawasan+'#'+kd_cluster+'#'+y.blok+'#'+y.nomor+'#'+y.flag_st+'#Y" '+isSelected6+'>Y</option>'+
                  '<option value="'+kd_kawasan+'#'+kd_cluster+'#'+y.blok+'#'+y.nomor+'#'+y.flag_st+'#T" '+isSelected7+'>T</option>'+
                '</select></td>'+
              '</tr>'+
            '';
          });

          // $('#tbl_kawasan').html(row);
          $('#tbl_kawasan').append(row);
          $('#v_loading').hide();
        },
        error   : function(xhr) {
          $('#v_loading').hide();
          read_error(xhr);
        }
      });
    }

    function sync_dt() {
      $('#v_loading').show();
        var kd_kawasan = $('#kd_kawasan').val();
        var kd_cluster = $('#kd_cluster').val();

      $.ajax({
        type  : 'POST',
        url   : 'register_tahap_bangun_c/sync_dt',
        data  : {
          "_token"  : '{{ csrf_token() }}',
          "kd_unit" : kd_unit,
          "kd_lokasi" : kd_lokasi,
          "kd_kawasan" : kd_kawasan,
          "kd_cluster" : kd_cluster,            
        },
        success : function(msg) {
          //show_jenis_bangunan()
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
            url: 'register_tahap_bangun_c/data_cluster',
            cache: false,
            data: {
              "_token"  : '{{ csrf_token() }}',
              "kd_unit" : kd_unit,
              "kd_lokasi" : kd_lokasi,
              "kd_kawasan" : kd_kawasan                
            },
            success: function(msg){

                row = '<select class="form-control col-sm-12" name="kd_cluster" id="kd_cluster" onchange="data_blok_no()">';
                $.each(msg, function(x, y) {
                  row += '<option value="'+ y.kd_cluster +'">'+ y.nm_cluster +'</option>';
                });
                row += '</select>';

                $('#div_cluster').html(row);
                
                data_blok_no();
            },
            error: function(){
                $('#v_loading').hide();
                alert('Proses Get Cluster Gagal');
            }
        });
        
        return false;
    }

    // function data_tahap() {
    //     var kd_kawasan = $('#kd_kawasan').val();
    //     var kd_cluster = $('#kd_cluster').val();
    //     var row;
        
    //     $.ajax({
    //         type: 'POST',
    //         url: 'register_tahap_bangun_c/data_tahap',
    //         cache: false,
    //         data: {
    //           "_token"  : '{{ csrf_token() }}',
    //           "kd_unit" : kd_unit,
    //           "kd_lokasi" : kd_lokasi,
    //           "kd_kawasan" : kd_kawasan,
    //           "kd_cluster" : kd_cluster
    //         },
    //         success: function(msg){

    //             row = '<select class="form-control col-sm-12" name="tahap_bangun" id="tahap_bangun" onchange="data_blok_no()">';
    //             $.each(msg, function(x, y) {
    //               row += '<option value="'+ y.tahap_bangun +'">'+ y.tahap_bangun +'</option>';
    //             });
    //             row += '</select>';

    //             $('#div_tahap').html(row);
                
    //             data_blok_no();
    //         },
    //         error: function(){
    //             $('#v_loading').hide();
    //             alert('Proses Get Cluster Gagal');
    //         }
    //     });
        
    //     return false;
    // }

    function data_cluster_add() {
        var kd_kawasan_add = $('#kd_kawasan_add').val();
        var row;
        
        $.ajax({
            type: 'POST',
            url: 'register_tahap_bangun_c/data_cluster',
            cache: false,
            data: {
              "_token"  : '{{ csrf_token() }}',
              "kd_unit" : kd_unit,
              "kd_lokasi" : kd_lokasi,
              "kd_kawasan" : kd_kawasan_add                
            },
            success: function(msg){

                row = '<select class="form-control col-sm-12" name="kd_cluster_add" id="kd_cluster_add" >';
                $.each(msg, function(x, y) {
                  row += '<option value="'+ y.kd_cluster +'">'+ y.nm_cluster +'</option>';
                });
                row += '</select>';

                $('#div_cluster_add').html(row);
                
                //data_blok_no();
            },
            error: function(){
                $('#v_loading').hide();
                alert('Proses Get Cluster Gagal');
            }
        });
        
        return false;
    }

    function tipe_rumah() {
        var kd_kawasan = $('#kd_kawasan_add').val();
        var kd_cluster = $('#kd_cluster_add').val();
        var kd_jenis = $('#kd_jenis_add').val();
        var row;
        
        $.ajax({
            type: 'POST',
            url: 'register_tahap_bangun_c/tipe_rumah',
            cache: false,
            data: {
              "_token"  : '{{ csrf_token() }}',
              "kd_unit" : kd_unit,
              "kd_lokasi" : kd_lokasi,
              "kd_kawasan" : kd_kawasan,
              "kd_cluster" : kd_cluster,
              "kd_jenis" : kd_jenis                   
            },
            success: function(msg){

                row = '<select class="form-control col-sm-12" name="kd_tipe_add" id="kd_tipe_add" onchange="data_blok_no()">';
                $.each(msg, function(x, y) {
                  row += '<option value="'+ y.kd_tipe +'">'+ y.nm_tipe +'</option>';
                });
                row += '</select>';

                $('#div_tipe_add').html(row);
                
            },
            error: function(){
                $('#v_loading').hide();
                alert('Proses Tipe Rumah Gagal');
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
          url: "register_tahap_bangun_c/delete_dt",
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
      var kd_kawasan    = $('#kd_kawasan').val();
      var kd_cluster    = $('#kd_cluster').val();
      var tahap_bangun_edit    = $('#tahap_bangun_edit').val();

        $.ajax({
          data: data_all,
          url: "register_tahap_bangun_c/save",
          headers : { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
          type: "POST",
          success: function (data) {
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

  function pop_tipe() {
    var keyword = $('#sys-modal-default-keyword').val();
    var kd_kawasan  = $('#kd_kawasan_add').val();

    $.ajax({
      type  : 'POST',
      url   : 'register_tahap_bangun_c/pop_tipe?page='+sys_search_unit_page,
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
                          '<h3 class="card-title">Tipe Rumah</h3>'+
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
                        '<td>'+y.KD_TIPE+'</td>'+
                        '<td>'+y.NM_TIPE+'</td>'+
                        '<td><button type="button" class="btn btn-info btn-sm" onclick="pop_tipe_set(\''+y.KD_TIPE+'\', \''+y.NM_TIPE+'\')">Pilih</button></td>'+
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
                            '<button type="button" class="btn btn-info btn-flat btn-sm" onclick="sys_search_unit_page--; pop_tipe();"><<</button>'+
                          '</span>'+
                          '<input type="number" class="form-control form-control-sm rounded-0" min="1" id="sys_search_unit_page_input" style="text-align: center;" value="'+sys_search_unit_page+'">'+
                          '<span class="input-group-append">'+
                            '<button type="button" class="btn btn-info btn-flat btn-sm" onclick="sys_search_unit_page++; pop_tipe();">>></button>'+
                            '<button type="button" class="btn btn-default btn-flat btn-sm" onclick="sys_search_unit_page = $(\'#sys_search_unit_page_input\').val(); pop_tipe();">Go!</button>'+
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

  function pop_tipe_set(KD_TIPE, NM_TIPE) {
    $('#kd_tipe_add').val(KD_TIPE);
    $('#nm_tipe_add').val(NM_TIPE);
    $('#sys-modal-default').modal('hide');
  }

  var search_blk_no_page = 1;

  function search_blok_no() {
    var keyword = $('#sys-modal-default-keyword').val();
    var kd_kawasan  = $('#kd_kawasan_add').val();
    var kd_cluster  = $('#kd_cluster_add').val();

    $.ajax({
      type  : 'POST',
      url   : 'register_tahap_bangun_c/search_blok_no?page='+search_blk_no_page,
      data  : {
        "_token"  : '{{ csrf_token() }}',
        "keyword" : keyword,
        "kd_kawasan" : kd_kawasan,
        "kd_cluster" : kd_cluster
      },
      success : function(msg){
        var tbl = ''+
          '<div class="row">'+
                  '<div class="col-12">'+
                    '<div class="card">'+
                        '<div class="card-header">'+
                          '<h3 class="card-title">Stok Non Marketing</h3>'+
                        '</div>'+

                        '<div class="card-body table-responsive p-0" style="height: 300px;">'+
                  '<table class="table table-sm table-head-fixed text-nowrap">'+
                    '<thead>'+
                      '<tr>'+
                        '<th>Cluster</th>'+
                        '<th>Tipe</th>'+
                        '<th>Blok/Nomor</th>'+
                        '<th>#</th>'+
                      '</tr>'+
                    '</thead>'+
                    '<tbody>'+
        '';
        $.each(msg['data'], function(x, y){
          tbl += ''+
                      '<tr>'+
                        '<td>'+y.KD_CLUSTER+'</td>'+
                        '<td>'+y.KD_TIPE+'</td>'+
                        '<td>'+y.BLOK+'/'+y.NOMOR+'</td>'+
                        '<td><button type="button" class="btn btn-info btn-sm" onclick="pop_blok_no(\''+y.KD_KAWASAN+'\', \''+y.KD_CLUSTER+'\', \''+y.BLOK+'\', \''+y.NOMOR+'\', \''+y.KD_TIPE+'\', \''+y.KD_JENIS+'\', \''+y.NM_TIPE+'\', \''+y.STOK_ID+'\')">Pilih</button></td>'+
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
                            '<button type="button" class="btn btn-info btn-flat btn-sm" onclick="search_blk_no_page--; pop_tipe();"><<</button>'+
                          '</span>'+
                          '<input type="number" class="form-control form-control-sm rounded-0" min="1" id="search_blk_no_page_input" style="text-align: center;" value="'+search_blk_no_page+'">'+
                          '<span class="input-group-append">'+
                            '<button type="button" class="btn btn-info btn-flat btn-sm" onclick="search_blk_no_page++; pop_tipe();">>></button>'+
                            '<button type="button" class="btn btn-default btn-flat btn-sm" onclick="search_blk_no_page = $(\'#search_blk_no_page_input\').val(); pop_tipe();">Go!</button>'+
                          '</span>'+
                      '</div>'+
            '</div>'+
          '</div>'+
        '';

        $('#sys-modal-default-body').html(tbl);
        $('#sys-modal-default-btn-search').attr('onclick', 'sys_search_unit()');
        $('#sys-modal-default').modal("show");
        $('#saveBtnVal').val("edit");
      },
      error   : function(xhr){
        read_error(xhr);
      }
    });
  }

  function pop_blok_no(KD_KAWASAN, KD_CLUSTER, BLOK, NOMOR, KD_TIPE, KD_JENIS, NM_TIPE,STOK_ID) {
    $('#kd_tipe_add').val(KD_TIPE);
    $('#nm_tipe_add').val(NM_TIPE);
    $('#blok').val(BLOK);
    $('#nomor').val(NOMOR);
    $('#stok_id').val(STOK_ID);
    $('#kd_jenis_add').val(KD_JENIS);
    $('#sys-modal-default').modal('hide');
  }

  function search_dt() {
    //$('#v_loading').show();
    data_blok_no();
    //$('#v_loading').hide();
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
                <h3 class="card-title">Register Tahap Bangun</h3>
              </a>
              <a class="navbar-inline">
                {!! $dt['button'] !!}
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
            <form id="CustomerForm" name="CustomerForm" class="form-horizontal" autocomplete="off" onsubmit="return save_process()">
            <table id="tbl_kawasan" class="table table-bordered table-hover">
              <div class="form-group">
                <label></label>
              </div>
              <div class="form-group">
                <label for="kd_kawasan" class="col-sm-12">Kawasan</label>
                <div class="col-sm-12">{!! $dt['kd_kawasan'] !!}</div>
              </div>
              <div class="form-group">
                <label for="kd_cluser" class="col-sm-12">Cluster</label>
                <div id="div_cluster" class="col-sm-12"></div>
              </div>
              <div class="row">
                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="no_awal" class="col-sm-12">Blok / Nomor</label>
                    <div id="div_no_awal" class="col-sm-12"><input type="text" size="10" class="form-control" name="no_awal" id="no_awal"></div>
                  </div>
                </div>
                <div class="col-sm-1">
                  <div class="form-group">
                    <label class="col-sm-12 text-center"></label>
                    <div class="col-sm-12 text-center"><label class="col-sm-12 text-center">S/D</label></div>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="no_akhir" class="col-sm-12">Blok / Nomor</label>
                    <div id="div_no_akhir" class="col-sm-12"><input type="text" size="10" class="form-control" name="no_akhir" id="no_akhir"></div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="tahap_bangun" class="col-sm-12">Tahap Bangun</label>
                <div id="div_tahap" class="col-sm-12">
                  <select class="form-control col-sm-12" name="tahap_bangun" id="tahap_bangun" onchange="data_blok_no()">';
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                  </select>
                </div>
              </div>
              <thead >
                <tr >
                  <th style="width: 10px">#</th>
                  <th style="width: 130px; text-align: center;">Blok</th>
                  <th style="text-align: center;">Nomor</th>
                  <th style="text-align: center;">Tipe</th>
                  <th style="width: 120px; text-align: center;">Tahap Bangun</th>
                  <th style="width: 200px; text-align: center;">Tipe Rumah</th>
                  <th style="width: 95px; text-align: center;">Flag ST</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
            <div class="col-sm-offset-2 col-sm-10">
             <button type="submit" class="btn btn-primary" id="saveBtn" style="display:none;" value="create">Save</button>
            </div>
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
@endsection