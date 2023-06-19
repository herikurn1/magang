@extends('layouts.template')

@section('js')
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript">
		var kd_unit 		= "{{ session('kd_unit') }}";
		var kd_lokasi 	= "{{ session('kd_lokasi') }}";	
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
        url   : 'lap_kinerja_c/show_data_item_defect',
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
          url: "lap_kinerja_c/delete_dt",
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
          url: "lap_kinerja_c/save",
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
        
        $.ajax({
            type: 'POST',
            url: 'lap_kinerja_c/data_cluster',
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
            url: 'lap_kinerja_c/data_tahap',
            cache: false,
            data: {
              "_token"  : '{{ csrf_token() }}',
              "kd_unit" : kd_unit,
              "kd_lokasi" : kd_lokasi,
              "kd_kawasan" : kd_kawasan,
              "kd_cluster" : kd_cluster
            },
            success: function(msg){

                row = '<select class="form-control col-sm-12" name="tahap_bangun" id="tahap_bangun" onchange="clear_data()">';
                $.each(msg, function(x, y) {
                  row += '<option value="'+ y.tahap_bangun +'">'+ y.tahap_bangun +'</option>';
                });
                row += '</select>';

                $('#div_tahap').html(row);
                
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
        var kd_kawasan = $('#kd_kawasan').val();
        var kd_cluster = $('#kd_cluster').val();
        var nik_petugas = $('#nik_petugas').val();
        var nm_sm = $('#nm_petugas').val();
        var nm_kawasan = $("#kd_kawasan option:selected").text();
        var nm_cluster = $("#kd_cluster option:selected").text();
        var periode_1  = $('#periode1').val();//'16/02/2021';
        var periode_2  = $('#periode2').val();//'16/02/2021';        
        var tahap_bangun  = $('#tahap_bangun').val();

        $('#kawasan_kualitas_bangunan').html(nm_kawasan);
        $('#cluster_kualitas_bangunan').html(nm_cluster);
        $('#sm_kualitas_bangunan').html(nm_sm);
        $('#periode_kualitas_bangunan').html(periode_1+' sampai '+periode_2);

      $.ajax({
        type  : 'POST',
        url   : 'lap_kinerja_c/search_dt',
        data  : {
          "kd_unit"   : kd_unit,
          "kd_lokasi" : kd_lokasi,
          "kd_kawasan" : kd_kawasan,
          "kd_cluster" : kd_cluster, 
          "nik_petugas" : nik_petugas,    
          "periode_1" : periode_1,
          "periode_2" : periode_2,
          "tahap_bangun" : tahap_bangun,
          "_token"    : '{{ csrf_token() }}'
        },
        success : function(msg) {          
          var row;
          var no=1;
          var tot_unit = jml_unit_st = jml_unit_ = 0;
          var tot_defect = 0;
          var tot_c = tot_a1 = tot_a2 = tot_a3 = 0;
          var msg_2 = msg;
          var A1 = 'A1';
          var A2 = 'A2';
          var A3 = 'A3';

          $.each(msg_2, function(x, y) {
            //console.log(y.jml_unit);
            tot_unit = tot_unit + parseFloat(y.jml_unit);
            // tot_unit = tot_defect + parseFloat(y.jml_unit).toFixed(0) ;
            tot_defect = tot_defect + parseFloat(y.jml_defect);
            tot_c = tot_c + parseFloat(y.total_defect);
            tot_a1 = tot_a1 + parseFloat(y.a1);
            tot_a2 = tot_a2 + parseFloat(y.a2);
            tot_a3 = tot_a3 + parseFloat(y.a3);
            jml_unit_st = parseFloat(y.jml_unit_st);
          });
          jml_unit_ = tot_unit + jml_unit_st;
          tot_c = tot_defect / tot_unit;

          $('#tbl_lap_kinerja tbody').empty();

          $("#tbl_lap_kinerja tbody").find("tr:gt(0)").remove(); // CLEAR TABLE
          $.each(msg, function(x, y) {

            if(parseFloat(y.jml_unit) == 0){
              //
            }else{
              row += ''+
                '<tr style="">'+
                  '<td>'+ no++ +'</td>'+
                  '<td>'+y.nama+'</td>'+
                  '<td style="text-align: center;">'+y.jml_unit+'</td>'+
                  '<td style="text-align: center;" onclick="rekap_defect(\''+kd_kawasan+'\',\''+kd_cluster+'\',\''+nm_kawasan+'\',\''+nm_cluster+'\',\''+nm_sm+'\',\''+periode_1+'\',\''+periode_2+'\',\''+y.user_id+'\', \''+y.user_id_bawahan+'\', \''+y.nama+'\', \''+y.jml_unit+'\', \''+y.jml_defect+'\', \''+y.total_defect_f+'\', \''+tot_unit+'\', \''+tahap_bangun+'\')"><div class="link_cursor">'+y.jml_defect+'</div></td>'+
                  '<td style="text-align: center;">'+y.total_defect_f+'</td>'+
                  '<td style="text-align: center;" onclick="lap_detail_ageing(\''+kd_kawasan+'\',\''+kd_cluster+'\',\''+nm_kawasan+'\',\''+nm_cluster+'\',\''+nm_sm+'\',\''+periode_1+'\',\''+periode_2+'\',\''+y.user_id+'\', \''+y.user_id_bawahan+'\', \''+y.nama+'\', \''+y.jml_unit+'\', \''+y.jml_defect+'\', \''+y.total_defect_f+'\', \''+tot_unit+'\', \''+A1+'\', \''+tahap_bangun+'\')"><div class="link_cursor">'+y.a1+'</div></td>'+
                  '<td style="text-align: center;" onclick="lap_detail_ageing(\''+kd_kawasan+'\',\''+kd_cluster+'\',\''+nm_kawasan+'\',\''+nm_cluster+'\',\''+nm_sm+'\',\''+periode_1+'\',\''+periode_2+'\',\''+y.user_id+'\', \''+y.user_id_bawahan+'\', \''+y.nama+'\', \''+y.jml_unit+'\', \''+y.jml_defect+'\', \''+y.total_defect_f+'\', \''+tot_unit+'\', \''+A2+'\', \''+tahap_bangun+'\')"><div class="link_cursor">'+y.a2+'</div></td>'+
                  '<td style="text-align: center;" onclick="lap_detail_ageing(\''+kd_kawasan+'\',\''+kd_cluster+'\',\''+nm_kawasan+'\',\''+nm_cluster+'\',\''+nm_sm+'\',\''+periode_1+'\',\''+periode_2+'\',\''+y.user_id+'\', \''+y.user_id_bawahan+'\', \''+y.nama+'\', \''+y.jml_unit+'\', \''+y.jml_defect+'\', \''+y.total_defect_f+'\', \''+tot_unit+'\', \''+A3+'\', \''+tahap_bangun+'\')"><div class="link_cursor">'+y.a3+'</div></td>'+
                  '<td style="text-align: center;" onclick="lap_cycle_time(\''+kd_kawasan+'\',\''+kd_cluster+'\',\''+nm_kawasan+'\',\''+nm_cluster+'\',\''+nm_sm+'\',\''+periode_1+'\',\''+periode_2+'\',\''+y.user_id+'\', \''+y.user_id_bawahan+'\', \''+y.nama+'\', \''+y.jml_unit+'\', \''+y.jml_defect+'\', \''+y.total_defect_f+'\', \''+tot_unit+'\', \''+tahap_bangun+'\')"><div class="link_cursor">'+y.avg_day+'</div></td>'+
                '</tr>'+
              '';
            }
          });

          //$('#tbl_kawasan').append(row);

            row += ''+
              '<tr style="font-weight: bold;">'+
                '<td></td>'+
                '<td style="text-align: Right;">Total</td>'+
                '<td style="text-align: center;">'+tot_unit+'</td>'+
                '<td style="text-align: center;" onclick="lap_grafik(\''+kd_kawasan+'\',\''+kd_cluster+'\',\''+nm_kawasan+'\',\''+nm_cluster+'\',\''+nik_petugas+'\',\''+nm_sm+'\',\''+periode_1+'\',\''+periode_2+'\',\''+tahap_bangun+'\')"><div class="link_cursor">'+tot_defect+'</div></td>'+
                '<td style="text-align: center;">'+parseFloat(tot_c).toFixed(2)+'</td>'+
                '<td style="text-align: center;">'+tot_a1+'</td>'+
                '<td style="text-align: center;">'+tot_a2+'</td>'+
                '<td style="text-align: center;">'+tot_a3+'</td>'+
                '<td style="text-align: center;"></td>'+
              '</tr>';
            row += ''+
              '<tr style="font-weight: bold;">'+
                '<td></td>'+
                '<td style="text-align: Right;">Total Unit ST</td>'+
                '<td style="text-align: center;">'+jml_unit_st+'</td>'+
                '<td style="text-align: center;"></td>'+
                '<td style="text-align: center;"></td>'+
                '<td style="text-align: center;"></td>'+
                '<td style="text-align: center;"></td>'+
                '<td style="text-align: center;"></td>'+
                '<td style="text-align: center;"></td>'+
              '</tr>';
            row += ''+
              '<tr style="font-weight: bold;">'+
                '<td></td>'+
                '<td style="text-align: Right;">Total Unit</td>'+
                '<td style="text-align: center;">'+jml_unit_+'</td>'+
                '<td style="text-align: center;"></td>'+
                '<td style="text-align: center;"></td>'+
                '<td style="text-align: center;"></td>'+
                '<td style="text-align: center;"></td>'+
                '<td style="text-align: center;"></td>'+
                '<td style="text-align: center;"></td>'+
              '</tr>';              
            '';
            //<a class="nav-link" href="/mahasiswa">'+tot_defect+'</a>
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

    function lap_formulir_kualitas_bangunan(kd_kawasan, kd_cluster, nm_kawasan, nm_cluster, nm_sm, periode_1, periode_2, blok, nomor, no_formulir, jns_pekerjaan, itm_defect, desc_defect, kat_defect, nm_lantai, path_foto_denah, src_foto_denah, path_foto_defect, src_foto_defect, tgl_foto, tgl_jt_perbaikan, tgl_selesai, user_id, user_id_bawahan, nama, jml_unit, jml_defect, total_defect, tot_unit, kd_kategori_defect){
       $('#card_formulir_kualitas').show();

        $('#nomor_formulir_kualitas').html(no_formulir);
        $('#qc_formulir_kualitas').html();
        $('#kawasan_formulir_kualitas').html(nm_kawasan);
        $('#jns_pekerjaan_formulir_kualitas').html(jns_pekerjaan);
        $('#cluster_formulir_kualitas').html(nm_cluster);
        $('#itm_defect_formulir_kualitas').html(itm_defect);
        $('#blok_unit_formulir_kualitas').html(blok+'/'+nomor);
        $('#des_defect_formulir_kualitas').html(desc_defect);
        $('#lantai_formulir_kualitas').html(nm_lantai);
        $('#kategori_formulir_kualitas').html(kat_defect);
        $('#kontraktor_formulir_kualitas').html();
        $('#tgl_temuan_formulir_kualitas').html(tgl_foto);
        $('#sm_formulir_kualitas').html(nm_sm);
        $('#tgl_perbaikan_formulir_kualitas').html(tgl_jt_perbaikan);
        $('#bi_formulir_kualitas').html(nama);
        $('#tgl_selesai_formulir_kualitas').html(tgl_selesai);
        $('#formulir_denah').attr('src','https://sqii.gadingemerald.com/public/'+path_foto_denah+''+src_foto_denah);
        $('#formulir_temuan').attr('src','https://sqii.gadingemerald.com/public/'+path_foto_defect+''+src_foto_defect);
        $('#formulir_perbaikan').attr('src','https://sqii.gadingemerald.com/public/'+path_foto_denah+''+src_foto_denah);
        //window.open('http://localhost:8000/sqii/lap_kinerja_c');
    }    

    function lap_detail_ageing(kd_kawasan, kd_cluster, nm_kawasan, nm_cluster, nm_sm, periode_1, periode_2, user_id,user_id_bawahan, nama, jml_unit, jml_defect, total_defect, tot_unit, tipe_ageing, tahap_bangun){

      periode1 = periode_1.replace("/", "-");
      periode2 = periode_2.replace("/", "-");
      periode_1 = periode1.replace("/", "-");
      periode_2 = periode2.replace("/", "-");      
    

      window.open('{{ url("sqii/lap_detil_aging_c/") }}'+'/'+kd_kawasan+'/'+kd_cluster+'/'+nm_kawasan+'/'+nm_cluster+'/'+nm_sm+'/'+periode_1+'/'+periode_2+'/'+user_id+'/'+user_id_bawahan+'/'+nama+'/'+jml_unit+'/'+jml_defect+'/'+total_defect+'/'+tot_unit+'/'+tipe_ageing+'/'+session_user_id+'/'+tahap_bangun); 
    }

    function lap_cycle_time(kd_kawasan, kd_cluster, nm_kawasan, nm_cluster, nm_sm, periode_1, periode_2, user_id, user_id_bawahan, nama, jml_unit, jml_defect, total_defect, tot_unit, tahap_bangun){

      periode1 = periode_1.replace("/", "-");
      periode2 = periode_2.replace("/", "-");
      periode_1 = periode1.replace("/", "-");
      periode_2 = periode2.replace("/", "-");      
    
      window.open('{{ url("sqii/lap_cycle_time_c/") }}'+'/'+kd_kawasan+'/'+kd_cluster+'/'+nm_kawasan+'/'+nm_cluster+'/'+nm_sm+'/'+periode_1+'/'+periode_2+'/'+user_id+'/'+user_id_bawahan+'/'+nama+'/'+jml_unit+'/'+jml_defect+'/'+total_defect+'/'+tot_unit+'/'+session_user_id+'/'+tahap_bangun); 
    }

    function lap_grafik_defect(kd_kawasan,kd_cluster,nm_kawasan,nm_cluster,nik_petugas,nm_sm,periode_1,periode_2,kd_kategori,chart,tahap_bangun){

      periode1 = periode_1.replace("/", "-");
      periode2 = periode_2.replace("/", "-");
      periode_1 = periode1.replace("/", "-");
      periode_2 = periode2.replace("/", "-");      
    
      window.open('{{ url("sqii/lap_grafic_defect_c/") }}'+'/'+kd_kawasan+'/'+kd_cluster+'/'+nm_kawasan+'/'+nm_cluster+'/'+nik_petugas+'/'+nm_sm+'/'+periode_1+'/'+periode_2+'/'+kd_kategori+'/'+chart+'/'+session_user_id+'/'+tahap_bangun); 

    }

    function lap_grafik(kd_kawasan,kd_cluster,nm_kawasan,nm_cluster,nik_petugas,nm_sm,periode_1,periode_2,tahap_bangun){

       lap_grafik_defect(kd_kawasan,kd_cluster,nm_kawasan,nm_cluster,nik_petugas,nm_sm,periode_1,periode_2,1,'piechart',tahap_bangun);
    }

    function generate_qrcode() {
        alert('Generate QRCODE!!');return false;
        var kd_kawasan = $('#kd_kawasan').val();
        var kd_cluster = $('#kd_cluster').val();

      $.ajax({
        type  : 'POST',
        url   : 'lap_kinerja_c/search_dt',
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

          $('#tbl_kawasan tbody').empty();

          $("#tbl_kawasan").find("tr:gt(0)").remove(); // CLEAR TABLE
          $.each(msg, function(x, y) {
            row += ''+
              '<tr>'+
                '<td>'+ no++ +'</td>'+
                '<td>'+y.blok+'</td>'+
                '<td>'+y.nomor+'</td>'+
                '<td>'+y.nm_tipe+'</td>'+
                '<td>'+y.qr_code+'</td>'+
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

    function cetak_qrcode() {
        alert('Cetak Label QRCODE!!');return false;
        var kd_kawasan = $('#kd_kawasan').val();
        var kd_cluster = $('#kd_cluster').val();

      $.ajax({
        type  : 'POST',
        url   : 'lap_kinerja_c/search_dt',
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

          $('#tbl_kawasan tbody').empty();

          $("#tbl_kawasan").find("tr:gt(0)").remove(); // CLEAR TABLE
          $.each(msg, function(x, y) {
            row += ''+
              '<tr>'+
                '<td>'+ no++ +'</td>'+
                '<td>'+y.blok+'</td>'+
                '<td>'+y.nomor+'</td>'+
                '<td>'+y.nm_tipe+'</td>'+
                '<td>'+y.qr_code+'</td>'+
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

   var sys_search_kry_page = 1; 

  function nik_petugas() {
    $('#sys-modal-default').modal("show");
    var keyword = $('#sys-modal-default-keyword').val();
    var kd_kawasan  = $('#kd_kawasan').val();
    var kd_cluster  = $('#kd_cluster').val();

    $.ajax({
      type  : 'POST',
      url   : 'lap_kinerja_c/nik_petugas?page='+sys_search_kry_page,
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
      url: '{{ url("sqii/lap_kinerja_c/") }}/print_dt',
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
	</script>
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-12">
			<div class="card card-primary card-outline">
		      	<div class="card-header">
              <nav class="navbar justify-content-between">
                <a class="form-brand">
                  <h3 class="card-title">Laporan Kinerja</h3>
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
                  <label for="nik_petugas" class="col-sm-2 control-label">Site Manager</label>
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
                  <div class="row">
                    <div class="col-md-3">
                      <label for="periode1" class="col-sm-12 control-label">Periode</label>
                      <div class="col-sm-12">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">
                              <i class="far fa-calendar-alt"></i>
                            </span>
                          </div>
                            <input type="text" class="form-control datepicker" id="periode1" name="periode1" placeholder="From" value="<?php echo date('01/m/Y');?>" maxlength="10">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <label for="periode2" class="col-sm-12 control-label">&nbsp;</label>
                      <div class="col-sm-12">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">
                              <i class="far fa-calendar-alt"></i>
                            </span>
                          </div>
                            <input type="text" class="form-control datepicker" id="periode2" name="periode2" placeholder="To" value="<?php echo date('t/m/Y');?>" maxlength="510">
                        </div>
                      </div>
                    </div>
                  </div>
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
              <h3 class="card-title">Laporan kinerja</h3>
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
                  <dt class="col-sm-2">Site Manager</dt>
                  <dd class="col-sm-10" id="sm_kualitas_bangunan"></dd>
                  <dt class="col-sm-2">Periode</dt>
                  <dd class="col-sm-10" id="periode_kualitas_bangunan"></dd>
                </dl>

                <table id="tbl_lap_kinerja" class="table table-bordered table-hover">
                  <thead style="text-align: center;font-weight: bold;">                  
                    <tr>
                      <td style="width: 10px;vertical-align: middle;" rowspan="3">#</td>
                      <td style="vertical-align: middle;" rowspan="3">NAMA BUILDING INSPECTOR</td>
                      <td style="vertical-align: middle;" rowspan="2">JUMLAH UNIT</td>
                      <td colspan="2">DEFECT</td>
                      <td colspan="3">AGEING</td>
                      <td >CYCLE TIME</td>
                    </tr>
                    <tr>
                      <td>JML DEFECT</td>
                      <td>DEFECT / UNIT</td>
                      <td colspan="3">SUDAH LEWAT</td>
                      <td >RATA-RATA UNIT/HARI (Min. 8 unit)</td>
                    </tr>
                    <tr>
                      <td>&nbsp;(a)</td>
                      <td>(b)</td>
                      <td>(c) = (b) / (a)</td>
                      <td>1-7</td>
                      <td>8-13</td>
                      <td>&gt;14</td>
                      <td>&nbsp;</td>
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