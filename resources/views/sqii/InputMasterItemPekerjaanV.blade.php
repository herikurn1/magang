@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit 		= "{{ session('kd_unit') }}";
		var kd_lokasi 	= "{{ session('kd_lokasi') }}";		

    $(function() {
      // data_blok_no();
    });

    function data_blok_no() {
        var jns_pekerjan = $('#jns_pekerjan').val();
        var kd_tahapan = $('#kd_tahapan').val();

      $.ajax({
        type  : 'POST',
        url   : 'input_dt_mst_item_p15_p35_c/data_blok_no',
        data  : {
          "kd_unit"   : kd_unit,
          "kd_lokasi" : kd_lokasi,
          "jns_pekerjan" : jns_pekerjan,
          "kd_tahapan" : kd_tahapan,
          "_token"    : '{{ csrf_token() }}'
        },
        success : function(msg) {          
          var row;
          var no=1;
          //$('#tbl_kawasan tbody').empty();

          $("#tbl_kawasan").find("tr:gt(0)").remove(); // CLEAR TABLE
          $.each(msg, function(x, y) {
            if( y.flag_header == 'H'){
              style    = 'style="background-color:#ebebeb";';
              urut     = y.urut_header;
            }else{style =''; urut = y.urut_detail;}

            row += ''+
              '<tr '+style+' >'+
                '<td>'+ no++ +'</td>'+
                '<td>'+y.urut_header+'.'+y.urut_detail+'</td>'+
                '<td>'+y.jenis_pekerjaan+'</td>'+
                '<td>'+y.kd_item_pekerjaan+'</td>'+
                '<td>'+y.flag_header+'</td>'+
                '<td>'+y.nm_pekerjaan+'</td>'+
                '<td style="text-align: center;">'+
                  '<button type="button" class="btn btn-info btn-sm" onclick="edit_data(\''+y.kd_item_pekerjaan+'\',\''+y.nm_pekerjaan+'\',\''+y.flag_header+'\',\''+y.parent_id+'\',\''+urut+'\')">Edit</button>&nbsp;&nbsp;'+
                  '<button type="button" class="btn btn-info btn-sm" onclick="delete_dt2(\''+y.kd_item_pekerjaan+'\')">Hapus</button></td>'+
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

    function save_dt() {
      $('#saveBtn').click();
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
          url: "input_dt_mst_item_p15_p35_c/delete_dt",
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
      var kd_tahapan    = $('#kd_tahapan').val();
      var jns_pekerjan    = $('#jns_pekerjan').val();
      $('#kd_tahapan_bwh').val(kd_tahapan);
      $('#jns_pekerjan_bwh').val(jns_pekerjan);
      var data_all      = $('#BawahanForm').serializeArray();

        $.ajax({
          data: data_all,
          url: "input_dt_mst_item_p15_p35_c/save",
          type: "POST",

          success: function (data) {

              $('#BawahanForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              data_blok_no();
          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtnStaff').html('Save Changes');
              $('#ajaxModel').modal('hide');
          }
      });
        return false;
    }

    function delete_dt2(kd_item_pekerjaan) {
      var ans = confirm('Yakin data '+kd_item_pekerjaan+' akan dihapus?');
      
      if(ans){
          $.ajax({
            data  : {
              "kd_item_pekerjaan" : kd_item_pekerjaan,
              "_token"          : '{{ csrf_token() }}'
            },
            url: "input_dt_mst_item_p15_p35_c/delete_item_pekerjaan",
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

  function edit_data(kd_item_pekerjaan,nm_pekerjaan,flag_header,parent_id,urut) {
    item_header_detail(parent_id,pertama=0);

    $('#tipe_h_d').val(flag_header);
    $('#id_detail').val(nm_pekerjaan);
    $('#kd_item_pekerjaan').val(kd_item_pekerjaan);
    $('#id_urut').val(urut);

    $('#saveBtnStaffVal').val("edit");
    $('#CustomerForm').trigger("reset");
    $('#modelHeading').html("Edit Item Pekerjaan");
    $('#ajaxModel').modal('show');   

    return false;
  } 

  function add_staff() {
    var kd_item_pekerjaana = '';
    item_header_detail(kd_item_pekerjaana,pertama=1);
    $('#saveBtnStaffVal').val("create");
    $('#CustomerForm').trigger("reset");
    $('#modelHeading').html("Tambah Item Pekerjaan");
    $('#ajaxModel').modal('show');   

    return false;
  } 

  function item_header_detail(kd_item_pekerjaana,pertama) {
    var jns_pekerjan = $('#jns_pekerjan').val();
    var kd_tahapan = $('#kd_tahapan').val();
    var tipe_h_d    = 'H';
    var row;
    var selected    = '';
    var disabled ;

    if(kd_item_pekerjaana == 'null' || pertama == 1){
       disabled    = 'disabled';
    }else{
      disabled    = '';
    }
      
      $.ajax({
          type: 'POST',
          url: 'input_dt_mst_item_p15_p35_c/item_header_detail',
          cache: false,
          data: {
            "_token"  : '{{ csrf_token() }}',
            "kd_unit" : kd_unit,
            "kd_lokasi" : kd_lokasi,
            "jns_pekerjan" : jns_pekerjan,
            "tipe_h_d" : tipe_h_d,
            "kd_tahapan" : kd_tahapan,
            "kd_tahapan" : kd_tahapan,
            "kd_tahapan" : kd_tahapan
          },
          success: function(msg){

              row = '<select class="form-control col-sm-12" name="id_header" id="id_header"  '+ disabled +' >';
              $.each(msg, function(x, y) {
                if( y.kd_item_pekerjaan == kd_item_pekerjaana){
                  selected    = 'selected';
                }

                row += '<option value="'+ y.kd_item_pekerjaan +'#'+ y.parent_id +'#'+ y.kd_tahap +'#'+ y.jenis_pekerjaan +'#'+ y.flag_header +'#'+ y.nm_pekerjaan +'" '+ selected +' >'+ y.nm_pekerjaan +'</option>';

                selected    = '';
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
</script>
@endsection

@section('content')
<div class="row">
  <div class="col-sm-12">
  <div class="card card-primary card-outline">
          <div class="card-header">
            <nav class="navbar justify-content-between">
              <a class="form-brand">
                <h3 class="card-title">Input Data Master Item Pekerjaan Pertahap</h3>
              </a>
              <a class="navbar-inline">
                <button type="button" class="btn btn-default btn-flat" onclick="data_blok_no();">
                  <i class="fas fa-search"></i>
                </button>
              </a>
            </nav>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label for="kd_tahapan">Tahapan Pekerjaan</label>
              <div id="div_tahapan"></div>{!! $dt['kd_tahapan'] !!}
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
                      <th style="width: 10px">Urut</th>
                      <th style="width: 130px; text-align: center;">Jenis Pekerjaan</th>
                      <th style="text-align: center;">Kd Item Pekerjaan</th>
                      <th style="text-align: center;">Flag Header</th>
                      <th style="text-align: center;">Item Pekerjaan</th>
                      <th style="text-align: center;">Action</th>
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
                   <input type="hidden" id="kd_tahapan_bwh" name="kd_tahapan_bwh" value="" >
                   <input type="hidden" id="jns_pekerjan_bwh" name="jns_pekerjan_bwh" value="" >
                   <input type="hidden" id="kd_item_pekerjaan" name="kd_item_pekerjaan" value="" >
                    <div class="form-group">
                        <div class="col-sm-12">
                          <div class="row">
                            <div class="col-2">
                              <label for="stok_add" class="col-sm-12 control-label">Header/Detail</label>
                              <select class="form-control col-sm-12" name="tipe_h_d" id="tipe_h_d" onchange="set_header();">;
                                <option value="H">Header</option>
                                <option value="D">Detail</option>
                              </select>
                            </div>
                            <div class="col-4">
                              <label for="stok_add" class="col-sm-12 control-label">Header</label>
                              <div class="" id="div_available_stok"></div>
                            </div>
                            <div class="col-4">
                              <label for="id_detail" class="col-sm-12 control-label">Item Pekerjaan</label>
                              <input type="text" class="form-control" id="id_detail" name="id_detail" value="" maxlength="50" >
                            </div>
                            <div class="col-2">
                              <label for="id_urut" class="col-sm-12 control-label">Urut</label>
                              <input type="number" class="form-control form-control-sm rounded-0" min="1" name="id_urut" id="id_urut" style="text-align: center;" value="1">
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