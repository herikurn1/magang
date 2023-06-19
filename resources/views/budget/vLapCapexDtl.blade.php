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

		function search_kategori_budget() {
			var keyword = $('#sys-modal-default-keyword').val();

			$('#v_loading').show();
			$.ajax({
				type 	: 'POST',
				url 	: 'lap_capex_dtl/search_kategori_budget',
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
														'<button type="button" class="btn btn-info btn-sm" onclick="search_kategori_budget_set(\''+y.kd_kategori+'\', \''+y.nm_kategori+'\')">Pilih</button>'+
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
					$('#sys-modal-default-btn-search').attr('onclick', 'search_kategori_budget()');
					$('#sys-modal-default').modal("show");
				},
				error 	: function(xhr) {
					$('#v_loading').hide();
					read_error(xhr);
				}
			});

			return false;
		}

		function search_kategori_budget_set(kd_kategori_budget, nm_kategori_budget) {
			$('#kd_kategori_budget').val(kd_kategori_budget);
			$('#nm_kategori_budget').val(nm_kategori_budget);

			$('#sys-modal-default-keyword').val('');
			$('#sys-modal-default').modal("hide");
		}

		function clear_kategori_budget() {
			$('#kd_kategori_budget').val('');
			$('#nm_kategori_budget').val('');
			$('#kd_jenis').val('');
			$('#nm_jenis').val('');
		}

		function search_jenis(kd_kategori_budget, nm_kategori_budget) {
			if(kd_kategori_budget != undefined){
				$('#sys-modal-default-helper-1').val(kd_kategori_budget);
			}

			var keyword 			= $('#sys-modal-default-keyword').val();
			var kd_kategori_budget 	= $('#kd_kategori_budget').val();

			$('#v_loading').show();
			$.ajax({
				type 	: 'POST',
				url 	: 'lap_capex_dtl/search_jenis',
				data 	: {
					"_token"				: '{{ csrf_token() }}',
					"keyword"				: keyword,
					"kd_kategori_budget" 	: kd_kategori_budget
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
														'<button type="button" class="btn btn-info btn-sm" onclick="search_jenis_set( \''+y.kd_jenis+'\', \''+y.nm_jenis+'\')">Pilih</button>'+
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
					$('#sys-modal-default-btn-search').attr('onclick', 'search_jenis()');
					$('#sys-modal-default').modal("show");
				},
				error 	: function(xhr) {
					$('#v_loading').hide();
					read_error(xhr);
				}
			});

			return false;
		}

		function search_jenis_set(kd_jenis, nm_jenis) {
			$('#kd_jenis').val(kd_jenis);
			$('#nm_jenis').val(nm_jenis);

			$('#sys-modal-default-keyword').val('');
			$('#sys-modal-default').modal("hide");
		}

		function clear_jenis() {
			$('#kd_jenis').val('');
			$('#nm_jenis').val('');
		}

		function check_role_budget(func) {
			$.ajax({
				type 	: 'POST',
				url 	: 'lap_capex_dtl/check_role_budget',
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
				url 	: 'lap_capex_dtl/search_unit_budget',
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
			$('#nm_unit').val('');
		}

		function search_departemen_budget() {
			var keyword = $('#sys-modal-default-keyword').val();

			$('#v_loading').show();
			$.ajax({
				type 	: 'POST',
				url 	: 'lap_capex_dtl/search_departemen_budget',
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
			$('#nm_departemen').val('');
		}

		function search_detail() {
			var data_all = $('#frm_search').serializeArray();

			$('#v_loading').show();
			$.ajax({
				type	: 'POST',
				url 	: 'lap_capex_dtl/search_detail',
				data 	: data_all,
				success	: function (msg) {
					$('#v_loading').hide();

					var tbl = get_header();
					$('#card_detail').html(tbl);

					$('#span_thn_anggaran').html(msg['thn_anggaran']);
					$('#span_nm_unit').html(msg['nm_unit']);
					$('#span_nm_departemen').html(msg['nm_departemen']);
					$('#span_nm_kategori_budget').html(msg['nm_kategori_budget']);
					$('#span_nm_jenis').html(msg['nm_jenis']);

					var i = 1;
					$.each(msg[0]['detail'], function(x, y){
						var row = '<tr>'+
							'<td>'+i+'.</td>'+
							'<td>'+y.kd_barang+' - '+y.nm_barang+'</td>'+
							'<td>'+y.nm_urgency+'</td>'+
							'<td>'+y.catatan+'</td>'+
							'<td>'+y.qty_final+'</td>'+
							'<td>'+number_format_id(y.harga)+'</td>'+
							'<td>'+number_format_id(parseFloat(y.qty_final) * parseFloat(y.harga))+'</td>'+
						'</tr>';

						$('#tbl_item').append(row);

						i++;
					});
				},
				error 	: function(xhr) {
					$('#v_loading').hide();
					read_error(xhr);
				}
			});

			return false;
		}

		function hitung_jumlah_harga(id, act) {
			var qty 	= $('#'+act+'_qty_'+id).val();
			var harga 	= $('#'+act+'_harga_'+id).val();

			var jumlah_harga = parseFloat(qty) * parseFloat(harga);
			$('#'+act+'_jumlah_harga_'+id).val(jumlah_harga);
		}

		function get_header() {
			var tbl = ''+
				'<div class="row">'+
		          	'<div class="col-12">'+
	              		'<div class="card-body table-responsive p-0" style="height: 400px;">'+
	              			'<center>'+
		              			'<h4>LAPORAN DETAIL CAPEX DEPARTEMEN</h4>'+
		              			'Tahun Anggaran : <span id="span_thn_anggaran"></span><br>'+
		              		'</center>'+
	              			'<hr>'+
	              			'Unit : <span id="span_nm_unit"></span><br>'+
	              			'Nama Departemen : <span id="span_nm_departemen"></span><br>'+
	              			'Kategori CAPEX : <span id="span_nm_kategori_budget"></span><br>'+
	              			'Jenis CAPEX : <span id="span_nm_jenis"></span><br><br>'+

							'<table id="tbl_item" class="table table-sm table-head-fixed text-nowrap">'+
								'<thead>'+
				                    '<tr>'+
				                      	'<th>No</th>'+
				                      	'<th>Nama CAPEX</th>'+
				                      	'<th>Urgency</th>'+
				                      	'<th>Catatan</th>'+
				                      	'<th>Qty</th>'+
				                      	'<th>Harga Satuan (Rp.)</th>'+
				                      	'<th>Jumlah Harga (Rp.)</th>'+
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
				            <label class="col-sm-2 col-form-label text-nowrap">Unit & Departemen</label>
				            <div class="col-sm-5">
				            	<div class="row">
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

				            <label class="col-sm-1 offset-sm-1 col-form-label text-nowrap">Tahun</label>
				            <div class="col-sm-2">
				            	<select name="thn_anggaran" class="form-control form-control-sm" id="thn_anggaran" name="thn_anggaran">
				              	</select>
				            </div>
			          	</div>
			          	<div class="form-group row">
			          		<label class="col-sm-2 col-form-label text-nowrap">Kategori & Jenis</label>
				            <div class="col-sm-5">
				            	<div class="row">
				            		<div class="col-sm-6">
				            			<div class="input-group input-group-sm">
						                  	<input type="text" class="form-control" id="nm_kategori_budget" name="nm_kategori_budget" placeholder="Kategori" readonly>
						                  	<input type="hidden" id="kd_kategori_budget" name="kd_kategori_budget">
						                  	<span class="input-group-append">
						                    	<button type="button" class="btn btn-info btn-flat" onclick="search_kategori_budget()"><i class="fas fa-search"></i></button>
						                    	<button type="button" class="btn btn-warning btn-flat" onclick="clear_kategori_budget()"><i class="fas fa-times"></i></button>
						                  	</span>
						                </div>
				            		</div>
				            		<div class="col-sm-6">
				            			<div class="input-group input-group-sm">
						                  	<input type="text" class="form-control" id="nm_jenis" name="nm_jenis" placeholder="Jenis" readonly>
						                  	<input type="hidden" id="kd_jenis" name="kdjenis">
						                  	<span class="input-group-append">
						                    	<button type="button" class="btn btn-info btn-flat" onclick="search_jenis()"><i class="fas fa-search"></i></button>
						                    	<button type="button" class="btn btn-warning btn-flat" onclick="clear_jenis()"><i class="fas fa-times"></i></button>
						                  	</span>
						                </div>
				            		</div>
				            	</div>
				            </div>
				            
							<div class="offset-sm-1 col-sm-3">
				              	<button type="submit" class="btn btn-flat btn-info btn-sm btn-block">Search</button>
				            </div>
			          	</div>

						<div class="form-group row">
						<label class="col-sm-2 col-form-label text-nowrap">Approval By</label>
				            <div class="col-sm-5">
				            	<div class="row">
				            		<div class="col-sm-6">
				            			<div class="input-group input-group-sm">
										<select name="sts_approve" class="form-control form-control-sm" id="sts_approve" >
											<option value="" selected>All</option>
											<option value="E">New Entry</option>
											<option value="S">Submit to Kabag</option>
											<option value="K">Kabag</option>
											<option value="F">Finance</option>
											<option value="B">BOD</option>
				              			</select>

						                </div>
				            		</div>
								</div>
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