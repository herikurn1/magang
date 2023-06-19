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
				url 	: 'capex_vs_actual_dtl/check_role_budget',
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
				url 	: 'capex_vs_actual_dtl/search_unit_budget',
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

		function search_departemen_budget() {
			var keyword = $('#sys-modal-default-keyword').val();

			$('#v_loading').show();
			$.ajax({
				type 	: 'POST',
				url 	: 'capex_vs_actual_dtl/search_departemen_budget',
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
				                		'<h3 class="card-title">Departemen</h3>'+
				              		'</div>'+

				              		'<div class="card-body table-responsive p-0" style="height: 300px;">'+
										'<table class="table table-sm table-head-fixed text-nowrap">'+
											'<thead>'+
												'<tr>'+
													'<th>Kode</th>'+
													'<th>Nama Departemen</th>'+
												'</tr>'+
											'</thead>'+
											'<tbody>'+
					'';

					$.each(msg, function(x, y){
						tbl += ''+
												'<tr>'+
													'<td>'+y.kd_departemen+'</td>'+
													'<td>'+y.nm_departemen+'</td>'+
													'<td>'+
														'<button type="button" class="btn btn-info btn-sm" onclick="search_departemen_budget_set( \''+y.kd_departemen+'\', \''+y.nm_departemen+'\')">Pilih</button>'+
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

		function search_departemen_budget_set(kd_departemen, nm_departemen) {
			$('#kd_departemen').val(kd_departemen);
			$('#nm_departemen').val(kd_departemen+' - '+nm_departemen);

			$('#sys-modal-default-keyword').val('');
			$('#sys-modal-default').modal("hide");
		}

		function clear_departemen_budget() {
			$('#kd_departemen').val('');
			$('#nm_departemen').val('All');
		}

		function search_kategori() {
			var keyword 			= $('#sys-modal-default-keyword').val();

			$('#v_loading').show();
			$.ajax({
				type 	: 'POST',
				url 	: 'capex_vs_actual_dtl/search_kategori_budget',
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
				                		'<h3 class="card-title">Kategori</h3>'+
				              		'</div>'+

				              		'<div class="card-body table-responsive p-0" style="height: 300px;">'+
										'<table class="table table-sm table-head-fixed text-nowrap">'+
											'<thead>'+
												'<tr>'+
													'<th>Kode</th>'+
													'<th>Nama Kategori</th>'+
												'</tr>'+
											'</thead>'+
											'<tbody>'+
					'';

					$.each(msg, function(x, y){
						tbl += ''+
												'<tr>'+
													'<td>'+y.kd_kategori+'</td>'+
													'<td>'+y.nm_kategori+'</td>'+
													'<td>'+
														'<button type="button" class="btn btn-info btn-sm" onclick="search_kategori_set( \''+y.kd_kategori+'\', \''+y.nm_kategori+'\')">Pilih</button>'+
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
					$('#sys-modal-default-btn-search').attr('onclick', 'search_kategori()');
					$('#sys-modal-default').modal("show");
				},
				error 	: function(xhr) {
					$('#v_loading').hide();
					read_error(xhr);
				}
			});

			return false;
		}

		function search_kategori_set(kd_kategori, nm_kategori) {
			$('#kd_kategori').val(kd_kategori);
			$('#nm_kategori').val(nm_kategori);

			$('#sys-modal-default-keyword').val('');
			$('#sys-modal-default').modal("hide");
		}

		function clear_kategori() {
			$('#kd_kategori').val('');
			$('#nm_kategori').val('All Kategori');
		}

		function search_jenis() {
			var keyword 			= $('#sys-modal-default-keyword').val();
			var kd_kategori	= $('#kd_kategori').val();

			$('#v_loading').show();
			$.ajax({
				type 	: 'POST',
				url 	: 'capex_vs_actual_dtl/search_jenis',
				data 	: {
					"_token"	: '{{ csrf_token() }}',
					"keyword"	: keyword,
					"kd_kategori_budget" 	: kd_kategori
				},
				success : function(msg) {
					$('#v_loading').hide();

					var tbl = ''+
						'<div class="row">'+
				          	'<div class="col-12">'+
				            	'<div class="card">'+
				              		'<div class="card-header">'+
				                		'<h3 class="card-title">Jenis</h3>'+
				              		'</div>'+

				              		'<div class="card-body table-responsive p-0" style="height: 300px;">'+
										'<table class="table table-sm table-head-fixed text-nowrap">'+
											'<thead>'+
												'<tr>'+
													'<th>Kode</th>'+
													'<th>Nama Jenis</th>'+
												'</tr>'+
											'</thead>'+
											'<tbody>'+
					'';

					$.each(msg, function(x, y){
						tbl += ''+
												'<tr>'+
													'<td>'+y.kd_jenis+'</td>'+
													'<td>'+y.nm_jenis+'</td>'+
													'<td>'+
														'<button type="button" class="btn btn-info btn-sm" onclick="search_jenis_set( \''+y.kd_jenis+'\', \''+y.nm_jenis+'\', \''+y.kd_kategori+'\')">Pilih</button>'+
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
					$('#sys-modal-default-btn-search').attr('onclick', 'search_Jenis()');
					$('#sys-modal-default').modal("show");
				},
				error 	: function(xhr) {
					$('#v_loading').hide();
					read_error(xhr);
				}
			});

			return false;
		}

		function search_jenis_set(kd_jenis, nm_jenis, kd_kategori) {
			$('#kd_jns').val(kd_jenis);
			$('#nm_jns').val(nm_jenis);

			$('#sys-modal-default-keyword').val('');
			$('#sys-modal-default').modal("hide");
		}

		function clear_jenis() {
			$('#kd_kategori').val('');
			$('#nm_kategori').val('All Kategori');
			$('#kd_jns').val('');
			$('#nm_jns').val('All Jenis');
		}

		function search_detail() {
			var data_all = $('#frm_search').serializeArray();

			$('#v_loading').show();


			$.ajax({
				type	: 'POST',
				url 	: 'capex_vs_actual_dtl/search_detail',
				data 	: data_all,
				success	: function (msg) {
					$('#v_loading').hide();

					var tbl = get_header();
					$('#card_detail').html(tbl);

					$('#span_thn_anggaran').html(msg['thn_anggaran']);
					$('#span_nm_unit').html(msg['nm_unit']);
					$('#span_nm_departemen').html(msg['nm_departemen']);
					$('#span_nm_kategori').html(msg['nm_kategori']);
					$('#span_nm_jns').html(msg['nm_jns']);

					var i = 1;
					$.each(msg[0]['detail'], function(x, y){
						var row = '<tr>'+
							'<td style="border-left:1px solid black;">'+i+'.</td>'+
							'<td style="border-right:1px solid black;">'+y.nm_barang+'</td>'+
							'<td align="right" >'+number_format_id(y.qty_budget)+'</td>'+
							'<td align="right" >'+number_format_id(y.hrg_budget)+'</td>'+
							'<td align="right" style="border-right:1px solid black;">'+number_format_id(y.jml_hrg_budget)+'</td>'+
							'<td align="right" >'+number_format_id(y.qty_actual)+'</td>'+
							'<td align="right" >'+number_format_id(y.hrg_actual)+'</td>'+
							'<td align="right" style="border-right:1px solid black;">'+number_format_id(y.jml_hrg_actual)+'</td>'+
							'<td align="right" style="border-right:1px solid black;">'+number_format_id(y.sisa)+'</td>'+
							'<td align="right" style="border-right:1px solid black;">'+number_format_id(parseFloat(y.persen))+'</td>'+
						'</tr>';

						$('#tbl_item').append(row);

						i++;
					});

					$.each(msg[1]['summary'], function(x, y){
						var row2 = '<tr style="border-top:2px solid black;white-space: nowrap;">'+
							'<td cols=2><center><B>Total (Rp.)</B></cemter></td>'+
							'<td align="right"></td>'+
							'<td align="right"></td>'+
							'<td align="right"></td>'+
							'<td align="right"><B>'+number_format_id(y.sum_budget)+'</B></td>'+
							'<td align="right"></td>'+
							'<td align="right"></td>'+
							'<td align="right"><B>'+number_format_id(y.sum_realisasi)+'</B></td>'+
							'<td align="right"><B>'+number_format_id(y.sisa)+'</B></td>'+
							'<td align="right"></td>'+
						'</tr>';

						$('#tbl_item').append(row2);

					});

				},
				error 	: function(xhr) {
					$('#v_loading').hide();
					read_error(xhr);
				}
			});

			return false;
		}


		function get_header() {
			var cpx_before = $('#thn_anggaran').val() - 1;
			var cpx_curr = $('#thn_anggaran').val();
			
			var tbl = ''+
				'<div class="row">'+
		          	'<div class="col-12">'+
	              		'<div class="card-body table-responsive p-0" style="height: 400px;">'+
	              			'<center>'+
							  	'<h4>Laporan Detail Budget Capex vs Realisasi</h4>'+
		              			'<h6>TAHUN ANGGARAN : <span id="span_thn_anggaran"></span></h4>'+
		              		'</center>'+
	              			'<hr>'+
	              			'Unit : <span id="span_nm_unit"></span><br>'+
	              			'Departemen : <span id="span_nm_departemen"></span><br>'+
							'Kategori : <span id="span_nm_kategori"></span><br>'+
							'Jenis : <span id="span_nm_jns"></span><br>'+

							'<table id="tbl_item" class="table table-sm table-head-fixed text-nowrap">'+
								'<thead>'+
				                    '<tr>'+
				                      	'<th style="border-left:1px solid black; border-top:1px solid black;">No</th>'+
				                      	'<th style="border-right:1px solid black; border-top:1px solid black;">Nama CAPEX</th>'+
				                      	'<th colspan="3" style="border-right:1px solid black; border-top:1px solid black;"><center>Budget</center></th>'+
				                      	'<th colspan="3" style="border-right:1px solid black; border-top:1px solid black;"><center>Realisasi</center></th>'+
				                      	'<th style="border-right:1px solid black; border-top:1px solid black;"><center>Sisa</center></th>'+
										'<th style="border-right:1px solid black; border-top:1px solid black;"><center>%</center></th>'+
				                    '</tr>'+
									'<tr>'+
				                      	'<th style="border-left:1px solid black; border-bottom:1px solid black;"></th>'+
				                      	'<th style="border-right:1px solid black; border-bottom:1px solid black;"></th>'+
				                      	'<th style="border-bottom:1px solid black;">Qty</th>'+
				                      	'<th style="border-bottom:1px solid black;"><center>Harga Satuan (Rp.)</center></th>'+
				                      	'<th style="border-bottom:1px solid black; border-right:1px solid black;"><center>Jumlah Harga (Rp.)</center></th>'+
										'<th style="border-bottom:1px solid black;"><center>Qty</center></th>'+
				                      	'<th style="border-bottom:1px solid black;"><center>Harga Satuan (Rp.)</center></th>'+
				                      	'<th style="border-bottom:1px solid black; border-right:1px solid black;"><center>Jumlah Harga (Rp.)</center></th>'+
										'<th style="border-bottom:1px solid black; border-right:1px solid black;"></th>'+
										'<th style="border-bottom:1px solid black; border-right:1px solid black;"></th>'+
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

							<div class="col-sm-5 row text-nowrap">
									<label class="col-sm-2 col-form-label text-nowrap">Kategori</label>
								
										<div class="col-sm-7">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control" id="nm_kategori" name="nm_kategori" placeholder="All Kategori" readonly>
						                  	<input type="hidden" id="kd_kategori" name="kd_kategori">
						                  	<span class="input-group-append">
						                    	<button type="button" class="btn btn-info btn-flat" onclick="search_kategori()"><i class="fas fa-search"></i></button>
						                    	<button type="button" class="btn btn-warning btn-flat"><i class="fas fa-times" onclick="clear_kategori()"></i></button>
						                  	</span>
										</div>
									
									</div>
							</div>
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

							<div class="col-sm-5 row text-nowrap">
									<label class="col-sm-2 col-form-label text-nowrap">Jenis</label> 
								
										<div class="col-sm-7">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control" id="nm_jns" name="nm_jns" placeholder="All Jenis" readonly>
						                  	<input type="hidden" id="kd_jns" name="kd_jns">
						                  	<span class="input-group-append">
						                    	<button type="button" class="btn btn-info btn-flat" onclick="search_jenis()"><i class="fas fa-search"></i></button>
						                    	<button type="button" class="btn btn-warning btn-flat"><i class="fas fa-times" onclick="clear_jenis()"></i></button>
						                  	</span>
										</div>
									
									</div>
							</div>
			          	</div>

						<div class="form-group row">
							<label class="col-sm-2 col-form-label text-nowrap">Departemen</label>
							<div class="col-sm-5">
								<div class="col-sm-6">
									<div class="input-group input-group-sm">
										<input type="text" class="form-control" id="nm_departemen" name="nm_departemen" placeholder="Departemen" readonly>
										<input type="hidden" id="kd_departemen" name="kd_departemen">
										<span class="input-group-append">
											<button type="button" class="btn btn-info btn-flat" onclick="check_role_budget('search_departemen_budget')"><i class="fas fa-search"></i></button>
											<button type="button" class="btn btn-warning btn-flat" onclick="check_role_budget('clear_departemen_budget')"><i class="fas fa-times"></i></button>
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