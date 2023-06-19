@extends('layouts.template')

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript">
		var kd_unit 		= "{{ session('kd_unit') }}";
		var kd_lokasi 	= "{{ session('kd_lokasi') }}";	
    var kd_kawasan   = "{{ $dt['kd_kawasan'] }}";  
    var kd_cluster   = "{{ $dt['kd_cluster'] }}"; 
    var nm_kawasan   = "{{ $dt['nm_kawasan'] }}"; 
    var nm_cluster   = "{{ $dt['nm_cluster'] }}"; 
    var nik_petugas   = "{{ $dt['nik_petugas'] }}"; 
    var nm_sm   = "{{ $dt['nm_sm'] }}"; 
    var periode_1   = "{{ $dt['periode_1'] }}";
    var periode_2   = "{{ $dt['periode_2'] }}"; 	

    $(function() {
      periode1 = periode_1.replace("-", "/");
      periode2 = periode_2.replace("-", "/");
      periode_1 = periode1.replace("-", "/");
      periode_2 = periode2.replace("-", "/");  
      //alert(session_user_id);

      $('#kawasan_grafik').html(nm_kawasan);
      $('#cluster_grafik').html(nm_cluster);
      $('#sm_grafik').html(nm_sm);
      $('#periode_grafik').html(periode_1+' sampai '+periode_2);      

      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart1);
      google.charts.setOnLoadCallback(drawChart2);
      google.charts.setOnLoadCallback(drawChart3);

        function drawChart1() {
         
        var data = google.visualization.arrayToDataTable([
            ['Product Name', 'Sales', 'Quantity'],

                @php
                foreach($products1 as $product) {
                    echo "['".$product["nm_item_defect"]."', ".$product["jml"].", ".$product["kd_kategori_defect"]."],";
                }
                @endphp
        ]);

          var options = {
            title: @php echo "'".$nm_kategori1."'"; @endphp,
            is3D: false,
            fontSize: 11,
            titleTextStyle: { fontSize: 20,
                              bold: true },
          };

          var chart = new google.visualization.PieChart(document.getElementById('piechart'));
          chart.draw(data, options);
        }

        function drawChart2() {
         
        var data = google.visualization.arrayToDataTable([
            ['Product Name', 'Sales', 'Quantity'],

                @php
                foreach($products2 as $product) {
                    echo "['".$product["nm_item_defect"]."', ".$product["jml"].", ".$product["kd_kategori_defect"]."],";
                }
                @endphp
        ]);

          var options = {
            title: @php echo "'".$nm_kategori2."'"; @endphp,
            is3D: false,
            fontSize: 11,
            titleTextStyle: { fontSize: 20,
                              bold: true },
          };

          var chart = new google.visualization.PieChart(document.getElementById('piechart2'));
          chart.draw(data, options);
        }

        function drawChart3() {
         
        var data = google.visualization.arrayToDataTable([
            ['Product Name', 'Sales', 'Quantity'],

                @php
                foreach($products3 as $product) {
                    echo "['".$product["nm_item_defect"]."', ".$product["jml"].", ".$product["kd_kategori_defect"]."],";
                }
                @endphp
        ]);

          var options = {
            title: @php echo "'".$nm_kategori3."'"; @endphp,
            is3D: false,
            fontSize: 11,
            titleTextStyle: { fontSize: 20,
                              bold: true },
          };

          var chart = new google.visualization.PieChart(document.getElementById('piechart3'));
          chart.draw(data, options);
        }                
    });
 
    function print_dt(){

        $.ajax({
          data  : {
            "kd_kawasan"    : kd_kawasan,
            "kd_cluster"    : kd_cluster,
            "nm_cluster"    : nm_cluster,
            "periode_1"     : periode_1,
            "periode_2"     : periode_2,
            "user_id_bawahan" : user_id_bawahan,
            "nama"          : nama,
            "jml_unit"      : jml_unit, 
            "tot_unit"      : tot_unit,
          },
          url: '{{ url("sqii/lap_grafic_defect_c/") }}/print_dt',
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
	</script>
@endsection

@section('content')
	<div class="row">  
        <div class="col-sm-12">
          <div id="card_grafik_defect" class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">Laporan Grafik Item Defect</h3>
              <div class="card-tools">
                <!-- <a class="navbar-inline">
                  <button type="button" class="btn btn-default btn-flat" onclick="print_dt()">
                    <i class="fas fa-print"></i>
                  </button>
                </a> -->
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
              </div>
            </div>
            <div class="card-body">
              <dl class="row">
                <dt class="col-sm-2">Kawasan</dt>
                <dd class="col-sm-10" id="kawasan_grafik"></dd>
                <dt class="col-sm-2">Cluster</dt>
                <dd class="col-sm-10" id="cluster_grafik"></dd>
                <dt class="col-sm-2">Site Manager</dt>
                <dd class="col-sm-10" id="sm_grafik"></dd>
                <dt class="col-sm-2">Periode</dt>
                <dd class="col-sm-10" id="periode_grafik"></dd>
              </dl>
              <div class="row">
                <div class="col-md-6">
                  <div id="piechart" style="width: 600px; height: 500px;"></div>
                </div>
                <div class="col-md-6">
                  <div id="piechart2" style="width: 600px; height: 500px;"></div>
                </div>
              </div> 
              <div class="row">
                <div class="col-md-6">
                  <div id="piechart3" style="width: 600px; height: 500px;"></div>
                </div>                
              </div>
            </div>            
          </div>
              
        </div>                              
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