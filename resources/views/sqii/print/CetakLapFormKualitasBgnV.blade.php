@extends('layouts.template_print')

@section('js')
  <script type="text/javascript">
(function() {
   // your page initialization code here
   // the DOM will be available here
   // alert('aloo');
})();
  </script>
@endsection
@section('content')
    <div class="col-sm-12">
      <div id="card_rekap_defect" class="invoice p-3 mb-3">
        <div class="card-header">
          <h3 class="card-title">Laporan Formulir Kualitas Bangunan  </h3>
          <small class="float-right">Report Date : {{date("d/m/Y")}}</small>
        </div>
        <div class="card-body">
            <dl class="row">
              <dt class="col-sm-2">No. Formulir</dt>
              <dd class="col-sm-4" id="nomor_formulir_kualitas">{{ $dt['no_formulir'] }}</dd>
                <dt class="col-sm-2">QC Field</dt>
                <dd class="col-sm-4" id="qc_formulir_kualitas">{{ $dt['nama_qc'] }}</dd>
              <dt class="col-sm-2">Kawasan</dt>
              <dd class="col-sm-4" id="kawasan_formulir_kualitas">{{ $dt['nm_kawasan'] }}</dd>
                <dt class="col-sm-2">Jenis Pekerjaan</dt>
                <dd class="col-sm-4" id="jns_pekerjaan_formulir_kualitas">{{ $dt['nm_kategori_defect'] }}</dd>
              <dt class="col-sm-2">Cluster</dt>
              <dd class="col-sm-4" id="cluster_formulir_kualitas">{{ $dt['nm_cluster'] }}</dd>
                <dt class="col-sm-2">Item Defect</dt>
                <dd class="col-sm-4" id="itm_defect_formulir_kualitas">{{ $dt['deskripsi'] }}</dd>
              <dt class="col-sm-2">Blok/Unit</dt>
              <dd class="col-sm-4" id="blok_unit_formulir_kualitas">{{ $dt['blok'] }}/{{ $dt['nomor'] }}</dd>
                <dt class="col-sm-2">Deskripsi Defect</dt>
                <dd class="col-sm-4"  id="des_defect_formulir_kualitas">{{ $dt['nm_item_defect'] }}</dd>
              <dt class="col-sm-2">Lokasi Lantai</dt>
              <dd class="col-sm-4" id="lantai_formulir_kualitas">{{ $dt['nm_lantai'] }}</dd>
                <dt class="col-sm-2">Kategori Defect</dt>
                <dd class="col-sm-4" id="kategori_formulir_kualitas">{{ $dt['status_defect'] }}</dd>
              <dt class="col-sm-2">Kontraktor</dt>
              <dd class="col-sm-4" id="kontraktor_formulir_kualitas">{{ $dt['nama_ktt'] }}</dd>
                <dt class="col-sm-2">Tanggal Temuan</dt>
                <dd class="col-sm-4" id="tgl_temuan_formulir_kualitas">{{ $dt['tgl_foto'] }}</dd>
              <dt class="col-sm-2">Site Manager</dt>
              <dd class="col-sm-4" id="sm_formulir_kualitas">{{ $dt['nm_sm'] }}</dd>
                <dt class="col-sm-2">Target Perbaikan</dt>
                <dd class="col-sm-4" id="tgl_perbaikan_formulir_kualitas">{{ $dt['tgl_jatuh_tempo_perbaikan'] }}</dd>
              <dt class="col-sm-2">Building Inspector</dt>
              <dd class="col-sm-4" id="bi_formulir_kualitas">{{ $dt['nama'] }}</dd>
                <dt class="col-sm-2">Selesai Perbaikan</dt>
                <dd class="col-sm-4" id="tgl_selesai_formulir_kualitas">{{ $dt['tgl_selesai'] }}</dd>
            </dl>

            <div class="tab-custom-content">
              <p class="lead mb-0" style="text-align: center;">FOTO PENUGASAN</p>
              <br>
              <div class="row">
                <div class="col-md-3">
                  <div class="color-palette-set">
                    <div class="bg-warning color-palette text-center"><strong>Denah</strong></div>
                    <div class="bg-light color-palette"><img class="img-fluid" src="https://sqii.gadingemerald.com/public/{{ $dt['path_foto_denah'] }}/{{ $dt['src_foto_denah'] }}" id="formulir_denah" alt="Photo Denah"></div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="color-palette-set">
                    <div class="bg-warning color-palette text-center"><strong>Foto Temuan</strong></div>
                    <div class="bg-light color-palette"><img class="img-fluid" src="https://sqii.gadingemerald.com/public/{{ $dt['path_foto_defect'] }}/{{ $dt['src_foto_defect'] }}" id="formulir_temuan" alt="Photo defect"></div>
                  </div>
                </div>
                <div class="col-md-3">
                 <div class="color-palette-set">
                    <div class="bg-warning color-palette text-center"><strong>Foto Perbaikan</strong></div>
                    <div class="bg-light color-palette"><img class="img-fluid" src="https://sqii.gadingemerald.com/public/{{ $dt['path_foto_perbaikan'] }}/{{ $dt['src_foto_perbaikan'] }}" id="formulir_perbaikan" alt="Photo perbaikan"></div>
                  </div>
                </div>                                      
              </div>               
            </div>
        </div>            
      </div>
    </div>
  </div>  
@endsection
