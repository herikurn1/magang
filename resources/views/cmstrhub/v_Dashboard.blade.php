@extends('layouts.template')

@section('css')
<!-- DataTables --> 
<link rel="stylesheet" href="{{ url('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

<style>
  .bg-A{
    background-color: #ff595e;
    color: #FFFFFF;
  }
  .bg-B{
    background-color: #ffca3a;
    color: #FFFFFF;
  }
  .bg-C{
    background-color: #8ac926;
    color: #FFFFFF;
  }
  .bg-D{
    background-color: #1982c4;
    color: #FFFFFF;
  }
  .bg-E{
    background-color: #83c5be;
  }
  .bg-F{
    background-color: #219ebc;
  }
  .bg-G{
    background-color: #fb8500;
  }
  .bg-H{
    background-color: #dda15e;
  }

  .bg-saldo{
    background-color: #59a5d8;
    color: #FFFFFF;
  }
</style>
@endsection

@section('js')
<!-- DataTables -->
<script src="{{ url('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ url('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ url('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<!-- Autonumeric -->
<script src="{{ url('bower_components/autoNumeric/autoNumeric.js') }}"></script>
<!-- Chart --> 
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

	<script type="text/javascript">
		var kd_unit 		  = "{{ session('kd_unit') }}";
		var kd_lokasi 	  = "{{ session('kd_lokasi') }}";

    $(document).ajaxStart(function() {
        $("#loading").show();
        }).ajaxStop(function() {
        $("#loading").hide();
    });

    $(function() {
          // get_data_grafik();
          // get_grafik_keluhan();
      $.ajax({
        type 	  : 'GET',
        url		  : 'dashboard/data-wait',
        // data    : {
        //   "_token" 		: '{{ csrf_token() }}',
        // },
        success : function(msg){
          // $.each(msg, function(x, y){
          //   $('#ttl_progress').html(y.TOTAL_DATA);
          // })
        }
      })
      $.ajax({
        type 	  : 'POST',
        url		  : 'dashboard/wait',
        data    : {
          "_token" 		: '{{ csrf_token() }}',
        },
        success : function(msg){
          $.each(msg, function(x, y){
            $('#ttl_wait').html(y.TOTAL_DATA);
          })
        }
      })
      $.ajax({
        type 	  : 'POST',
        url		  : 'dashboard/progress',
        data    : {
          "_token" 		: '{{ csrf_token() }}',
        },
        success : function(msg){
          $.each(msg, function(x, y){
            $('#ttl_progress').html(y.TOTAL_DATA);
          })
        }
      })
      $.ajax({
        type 	  : 'POST',
        url		  : 'dashboard/done',
        data    : {
          "_token" 		: '{{ csrf_token() }}',
        },
        success : function(msg){
          $.each(msg, function(x, y){
            $('#ttl_done').html(y.TOTAL_DATA);
          })
        }
      })
      $.ajax({
        type 	  : 'POST',
        url		  : 'dashboard/cancel',
        data    : {
          "_token" 		: '{{ csrf_token() }}',
        },
        success : function(msg){
          $.each(msg, function(x, y){
            $('#ttl_cancel').html(y.TOTAL_DATA);
          })
        }
      })
    });
    
    // function get_data_grafik(){
    //   $.ajax({
    //     type 	  : 'POST',
    //     url		  : 'dashboard/get_data_grafik',
    //     data 	  : {"kd_unit" : kd_unit},
    //     headers	: { 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
    //     success : function(msg){
    //       var data = JSON.parse(msg);

    //       var bulan1 		= data.bulan1;
    //       var tahun1 		= data.tahun1;
    //       var bulan2 		= data.bulan2;
    //       var tahun2 		= data.tahun2;
    //       var nilai1 		= data.nilai1;
		// 			var nilai2 		= data.nilai2;
    //       var thn1      = Array.from(new Set(tahun1));
    //       var thn2      = Array.from(new Set(tahun2));

    //       show_grafik(kd_unit,nm_unit,bulan1,thn1,bulan2,thn2,nilai1,nilai2);
    //     },
    //     error		: function(xhr, status, error) {
		// 			_errorread(error);
		// 		}
    //   });
    //   return false;
    // }

    // function show_grafik(kd_unit,nm_unit,bulan1,thn1,bulan2,thn2,nilai1,nilai2){		
    //     $('#container').highcharts({
    //       title: {
    //           text: 'Grafik Pengajuan Diterima (' + kd_unit + '-' + nm_unit + ')',
    //           x: -20 //center
    //       },
    //       subtitle: {
    //           text: 'as of ' + strDateTime,
    //           x   : -20
    //       },
    //       xAxis: {
    //           categories: bulan1
    //       },
    //       yAxis: {
    //           title: {
    //               text: 'This Month - Last Month'
    //           },
    //           plotLines: [{
    //               value: 0,
    //               width: 1,
    //               color: '#808080'
    //           }]
    //       },
    //       tooltip: {
    //           valueSuffix: ''
    //       },
    //       legend: {
    //           layout        : 'vertical',
    //           align         : 'right',
    //           verticalAlign : 'middle',
    //           borderWidth   : 0
    //       },
    //       plotOptions: {
    //         line: {
    //           dataLabels: {
    //             enabled: true
    //           }
    //         }
    //       },
    //       series: [{
    //           name: thn1,
    //           data: nilai1
    //       }, {
    //           name: thn2,
    //           data: nilai2
    //       }],
    //       credits: {
    //         enabled: false
    //       }
    //   });
    // }

    // function get_grafik_keluhan(){
    //   $.ajax({
    //     type 	  : 'POST',
    //     url		  : 'dashboard/get_grafik_keluhan',
    //     data 	  : {"kd_unit" : kd_unit},
    //     headers	: { 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
    //     success : function(msg){
    //       var data  = JSON.parse(msg);

    //       var bulan	= data.bulan;
    //       var tahun	= data.tahun;
    //       var nilai = data.nilai;

    //       var thn   = Array.from(new Set(tahun));

    //       show_grafik_keluhan(kd_unit,nm_unit,bulan,thn,nilai);
    //     },
    //     error		: function(xhr, status, error) {
		// 			_errorread(error);
		// 		}
    //   });
    //   return false;
    // }

    // function show_grafik_keluhan(kd_unit,nm_unit,bulan,thn,nilai){		
    //   $('#tab_keluhan').highcharts({
    //     chart	: {
    //       type: 'column'
    //     },
    //     title	: {
    //       text: '' + kd_unit + '-' + nm_unit + '',
    //     },
    //     subtitle: {
    //       text: ''
    //     },
    //     xAxis	: {
    //       categories: bulan
    //     },
    //     yAxis		: {
    //       min		: 0,
    //       title	: {
    //         text: ''
    //       }
    //     },
    //     tooltip	: {
    //       backgroundColor : '#FCFFC5',
    //       headerFormat	  : '<span style="font-size:10px">{point.key}</span><table>',
    //       pointFormat		  : '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
    //                         '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
    //       footerFormat	  : '</table>',
    //       shared			    : true,
    //       useHTML			    : true
    //     },
    //     plotOptions	: {
    //       column	: {
    //         pointPadding	: 0.2,
    //         borderWidth		: 0
    //       }
    //     },
    //     series: [{
    //       name: thn,
    //       data: nilai
    //     }],
    //     credits: {
    //       enabled: false
    //     }
    //   });
    // }

    const labels = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
    ];

    const data = {
        labels: labels,
        datasets: [{
            label: 'My First dataset',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: [0, 10, 5, 2, 20, 30, 45],
        }]
    };

    const config = {
        type: 'line',
        data: data,
        options: {}
    };

    const myChart = new Chart(
        document.getElementById('chart_pengajuan'),
        config
    );

    //Bar Chart
    const labels1 = [
        'Pengajuan Diterima',
        'Selesai'
    ];

    const data1 = {
        labels: labels1,
        datasets: [{
            label: '',
            data: [65, 62],
            backgroundColor: [
            'rgba(155, 197, 61, 0.2)',
            'rgba(255, 85, 84, 0.2)'
            ],
            borderColor: [
            'rgb(155, 197, 61)',
            'rgb(255, 85, 84)'
            ],
            borderWidth: 1
        }]
    };

    const config1 = {
        type: 'bar',
        data: data1,
        options: {
            scales: {
            y: {
                beginAtZero: true
            }
            }
        },
    };

    const myChart1 = new Chart(
        chartEl = document.getElementById('chart_selesai'),
        config1
    );
	</script>
@endsection

@section('content')
<div class="row">
  <div class="col-sm-12">
    <div class="card card-primary card-outline">
            <div class="card-header">
                <nav class="navbar justify-content-between">
                <a class="form-brand">
                    <h3 class="card-title">Dashboard</h3>
                </a>           
                </nav>
            </div>

            <div class="card-body">
              <!-- Small boxes (Stat box) -->
              <div class="row" id="panel_dept">
                  <div class="col-md-3">
                    <div class="small-box bg-D">
                      <div class="inner">
                      <h3 id="ttl_wait"></h3>

                      <p id="jml_nominal_'+ val.KD_NOMINAL +'">Menunggu Konfirmasi</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-spinner"></i>
                      </div>
                      <a href="dashboard/menunggu-konfirmasi" target="_blank" class="small-box-footer">Lihat Detail <i class="fas fa-chevron-circle-right"></i></a>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="small-box bg-B">
                      <div class="inner">
                      <h3 id="ttl_progress"></h3>

                      <p id="jml_nominal_'+ val.KD_NOMINAL +'">Sedang Diproses</p>
                      </div>
                      <div class="icon">
                          <i class="fas fa-cogs"></i>
                      </div>
                      <a href="dashboard/sedang-diproses" target="_blank" class="small-box-footer">Lihat Detail <i class="fas fa-chevron-circle-right"></i></a>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="small-box bg-C">
                      <div class="inner">
                      <h3 id="ttl_done"></h3>

                      <p id="jml_nominal_'+ val.KD_NOMINAL +'">Selesai</p>
                      </div>
                      <div class="icon">
                          <i class="fas fa-check"></i>
                      </div>
                      <a href="dashboard/selesai" target="_blank" class="small-box-footer">Lihat Detail <i class="fas fa-chevron-circle-right"></i></a>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="small-box bg-A">
                      <div class="inner">
                      <h3 id="ttl_cancel"></h3>

                      <p id="jml_nominal_'+ val.KD_NOMINAL +'">Dibatalkan</p>
                      </div>
                      <div class="icon">
                          <i class="fas fa-ban"></i>
                      </div>
                      <a href="dashboard/dibatalkan" target="_blank" class="small-box-footer">Lihat Detail <i class="fas fa-chevron-circle-right"></i></a>
                    </div>
                  </div>
              </div>

              <!-- Chart Box Status -->
         
              <!-- End Chart Box Status -->

             
            
            </div>  

            <!-- <div class="card-footer">
                Footer
            </div> -->

        <div id="v_loading" class="overlay" style="display: none;">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
        </div>
    </div>
</div>
@endsection