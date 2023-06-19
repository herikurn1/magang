@extends('layouts.template_print')

@section('js')
  <script type="text/javascript">
    $(function() {
      // print();
    });
  </script>
@endsection
@section('content')
    <div class="col-sm-12">
      <div id="card_rekap_defect" class="invoice p-3 mb-3">
       <!--  <div class="card-header">
          <h3 class="card-title">Formulir P15</h3>
          <small class="float-right">Report Date : {{date("d/m/Y")}}</small> table-bordered
        </div> -->
        <div class="card-body">
            <table id="tbl_rekap_defect" class="table  table-hover" onload="print()">
                <thead style="text-align: center;font-weight: bold;">   
                  <tr>
                    <td colspan="4" rowspan="4" style="border-color:#000000; border-style:double solid solid double; border-width:2px 1px 1px 2px;width:218px;text-align: center;">
                      <!-- <p class="text-center">123<div style="width: 150px;"><img class="img-fluid" src="http://172.16.0.40/portalnew/img/logo_pss.jpg" id="logo" alt="Logo"></div></p> -->
<!-- <div class="row">
  <div style="width: 150px;">
    <div class="color-palette-set">
      <div class="bg-light color-palette"></div>
    </div>
  </div>
</div>                       -->
                    </td>
                    <td colspan="4" rowspan="2" style="border-top:2px double #000000; text-align:center; width:730px"><strong><span style="color:#000000;  font-size:large">IZIN PELAKSANAAN KERJA ( IPK)</span></strong></td>
                    <td style="border-color:#000000; border-style:double solid solid solid; border-width:2px 0px 1px 1px; vertical-align:bottom; width:104px;text-align:left;"><span style="color:#000000;  font-size:small">No</span></td>
                    <td style="border-color:#000000; border-style:double double solid solid; border-width:2px 2px 1px 0px; vertical-align:bottom; width:107px;text-align:left;"><span style="color:#000000;  font-size:small">: P15A &ndash; 2 LT</span></td>
                  </tr>
                  <tr>
                    <td style="border-color:#000000; border-style:solid solid solid solid; border-width:0px 0px 1px 1px; text-align:left;"><span style="color:#000000;  font-size:small">Revisi</span></td>
                    <td style="border-color:#000000; border-style:solid double solid solid; border-width:0px 2px 1px 0px; text-align:left;"><span style="color:#000000;  font-size:small">: 00</span></td>
                  </tr>
                  <tr>
                    <td colspan="4" rowspan="2" style="border-color:#000000; border-style:solid; border-width:1px; text-align:center; width:730px"><strong><span style="color:#000000;  font-size:xx-large">{!! $dt['nm_tahap'] !!}</span></strong></td>
                    <td style="border-color:#000000; border-style:solid solid solid solid; border-width:0px 0px 1px 1px; text-align:left;"><span style="color:#000000;  font-size:small;">Tanggal</span></td>
                    <td style="border-color:#000000; border-style:solid double solid solid; border-width:0px 2px 1px 0px; text-align:left;"><span style="color:#000000;  font-size:small;">: {!! $dt['tgl'] !!}</span></td>
                  </tr>
                  <tr>
                    <td style="border-color:#000000; border-style:solid solid solid solid; border-width:0px 0px 1px 1px; text-align:left;"><span style="color:#000000;  font-size:small;">Halaman</span></td>
                    <td style="border-color:#000000; border-style:solid double solid solid; border-width:0px 2px 1px 0px; text-align:left;"><span style="color:#000000;  font-size:small;">: 1/1</span></td>
                  </tr>
                  <td colspan="4" rowspan="1" style="border-left:2px double #000000; width:218px;text-align:left;"><span style="color:#000000; font-size:small">PROYEK/CLUSTER</span></td>
                    <td style="text-align:left; width:596px">{!! $dt['nm_kawasan'] !!} / {!! $dt['nm_cluster'] !!}</td>
                    <td colspan="3" rowspan="2"><span style="color:#000000; font-size:small">KONTRAKTOR</span></td>
                    <td colspan="2" rowspan="2" style="border-right:2px double #000000; text-align:left;">{!! $dt['nama'] !!}</td>
                  </tr>
                  <tr>
                    <td colspan="4" rowspan="1" style="border-left:2px double #000000; width:218px;text-align:left;"><span style="color:#000000; font-size:small">SITE MANAGER</span></td>
                    <td style="text-align:left; width:596px">{!! $dt['nama_sm'] !!}</td>
                  </tr>
                  <tr>
                    <td colspan="4" rowspan="1" style="border-left:2px double #000000; width:218px; text-align:left;"><span style="color:#000000;  font-size:small">INSPECTOR</span></td>
                    <td style="text-align:left; width:596px">{!! $dt['nama_bi'] !!}</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td style="border-right:2px double #000000;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="10" style="border-color:#000000; border-style:solid double solid double; border-width:1px 2px 1px 2px; width:61px"><span style="color:#000000;  font-size:small">Sebelum pentahapan kerja dimulai, terlebih dahulu harus dilaksanakan test dan pemeriksanaan bersama untuk beberapa hal yang merupakan PRASYARAT disetujuinya izin pentahapan kerja ini, sebagai berikut :</span></td>
                  </tr>
                  <tr>
                    <td colspan="1" rowspan="3" style="border-color:#000000; border-style:solid solid solid double; border-width:0px 1px 1px 2px; text-align:center; vertical-align:middle; width:33px"><strong><span style="color:#000000;  font-size:small">NO</span></strong></td>
                    <td colspan="4" rowspan="3" style="border-color:#000000; border-style:solid; border-width:1px; text-align:center; vertical-align:middle; width:781px"><strong><span style="color:#000000;  font-size:small">URAIAN PEKERJAAN</span></strong></td>
                    <td colspan="3" rowspan="1"></td>
                    <td colspan="2" rowspan="1" style="border-color:#000000; border-style:solid double solid solid; border-width:0px 2px 0px 1px;">TGL / PARAF</td>
                  </tr>
                  <tr>
                    <td colspan="3" rowspan="1" style="border-color:#000000; border-style:solid; border-width:1px; text-align:center"><span style=" font-size:small">{!! $dt['blok'] !!} / {!! $dt['nomor'] !!}</span></td>
                    <td rowspan="2" style="border-color:#000000; border-style:solid; border-width:1px; text-align:center; vertical-align:middle;"><strong><span style="color:#000000;  font-size:small">Kontraktor</span></strong></td>
                    <td rowspan="2" style="border-color:#000000; border-style:solid double solid solid; border-width:1px 2px 1px 1px; text-align:center; vertical-align:middle;"><strong><span style="color:#000000;  font-size:small">Inspektor</span></strong></td>
                  </tr>
                  <tr>
                    <td rowspan="1" style="border-color:#000000; border-style:solid; border-width:1px; text-align:center; width:41px"><strong><span style="font-family:Times New Roman">&radic;</span></strong></td>
                    <td rowspan="1" style="border-color:#000000; border-style:solid; border-width:1px; text-align:center; width:41px"><span style=" font-size:large">O</span></td>
                    <td rowspan="1" style="border-color:#000000; border-style:solid; border-width:1px; text-align:center; width:41px"><span style=" font-size:large">P</span></td>
                  </tr>          
                </thead>
              <tbody>{!! $tbl['row_tbl'] !!}
                  <tr>
                    <td style="border-left:2px double #000000; border-top:1px solid #000000; width:33px">&nbsp;</td>
                    <td colspan="2" rowspan="1" style="border-top:1px solid #000000; width:99px">Notasi :</td>
                    <td style="border-top:1px solid #000000;width:81px">&nbsp;</td>
                    <td style="border-top:1px solid #000000;width:596px">&nbsp;</td>
                    <td colspan="3" style="border-top:1px solid #000000;border-left:1px solid #000000;" rowspan="1">Catatan :</td>
                    <td style="border-top:1px solid #000000;">&nbsp;</td>
                    <td style="border-right:2px double #000000; border-top:1px solid #000000;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td style="border-left:2px double #000000; width:33px">&nbsp;</td>
                    <td style="border-color:#000000; border-style:solid; border-width:1px; text-align:center; width:44px"><strong><span style="color:#000000;  font-size:small">&radic;</span></strong></td>
                    <td colspan="3" rowspan="1" style="width:53px"><span style="color:#000000;  font-size:small">BAIK</span></td>
                    <td style="border-left:1px solid #000000;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td style="border-right:2px double #000000;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td style="border-left:2px double #000000; width:33px">&nbsp;</td>
                    <td style="order-color:#000000; border-style:solid; border-width:1px; text-align:center; width:44px"><span style="color:#000000;  font-size:large">O</span></td>
                    <td colspan="3" rowspan="1" style="width:53px"><span style="color:#000000;  font-size:small">SALAH</span></td>
                    <td style="border-left:1px solid #000000;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td style="border-right:2px double #000000;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td style="border-left:2px double #000000; width:33px">&nbsp;</td>
                    <td style="order-color:#000000; border-style:solid; border-width:1px; text-align:center; width:44px"><span style="color:#000000;  font-size:large">P</span></td>
                    <td colspan="3" rowspan="1" style="width:53px"><span style="color:#000000;  font-size:small">TANGGAL PERBAIKAN SELESAI</span></td>
                    <td style="border-left:1px solid #000000;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td style="border-right:2px double #000000;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td style="border-left:2px double #000000; width:33px">&nbsp;</td>
                    <td style="text-align:center; width:44px"><span style=" font-size:small">*</span></td>
                    <td colspan="3" rowspan="1" style="width:53px"><span style=" font-size:small">CEK OLEH SM</span></td>
                    <td style="border-left:1px solid #000000;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td style="border-right:2px double #000000;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td style="border-left:2px double #000000; border-bottom:1px solid #000000; width:33px">&nbsp;</td>
                    <td style="border-bottom:1px solid #000000; text-align:center; width:44px"><span style="font-size:small">**</span></td>
                    <td colspan="3" rowspan="1" style="border-bottom:1px solid #000000; width:53px"><span style=" font-size:small">CEK OLEH PM</span></td>
                    <td style="border-left:1px solid #000000; border-bottom:1px solid #000000;">&nbsp;</td>
                    <td style="border-bottom:1px solid #000000;">&nbsp;</td>
                    <td style="border-bottom:1px solid #000000;">&nbsp;</td>
                    <td style="border-bottom:1px solid #000000;">&nbsp;</td>
                    <td style="border-right:2px double #000000;border-bottom:1px solid #000000;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td style="border-left:2px double #000000; width:33px">&nbsp;</td>
                    <td style="width:44px">&nbsp;</td>
                    <td style="width:53px">&nbsp;</td>
                    <td style="width:81px">&nbsp;</td>
                    <td style="width:596px">&nbsp;</td>
                    <td style="border-left:1px solid #000000;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td style="border-right:2px double #000000;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td style="border-left:2px double #000000; width:33px">&nbsp;</td>
                    <td colspan="2" rowspan="1" style="width:44px"><span style=" font-size:small">Disclaimer :</span></td>
                    <td colspan="2" rowspan="1" style="width:86px"><span style="color:#000000;">Cetak by system SQII, tidak memerlukan tanda tangan</span></td>
                    <td style="border-left:1px solid #000000;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td style="border-right:2px double #000000;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td style="border-left:2px double #000000; border-bottom:2px double #000000; width:33px">&nbsp;</td>
                    <td style="border-bottom:2px double #000000; width:44px">&nbsp;</td>
                    <td style="border-bottom:2px double #000000; width:53px">&nbsp;</td>
                    <td style="border-bottom:2px double #000000; width:81px">&nbsp;</td>
                    <td style="border-bottom:2px double #000000; width:596px">&nbsp;</td>
                    <td style="border-left:1px solid #000000; border-bottom:2px double #000000;">&nbsp;</td>
                    <td style="border-bottom:2px double #000000;">&nbsp;</td>
                    <td style="border-bottom:2px double #000000;">&nbsp;</td>
                    <td style="border-bottom:2px double #000000;">&nbsp;</td>
                    <td style="border-bottom:2px double #000000;border-right:2px double #000000;">&nbsp;</td>
                  </tr>
              </tbody>
            </table>
        </div>            
      </div>
    </div>
  </div>  
@endsection
