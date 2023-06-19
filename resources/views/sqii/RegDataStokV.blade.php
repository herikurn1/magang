@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit 		= "{{ session('kd_unit') }}";
		var kd_lokasi 	= "{{ session('kd_lokasi') }}";		

    $(function() {
      //show_jenis_bangunan();
      data_cluster();
      data_cluster_add();
      data_cluster_tp_rmh();
      //tipe_rumah();
    });

    function data_blok_no() {
      $('#v_loading').show();

        var kd_kawasan = $('#kd_kawasan').val();
        var kd_cluster = $('#kd_cluster').val();
        var tahap_bangun = $('#tahap_bangun').val();

      $.ajax({
        type  : 'POST',
        url   : 'register_data_stok_c/data_blok_no',
        data  : {
          "kd_unit"   : kd_unit,
          "kd_lokasi" : kd_lokasi,
          "kd_kawasan" : kd_kawasan,
          "kd_cluster" : kd_cluster,  
          "tahap_bangun" : tahap_bangun,            
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
                '<td>'+y.blok+'</td>'+
                '<td>'+y.nomor+'</td>'+
                '<td>'+y.nm_tipe+'</td>'+
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
        url   : 'register_data_stok_c/sync_dt',
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
            url: 'register_data_stok_c/data_cluster',
            cache: false,
            data: {
              "_token"  : '{{ csrf_token() }}',
              "kd_unit" : kd_unit,
              "kd_lokasi" : kd_lokasi,
              "kd_kawasan" : kd_kawasan                
            },
            success: function(msg){

                row = '<select class="form-control col-sm-12" name="kd_cluster" id="kd_cluster" onchange="data_tahap()">';
                $.each(msg, function(x, y) {
                  row += '<option value="'+ y.kd_cluster +'">'+ y.nm_cluster +'</option>';
                });
                row += '</select>';

                $('#div_cluster').html(row);
                
                data_tahap();
            },
            error: function(){
                $('#v_loading').hide();
                alert('Proses Get Cluster Gagal');
            }
        });
        
        return false;
    }

    function data_tahap() {
        var kd_kawasan = $('#kd_kawasan').val();
        var kd_cluster = $('#kd_cluster').val();
        var row;
        
        $.ajax({
            type: 'POST',
            url: 'register_data_stok_c/data_tahap',
            cache: false,
            data: {
              "_token"  : '{{ csrf_token() }}',
              "kd_unit" : kd_unit,
              "kd_lokasi" : kd_lokasi,
              "kd_kawasan" : kd_kawasan,
              "kd_cluster" : kd_cluster
            },
            success: function(msg){

                row = '<select class="form-control col-sm-12" name="tahap_bangun" id="tahap_bangun" onchange="data_blok_no()">';
                $.each(msg, function(x, y) {
                  row += '<option value="'+ y.tahap_bangun +'">'+ y.tahap_bangun +'</option>';
                });
                row += '</select>';

                $('#div_tahap').html(row);
                
                data_blok_no();
            },
            error: function(){
                $('#v_loading').hide();
                alert('Proses Get Cluster Gagal');
            }
        });
        
        return false;
    }

    function data_cluster_add() {
        var kd_kawasan_add = $('#kd_kawasan_add').val();
        var row;
        
        $.ajax({
            type: 'POST',
            url: 'register_data_stok_c/data_cluster',
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
            url: 'register_data_stok_c/tipe_rumah',
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
          url: "register_data_stok_c/delete_dt",
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
      var data_all      = $('#InputForm').serializeArray();
      var kd_kawasan    = $('#kd_kawasan').val();
      var kd_cluster    = $('#kd_cluster').val();

        $.ajax({
          data: data_all,
          url: "register_data_stok_c/save",
          type: "POST",

          success: function (data) {

              $('#InputForm').trigger("reset");
              add_dt();
              //table.draw();

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
      url   : 'register_data_stok_c/pop_tipe?page='+sys_search_unit_page,
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
      url   : 'register_data_stok_c/search_blok_no?page='+search_blk_no_page,
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

    function data_cluster_tp_rmh() {
        var kd_kawasan_tp_rmh = $('#kd_kawasan_tp_rmh').val();
        var row;
        
        $.ajax({
            type: 'POST',
            url: 'register_data_stok_c/data_cluster',
            cache: false,
            data: {
              "_token"  : '{{ csrf_token() }}',
              "kd_unit" : kd_unit,
              "kd_lokasi" : kd_lokasi,
              "kd_kawasan" : kd_kawasan_tp_rmh                
            },
            success: function(msg){

                row = '<select class="form-control col-sm-12" name="kd_cluster_tp_rmh" id="kd_cluster_tp_rmh" >';
                $.each(msg, function(x, y) {
                  row += '<option value="'+ y.kd_cluster +'">'+ y.nm_cluster +'</option>';
                });
                row += '</select>';

                $('#div_cluster_tp_rmh').html(row);
                
                //data_blok_no();
            },
            error: function(){
                $('#v_loading').hide();
                alert('Proses Get Cluster Gagal');
            }
        });
        
        return false;
    }

    function save_tipe_rumah() {
      var data_all      = $('#InputFormTpRumah').serializeArray();
      var kd_kawasan    = $('#kd_kawasan_tp_rmh').val();
      var kd_cluster    = $('#kd_cluster_tp_rmh').val();

        $.ajax({
          data: data_all,
          url: "register_data_stok_c/save_tipe_rumah",
          type: "POST",

          success: function (data) {

              $('#InputFormTpRumah').trigger("reset");
              add_dt();
              //table.draw();

          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }
      });

        return false;
    }

    function save_process_upload(dataall) {
      // $('#kd_jenis_l').val(kd_jenis_l);
      // $('#kd_tipe_l').val(kd_tipe_l);
      // $('#kd_kawasan_l').val(kd_kawasan_l);

      let formData      = new FormData($('#UploadFormStok')[0]);

      $.ajaxSetup({
          headers: {
              "X-CSRF-TOKEN": '{{ csrf_token() }}'
          }
      });
      
      $.ajax({
        data: formData,
        url: "register_data_stok_c/save_process_upload",
        type: "POST",
        cache:false,
        dataType: false,
        processData: false,
        contentType: false,
        success: function (data) {
          alert(data);
          $('#ajaxModel').modal('hide');
          $('#UploadFormStok').trigger("reset");
          // data_denah(kd_kawasan_l,kd_jenis_l,kd_tipe_l);
        },
        error: function (data) {
            console.log('Error:', data);
            $('#saveBtnStaff').html('Save Changes');
        }
      });
        return false;
    }

    function save_process_upload_tp_rmh(){
      let formData      = new FormData($('#UploadFormTpRmh')[0]);

      $.ajaxSetup({
          headers: {
              "X-CSRF-TOKEN": '{{ csrf_token() }}'
          }
      });
      
      $.ajax({
        data: formData,
        url: "register_data_stok_c/save_process_upload_tp_rmh",
        type: "POST",
        cache:false,
        dataType: false,
        processData: false,
        contentType: false,
        success: function (data) {
          alert(data);
          $('#ajaxModel').modal('hide');
          $('#UploadFormStok').trigger("reset");
          // data_denah(kd_kawasan_l,kd_jenis_l,kd_tipe_l);
        },
        error: function (data) {
            console.log('Error:', data);
            $('#saveBtnStaff').html('Save Changes');
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
                <h3 class="card-title">Register Data Stok (Blok/No) & Input Data Bangunan Non SRIS</h3>
              </a>
              <a class="navbar-inline">
                <!-- {!! $dt['button'] !!} -->
                <button type="button" class="btn btn-default btn-flat" onclick="sync_dt()">
                  <i class="fas fa-sync"></i>
                </button>
                <button type="button" class="btn btn-default btn-flat" onclick="search_dt()">
                  <i class="fas fa-search"></i>
                </button>
              </a>
            </nav>
          </div>
          <div class="card-body">
            
            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Data Blok/No</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Input Stok</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="custom-content-below-messages-tab" data-toggle="pill" href="#custom-content-below-messages" role="tab" aria-controls="custom-content-below-messages" aria-selected="false">Upload Stok</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#content_tipe_rumah" role="tab" aria-controls="content_tipe_rumah" aria-selected="false">Input Tipe Rumah</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="custom-content-below-messages-tab" data-toggle="pill" href="#content_tipe_rumah_upload" role="tab" aria-controls="content_tipe_rumah_upload" aria-selected="false">Upload Tipe rumah</a>
              </li>
            </ul>
            <div class="tab-content" id="custom-content-below-tabContent">
              <div class="tab-pane fade active show" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
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
                  <div class="form-group">
                    <label for="tahap_bangun" class="col-sm-12">Tahap Bangun</label>
                    <div id="div_tahap" class="col-sm-12"></div>
                  </div>
                  <thead >                  
                    <tr >
                      <th style="width: 10px">#</th>
                      <th style="width: 130px; text-align: center;">Blok</th>
                      <th style="text-align: center;">Nomor</th>
                      <th style="text-align: center;">Tipe</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
              <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                <div class="form-group">
                  <label></label>
                </div>
                <form id="InputForm" name="InputForm" class="form-horizontal" autocomplete="off" onsubmit="return save_process()">
                   <input type="hidden" name="saveBtnVal" id="saveBtnVal" value="create">
                   <!-- <input type="hidden" name="kd_item_defect" id="kd_item_defect" value=""> -->
                    <div class="form-group">
                        <label for="kd_kawasan_add" class="col-sm-2 control-label">Kawasan</label>
                        <div class="col-sm-12"> {!! $dt['kd_kawasan_add'] !!} </div>
                        <label for="kd_cluster" class="col-sm-2 control-label">Cluster</label>
                        <div class="col-sm-12" id="div_cluster_add"></div>
                        <label for="kd_jenis" class="col-sm-2 control-label">Jenis</label>
                        <div class="col-sm-12"> {!! $dt['kd_jenis_add'] !!} </div>
                        <label for="kd_tipe" class="col-sm-2 control-label">Tipe</label>
                        <div class="col-sm-12">
                          <div class="row">
                            <div class="col-11">
                              <input type="hidden" class="form-control" id="kd_tipe_add" name="kd_tipe_add" value="" maxlength="50" readonly="readonly">
                              <input type="text" class="form-control" id="nm_tipe_add" name="nm_tipe_add" placeholder="" value="" maxlength="50" readonly="readonly">
                            </div>
                            <div class="col">
                              <button type="button" class="btn btn-default btn-flat" >
                                <i class="fas fa-sync"></i>
                              </button>
                              <button type="button" class="btn btn-default btn-flat" onclick="pop_tipe()">
                                <i class="fas fa-search"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                        <label for="blok" class="col-sm-2 control-label">Blok</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="blok" name="blok" placeholder="Enter Blok" value="" maxlength="50" >
                        </div>
                        <label for="nomor" class="col-sm-2 control-label">Nomor</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="nomor" name="nomor" placeholder="Enter Nomor" value="" maxlength="50" >
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                      <input type="hidden" class="form-control" id="stok_id" name="stok_id" value="" readonly="readonly">
                      <button type="button" class="btn btn-primary" id="addBtn" value="add" >Add</button>
                      <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save</button>
                      <button type="button" class="btn btn-primary" id="SearchBtn" value="search" onclick="search_blok_no();">Search</button>
                    </div>
                    @csrf
                </form>
              </div>
              <div class="tab-pane fade" id="custom-content-below-messages" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
                <div class="form-group">
                  <label></label>
                </div>
                 <form id="UploadFormStok" name="UploadForm" class="form-horizontal" autocomplete="off" enctype="multipart/form-data" onsubmit="return save_process_upload(this)">
                   <input type="hidden" name="UploadBtnVal" id="UploadBtnVal" value="create">
                    <div class="form-group">
                        <label  class="col-sm-6 control-label">Keterangan :</label>
                        <div class="col-sm-12">
                            <font color="red"><i>Sebelum Upload File Stok Non Marketing. Harus download template excel Stok Non Marketing dahulu. Isilah sesuai data yg sebenarnya. Lalu mulai Upload</i></font>
                        </div>
                        <label for="kd_kawasan" class="col-sm-6 control-label">Template Excel Stok Non Kartu Rumah</label>
                        <div class="col-sm-12">
                          <a href="http://172.16.0.6/sqii/ext-lib/res/STOK_NON.xls" target="_blank" class="btn btn-primary">Download Stok Non Marketing</a>
                        </div>
                        <label for="kd_cluster" class="col-sm-6 control-label">Pilih File Stok Non Marketing</label>
                        <div class="col-sm-12">
                          <input type="file" class="form-control-file" id="berkas2" name="berkas2">
                        </div>
                    </div>

                    <div class="col-sm-offset-6 col-sm-10">
                     <button type="submit" class="btn btn-primary" id="UploadBtn" value="create">Upload</button>
                    </div>
                    @csrf
                </form>
              </div>
              <div class="tab-pane fade" id="content_tipe_rumah" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                <div class="form-group">
                  <label></label>
                </div>
                <form id="InputFormTpRumah" name="InputFormTpRumah" class="form-horizontal" autocomplete="off" onsubmit="return save_tipe_rumah()">
                   <input type="hidden" name="saveBtnTpRmhVal" id="saveBtnTpRmhVal" value="create">
                   <!-- <input type="hidden" name="kd_item_defect" id="kd_item_defect" value=""> -->
                    <div class="form-group">
                        <label for="kd_kawasan_add" class="col-sm-2 control-label">Kawasan</label>
                        <div class="col-sm-12"> {!! $dt['kd_kawasan_tp_rmh'] !!} </div>
                        <label for="kd_cluster" class="col-sm-2 control-label">Cluster</label>
                        <div class="col-sm-12" id="div_cluster_tp_rmh"></div>
                        <label for="kd_jenis" class="col-sm-2 control-label">Jenis</label>
                        <div class="col-sm-12"> {!! $dt['kd_jenis_tp_rmh'] !!} </div>
                        <label for="kd_tipe" class="col-sm-2 control-label">Kode Tipe</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="kd_tipe" name="kd_tipe" placeholder="Kode Tipe" value="" maxlength="50" >
                        </div>
                        <label for="nm_tipe" class="col-sm-2 control-label">Nama Tipe</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="nm_tipe" name="nm_tipe" placeholder="Nama Tipe" value="" maxlength="50" >
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                      <input type="hidden" class="form-control" id="stok_id" name="stok_id" value="" readonly="readonly">
                      <button type="button" class="btn btn-primary" id="addBtnTpRmh" value="add" >Add</button>
                      <button type="submit" class="btn btn-primary" id="saveBtnTpRmh" value="create">Save</button>
                      <button type="button" class="btn btn-primary" id="SearchBtn" value="search" onclick="search_blok_no();">Search</button>
                    </div>
                    @csrf
                </form>
              </div>
              <div class="tab-pane fade" id="content_tipe_rumah_upload" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
                <div class="form-group">
                  <label></label>
                </div>
                 <form id="UploadFormTpRmh" name="UploadFormTpRmh" class="form-horizontal" autocomplete="off" enctype="multipart/form-data" onsubmit="return save_process_upload_tp_rmh()">
                   <input type="hidden" name="UploadBtnValTpRmh" id="UploadBtnValTpRmh" value="create">
                    <div class="form-group">
                        <label  class="col-sm-6 control-label">Keterangan :</label>
                        <div class="col-sm-12">
                            <font color="red"><i>Sebelum Upload File Tipe Rumah Non Marketing. Harus download template excel Tipe Rumah Non Marketing dahulu. Isilah sesuai data yg sebenarnya. Lalu mulai Upload</i></font>
                        </div>
                        <label for="kd_kawasan" class="col-sm-6 control-label">Template Excel Stok Non Kartu Rumah</label>
                        <div class="col-sm-12">
                          <a href="http://172.16.0.6/sqii/ext-lib/res/TIPE_RUMAH.xls" target="_blank" class="btn btn-primary">Download Tipe Rumah</a>
                        </div>
                        <label for="kd_cluster" class="col-sm-6 control-label">Pilih File Stok Non Marketing</label>
                        <div class="col-sm-12">
                            <input type="file" class="form-control-file" id="berkas" name="berkas">
                        </div>
                    </div>

                    <div class="col-sm-offset-6 col-sm-10">
                     <button type="submit" class="btn btn-primary" id="UploadBtn" value="create">Upload</button>
                    </div>
                    @csrf
                </form>
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
@endsection