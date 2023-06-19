@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit 		 = "{{ session('kd_unit') }}";
		var kd_lokasi 	 = "{{ session('kd_lokasi') }}";	
    var session_user_id   = "{{ session('user_id') }}";
    var kd_kawasan   = "{{ $dt['kd_kawasan'] }}";  
    var kd_cluster   = "{{ $dt['kd_cluster'] }}"; 
    var nm_kawasan   = "{{ $dt['nm_kawasan'] }}"; 
    var nm_cluster   = "{{ $dt['nm_cluster'] }}"; 
    var nm_sm        = "{{ $dt['nm_sm'] }}"; 
    var periode_1    = "{{ $dt['periode_1'] }}";
    var periode_2    = "{{ $dt['periode_2'] }}";
    var user_id      = "{{ $dt['user_id'] }}";
    var user_id_bawahan  = "{{ $dt['user_id_bawahan'] }}";
    var nama         = "{{ $dt['nama'] }}";
    var jml_unit     = "{{ $dt['jml_unit'] }}";
    var jml_defect   = "{{ $dt['jml_defect'] }}";
    var total_defect = "{{ $dt['total_defect'] }}";
    var tot_unit     = "{{ $dt['tot_unit'] }}";
    var tahap_bangun = "{{ $dt['tahap_bangun'] }}";

    $(function() {
      $('#kawasan_rekap_defect').html(nm_kawasan);
      $('#cluster_rekap_defect').html(nm_cluster);
      $('#sm_rekap_defect').html(nm_sm);
      $('#bi_rekap_defect').html(nama);
      $('#jml_unit_rekap_defect').html(jml_unit);
      $('#tot_unit_rekap_defect').html(tot_unit);
    });

    function print_dt(){

        $.ajax({
          data  : {
            "kd_kawasan"    : kd_kawasan,
            "kd_cluster"    : kd_cluster,
            "nm_kawasan"    : nm_kawasan,
            "nm_cluster"    : nm_cluster, 
            "nm_sm"         : nm_sm,    
            "periode_1"     : periode_1,
            "periode_2"     : periode_2,
            "user_id"       : user_id,
            "user_id_bawahan" : user_id_bawahan,
            "nama"          : nama,
            "jml_unit"      : jml_unit, 
            "jml_defect"    : jml_defect,    
            "total_defect"  : total_defect,
            "tot_unit"      : tot_unit,
            "session_user_id" : session_user_id,
            "tahap_bangun"  : tahap_bangun
          },
          url: '{{ url("sqii/lap_defect_c/") }}/print_dt',
          type: "POST",
          dataType: 'html',
          headers : { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
          success: function (data) {
            var w = window.open();
            w.document.title = 'Daftar Member';
            $(w.document.body).html(data);
            $('#v_loading').hide();
          },
          error: function (data) {
              console.log('Error:', data);
          }
      });

        return false;

    }

    function lap_detail_kualitas(kd_kawasan1, kd_cluster1, nm_kawasan1, nm_cluster1, nm_sm1, periode_11, periode_21, user_id1,user_id_bawahan1, nama1, jml_unit1, jml_defect1, total_defect1, tot_unit1, kd_kategori_defect1,tahap_bangun){

      periode11 = periode_11.replace("/", "-");
      periode21 = periode_21.replace("/", "-");
      periode_11 = periode11.replace("/", "-");
      periode_21 = periode21.replace("/", "-");      
    
      window.open('{{ url("sqii/lap_detil_kualitas_c/") }}'+'/'+kd_kawasan1+'/'+kd_cluster1+'/'+nm_kawasan1+'/'+nm_cluster1+'/'+nm_sm1+'/'+periode_11+'/'+periode_21+'/'+user_id1+'/'+user_id_bawahan1+'/'+nama1+'/'+jml_unit1+'/'+jml_defect1+'/'+total_defect1+'/'+tot_unit1+'/'+kd_kategori_defect1+'/'+session_user_id+'/'+tahap_bangun);

      // window.open('http://localhost:8000/sqii/lap_detil_kualitas_c/'+kd_kawasan1+'/'+kd_cluster1+'/'+nm_kawasan1+'/'+nm_cluster1+'/'+nm_sm1+'/'+periode_11+'/'+periode_21+'/'+user_id1+'/'+user_id_bawahan1+'/'+nama1+'/'+jml_unit1+'/'+jml_defect1+'/'+total_defect1+'/'+tot_unit1+'/'+kd_kategori_defect1+'/'+session_user_id); 

      // window.open('http://localhost:8000/sqii/lap_defect_c/'+kd_kawasan+'/'+kd_cluster+'/'+nm_kawasan+'/'+nm_cluster+'/'+nm_sm+'/'+periode_1+'/'+periode_2+'/'+user_id+'/'+user_id_bawahan+'/'+nama+'/'+jml_unit+'/'+jml_defect+'/'+total_defect+'/'+tot_unit+'/'+session_user_id); 


      //  $('#card_detail_kualitas').show();

      //   $('#periode_detail_kualitas').html(periode_1+' sampai '+periode_2);
      //   $('#kawasan_detail_kualitas').html(nm_kawasan);
      //   $('#cluster_detail_kualitas').html(nm_cluster);
      //   $('#sm_detail_kualitas').html(nm_sm);
      //   $('#bi_detail_kualitas').html(nama);
      //   $('#jml_detail_kualitas').html(jml_unit);
      //   $('#tot_detail_kualitas').html(tot_unit);
      //   //window.open('http://localhost:8000/sqii/lap_kinerja_c');

      // $.ajax({
      //   type  : 'POST',
      //   url   : 'lap_kinerja_c/lap_detail_kualitas',
      //   data  : {
      //     "kd_unit"   : kd_unit,
      //     "kd_lokasi" : kd_lokasi,
      //     "kd_kawasan" : kd_kawasan,
      //     "kd_cluster" : kd_cluster, 
      //     "user_id_bawahan" : user_id_bawahan,
      //     "kd_kategori_defect" : kd_kategori_defect,
      //     "periode_1" : periode_1,
      //     "periode_2" : periode_2,
      //     "_token"    : '{{ csrf_token() }}'
      //   },
      //   success : function(msg) {          
      //     var row;
      //     var no=1;

      //     $('#tbl_detail_kualitas tbody').empty();

      //     $("#tbl_detail_kualitas").find("tr:gt(0)").remove(); // CLEAR TABLE
      //     $.each(msg, function(x, y) {
      //       row += ''+
      //         '<tr onclick="lap_formulir_kualitas_bangunan(\''+kd_kawasan+'\',\''+kd_cluster+'\',\''+nm_kawasan+'\',\''+nm_cluster+'\',\''+nm_sm+'\',\''+periode_1+'\',\''+periode_2+'\',\''+y.blok+'\',\''+y.nomor+'\',\''+y.no_formulir+'\',\''+y.nm_kategori_defect+'\',\''+y.deskripsi+'\',\''+y.nm_item_defect+'\',\''+y.status_defect+'\',\''+y.nm_lantai+'\',\''+y.path_foto_denah+'\',\''+y.src_foto_denah+'\',\''+y.path_foto_defect+'\',\''+y.src_foto_defect+'\',\''+y.path_foto_perbaikan+'\',\''+y.src_foto_perbaikan+'\',\''+y.tgl_foto+'\',\''+y.tgl_jt_perbaikan+'\',\''+y.tgl_selesai+'\',\''+y.user_id+'\', \''+y.user_id_bawahan+'\', \''+nama+'\', \''+y.jml_unit+'\', \''+y.jml_defect+'\', \''+y.total_defect+'\')">'+
      //           '<td>'+ no++ +'</td>'+
      //           '<td>'+y.blok+'/'+y.nomor+'</td>'+
      //           '<td style="text-align: center;">'+y.no_formulir+'</td>'+
      //           '<td style="text-align: center;">'+y.nm_kategori_defect+'</td>'+
      //           '<td style="text-align: center;">'+y.deskripsi+'</td>'+
      //           '<td style="text-align: center;">'+y.nm_item_defect+'</td>'+
      //           '<td style="text-align: center;">'+y.status_defect+'</td>'+
      //           '<td style="text-align: center;">'+y.ageing+'</td>'+
      //         '</tr>'+
      //       '';
      //     });

      //     // $('#tbl_kawasan').html(row);
      //     $('#tbl_detail_kualitas').append(row);
      //   },
      //   error   : function(xhr) {
      //     read_error(xhr);
      //   }
      // });
    }   

	</script>
@endsection

@section('content')
    <div class="col-sm-12">
      <div id="card_rekap_defect" class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Rekap Defect</h3>
          <div class="card-tools">
            <a class="navbar-inline">
              <button type="button" class="btn btn-default btn-flat" onclick="print_dt()">
                <i class="fas fa-print"></i>
              </button>
            </a>
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
            <dl class="row">
              <dt class="col-sm-2">Kawasan</dt>
              <dd class="col-sm-4" id="kawasan_rekap_defect"></dd>
              <dt class="col-sm-2">Cluster</dt>
              <dd class="col-sm-4" id="cluster_rekap_defect"></dd>
              <dt class="col-sm-2">Site Manager</dt>
              <dd class="col-sm-4" id="sm_rekap_defect"></dd>
              <dt class="col-sm-2">Building Inspector</dt>
              <dd class="col-sm-4" id="bi_rekap_defect"></dd>
              <dt class="col-sm-2">Jumlah Unit(a)</dt>
              <dd class="col-sm-4" id="jml_unit_rekap_defect"></dd>
              <dt class="col-sm-2">Total Unit</dt>
              <dd class="col-sm-4" id="tot_unit_rekap_defect"></dd>
            </dl>

            <table id="tbl_rekap_defect" class="table table-bordered table-hover" onload="print()">
              <thead style="text-align: center;font-weight: bold;">                  
                <tr>
                  <th rowspan="2" style="width: 10px;vertical-align: middle;">#</th>
                  <th rowspan="2" style="vertical-align: middle;">JENIS PEKERJAAN</th>
                  <th colspan="2">DEFECT SEDANG</th>
                  <th colspan="2">DEFECT BERAT</th>
                  <th colspan="2">TOTAL DEFECT (d)</th>
                </tr>
                <tr>
                  <td>JUMLAH(b)</td>
                  <td>(b)/(a)</td>
                  <td>JUMLAH(c)</td>
                  <td>(c)/(a)</td>
                  <td>JUMLAH</td>
                  <td>(d)/(a)</td>
                </tr>
              </thead>
              <tbody>{!! $tbl['row_tbl'] !!}</tbody>
            </table>
        </div>            
      </div>
          <!-- /.card -->
    </div>
	</div>

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