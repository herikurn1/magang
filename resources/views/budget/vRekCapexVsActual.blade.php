@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit_default 	= "{{ session('kd_unit') }}";
		var nm_unit_default 	= "{{ session('nm_unit') }}";
		var kd_lokasi_default 	= "{{ session('kd_lokasi') }}";

		var no 				= 1;
		var thn_anggaran 	= {!! $dt['thn_anggaran'] !!};
		var data_user 		= {!! $dt['data_user'] !!};

		$(function() {
			$('#kd_unit').val(kd_unit_default);
			$('#nm_unit').val(nm_unit_default);

			$('#kd_departemen').val(data_user[0]['kd_departemen']);
			$('#nm_departemen').val(data_user[0]['nm_departemen']);

			$.each(thn_anggaran, function(x, y) {
				var o = new Option(y.thn_anggaran, y.thn_anggaran);
				$(o).html(y.thn_anggaran);
				$('#thn_anggaran').append(o);
			});
		});

		function check_role_budget(func) {
			$.ajax({
				type 	: 'POST',
				url 	: 'rek_capex_vs_actual/check_role_budget',
				data 	: {
					"func"		: func,
					"_token"	: '{{ csrf_token() }}'
				},
				success : function(msg) {
					if(msg == "Y"){
						window[func]();
					}
				},
				error 	: function(xhr) {
					read_error(xhr);
				}
			});
		}

		function search_unit_budget() {
			var keyword 			= $('#sys-modal-default-keyword').val();

			$('#v_loading').show();
			$.ajax({
				type 	: 'POST',
				url 	: 'rek_capex_vs_actual/search_unit_budget',
				data 	: {
					"_token"	: '{{ csrf_token() }}',
					"keyword"	: keyword
				},
				success : function(msg) {
					$('#v_loading').hide();

					var tbl = ''+
						'<div class="row">'+
				          	'<div class="col-12">'+
				            	'<div class="card">'+
				              		'<div class="card-header">'+
				                		'<h3 class="card-title">Unit</h3>'+
				              		'</div>'+

				              		'<div class="card-body table-responsive p-0" style="height: 300px;">'+
										'<table class="table table-sm table-head-fixed text-nowrap">'+
											'<thead>'+
												'<tr>'+
													'<th>Kode</th>'+
													'<th>Nama Unit</th>'+
												'</tr>'+
											'</thead>'+
											'<tbody>'+
					'';

					$.each(msg, function(x, y){
						tbl += ''+
												'<tr>'+
													'<td>'+y.kd_unit+'</td>'+
													'<td>'+y.nm_unit+'</td>'+
													'<td>'+
														'<button type="button" class="btn btn-info btn-sm" onclick="search_unit_budget_set( \''+y.kd_unit+'\', \''+y.nm_unit+'\')">Pilih</button>'+
													'</td>'+
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
					'';

					$('#sys-modal-default-body').html(tbl);
					$('#sys-modal-default-btn-search').attr('onclick', 'search_unit_budget()');
					$('#sys-modal-default').modal("show");
				},
				error 	: function(xhr) {
					$('#v_loading').hide();
					read_error(xhr);
				}
			});

			return false;
		}

		function search_unit_budget_set(kd_unit, nm_unit) {
			$('#kd_unit').val(kd_unit);
			$('#nm_unit').val(nm_unit);

			$('#sys-modal-default-keyword').val('');
			$('#sys-modal-default').modal("hide");
		}

		function clear_unit_budget() {
			$('#kd_unit').val('');
			$('#nm_unit').val('All');
		}

		function search_detail() {
			var data_all = $('#frm_search').serializeArray();

			$('#v_loading').show();

			// var tipe_rpt = $('#tab').prop('checked');

			// if (tipe_rpt == true) {
			$.ajax({
				type	: 'POST',
				url 	: 'rek_capex_vs_actual/search_detail',
				data 	: data_all,
				success	: function (msg) {
					$('#v_loading').hide();

					var tbl = get_header();
					$('#card_detail').html(tbl);

					$('#span_thn_anggaran').html(msg['thn_anggaran']);
					$('#span_nm_unit').html(msg['nm_unit']);

					var i = 1;
					$.each(msg[0]['detail'], function(x, y){
						var row = '<tr>'+
							'<td>'+y.BUDGET+'</td>'+
							'<td>'+y.kd_unit+'</td>'+
							'<td align="right">'+number_format_id(y.CAPEX_CURR)+'</td>'+
							'<td align="right">'+number_format_id(y.BANGUNAN)+'</td>'+
							'<td align="right">'+number_format_id(y.KENDARAAN)+'</td>'+
							'<td align="right">'+number_format_id(y.PERALATAN)+'</td>'+
							'<td align="right">'+number_format_id(y.KOMPUTER)+'</td>'+
							'<td align="right">'+number_format_id(y.MESIN)+'</td>'+
							'<td align="right">'+number_format_id(y.LAIN2)+'</td>'+
						'</tr>';

						$('#tbl_item').append(row);
						i++;

					});

					$.each(msg[1]['summary_budget'], function(x, y){
						var row1 = '<tr style="border:2px solid black;white-space: nowrap;">'+
							'<td colspan=2 align="center"><B>Total Budget</B></td>'+
							'<td align="right">'+number_format_id(y.capex_curr)+'</td>'+
							'<td align="right">'+number_format_id(y.bangunan)+'</td>'+
							'<td align="right">'+number_format_id(y.kendaraan)+'</td>'+
							'<td align="right">'+number_format_id(y.peralatan)+'</td>'+
							'<td align="right">'+number_format_id(y.komputer)+'</td>'+
							'<td align="right">'+number_format_id(y.mesin)+'</td>'+
							'<td align="right">'+number_format_id(y.lain2)+'</td>'+
						'</tr>';

						$('#tbl_item').append(row1);

					});

					$.each(msg[2]['summary_actual'], function(a, b){
						var row2 = '<tr style="border:2px solid black;white-space: nowrap;">'+
							'<td colspan=2 align="center"><B>Total Actual</B></td>'+
							'<td align="right">'+number_format_id(b.capex_curr)+'</td>'+
							'<td align="right">'+number_format_id(b.bangunan)+'</td>'+
							'<td align="right">'+number_format_id(b.kendaraan)+'</td>'+
							'<td align="right">'+number_format_id(b.peralatan)+'</td>'+
							'<td align="right">'+number_format_id(b.komputer)+'</td>'+
							'<td align="right">'+number_format_id(b.mesin)+'</td>'+
							'<td align="right">'+number_format_id(b.lain2)+'</td>'+
						'</tr>';

						$('#tbl_item').append(row2);

					});

					$.each(msg[3]['summary_nonrealisasi'], function(c, d){
						var row3 = '<tr style="border:2px solid black;white-space: nowrap; background-color:yellow">'+
							'<td colspan=2 align="center"><B>Total Blm Realisasi</B></td>'+
							'<td align="right">'+number_format_id(d.capex_curr)+'</td>'+
							'<td align="right">'+number_format_id(d.bangunan)+'</td>'+
							'<td align="right">'+number_format_id(d.kendaraan)+'</td>'+
							'<td align="right">'+number_format_id(d.peralatan)+'</td>'+
							'<td align="right">'+number_format_id(d.komputer)+'</td>'+
							'<td align="right">'+number_format_id(d.mesin)+'</td>'+
							'<td align="right">'+number_format_id(d.lain2)+'</td>'+
						'</tr>';

						$('#tbl_item').append(row3);

					});

					
				},
				error 	: function(xhr) {
					$('#v_loading').hide();
					read_error(xhr);
				}
			});
			// } else {
				
			// 	$.ajax({
			// 			type: 'GET',
			// 			url: 'rek_capex_vs_actual/search_chart',
			// 			data: data_all,
			// 			cache: false,
			// 			success: function(msg){
			// 				var cpx_curr = $('#thn_anggaran').val();
			// 				$('#v_loading').hide();
			// 				var tbl = ''+
			// 					'<div class="row">'+
			// 						'<div class="col-12">'+
			// 							'<div class="card-body table-responsive p-0" style="height: 400px;">'+
			// 								'<center>'+
			// 									'<h4>PT Summarecon Agung TBK</h4>'+
			// 									'<h4>Budget & Realisasi '+cpx_curr+'</h4>'+
			// 								'</center>'+
			// 								'<hr>'+
			// 								'</div>'+
			// 							'</div>'+
			// 						'</div>'+
			// 					'';
			// 				$('#card_detail').html(tbl);

			// 				var a = $('#graf').prop('checked')
			// 				if (a == true)
			// 				{
			// 					//alert ('tes');
			// 					var dt = JSON.parse(msg);
			// 					debug = dt;
			// 					pie_chart_graf(dt);
			// 				}
			// 				// else
			// 				// 	$('#card_detail').html(msg);				
							
			// 				// 	$('#v_loading').hide();
							
			// 			}
			// 	});
			// }

			return false;
		}


		function get_header() {
			var cpx_before = $('#thn_anggaran').val() - 1;
			var cpx_curr = $('#thn_anggaran').val();
			var tbl = ''+
				'<div class="row">'+
		          	'<div class="col-12">'+
	              		'<div class="card-body table-responsive p-0" style="height: 400px;">'+
	              			// '<center>'+
							//   	'<h4>PT Summarecon Agung TBK</h4>'+
		              			'<h4>Budget & Realisasi  <span id="span_thn_anggaran"></span></h4>'+
		              		// '</center>'+
	              			// '<hr>'+
	              			'Unit : <span id="span_nm_unit"></span><br>'+

							'<table id="tbl_item" class="table table-sm table-head-fixed text-nowrap">'+
								'<thead>'+
				                    '<tr>'+
				                      	'<th>#</th>'+
				                      	'<th style= "background-color:orange"><center>Unit</center></th>'+
				                      	'<th style= "background-color:Chartreuse"><center>CAPEX '+cpx_curr+'</center></th>'+
				                      	'<th style= "background-color:LimeGreen"><center>Bangunan</center></th>'+
				                      	'<th style= "background-color:LimeGreen"><center>Kendaraan</center></th>'+
				                      	'<th style= "background-color:LimeGreen"><center>Peralatan</center></th>'+
										'<th style= "background-color:LimeGreen"><center>Komputer</center></th>'+
				                      	'<th style= "background-color:LimeGreen"><center>Mesin</center></th>'+
										'<th style= "background-color:LimeGreen"><center>Lain-lain</center></th>'+
				                    '</tr>'+
				              	'</thead>'+
								'<tbody>'+
				            	'</tbody>'+
				            '</table>'+
						'</div>'+
					'</div>'+
				'</div>'+
			'';

			return tbl;
		}

		function pie_chart_graf(dt){
				var cpx_curr = $('#thn_anggaran').val();

				$('#card_detail').highcharts({
					chart: {
						type: 'pie',
						options3d: {
						enabled: true,
						alpha: 45,
						beta: 0
						}
					},
					title: {
						text: 'CAPEX per Kategori '+ cpx_curr
					},
					tooltip: {
						pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>'
					},
					plotOptions: {
						pie: {
							allowPointSelect: true,
							cursor: 'pointer',
							depth: 35,
							dataLabels: {
								enabled: true,
								format: '<b>{point.name}</b>: {point.percentage:.2f} %',
								style: {
									color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
								}
							}
						}
					},
					series: [{
						data: dt
					}]
				});
			
		}
	</script>
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-12">
			<div class="card card-primary card-outline">
		      	<form class="form-horizontal" id="frm_search" autocomplete="off" onsubmit="return search_detail()">
			        <div class="card-body">
						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label text-nowrap">Tahun</label>
							<div class="col-sm-5">
								<div class="col-sm-3">
									<select name="thn_anggaran" class="form-control form-control-sm" id="thn_anggaran" name="thn_anggaran">
									</select>
								</div>
							</div>

							<!-- <div class="col-sm-2 row text-nowrap">
									<label>Jenis Report:</label> &nbsp; &nbsp; &nbsp;
									<div class="col-sm-5">
										<div class="col-sm-3">
											<input type="radio" name="tipe" id="tab" checked="checked" /> Tabular &nbsp; 
											<input type="radio" name="tipe" id="graf" /> Grafik
										</div>
									</div>
							</div> -->
						</div>
					
			          	<div class="form-group row">
				            <label class="col-sm-2 col-form-label text-nowrap">Unit</label>
				            <div class="col-sm-5">
				            		<div class="col-sm-6">
				            			<div class="input-group input-group-sm">
						                  	<input type="text" class="form-control" id="nm_unit" name="nm_unit" placeholder="Unit" readonly>
						                  	<input type="hidden" id="kd_unit" name="kd_unit">
						                  	<span class="input-group-append">
						                    	<button type="button" class="btn btn-info btn-flat" onclick="check_role_budget('search_unit_budget')"><i class="fas fa-search"></i></button>
						                    	<button type="button" class="btn btn-warning btn-flat"><i class="fas fa-times" onclick="check_role_budget('clear_unit_budget')"></i></button>
						                  	</span>
						                </div>
				            		</div>
				            </div>
			          	</div>

			          	<div class="form-group row">
				            
							<div class="offset-sm-2 col-sm-1">
				              	<button type="submit" id="btnCari" class="btn btn-flat btn-info btn-sm btn-block">Search</button>
				            </div>
			          	</div>

						
						<div class="form-group row">
							<div class="col-sm-12">
					          	<div class="card card-primary card-outline card-outline-tabs">
				              		<div class="card-body" id="card_detail">
						                
					              	</div>
				            	</div>
				            </div>
		            	</div>
			        </div>
			        <input type="hidden" id="act" name="act" value="add">
					<button type="submit" id="btn_save" style="display: none;">Save</button>
					@csrf
		      	</form>
		      	<div id="v_loading" class="overlay" style="display: none;">
					<i class="fas fa-2x fa-sync-alt fa-spin"></i>
				</div>
	    	</div>
		</div>
	</div>
@endsection