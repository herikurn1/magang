@extends('layouts.template')

@section('js')
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    var kd_unit     = "{{ session('kd_unit') }}";
    var kd_lokasi   = "{{ session('kd_lokasi') }}"; 
    var session_user_id   = "{{ session('user_id') }}";   

    $(function() {
      //Date picker
      $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true
      });

      google.charts.load('current', {'packages':['corechart']});

      data_cluster();
    });

    function item_defect() {

      var kd_kategori = $('#kd_kategori').val();

      $.ajax({
        type  : 'POST',
        url   : 'lap_rekap_progres_c/show_data_item_defect',
        data  : {
          "kd_kategori"   : kd_kategori,
          "_token"    : '{{ csrf_token() }}'
        },
        success : function(msg) {          
          var row;
          var no=1;

          $('#tbl_kawasan tbody').empty();

          $.each(msg, function(x, y) {
            row += ''+
              '<tr>'+
                '<td>'+ no++ +'</td>'+
                '<td>'+y.nm_item_defect+'</td>'+
                '<td style="text-align: center;">'+
                  '<button type="button" class="btn btn-info btn-sm" onclick="edit_dt(\''+y.kd_item_defect+'\',\''+y.nm_item_defect+'\')">Edit</button>&nbsp'+
                  '<button type="button" class="btn btn-info btn-sm" onclick="delete_dt(\''+y.kd_item_defect+'\')">Hapus</button></td>'+
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

    function edit_dt(kd_item_defect,nm_item_defect) {
      $('#modelHeading').html("Edit Item Defect");
      $('#saveBtnVal').val("edit");
      $('#ajaxModel').modal('show');
      $('#kd_item_defect').val(kd_item_defect);
      $('#nm_item_defect').val(nm_item_defect);
    }

    function delete_dt(kd_item_defect) {
        $.ajax({
          data  : {
            "kd_item_defect"   : kd_item_defect,
            "_token"    : '{{ csrf_token() }}'
          },
          url: "lap_rekap_progres_c/delete_dt",
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
      var kd_kategori = $('#kd_kategori').val();
      if (kd_kategori == ''){
        $('#ajaxModel').modal('hide');
        alert('Pilih Kode Kategori');
        return false;
      }

        $.ajax({
          data: data_all,
          url: "lap_rekap_progres_c/save",
          type: "POST",

          success: function (data) {

              $('#CustomerForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              item_defect();
              //table.draw();

          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
              $('#ajaxModel').modal('hide');
          }
      });

        return false;
    }   

    function data_cluster() {
        var kd_kawasan = $('#kd_kawasan').val();
        var row;
        // DD($r);
        $.ajax({
            type: 'POST',
            url: 'lap_rekap_progres_c/data_cluster',
            cache: false,
            data: {
              "_token"  : '{{ csrf_token() }}',
              "kd_unit" : kd_unit,
              "kd_lokasi" : kd_lokasi,
              "kd_kawasan" : kd_kawasan                
            },
            success: function(msg){

                row = '<select class="form-control col-sm-12" name="kd_cluster" id="kd_cluster" onchange="clear_data();data_tahap();">';
                $.each(msg, function(x, y) {
                  kd_cluster_str = y.kd_cluster;
                  row += '<option value="'+ y.kd_cluster +'">'+ y.nm_cluster +' - '+ kd_cluster_str.trim().toLowerCase() +'</option>';
                });
                row += '</select>';

                $('#div_cluster').html(row);
                data_tahap();
                // data_periode();
            },
            error: function(){
                $('#v_loading').hide();
                alert('Proses Get Cluster Gagal');
            }
        });
        
        return false;
    }      

    function data_periode() {
        var kd_kawasan    = $('#kd_kawasan').val();
        var kd_cluster    = $('#kd_cluster').val();
        var tahap_bangun  = $('#tahap_bangun').val();
        var row;
        
        $.ajax({
            type: 'POST',
            url: 'lap_rekap_progres_c/data_periode',
            cache: false,
            data: {
              "_token"  : '{{ csrf_token() }}',
              "kd_unit" : kd_unit,
              "kd_lokasi" : kd_lokasi,
              "kd_kawasan" : kd_kawasan,
              "kd_cluster" : kd_cluster,
              "tahap_bangun" : tahap_bangun
            },
            success: function(msg){

                row = '<select class="form-control col-sm-12" name="kd_periode" id="kd_periode" onchange="clear_data();">';
                $.each(msg, function(x, y) {
                  row += '<option value="'+ y.kd_periode +'">'+ y.nm_periode +' # '+ y.tgl_awal +' s.d. '+ y.tgl_akhir +'</option>';
                });
                row += '</select>';

                $('#div_periode').html(row);
                
                clear_data();
            },
            error: function(){
                $('#v_loading').hide();
                alert('Proses Get Cluster Gagal');
            }
        });
        
        return false;
    }

    function clear_data(){
      $('#tbl_kawasan tbody').empty();
    }

    function search_dt() {
      $('#v_loading').show();

        $('#card_kualitas_bangunan').show();
        var kd_kawasan    = $('#kd_kawasan').val();
        var kd_cluster    = $('#kd_cluster').val();
        var nm_kawasan    = $("#kd_kawasan option:selected").text();
        var nm_cluster    = $("#kd_cluster option:selected").text();
        var periode       = $("#kd_periode option:selected").text();   
        var tahap_bangun  = $('#tahap_bangun').val();
        var kd_periode    = $('#kd_periode').val();

        $('#kawasan_kualitas_bangunan').html(nm_kawasan);
        $('#cluster_kualitas_bangunan').html(nm_cluster);
        $('#periode_kualitas_bangunan').html(periode);

      $.ajax({
        type  : 'POST',
        url   : 'lap_rekap_progres_c/search_dt',
        data  : {
          "kd_unit"   : kd_unit,
          "kd_lokasi" : kd_lokasi,
          "kd_kawasan" : kd_kawasan,
          "kd_cluster" : kd_cluster, 
          "tahap_bangun" : tahap_bangun,
          "kd_periode" : kd_periode,
          "_token"    : '{{ csrf_token() }}'
        },
        success : function(msg) {          
          var row;
          var no        = 1;
          var tot_unit  = tot_rencana = tot_aktual = tot_calc_akt = tot_deviasi = 0;
          var msg_2     = msg;
          $.each(msg_2, function(x, y) {
            //console.log(y.jml_unit);
            // tot_unit = tot_unit + parseFloat(y.jml_unit);
          });

          $('#tbl_lap_kinerja tbody').empty();

          $("#tbl_lap_kinerja tbody").find("tr:gt(0)").remove(); // CLEAR TABLE
          $.each(msg, function(x, y) {
            row += ''+
              '<tr style="">'+
                '<td>'+ no++ +'</td>'+
                '<td onclick="detail_progress_kontraktor(\''+kd_kawasan+'\',\''+kd_cluster+'\',\''+nm_kawasan+'\',\''+nm_cluster+'\',\''+kd_periode+'\',\''+periode+'\',\''+y.user_id+'\',\''+y.nama+'\')"><div class="link_cursor">'+y.nama+'</div></td>'+
                '<td style="text-align: center;">'+y.jml_unit+'</td>'+
                '<td style="text-align: center;">'+y.bobot_rencana+'</td>'+
                '<td style="text-align: center;">'+y.bobot_aktual+'</td>'+
                '<td style="text-align: center;">'+y.deviasi+'</td>'+
                '<td style="text-align: center;"></td>'+
              '</tr>'+
            '';

            tot_unit      = tot_unit + parseFloat(y.jml_unit);
            tot_rencana   = tot_rencana + parseFloat(y.bobot_rencana);
            tot_aktual    = tot_aktual + parseFloat(y.bobot_aktual); 
            tot_calc_akt  = tot_calc_akt + parseFloat(y.calc_aktual); 
            tot_deviasi   = tot_deviasi + parseFloat(y.deviasi);
          });

            no            = no - 1;
            tot_rencana   = tot_rencana / no;
            tot_aktual    = tot_aktual / no; 
            tot_calc_akt  = tot_calc_akt / tot_unit; 
            tot_deviasi   = tot_deviasi / no;

            row += ''+
              '<tr style="">'+
                '<td></td>'+
                '<td ></div></td>'+
                '<td style="text-align: center;font-weight: bold;">'+tot_unit+'</td>'+
                '<td style="text-align: center;font-weight: bold;">'+tot_rencana.toFixed(2)+'</td>'+
                '<td style="text-align: center;font-weight: bold;">'+tot_calc_akt.toFixed(2)+'</td>'+
                '<td style="text-align: center;font-weight: bold;">'+tot_deviasi.toFixed(2)+'</td>'+
                '<td style="text-align: center;font-weight: bold;"></td>'+
              '</tr>'+
            '';

          $('#tbl_lap_kinerja tbody').append(row);
          $('#v_loading').hide();
        },
        error   : function(xhr) {
          $('#v_loading').hide();
          read_error(xhr);
        }
      });
    }

    function rekap_defect(kd_kawasan, kd_cluster, nm_kawasan, nm_cluster, nm_sm, periode_1, periode_2, user_id,user_id_bawahan, nama, jml_unit, jml_defect, total_defect, tot_unit, tahap_bangun){

      periode1 = periode_1.replace("/", "-");
      periode2 = periode_2.replace("/", "-");
      periode_1 = periode1.replace("/", "-");
      periode_2 = periode2.replace("/", "-");      
      kd_kategori_defect = '1';
    
      window.open('{{ url("sqii/lap_defect_c/") }}'+'/'+kd_kawasan+'/'+kd_cluster+'/'+nm_kawasan+'/'+nm_cluster+'/'+nm_sm+'/'+periode_1+'/'+periode_2+'/'+user_id+'/'+user_id_bawahan+'/'+nama+'/'+jml_unit+'/'+jml_defect+'/'+total_defect+'/'+tot_unit+'/'+session_user_id+'/'+tahap_bangun); 
    }



   var sys_search_kry_page = 1; 

  function nik_petugas() {
    $('#sys-modal-default').modal("show");
    var keyword = $('#sys-modal-default-keyword').val();
    var kd_kawasan  = $('#kd_kawasan').val();
    var kd_cluster  = $('#kd_cluster').val();

    $.ajax({
      type  : 'POST',
      url   : 'lap_rekap_progres_c/nik_petugas?page='+sys_search_kry_page,
      data  : {
        "_token"  : '{{ csrf_token() }}',
        "keyword" : keyword,
        "kd_kawasan" : kd_kawasan,
        "kd_cluster" : kd_cluster

      },
      success : function(msg){
        //$('#sys-modal-default').modal("show");
        var tbl = ''+
          '<div class="row">'+
                  '<div class="col-12">'+
                    '<div class="card">'+
                        '<div class="card-header">'+
                          '<h3 class="card-title">Nama Site Manager</h3>'+
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
                        '<td><button type="button" class="btn btn-info btn-sm" onclick="nik_karyawan_set(\''+y.USER_ID+'\', \''+y.NAMA+'\')">Pilih</button></td>'+
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
                            '<button type="button" class="btn btn-info btn-flat btn-sm" onclick="sys_search_kry_page--; nik_petugas();"><<</button>'+
                          '</span>'+
                          '<input type="number" class="form-control form-control-sm rounded-0" min="1" id="sys_search_kry_page_input" style="text-align: center;" value="'+sys_search_kry_page+'">'+
                          '<span class="input-group-append">'+
                            '<button type="button" class="btn btn-info btn-flat btn-sm" onclick="sys_search_kry_page++; nik_petugas();">>></button>'+
                            '<button type="button" class="btn btn-default btn-flat btn-sm" onclick="sys_search_kry_page = $(\'#sys_search_kry_page_input\').val(); nik_petugas();">Go!</button>'+
                          '</span>'+
                      '</div>'+
            '</div>'+
          '</div>'+
        '';

        $('#sys-modal-default-body').html(tbl);
        $('#sys-modal-default-btn-search').attr('onclick', 'nik_petugas()');
        // $('#sys-modal-default').modal("show");
      },
      error   : function(xhr){
        read_error(xhr);
      }
    });
  }

  function nik_karyawan_set(USER_ID, NAMA) {
    $('#nik_petugas').val(USER_ID);
    $('#nm_petugas').val(NAMA);
    $('#sys-modal-default').modal('hide');
  }  

  function print_dt(){
    $('#v_loading').show();
    var kd_kawasan = $('#kd_kawasan').val();
    var kd_cluster = $('#kd_cluster').val();
    var nik_petugas = $('#nik_petugas').val();
    var nm_sm = $('#nm_petugas').val();
    var nm_kawasan = $("#kd_kawasan option:selected").text();
    var nm_cluster = $("#kd_cluster option:selected").text();
    var periode_1  = $('#periode1').val();//'16/02/2021';
    var periode_2  = $('#periode2').val();//'16/02/2021';   
    var tahap_bangun  = $('#tahap_bangun').val();
    
    $.ajax({
      data  : {
          "kd_unit"   : kd_unit,
          "kd_lokasi" : kd_lokasi,
          "kd_kawasan" : kd_kawasan,
          "kd_cluster" : kd_cluster,
          "nm_kawasan" : nm_kawasan,
          "nm_cluster" : nm_cluster, 
          "nik_petugas" : nik_petugas,  
          "nm_sm" : nm_sm,    
          "periode_1" : periode_1,
          "periode_2" : periode_2,
          "tahap_bangun" : tahap_bangun
      },
      url: '{{ url("sqii/lap_rekap_progres_c/") }}/print_dt',
      type: "POST",
      dataType: 'html',
      headers : { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
      success: function (data) {
        // console.log(data);
        var w = window.open();
        w.document.title = '12';
        $(w.document.body).html(data);
        $('#v_loading').hide();
      },
      error: function (data) {
        $('#v_loading').hide();
          console.log('Error:', data);
      }
  });

    return false;
  }

  function detail_progress_kontraktor(kd_kawasan, kd_cluster, nm_kawasan, nm_cluster, kd_periode, periode,user_id,nama){
    // console.log('aloo cek');return false; 
    //     
    kd_kategori_defect = '1';
    var periode1 = periode.replaceAll("/", "-");
    var periode2 = periode1.replaceAll("#", "-");
    // console.log(periode);return false; 
  
    // window.open('{{ url("sqii/lap_detail_progres_kontraktor_c/") }}'+'/'+kd_kawasan+'/'+kd_cluster+'/'+nm_kawasan+'/'+nm_cluster); 
    // window.open('{{ url("sqii/lap_detail_progres_kontraktor_c/") }}'+'/'+kd_kawasan+'/'+kd_cluster+'/'+nm_kawasan+'/'+nm_cluster+'/'+kd_periode+'/'+periode2);
    window.open('{{ url("sqii/lap_detail_progres_kontraktor_c/") }}'+'/'+kd_kawasan+'/'+kd_cluster+'/'+nm_kawasan+'/'+nm_cluster+'/'+kd_periode+'/'+periode2+'/'+user_id+'/'+nama+'/'+session_user_id);  
    // window.open('{{ url("sqii/lap_detail_kinerja_P3515_orang_c/") }}'+'/'+kd_kawasan+'/'+kd_cluster+'/'+nm_kawasan+'/'+nm_cluster+'/'+nm_sm+'/'+periode_1+'/'+periode_2+'/'+user_id+'/'+user_id_bawahan+'/'+nama+'/'+jml_unit+'/'+jml_defect+'/'+total_defect+'/'+tot_unit+'/'+session_user_id+'/'+tahap_bangun); 
    // window.open('{{ url("sqii/lap_detail_progres_kontraktor_c/") }}'+'/'+kd_kawasan+'/'+kd_cluster+'/'+nm_kawasan+'/'+nm_cluster+'/'+kd_periode+'/'+periode2+'/'+user_id+'/'+nama+'/'+session_user_id); 
  }

  function data_tahap() {
      var kd_kawasan = $('#kd_kawasan').val();
      var kd_cluster = $('#kd_cluster').val();
      var row;
      
      $.ajax({
          type: 'POST',
          url: 'lap_rekap_progres_c/data_tahap',
          cache: false,
          data: {
            "_token"  : '{{ csrf_token() }}',
            "kd_unit" : kd_unit,
            "kd_lokasi" : kd_lokasi,
            "kd_kawasan" : kd_kawasan,
            "kd_cluster" : kd_cluster
          },
          success: function(msg){

              row = '<select class="form-control col-sm-12" name="tahap_bangun" id="tahap_bangun" onchange="clear_data();data_periode();">';
              $.each(msg, function(x, y) {
                row += '<option value="'+ y.tahap_bangun +'">'+ y.tahap_bangun +'</option>';
              });
              row += '</select>';

              $('#div_tahap').html(row);
              
              data_periode();
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
                  <h3 class="card-title">Rekap Progres</h3>
                </a>
                <a class="navbar-inline">
                  <!-- {!! $dt['button'] !!} -->
                  <button type="button" class="btn btn-default btn-flat" onclick="print_dt()">
                    <i class="fas fa-print"></i>
                  </button>
                  <button type="button" class="btn btn-default btn-flat" onclick="search_dt()">
                    <i class="fas fa-search"></i>
                  </button>
                </a>
              </nav>
            </div>
            <!-- <section class="content-header"></section> -->
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
                  <label for="tahap_bangun" class="col-sm-12">Tahap Bangun</label>
                  <div id="div_tahap" class="col-sm-12"></div>
                </div>
                <div class="form-group">
                  <label for="periode" class="col-sm-12">Periode</label>
                  <div id="div_periode" class="col-sm-12"></div>
                </div>
              </div>
          <div id="v_loading" class="overlay" style="display: none;">
            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
          </div>
              </div>
              <!-- /.card -->
        </div>


        <div class="col-sm-12">
          <div id="card_kualitas_bangunan" style="display: none;" class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">Dashboard/Rekap Progress</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
              </div>
            </div>
            <div class="card-body">
                <dl class="row">
                  <dt class="col-sm-2">Kawasan</dt>
                  <dd class="col-sm-10" id="kawasan_kualitas_bangunan"></dd>
                  <dt class="col-sm-2">Cluster</dt>
                  <dd class="col-sm-10" id="cluster_kualitas_bangunan"></dd>
                  <dt class="col-sm-2">Periode</dt>
                  <dd class="col-sm-10" id="periode_kualitas_bangunan"></dd>
                </dl>

                <table id="tbl_lap_kinerja" class="table table-bordered table-hover">
                  <thead style="text-align: center;font-weight: bold;">                  
                    <tr>
                      <td style="width: 10px;vertical-align: middle;">#</td>
                      <td >KONTRAKTOR</td>
                      <td >TOTAL UNIT</td>
                      <td >RENCANA (%)</td>
                      <td >AKTUAL (%)</td>
                      <td >DEVIASI (%)</td>
                      <td >KETERANGAN</td>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
            </div>            
          </div>
        </div>                                 
    </div>
  </div>

<!--   <div id="v_loading" class="overlay" style="display: none;">
    <i class="fas fa-2x fa-sync-alt fa-spin"></i>
  </div>       -->
  <div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="CustomerForm" name="CustomerForm" class="form-horizontal" autocomplete="off" onsubmit="return save_process()">
                   <input type="hidden" name="saveBtnVal" id="saveBtnVal" value="create">
                   <input type="hidden" name="kd_item_defect" id="kd_item_defect" value="">
                    <div class="form-group">
                        <label for="nm_item_defect" class="col-sm-2 control-label">Nama</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="nm_item_defect" name="nm_item_defect" placeholder="Enter Name" value="" maxlength="50" required="">
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