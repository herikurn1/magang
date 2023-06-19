@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit 		            = "{{ session('kd_unit') }}";
		var kd_lokasi 	            = "{{ session('kd_lokasi') }}";	
    var kd_kawasan              = "{{ $dt['kd_kawasan'] }}";
    var kd_cluster              = "{{ $dt['kd_cluster'] }}";
    var nm_kawasan              = "{{ $dt['nm_kawasan'] }}";
    var nm_cluster              = "{{ $dt['nm_cluster'] }}";
    var nm_sm                   = "{{ $dt['nm_sm'] }}";
    var periode_1               = "{{ $dt['periode_1'] }}";
    var periode_2               = "{{ $dt['periode_2'] }}";
    var blok                    = "{{ $dt['blok'] }}";
    var nomor                   = "{{ $dt['nomor'] }}";
    var no_formulir             = "{{ $dt['no_formulir'] }}";
    var nm_kategori_defect      = "{{ $dt['nm_kategori_defect'] }}";
    var deskripsi               = "{{ $dt['deskripsi'] }}";
    var nm_item_defect          = "{{ $dt['nm_item_defect'] }}";
    var status_defect           = "{{ $dt['status_defect'] }}";
    var nm_lantai               = "{{ $dt['nm_lantai'] }}";
    var path_foto_denah         = "{{ $dt['path_foto_denah'] }}";
    path_foto_denah     = path_foto_denah.replace("-", "/");
    path_foto_denah     = path_foto_denah.replace("-", "/");
    path_foto_denah     = path_foto_denah.replace("-", "/");  

    var src_foto_denah          = "{{ $dt['src_foto_denah'] }}";
    var path_foto_defect        = "{{ $dt['path_foto_defect'] }}";
    path_foto_defect     = path_foto_defect.replace("-", "/");
    path_foto_defect     = path_foto_defect.replace("-", "/");
    path_foto_defect     = path_foto_defect.replace("-", "/");  

    var src_foto_defect         = "{{ $dt['src_foto_defect'] }}";
    var path_foto_perbaikan     = "{{ $dt['path_foto_perbaikan'] }}";
    path_foto_perbaikan     = path_foto_perbaikan.replace("-", "/");
    path_foto_perbaikan     = path_foto_perbaikan.replace("-", "/");
    path_foto_perbaikan     = path_foto_perbaikan.replace("-", "/");  

    var src_foto_perbaikan      = "{{ $dt['src_foto_perbaikan'] }}";
    var tgl_foto                = "{{ $dt['tgl_foto'] }}";
    var tgl_jatuh_tempo_perbaikan   = "{{ $dt['tgl_jatuh_tempo_perbaikan'] }}";
    var tgl_selesai             = "{{ $dt['tgl_selesai'] }}";
    var nama                    = "{{ $dt['nama'] }}";
    var nama_ktt                = "{{ $dt['nama_ktt'] }}";
    var nama_qc                 = "{{ $dt['nama_qc'] }}";
    var user_id_bawahan         = "{{ $dt['user_id_bawahan'] }}";
    var kd_kategori_defect      = "{{ $dt['kd_kategori_defect'] }}";
    var tahap_bangun            = "{{ $dt['tahap_bangun'] }}";

    $(function() {
      $('#nomor_formulir_kualitas').html(no_formulir);
      $('#qc_formulir_kualitas').html(nama_qc);
      $('#kawasan_formulir_kualitas').html(nm_kawasan);
      $('#jns_pekerjaan_formulir_kualitas').html(nm_kategori_defect);
      $('#cluster_formulir_kualitas').html(nm_cluster);
      $('#itm_defect_formulir_kualitas').html(deskripsi);
      $('#blok_unit_formulir_kualitas').html(blok+'/'+nomor);
      $('#des_defect_formulir_kualitas').html(nm_item_defect);
      $('#lantai_formulir_kualitas').html(nm_lantai);
      $('#kategori_formulir_kualitas').html(status_defect);
      $('#kontraktor_formulir_kualitas').html(nama_ktt);
      $('#tgl_temuan_formulir_kualitas').html(tgl_foto);
      $('#sm_formulir_kualitas').html(nm_sm);
      $('#tgl_perbaikan_formulir_kualitas').html(tgl_jatuh_tempo_perbaikan);
      $('#bi_formulir_kualitas').html(nama);
      $('#tgl_selesai_formulir_kualitas').html(tgl_selesai);
      $('#formulir_denah').attr('src','https://sqii.gadingemerald.com/public/'+path_foto_denah+''+src_foto_denah);
      $('#formulir_temuan').attr('src','https://sqii.gadingemerald.com/public/'+path_foto_defect+''+src_foto_defect);
      $('#formulir_perbaikan').attr('src','https://sqii.gadingemerald.com/public/'+path_foto_perbaikan+''+src_foto_perbaikan);   
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
            "user_id_bawahan" : user_id_bawahan,
            "nama"          : nama,
            "kd_kategori_defect" : kd_kategori_defect,
            "no_formulir"   : no_formulir,
            "tahap_bangun"   : tahap_bangun
          },
          url: '{{ url("sqii/lap_form_kualitas_bgn_c/") }}/print_dt',
          type: "POST",
          dataType: 'html',
          headers : { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
          success: function (data) {
            var w = window.open();
            w.document.title = 'Daftar Member';
            $(w.document.body).html(data);
            // $('#v_loading').hide();
          },
          error: function (data) {
              console.log('Error:', data);
          }
      });
        return false;
    }
	</script>
@endsection

@section('content')
    <div class="col-sm-12">
          <div id="card_formulir_kualitas" class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">Laporan Formulir Kualitas Bangunan</h3>
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
                  <dt class="col-sm-2">No. Formulir</dt>
                  <dd class="col-sm-4" id="nomor_formulir_kualitas"></dd>
                    <dt class="col-sm-2">QC Field</dt>
                    <dd class="col-sm-4" id="qc_formulir_kualitas"></dd>
                  <dt class="col-sm-2">Kawasan</dt>
                  <dd class="col-sm-4" id="kawasan_formulir_kualitas"></dd>
                    <dt class="col-sm-2">Jenis Pekerjaan</dt>
                    <dd class="col-sm-4" id="jns_pekerjaan_formulir_kualitas"></dd>
                  <dt class="col-sm-2">Cluster</dt>
                  <dd class="col-sm-4" id="cluster_formulir_kualitas"></dd>
                    <dt class="col-sm-2">Item Defect</dt>
                    <dd class="col-sm-4" id="itm_defect_formulir_kualitas"></dd>
                  <dt class="col-sm-2">Blok/Unit</dt>
                  <dd class="col-sm-4" id="blok_unit_formulir_kualitas"></dd>
                    <dt class="col-sm-2">Deskripsi Defect</dt>
                    <dd class="col-sm-4"  id="des_defect_formulir_kualitas"></dd>
                  <dt class="col-sm-2">Lokasi Lantai</dt>
                  <dd class="col-sm-4" id="lantai_formulir_kualitas"></dd>
                    <dt class="col-sm-2">Kategori Defect</dt>
                    <dd class="col-sm-4" id="kategori_formulir_kualitas"></dd>
                  <dt class="col-sm-2">Kontraktor</dt>
                  <dd class="col-sm-4" id="kontraktor_formulir_kualitas"></dd>
                    <dt class="col-sm-2">Tanggal Temuan</dt>
                    <dd class="col-sm-4" id="tgl_temuan_formulir_kualitas"></dd>
                  <dt class="col-sm-2">Site Manager</dt>
                  <dd class="col-sm-4" id="sm_formulir_kualitas"></dd>
                    <dt class="col-sm-2">Target Perbaikan</dt>
                    <dd class="col-sm-4" id="tgl_perbaikan_formulir_kualitas"></dd>
                  <dt class="col-sm-2">Building Inspector</dt>
                  <dd class="col-sm-4" id="bi_formulir_kualitas">A</dd>
                    <dt class="col-sm-2">Selesai Perbaikan</dt>
                    <dd class="col-sm-4" id="tgl_selesai_formulir_kualitas"></dd>
                </dl>

                <div class="tab-custom-content">
                  <p class="lead mb-0" style="text-align: center;">FOTO PENUGASAN</p>
                  <br>
                  <div class="row">
                    <!-- /.col -->
                    <div class="col-md-3">
                      <div class="color-palette-set">
                        <div class="bg-warning color-palette text-center"><strong>Denah</strong></div>
                        <div class="bg-light color-palette"><img class="img-fluid" src="../../adminlte/dist/img/photo1.png" id="formulir_denah" alt="Photo Denah"></div>
                      </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3">
                      <div class="color-palette-set">
                        <div class="bg-warning color-palette text-center"><strong>Foto Temuan</strong></div>
                        <div class="bg-light color-palette"><img class="img-fluid" src="../../adminlte/dist/img/photo1.png" id="formulir_temuan" alt="Photo defect"></div>
                      </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3">
                     <div class="color-palette-set">
                        <div class="bg-warning color-palette text-center"><strong>Foto Perbaikan</strong></div>
                        <div class="bg-light color-palette"><img class="img-fluid" src="../../adminlte/dist/img/photo1.png" id="formulir_perbaikan" alt="Photo perbaikan"></div>
                      </div>
                    </div>
                    <!-- /.col -->                                        
                  </div>               
                </div>
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