@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit 		= "{{ session('kd_unit') }}";
		var kd_lokasi 	= "{{ session('kd_lokasi') }}";		

    $(function() {
      //show_jenis_bangunan();
      data_cluster();
      data_tahapan()
    });

    function data_blok_no() {
        var kd_kawasan = $('#kd_kawasan').val();
        var kd_cluster = $('#kd_cluster').val();
        var jns_pekerjan = $('#jns_pekerjan').val();
        var kd_tahapan = $('#kd_tahapan').val();

      $.ajax({
        type  : 'POST',
        url   : 'input_dt_item_p15_p35_c/data_blok_no',
        data  : {
          "kd_unit"   : kd_unit,
          "kd_lokasi" : kd_lokasi,
          "kd_kawasan" : kd_kawasan,
          "kd_cluster" : kd_cluster,
          "jns_pekerjan" : jns_pekerjan,
          "kd_tahapan" : kd_tahapan,
          "_token"    : '{{ csrf_token() }}'
        },
        success : function(msg) {          
          var row;
          var no=1;
          var row_bobot; 
          var bobot_dtl=0;
          //$('#tbl_kawasan tbody').empty();

          $("#tbl_kawasan").find("tr:gt(0)").remove(); // CLEAR TABLE
          $.each(msg, function(x, y) {

            if(no == 1){
              row_bobot = y.total_bobot;
            }

            if( y.flag_header == 'H'){
              style    = 'style="background-color:#ebebeb";';
              bobot    = '<input type="number" class="form-control form-control-sm rounded-0" min="1" name="bobot_'+y.kd_item_pekerjaan+'" id="bobot_'+y.kd_item_pekerjaan+'" style="text-align: center;" value="'+y.bobot+'">';
              upd    = '<button type="button" class="btn btn-info btn-sm" onclick="update_dt2(\''+y.kd_item_pekerjaan+'\',\''+y.kd_kawasan+'\',\''+y.kd_cluster+'\',\''+y.jenis_pekerjaan+'\',\''+y.kd_tahapan+'\')">Upd Bobot</button>';
            }else{style = bobot = upd = ''; bobot_dtl = bobot_dtl + parseFloat(y.bobot)}

            row += ''+
              '<tr '+style+'>'+
                '<td>'+ no++ +'</td>'+
                '<td>'+y.jenis_pekerjaan+'</td>'+
                '<td>'+y.kd_item_pekerjaan+'</td>'+
                '<td>'+y.flag_header+'</td>'+
                '<td>'+y.nm_pekerjaan+'</td>'+
                '<td>'+bobot+'</td>'+
                '<td style="width: 110px;text-align: center;">'+upd+'</td>'+
                '<td style="width: 70px;text-align: center;">'+
                  '<button type="button" class="btn btn-info btn-sm" onclick="delete_dt2(\''+y.kd_item_pekerjaan+'\',\''+y.kd_kawasan+'\',\''+y.kd_cluster+'\')">Hapus</button></td>'+
              '</tr>'+
            '';
          });

              row += ''+
                '<tr>'+
                  '<td></td>'+
                  '<td></td>'+
                  '<td></td>'+
                  '<td></td>'+
                  '<td></td>'+
                  '<td>'+bobot_dtl.toFixed(2)+' dari '+row_bobot+'</td>'+
                  '<td style="width: 110px;text-align: center;"></td>'+
                  '<td style="width: 70px;text-align: center;"></td>'+
                '</tr>'+
              '';          
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
        url   : 'input_dt_item_p15_p35_c/sync_dt',
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
            url: 'input_dt_item_p15_p35_c/data_cluster',
            cache: false,
            data: {
              "_token"  : '{{ csrf_token() }}',
              "kd_unit" : kd_unit,
              "kd_lokasi" : kd_lokasi,
              "kd_kawasan" : kd_kawasan                
            },
            success: function(msg){

                row = '<select class="form-control col-sm-12" name="kd_cluster" id="kd_cluster" onchange="data_tahapan()">';
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
        return false;
    }

    function data_tahapan() {
        var kd_kawasan = $('#kd_kawasan').val();
        var kd_cluster = $('#kd_cluster').val();
        var row;
        
        $.ajax({
            type: 'POST',
            url: 'input_dt_item_p15_p35_c/data_tahapan',
            cache: false,
            data: {
              "_token"  : '{{ csrf_token() }}',
              "kd_unit" : kd_unit,
              "kd_lokasi" : kd_lokasi,
              "kd_kawasan" : kd_kawasan,
              "kd_cluster" : kd_cluster                
            },
            success: function(msg){

                row = '<select class="form-control col-sm-12" name="kd_tahapan" id="kd_tahapan" onchange="data_blok_no()">';
                $.each(msg, function(x, y) {
                  row += '<option value="'+ y.kd_tahap +'">'+y.kd_tahap+' # '+ y.nm_tahap +'</option>';
                });
                row += '</select>';

                $('#div_tahapan').html(row);
                
                data_blok_no();
            },
            error: function(){
                $('#v_loading').hide();
                alert('Proses Get Tahapan Gagal');
            }
        });
        return false;
    }

    function add_dt() {
        $('#saveBtnVal').val("create");
        $('#kd_lantai').val('');
        $('#CustomerForm').trigger("reset");
        $('#modelHeading').html("Tambah Item Defect");
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

    function delete_dt(kd_kawasan,kd_cluster,blok,nomor) {
        $.ajax({
          data  : {
            "kd_kawasan"    : kd_kawasan,
            "kd_cluster"    : kd_cluster,
            "blok"          : blok,
            "nomor"         : nomor,
            "_token"        : '{{ csrf_token() }}'
          },
          url: "input_dt_item_p15_p35_c/delete_dt",
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

    function save_process2() {
      var kd_kawasan    = $('#kd_kawasan').val();
      var kd_cluster    = $('#kd_cluster').val();
      $('#kd_kawasan_bwh').val(kd_kawasan);
      $('#kd_cluster_bwh').val(kd_cluster);
      var data_all      = $('#BawahanForm').serializeArray();

        $.ajax({
          data: data_all,
          url: "input_dt_item_p15_p35_c/save",
          type: "POST",

          success: function (data) {

              $('#BawahanForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              data_blok_no();
              //table.draw();
          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtnStaff').html('Save Changes');
              $('#ajaxModel').modal('hide');
          }
      });
        return false;
    }

    function delete_dt2(kd_item_pekerjaan,kd_kawasan,kd_cluster) {
      var ans = confirm('Yakin data '+kd_item_pekerjaan+' akan dihapus?');
      
      if(ans){
          $.ajax({
            data  : {
              "kd_item_pekerjaan" : kd_item_pekerjaan,
              "kd_kawasan" : kd_kawasan,
              "kd_cluster" : kd_cluster,
              "_token"          : '{{ csrf_token() }}'
            },
            url: "input_dt_item_p15_p35_c/delete_item_pekerjaan",
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
  } 

  function add_staff() {
    item_header_detail();
    item_detail();
    $('#saveBtnVal').val("create");
    $('#kd_kawasan_add').val('');
    $('#kd_cluster_add').val('');
    $('#kd_jenis_add').val('');
    $('#kd_tipe_add').val('');
    $('#CustomerForm').trigger("reset");
    $('#modelHeading').html("Item Pekerjaan");
    $('#ajaxModel').modal('show');   

    return false;
  } 

  function item_header_detail() {
    var jns_pekerjan = $('#jns_pekerjan').val();
    var kd_tahapan = $('#kd_tahapan').val();
    var tipe_h_d    = 'H';//$('#tipe_h_d').val();
    var row;
    var disabled    = '';
      
      $.ajax({
          type: 'POST',
          url: 'input_dt_item_p15_p35_c/item_header_detail',
          cache: false,
          data: {
            "_token"  : '{{ csrf_token() }}',
            "kd_unit" : kd_unit,
            "kd_lokasi" : kd_lokasi,
            "jns_pekerjan" : jns_pekerjan,
            "tipe_h_d" : tipe_h_d,
            "kd_tahapan" : kd_tahapan
          },
          success: function(msg){

              row = '<select class="form-control col-sm-12" name="id_header" id="id_header" '+ disabled +' onchange="item_detail()">';
              $.each(msg, function(x, y) {
                row += '<option value="'+ y.kd_item_pekerjaan +'#'+ y.parent_id +'#'+ y.kd_tahap +'#'+ y.jenis_pekerjaan +'#'+ y.flag_header +'#'+ y.nm_pekerjaan +'#'+ y.urut_header +'#'+ y.urut_detail +'">'+ y.nm_pekerjaan +'</option>';
              });
              row += '</select>';

              $('#div_available_stok').html(row);
              item_detail();
              
          },
          error: function(){
              $('#v_loading').hide();
              alert('Proses Get Cluster Gagal');
          }
      });
      return false;
  }

  function item_detail() {
    var jns_pekerjan = $('#jns_pekerjan').val();
    var kd_tahapan = $('#kd_tahapan').val();
    var tipe_h_d    = $('#tipe_h_d').val();
    var id_header    = $('#id_header').val();
    var row;
    var disabled    = '';

      $.ajax({
          type: 'POST',
          url: 'input_dt_item_p15_p35_c/item_header_detail',
          cache: false,
          data: {
            "_token"  : '{{ csrf_token() }}',
            "kd_unit" : kd_unit,
            "kd_lokasi" : kd_lokasi,
            "jns_pekerjan" : jns_pekerjan,
            "tipe_h_d" : tipe_h_d,
            "kd_tahapan" : kd_tahapan,
            "id_header" : id_header
          },
          success: function(msg){

              if(tipe_h_d == 'H'){
                disabled = 'disabled';
              }else{
                //
              } 

              row = '<select class="form-control col-sm-12" name="id_detail" id="id_detail"  '+ disabled +'>';
              $.each(msg, function(x, y) {
                row += '<option value="'+ y.kd_item_pekerjaan +'#'+ y.parent_id +'#'+ y.kd_tahap +'#'+ y.jenis_pekerjaan +'#'+ y.flag_header +'#'+ y.nm_pekerjaan +'#'+ y.urut_header +'#'+ y.urut_detail +'">'+ y.nm_pekerjaan +'</option>';
              });
              row += '</select>';

              $('#div_item_detail').html(row);
              
          },
          error: function(){
              $('#v_loading').hide();
              alert('Proses Get Cluster Gagal');
          }
      });

      //set_header();
      
      return false;
  }        

  function set_header() {
    var tipe_h_d = $('#tipe_h_d').val();

    if(tipe_h_d == 'H'){
      $("#id_header").attr('disabled','disabled')
      .siblings().removeAttr('disabled');
    }else{
      $("#id_header").removeAttr('disabled');
    }

    return false;
  }

  function update_dt2(kd_item_pekerjaan,kd_kawasan,kd_cluster,jns_pekerjan,kd_tahapan) {
    
    var bobot = $('#bobot_'+kd_item_pekerjaan).val();
    
      $.ajax({
          type: 'POST',
          url: 'input_dt_item_p15_p35_c/cek_total_bobot',
          cache: false,
          data: {
            "_token"  : '{{ csrf_token() }}',
            "kd_kawasan"        : kd_kawasan,
            "kd_cluster"        : kd_cluster,
            "bobot"             : bobot,
            "kd_item_pekerjaan" : kd_item_pekerjaan
          },
          success: function(msg){

            $.each(msg, function(x, y) {
                total_bobot = y.cek_total_bobot;
            });

            if(parseFloat(total_bobot) > parseFloat(0.00)){
              alert('Total bobot melebihi 100%, sebesar '+parseFloat(total_bobot)+'%.');
              return false;
            }else{
              var ans = confirm('Yakin data '+kd_item_pekerjaan+' akan diupdate?');
              if(ans){
                $.ajax({
                  data  : {
                    "bobot" : bobot,
                    "kd_item_pekerjaan" : kd_item_pekerjaan,
                    "kd_kawasan" : kd_kawasan,
                    "kd_cluster" : kd_cluster,
                    "_token"          : '{{ csrf_token() }}'
                  },
                  url: "input_dt_item_p15_p35_c/update_bobot",
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
            }          
          },
          error: function(){
              $('#v_loading').hide();
              alert('Proses Get Cluster Gagal');
          }
      });
    
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
                <h3 class="card-title">Input Data Item Pekerjaan Percluster</h3>
              </a>
              <a class="navbar-inline">
                <button type="button" class="btn btn-default btn-flat" onclick="data_blok_no()">
                  <i class="fas fa-search"></i>
                </button>
              </a>
            </nav>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label for="kd_kawasan">Kawasan</label>
              {!! $dt['kd_kawasan'] !!}
            </div>
            <div class="form-group">
              <label for="kd_cluser">Cluster</label>
              <div id="div_cluster"></div>
            </div>
            <div class="form-group">
              <label for="kd_tahapan">Tahapan Pekerjaan</label>
              <div id="div_tahapan"></div>
            </div> 
            <div class="form-group">
              <label for="jns_pekerjan">Jenis Pekerjaan</label>
              <select class="form-control col-sm-12" name="jns_pekerjan" id="jns_pekerjan" onchange="data_blok_no()">;
                <option value="E">P15</option>
                <option value="U">P35</option>
              </select>
            </div>
            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Item Pekerjaan</a>
              </li>
            </ul>
            <div class="tab-content" id="custom-content-below-tabContent">
              <div class="tab-pane fade active show" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                <table id="tbl_kawasan" class="table table-bordered table-hover">
                  <div class="form-group">
                    <label class="col-sm-12"></label>
                    <div class="col-sm-12"><button type="button" class="btn btn-info btn-sm" onclick="add_staff()">Tambah</button></div>
                  </div>  
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th style="width: 130px; text-align: center;">Jenis Pekerjaan</th>
                      <th style="text-align: center;" class="text-nowrap">Kd Item Pekerjaan</th>
                      <th style="text-align: center;" class="text-nowrap">Flag Header</th>
                      <th style="text-align: center;" class="text-nowrap">Item Pekerjaan</th>
                      <th style="width: 100px; text-align: center;">Bobot</th>
                      <th style="text-align: center;" colspan="2">Action</th>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="BawahanForm" name="BawahanForm" class="form-horizontal" autocomplete="off" onsubmit="return save_process2()">
                   <input type="hidden" name="saveBtnStaffVal" id="saveBtnStaffVal" value="create">
                   <input type="hidden" name="kd_kawasan_bwh" id="kd_kawasan_bwh" value="">
                   <input type="hidden" name="kd_cluster_bwh" id="kd_cluster_bwh" value="">
                    <div class="form-group">
                        <div class="col-sm-12">
                          <div class="row">
                            <div class="col-2"><!-- onchange="set_jabatan_bawahan()" -->
                              <label for="stok_add" class="col-sm-12 control-label">Header/Detail</label>
                              <select class="form-control col-sm-12" name="tipe_h_d" id="tipe_h_d" onchange="item_header_detail();">;
                                <option value="H">Header</option>
                                <option value="D">Detail</option>
                              </select>
                            </div>
                            <div class="col-5">
                              <label for="stok_add" class="col-sm-12 control-label">Header</label>
                              <div class="" id="div_available_stok"></div>
                            </div>
                            <div class="col-5">
                              <label for="stok_add" class="col-sm-12 control-label">Item Pekerjaan</label>
                              <div class="" id="div_item_detail"></div>
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