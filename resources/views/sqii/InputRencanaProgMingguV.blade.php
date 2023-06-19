@extends('layouts.template')

@section('js')
  <script type="text/javascript">
    var kd_unit     = "{{ session('kd_unit') }}";
    var kd_lokasi   = "{{ session('kd_lokasi') }}";   

    $(function() {

      $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true
      });      
      data_cluster();
      data_tahapan()
    });

    function data_blok_no() {
        var kd_kawasan = $('#kd_kawasan').val();
        var kd_cluster = $('#kd_cluster').val();
        var kd_tahapan = $('#kd_tahapan').val();

      $.ajax({
        type  : 'POST',
        url   : 'input_rencana_progress_mingguan_c/data_blok_no',
        data  : {
          "kd_unit"   : kd_unit,
          "kd_lokasi" : kd_lokasi,
          "kd_kawasan" : kd_kawasan,
          "kd_cluster" : kd_cluster,
          "kd_tahapan" : kd_tahapan,
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
                '<td>'+y.nm_periode+'</td>'+
                '<td>'+y.tgl_awal+'</td>'+
                '<td>'+y.tgl_akhir+'</td>'+
                '<td>'+y.progres+'</td>'+
                '<td style="width: 70px;text-align: center;">'+
                  '<button type="button" class="btn btn-info btn-sm" onclick="delete_dt2(\''+y.kd_tahap+'\',\''+y.kd_kawasan+'\',\''+y.kd_cluster+'\',\''+y.kd_periode+'\',\''+y.nm_periode+'\')">Hapus</button></td>'+
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

    function data_cluster() {
        var kd_kawasan = $('#kd_kawasan').val();
        var row;
        
        $.ajax({
            type: 'POST',
            url: 'input_rencana_progress_mingguan_c/data_cluster',
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
            url: 'input_rencana_progress_mingguan_c/data_tahapan',
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
                  row += '<option value="'+ y.kd_tahap +'">'+ y.nm_tahap +'</option>';
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
          url: "input_rencana_progress_mingguan_c/delete_dt",
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
      var kd_tahap      = $('#kd_tahapan').val();
      $('#kd_kawasan_bwh').val(kd_kawasan);
      $('#kd_cluster_bwh').val(kd_cluster);
      $('#kd_tahap_bwh').val(kd_tahap);
      var data_all      = $('#BawahanForm').serializeArray();

        $.ajax({
          data: data_all,
          url: "input_rencana_progress_mingguan_c/save",
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

    function delete_dt2(kd_tahap,kd_kawasan,kd_cluster,kd_periode,nm_periode) {
      var ans = confirm('Yakin data '+nm_periode+' akan dihapus?');
      
      if(ans){
          $.ajax({
            data  : {
              "kd_tahap" : kd_tahap,
              "kd_kawasan" : kd_kawasan,
              "kd_cluster" : kd_cluster,
              "kd_periode" : kd_periode,
              "_token"          : '{{ csrf_token() }}'
            },
            url: "input_rencana_progress_mingguan_c/delete_item_pekerjaan",
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
    var kd_cluster = $('#kd_cluster').val();
    var kd_kawasan = $('#kd_kawasan').val();
    var tgl;
    
    $.ajax({
        type: 'POST',
        url: 'input_rencana_progress_mingguan_c/cek_minggu_pertama',
        cache: false,
        data: {
          "_token"  : '{{ csrf_token() }}',
          "kd_unit" : kd_unit,
          "kd_lokasi" : kd_lokasi,
          "kd_kawasan" : kd_kawasan,
          "kd_cluster" : kd_cluster                
        },
        success: function(msg){
          $.each(msg, function(x, y) {
            tgl = y.tgl_awal;
          });

          $('#saveBtnVal').val("create");
          $('#kd_kawasan_add').val('');
          $('#kd_cluster_add').val('');
          $('#kd_jenis_add').val('');
          $('#kd_tipe_add').val('');
          $('#periode1').val(tgl);
          $('#CustomerForm').trigger("reset");
          $('#modelHeading').html("Entry Progress");
          $('#ajaxModel').modal('show');            
            
        },
        error: function(){
            $('#v_loading').hide();
            alert('Proses Gagal');
        }
    });

    return false;
  } 

  function update_dt2(kd_item_pekerjaan,kd_kawasan,kd_cluster) {
    var ans = confirm('Yakin data '+kd_item_pekerjaan+' akan diupdate?');
    var bobot = $('#bobot_'+kd_item_pekerjaan).val();
    
    if(ans){
      $.ajax({
        data  : {
          "bobot" : bobot,
          "kd_item_pekerjaan" : kd_item_pekerjaan,
          "kd_kawasan" : kd_kawasan,
          "kd_cluster" : kd_cluster,
          "_token"          : '{{ csrf_token() }}'
        },
        url: "input_rencana_progress_mingguan_c/update_bobot",
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
</script>
@endsection

@section('content')
<div class="row">
  <div class="col-sm-12">
  <div class="card card-primary card-outline">
          <div class="card-header">
            <nav class="navbar justify-content-between">
              <a class="form-brand">
                <h3 class="card-title">Entry Rencana Progres Mingguan</h3>
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
              <label for="kd_tahapan">Tahapan</label>
              <div id="div_tahapan"></div>
            </div> 
            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Data Progress</a>
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
                      <th style="width: 130px; text-align: center;">Minggu Ke</th>
                      <th style="text-align: center;" class="text-nowrap">Tgl Awal</th>
                      <th style="text-align: center;" class="text-nowrap">Tgl Akhir</th>
                      <th style="text-align: center;" class="text-nowrap">Rencana Progres</th>
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
                   <input type="hidden" name="kd_tahap_bwh" id="kd_tahap_bwh" value="">
                    <div class="form-group">
                        <div class="col-sm-12">
                          <div class="row">
                            <div class="col-3">
                              <label for="stok_add" class="col-sm-12 control-label">Minggu Ke</label>
                              <input type="text" class="form-control" id="nm_periode" name="nm_periode" value="">
                            </div>
                            <div class="col-3">
                              <label for="stok_add" class="col-sm-12 control-label">Tanggal Awal</label>
                              <input type="text" class="form-control datepicker" id="periode1" name="periode1" placeholder="From" value="<?php echo date('01/01/2021');?>" maxlength="10">
                            </div>
                            <div class="col-3">
                              <label for="stok_add" class="col-sm-12 control-label">Tanggal Akhir</label>
                              <input type="text" class="form-control datepicker" id="periode2" name="periode2" placeholder="From" value="<?php echo date('t/m/Y')?>" maxlength="10">
                            </div>
                            <div class="col-3">
                              <label for="stok_add" class="col-sm-12 control-label">Rencana Progress</label>
                              <input type="number" class="form-control form-control-sm rounded-0" min="1" name="progress" id="progress" style="text-align: right;" value="">
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