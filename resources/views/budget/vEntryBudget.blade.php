@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit 		= "{{ session('kd_unit') }}";
		var kd_lokasi 		= "{{ session('kd_lokasi') }}";

		var no 				= 1;
		var thn_anggaran 	= {!! $dt['thn_anggaran'] !!};
		var data_user 		= {!! $dt['data_user'] !!};
		var data_urgency	= {!! $dt['data_urgency'] !!};

		var search_barang_page 	= '1';
		var last_thn_anggaran = '';

		$(function() {
			$.each(thn_anggaran, function(x, y) {
				var o = new Option(y.thn_anggaran, y.thn_anggaran);
				$(o).html(y.thn_anggaran);
				$('#thn_anggaran').append(o);

				last_thn_anggaran = y.thn_anggaran;
			});

			$('#thn_anggaran').val(last_thn_anggaran).trigger("change");

			$('#departemen_lbl').val(data_user[0]['kd_departemen']+' - '+data_user[0]['nm_departemen']);
			$('#kd_departemen').val(data_user[0]['kd_departemen']);

			add_dt();
		});

		// function count_data() {  
		// 	var thn_anggaran = $('#thn_anggaran') ;
		// 	$.ajax({
		// 		url:'entry_budget/count_data',
		// 		type: "POST",
		// 		data: {
		// 			"thn_anggaran"	: thn_anggaran,
		// 			"_token" 		: '{{ csrf_token() }}',
		// 			"kd_unit"	: kd_unit,
		// 			"kd_lokasi"	: kd_lokasi
		// 		},,
		// 		success: function (txtBack) { 
		// 			alert(txtBack);
		// 		})
		// 	}); 
		// };

		function add_dt() {
			$('#no_pengajuan').val('');
			$('#tgl_entry').val('<?php echo date('d/m/Y'); ?>');
			$('#status_approval').val('E');
			$('#nm_status_approval').val('ENTRY OK');
			$('#act').val('add');

			$('#budget-tabContent-barang').html(get_header());
			$('#budget-tabContent-view').html(get_viewHeaderTahunPrev());
		}

		function save_dt() {
			$('#btn_save').click();
		}

		function save_process() {
			var data_all 			= $('#frm_mst_pengajuan').serializeArray();
			data_all.push({name : "kd_unit", value : kd_unit});
			data_all.push({name : "kd_lokasi", value : kd_lokasi});

			var status_approval 	= $('#status_approval').val();
			var nm_status_approval 	= $('#nm_status_approval').val();
			var act 				= $('#act').val();
			
			if(status_approval == "E"){
				$('#v_loading').show();
				$.ajax({
					type 	: 'POST',
					url 	: 'entry_budget/save',
					data 	: data_all,
					success	: function(msg) {
						$('#v_loading').hide();
						if(act == "add"){
							alert(msg);
							$('#act').val('edit');
							// $('#no_pengajuan').val(msg);
						}

						show_dtl();
					},
					error 	: function(xhr) {
						$('#v_loading').hide();
						read_error(xhr);
					}
				});
			}else{
				alert('Status Pengajuan '+nm_status_approval+', data tidak dapat diubah');
			}

			return false;
		}

		function search_dt() {
			$('#v_loading').show();

			var keyword = $('#sys-modal-default-keyword').val();

			$.ajax({
				type 	: 'POST',
				url 	: 'entry_budget/search_dt',
				data 	: {
					"_token"	: '{{ csrf_token() }}',
					"keyword"	: keyword,
					"kd_unit"	: kd_unit,
					"kd_lokasi"	: kd_lokasi
				},
				success : function(msg) {
					$('#v_loading').hide();

					var tbl = ''+
						'<div class="row">'+
				          	'<div class="col-12">'+
				            	'<div class="card">'+
				              		'<div class="card-header">'+
				                		'<h3 class="card-title">Daftar Pengajuan CAPEX</h3>'+
				              		'</div>'+

				              		'<div class="card-body table-responsive p-0" style="height: 300px;">'+
										'<table class="table table-sm table-head-fixed text-nowrap">'+
											'<thead>'+
												'<tr>'+
													'<th>No. Pengajuan</th>'+
													'<th>Thn. Anggaran</th>'+
													'<th>Departemen</th>'+
													'<td>#</td>'+
												'</tr>'+
											'</thead>'+
											'<tbody>'+
					'';

					$.each(msg, function(x, y){
						tbl += ''+
												'<tr>'+
													'<td>'+y.no_pengajuan+'</td>'+
													'<td>'+y.thn_anggaran+'</td>'+
													'<td>'+y.nm_departemen+'</td>'+
													'<td><button type="button" class="btn btn-info btn-sm" onclick="search_dt_set(\''+y.no_pengajuan+'\', \''+y.thn_anggaran+'\', \''+y.kd_departemen+'\', \''+y.nm_departemen+'\', \''+y.tgl_entry+'\', \''+y.status_approval+'\', \''+y.nm_status_approval+'\')">Pilih</button></td>'+
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
					$('#sys-modal-default-btn-search').attr('onclick', 'search_dt()');
					$('#sys-modal-default').modal("show");
				},
				error 	: function(xhr) {
					$('#v_loading').hide();
					read_error(xhr);
				}
			});

			return false;
		}

		function search_dt_set(no_pengajuan, thn_anggaran, kd_departemen, nm_departemen, tgl_entry, status_approval, nm_status_approval) {
			$('#no_pengajuan').val(no_pengajuan);
			$('#thn_anggaran').val(thn_anggaran);
			$('#kd_departemen').val(kd_departemen);
			$('#departemen_lbl').val(kd_departemen+ ' - '+nm_departemen);
			$('#tgl_entry').val(tgl_entry);
			$('#status_approval').val(status_approval);
			$('#nm_status_approval').val(nm_status_approval);
			$('#act').val('edit');

			$('#sys-modal-default').modal("hide");

			show_dtl();
		}

		function delete_dt() {
			var no_pengajuan 		= $('#no_pengajuan').val();
			var status_approval 	= $('#status_approval').val();
			var nm_status_approval 	= $('#nm_status_approval').val();
			var act 				= $('#act').val();

			var err = 0;
			var err_msg = "";

			if((no_pengajuan == "") || (act != "edit")){
				err++;
				err_msg += "Harap pilih No. Pengajuan\n";
			}

			if(status_approval != "E"){
				err++;
				err_msg += "Status Pengajuan "+nm_status_approval+", data tidak dapat dibatalkan\n";
			}

			if(err == 0){
				var ans = confirm('Apakah yakin ingin membatalkan pengajuan ini ?');

				if(ans){
					$('#v_loading').show();

					$.ajax({
						type 	: 'POST',
						url 	: 'entry_budget/delete_dt',
						data 	: {
							"no_pengajuan"	: no_pengajuan,
							"_token"		: '{{ csrf_token() }}'
						},
						success : function(msg) {
							$('#v_loading').hide();
							add_dt();
						},
						error 	: function(xhr) {
							$('#v_loading').hide();
							read_error(xhr);
						}
					});
				}
			}else{
				alert(err_msg);
			}
		}

		function submit_kabag() {
			var no_pengajuan 		= $('#no_pengajuan').val();
			var status_approval 	= $('#status_approval').val();
			var nm_status_approval 	= $('#nm_status_approval').val();
			var act 				= $('#act').val();

			var err = 0;
			var err_msg = "";

			// if((no_pengajuan == "") || (act != "edit")){
				// err++;
				// err_msg += "Harap pilih No. Pengajuan\n";
			// }

			if(status_approval != "E"){
				err++;
				err_msg += "Status Pengajuan "+nm_status_approval+", data tidak dapat disubmit\n";
			}

			if(err == 0){
				var ans = confirm('Apakah yakin ingin submit ke kabag ?');

				if(ans){
					$('#v_loading').show();

					$.ajax({
						type 	: 'POST',
						url 	: 'entry_budget/submit_kabag',
						data 	: {
							"no_pengajuan"	: no_pengajuan,
							"_token"		: '{{ csrf_token() }}'
						},
						success : function(msg) {
							$('#v_loading').hide();
							add_dt();
						},
						error 	: function(xhr) {
							$('#v_loading').hide();
							read_error(xhr);
						}
					});
				}
			}else{
				alert(err_msg);
			}
		}

		/* ============ Item Barang ============*/

		function add_dtl() {
			var act = $('#act').val();
			var status_approval 	= $('#status_approval').val();
			var nm_status_approval 	= $('#nm_status_approval').val();
			//if(act != "add"){
			if(status_approval == "E"){
				
				$('#tbl_item tbody').prepend(''+
					'<tr>'+
						'<td>#</td>'+
						'<td>'+
							'<div id="label_kategori_'+no+'" class="input-group">'+
								'<input type="text" class="form-control form-control-sm" placeholder="Pilih Kategori" readonly/>'+
								'<button type="button" class="btn btn-info btn-sm" onclick="search_kategori_budget(\''+no+'\')">...</button>'+
								'</div>'+
							'<div id="input_kategori_'+no+'" style="display: none;" class="input-group">'+
								'<input type="text" class="form-control form-control-sm" name="add_nm_kategori_budget[]" id="add_nm_kategori_budget_'+no+'" readonly/>'+
								'<button type="button" class="btn btn-info btn-sm" onclick="search_kategori_budget(\''+no+'\')" disabled>...</button>'+
								'<input type="hidden" name="add_kd_kategori_budget[]" id="add_kd_kategori_budget_'+no+'" />'+
								'<input type="hidden" name="add_kd_kategori[]" id="add_kd_kategori_'+no+'" />'+
								'<input type="hidden" class="form-control form-control-sm" name="add_nm_kategori[]" id="add_nm_kategori_'+no+'"  readonly/>'+
							'</div>'+
						'</td>'+
						'<td>'+
							'<div id="label_jenis_'+no+'" class="input-group">'+
								'<input type="text" class="form-control form-control-sm" placeholder="Pilih Jenis" readonly/>'+
								'<button type="button" class="btn btn-info btn-sm" onclick="search_jenis(\''+no+'\')">...</button>'+
							'</div>'+
							'<div id="input_jenis_'+no+'" style="display: none;" class="input-group">'+
								'<input type="text" class="form-control form-control-sm" name="add_nm_jenis[]" id="add_nm_jenis_'+no+'" readonly />'+
								'<button type="button" class="btn btn-info btn-sm" onclick="search_jenis(\''+no+'\')" disabled>...</button>'+
								'<input type="hidden" name="add_kd_jenis[]" id="add_kd_jenis_'+no+'" />'+
							'</div>'+
						'</td>'+
						'<td>'+
								'<input type="text" class="form-control form-control-sm" name="add_nm_barang[]" id="add_nm_barang_'+no+'" style="text-transform:uppercase"/>'+
						'</td>'+
						'<td>'+
							'<input type="text" name="add_qty[]" id="add_qty_'+no+'" class="form-control form-control-sm" style="width: 80px; text-align: center;" onchange="hitung_jumlah_harga(\''+no+'\', \'add\')" />'+
						'</td>'+
						'<td>'+
							'<input type="text" name="add_harga[]" id="add_harga_'+no+'" class="form-control form-control-sm" style="width: 120px; text-align: right;" onchange="hitung_jumlah_harga(\''+no+'\', \'add\')" />'+
						'</td>'+
						'<td>'+
							'<input type="text" name="add_jumlah_harga[]" id="add_jumlah_harga_'+no+'" class="form-control form-control-sm" readonly style="width: 150px; text-align: right;" />'+
						'</td>'+
						'<td>'+
							'<select name="add_urgency[]" class="form-control form-control-sm" id="add_urgency_'+no+'" style="width: 120px;">'+
					        '</select>'+
						'</td>'+
						'<td>'+
							'<input type="text" name="add_catatan[]" id="add_catatan_'+no+'" class="form-control form-control-sm" style="width: 250px;" value="-" />'+
						'</td>'+
						'<td>'+
							'<button type="button" class="btn btn-sm btn-default btn-flat" onclick="$(this).closest(\'tr\').remove();"><i class="fas fa-times"></i></button>'+
						'</td>'+
					'</tr>'+
				'');

				$.each(data_urgency, function(x, y) {
					var o = new Option(y.label, y.kode);
					$(o).html(y.label);
					$('#add_urgency_'+no).append(o);
				});

				no++;
			}else{ alert('Status Pengajuan '+nm_status_approval+', data tidak dapat di ubah'); }
			
		}

		function show_dtl() {
			var no_pengajuan = $('#no_pengajuan').val();

			$('#v_loading').show();
			$.ajax({
				type 	: 'POST',
				url 	: 'entry_budget/show_dtl',
				data 	: {
					"no_pengajuan"	: no_pengajuan,
					"_token" 		: '{{ csrf_token() }}'
				},
				success : function(msg) {
					$('#v_loading').hide();
					$('#budget-tabContent-barang').html(get_header());

					var i = 1;
					$.each(msg, function(x, y){
						var row = ''+
							'<tr>'+
								'<td>'+i+'.</td>'+
								'<td>'+y.nm_kategori_budget+'</td>'+
								'<td>'+y.nm_jenis+'</td>'+
								'<td>'+
									y.nm_barang+
									'<input type="hidden" name="edt_rowid[]" id="edt_rowid_'+y.rowid+'" value="'+y.rowid+'" />'+
								'</td>'+
								'<td>'+
									'<input type="text" name="edt_qty[]" id="edt_qty_'+y.rowid+'" class="form-control form-control-sm" style="width: 80px; text-align: center;" onchange="hitung_jumlah_harga(\''+y.rowid+'\', \'edt\')" value="'+y.qty_final+'" />'+
								'</td>'+
								'<td>'+
									'<input type="text" name="edt_harga[]" id="edt_harga_'+y.rowid+'" class="form-control form-control-sm" style="width: 120px; text-align: right;" onchange="hitung_jumlah_harga(\''+y.rowid+'\', \'edt\')" value="'+y.harga+'" />'+
								'</td>'+
								'<td>'+
									'<input type="text" name="edt_jumlah_harga[]" id="edt_jumlah_harga_'+y.rowid+'" class="form-control form-control-sm" readonly style="width: 150px; text-align: right;" value="'+y.jumlah_harga+'" />'+
								'</td>'+
								'<td>'+
									'<select name="edt_urgency[]" class="form-control form-control-sm" id="edt_urgency_'+y.rowid+'" style="width: 120px;">'+
							        '</select>'+
								'</td>'+
								'<td>'+
									'<input type="text" name="edt_catatan[]" id="edt_catatan_'+y.rowid+'" class="form-control form-control-sm" style="width: 250px;" value="'+y.catatan+'" />'+
								'</td>'+
								'<td><button type="button" class="btn btn-sm btn-link" onclick="view_history(\''+y.kd_barang+'\')">View History</button></td>'+
								'<td>'+
									'<button type="button" class="btn btn-sm btn-default btn-flat" onclick="delete_barang(\''+y.rowid+'\')"><i class="fas fa-times"></i></button>'+
								'</td>'+
							'</tr>'+
						'';

						$('#tbl_item').append(row);

						var params = [y.rowid];
						$.each(data_urgency, function(x, y) {
							var o = new Option(y.label, y.kode);
							$(o).html(y.label);
							$('#edt_urgency_'+params[0]).append(o);
						}, params);

						$('#edt_urgency_'+y.rowid).val(y.urgency);

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

		function show_dtl_yearprev() {
			var thn_anggaran = parseInt($('#thn_anggaran').val()) -1 ;
			
			$('#v_loading').show();
			$.ajax({
				type 	: 'POST',
				url 	: 'entry_budget/show_yearprev',
				data 	: {
					"thn_anggaran"	: thn_anggaran,
					"_token" 		: '{{ csrf_token() }}',
					"kd_unit"	: kd_unit,
					"kd_lokasi"	: kd_lokasi
				},
				success : function(msg) {
					$('#v_loading').hide();
					$('#budget-tabContent-view').html(get_viewHeaderTahunPrev());
					
					var i = 1;
					$.each(msg, function(x, y){
						var row = ''+
							'<tr>'+
								'<td>'+i+'.</td>'+
								'<td>'+y.nm_kategori_budget+'</td>'+
								'<td>'+y.nm_jenis+'</td>'+
								'<td>'+
									y.nm_barang+
									'<input type="hidden" name="copy_rowid[]" id="copy_rowid_'+y.rowid+'" value="'+y.rowid+'" />'+
								'</td>'+
								'<td>'+
									'<input type="text" name="copy_qty[]" id="copy_qty_'+y.rowid+'" class="form-control form-control-sm" style="width: 80px; text-align: center;" onchange="hitung_jumlah_harga(\''+y.rowid+'\', \'edt\')" value="'+y.qty+'" disabled/>'+
								'</td>'+
								'<td>'+
									'<input type="text" name="copy_harga[]" id="copy_harga_'+y.rowid+'" class="form-control form-control-sm" style="width: 120px; text-align: right;" onchange="hitung_jumlah_harga(\''+y.rowid+'\', \'edt\')" value="'+y.harga+'" disabled/>'+
								'</td>'+
								'<td>'+
									'<input type="text" name="copy_jumlah_harga[]" id="copy_jumlah_harga_'+y.rowid+'" class="form-control form-control-sm" readonly style="width: 150px; text-align: right;" value="'+y.jumlah_harga+'" />'+
								'</td>'+
								'<td>'+
								'<input type="text" name="copy_urgency[]" id="copy_urgency_'+y.rowid+'" class="form-control form-control-sm" readonly style="width: 150px; text-align: right;" value="'+y.nm_urgency+'" />'+
									// '<select name="copy_urgency[]" class="form-control form-control-sm" id="copy_urgency_'+y.rowid+'" style="width: 120px;">'+
									// <option value="E">"copy_urgency_'+y.rowid+'"</option>
							        // '</select>'+
								'</td>'+
								'<td>'+
									'<input type="text" name="copy_catatan[]" id="copy_catatan_'+y.rowid+'" class="form-control form-control-sm" style="width: 250px;" value="'+y.catatan+'" disabled/>'+
								'</td>'+
								// '<td><button type="button" class="btn btn-sm btn-link" onclick="view_history(\''+y.kd_barang+'\')">View History</button></td>'+
								// '<td>'+
								// 	'<button type="button" class="btn btn-sm btn-default btn-flat" onclick="delete_barang(\''+y.rowid+'\')"><i class="fas fa-times"></i></button>'+
								// '</td>'+
							'</tr>'+
						'';

						$('#tbl_year_prev').append(row);

						var params = [y.rowid];
						$.each(data_urgency, function(x, y) {
							var o = new Option(y.label, y.kode);
							$(o).html(y.label);
							$('#edt_urgency_'+params[0]).append(o);
						}, params);

						$('#edt_urgency_'+y.rowid).val(y.urgency);

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

		function salin_data() {
			var thn_anggaran = parseInt($('#thn_anggaran').val()) -1 ;
			
			$('#v_loading').show();
			$.ajax({
				type 	: 'POST',
				url 	: 'entry_budget/salin_data',
				data 	: {
					"thn_anggaran"	: thn_anggaran,
					"_token" 		: '{{ csrf_token() }}',
					"kd_unit"	: kd_unit,
					"kd_lokasi"	: kd_lokasi
				},
				success : function(msg) {
					$('#v_loading').hide();
					$('#budget-tabContent-barang').html(get_header());

					var i = 1;
					$.each(msg, function(x, y){

						var act = $('#act').val();

			//if(act != "add"){
				$('#tbl_item tbody').prepend(''+
					'<tr>'+
						'<td>#</td>'+
						'<td>'+
							'<div id="input_kategori_'+no+'" >'+
								'<input type="text" class="form-control form-control-sm" name="add_nm_kategori_budget[]" id="add_nm_kategori_budget_'+no+'" readonly style="width: 100px;" value= "'+y.add_nm_kategori_budget+'"  />'+
								'<input type="hidden" name="add_kd_kategori_budget[]" id="add_kd_kategori_budget_'+no+'" value= "'+y.add_kd_kategori_budget+'"/>'+

								'<input type="hidden" name="add_nm_kategori[]" id="add_nm_kategori_'+no+'" value= "'+y.add_nm_kategori+'"/>'+
								'<input type="hidden" name="add_kd_kategori[]" id="add_kd_kategori_'+no+'" value= "'+y.add_kd_kategori+'"/>'+
							'</div>'+
						'</td>'+
						'<td>'+
							'<div id="input_jenis_'+no+'" >'+
								'<input type="text" class="form-control form-control-sm" name="add_nm_jenis[]" id="add_nm_jenis_'+no+'" readonly style="width: 150px;" value= "'+y.add_nm_jenis+'" />'+
								'<input type="hidden" name="add_kd_jenis[]" id="add_kd_jenis_'+no+'" value= "'+y.add_kd_jenis+'" />'+
							'</div>'+
						'</td>'+
						'<td>'+
							'<div id="input_barang_'+no+'" >'+
								'<input type="text" class="form-control form-control-sm" name="add_nm_barang[]" id="add_nm_barang_'+no+'" readonly style="width: 200px;" value= "'+y.add_nm_barang+'" />'+
								'<input type="hidden" name="add_kd_barang[]" id="add_kd_barang_'+no+'" value= "'+y.add_kd_barang+'" />'+
							'</div>'+
						'</td>'+
						'<td>'+
							'<input type="text" name="add_qty[]" id="add_qty_'+no+'" class="form-control form-control-sm" style="width: 80px; text-align: center;" onchange="hitung_jumlah_harga(\''+no+'\', \'add\')" value= "'+y.add_qty+'" />'+
						'</td>'+
						'<td>'+
							'<input type="text" name="add_harga[]" id="add_harga_'+no+'" class="form-control form-control-sm" style="width: 120px; text-align: right;" onchange="hitung_jumlah_harga(\''+no+'\', \'add\')" value= "'+y.add_harga+'" />'+
						'</td>'+
						'<td>'+
							'<input type="text" name="add_jumlah_harga[]" id="add_jumlah_harga_'+no+'" class="form-control form-control-sm" readonly style="width: 150px; text-align: right;" value= "'+y.add_jumlah_harga+'" />'+
						'</td>'+
						'<td>'+
							'<select name="add_urgency[]" class="form-control form-control-sm" id="add_urgency_'+no+'" style="width: 120px;" value= "'+y.add_urgency+'">'+
					        '</select>'+
						'</td>'+
						'<td>'+
							'<input type="text" name="add_catatan[]" id="add_catatan_'+no+'" class="form-control form-control-sm" style="width: 250px;" value="'+y.add_catatan+'" />'+
						'</td>'+
						'<td>'+
							'<button type="button" class="btn btn-sm btn-default btn-flat" onclick="$(this).closest(\'tr\').remove();"><i class="fas fa-times"></i></button>'+
						'</td>'+
					'</tr>'+
				'');

				$.each(data_urgency, function(x, y) {
					var o = new Option(y.label, y.kode);
					$(o).html(y.label);
					$('#add_urgency_'+no).append(o);
				});

				no++;
						// var row = ''+
						// 	'<tr>'+
						// 		'<td>'+i+'.</td>'+
						// 		'<td>'+y.nm_kategori_budget+'</td>'+
						// 		'<td>'+y.nm_jenis+'</td>'+
						// 		'<td>'+
						// 			y.nm_barang+
						// 			'<input type="hidden" name="edt_rowid[]" id="edt_rowid_'+y.rowid+'" value="'+y.rowid+'" />'+
						// 		'</td>'+
						// 		'<td>'+
						// 			'<input type="text" name="edt_qty[]" id="edt_qty_'+y.rowid+'" class="form-control form-control-sm" style="width: 80px; text-align: center;" onchange="hitung_jumlah_harga(\''+y.rowid+'\', \'edt\')" value="'+y.qty+'" />'+
						// 		'</td>'+
						// 		'<td>'+
						// 			'<input type="text" name="edt_harga[]" id="edt_harga_'+y.rowid+'" class="form-control form-control-sm" style="width: 120px; text-align: right;" onchange="hitung_jumlah_harga(\''+y.rowid+'\', \'edt\')" value="'+y.harga+'" />'+
						// 		'</td>'+
						// 		'<td>'+
						// 			'<input type="text" name="edt_jumlah_harga[]" id="edt_jumlah_harga_'+y.rowid+'" class="form-control form-control-sm" readonly style="width: 150px; text-align: right;" value="'+y.jumlah_harga+'" />'+
						// 		'</td>'+
						// 		'<td>'+
						// 			'<select name="edt_urgency[]" class="form-control form-control-sm" id="edt_urgency_'+y.rowid+'" style="width: 120px;" >'+
						// 	        '</select>'+
						// 		'</td>'+
						// 		'<td>'+
						// 			'<input type="text" name="edt_catatan[]" id="edt_catatan_'+y.rowid+'" class="form-control form-control-sm" style="width: 250px;" value="'+y.catatan+'" />'+
						// 		'</td>'+
						// 		'<td><button type="button" class="btn btn-sm btn-link" onclick="view_history(\''+y.kd_barang+'\')">View History</button></td>'+
						// 		'<td>'+
						// 			'<button type="button" class="btn btn-sm btn-default btn-flat" onclick="delete_barang(\''+y.rowid+'\')"><i class="fas fa-times"></i></button>'+
						// 		'</td>'+
						// 	'</tr>'+
						// '';

						// $('#tbl_item').append(row);

						// var params = [y.rowid];
						// $.each(data_urgency, function(x, y) {
						// 	var o = new Option(y.label, y.kode);
						// 	$(o).html(y.label);
						// 	$('#edt_urgency_'+params[0]).append(o);
						// }, params);

						// $('#edt_urgency_'+y.rowid).val(y.urgency);

						// i++;
					});
				},
				error 	: function(xhr) {
					$('#v_loading').hide();
					read_error(xhr);
				}
			});

			return false;
		}

		function view_history(kd_barang) {
			var thn_anggaran 	= $('#thn_anggaran').val();
			var kd_departemen 	= $('#kd_departemen').val();

			$('#v_loading').show();
			$.ajax({
				type 	: 'POST',
				url 	: 'entry_budget/view_history',
				data 	: {
					"thn_anggaran"	: thn_anggaran,
					"kd_unit"		: kd_unit,
					"kd_departemen" : kd_departemen,
					"kd_barang"		: kd_barang,
					"_token"		: '{{ csrf_token() }}'
				},
				success : function(msg) {
					$('#v_loading').hide();

					var tbl = ''+
						'<div class="row">'+
				          	'<div class="col-12">'+
				            	'<div class="card">'+
				              		'<div class="card-header">'+
				                		'<h3 class="card-title">History</h3>'+
				              		'</div>'+

				              		'<div class="card-body table-responsive p-0" style="height: 300px;">'+
										'<table class="table table-sm table-head-fixed text-nowrap">'+
											'<thead>'+
												'<tr>'+
													'<th>Thn Anggaran</th>'+
													'<th>No. Pengajuan</th>'+
													'<th>Qty Awal</th>'+
													'<th>Qty Finance</th>'+
													'<th>Qty Final</th>'+
													'<th>Harga</th>'+
													'<th>Jumlah Harga</th>'+
												'</tr>'+
											'</thead>'+
											'<tbody>'+
					'';

					$.each(msg, function(x, y){
						var jumlah_harga = parseFloat(y.qty_final) * parseFloat(y.harga);

						tbl += ''+
												'<tr>'+
													'<td>'+y.thn_anggaran+'</td>'+
													'<td>'+y.no_pengajuan+'</td>'+
													'<td>'+y.qty+'</td>'+
													'<td>'+y.qty_finance+'</td>'+
													'<td>'+y.qty_final+'</td>'+
													'<td>'+number_format_id(y.harga)+'</td>'+
													'<td>'+number_format_id(jumlah_harga)+'</td>'+
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
					$('#sys-modal-default-btn-search').attr('onclick', '');
					$('#sys-modal-default').modal("show");
				},
				error 	: function(xhr) {
					$('#v_loading').hide();
					read_error(xhr);
				}
			});
		}

		function delete_barang(id) {
			var no_pengajuan 		= $('#no_pengajuan').val();
			var status_approval 	= $('#status_approval').val();
			var nm_status_approval 	= $('#nm_status_approval').val();

			if(status_approval == "E"){
				var ans = confirm('Apakah anda yakin ingin menghapus item ini ?');
				if(ans){
					$('#v_loading').show();

					$.ajax({
						type 	: 'POST',
						url 	: 'entry_budget/delete_barang',
						data 	: {
							"no_pengajuan"	: no_pengajuan,
							"rowid"			: id,
							"_token" 		: '{{ csrf_token() }}'
						},
						success : function(msg) {
							$('#v_loading').hide();
							show_dtl();
						},
						error 	: function(xhr) {
							$('#v_loading').hide();
							read_error(xhr);
						}
					});
				}
			}else{
				alert('Status Pengajuan '+nm_status_approval+', data tidak dapat dihapus');
			}
		}

		function search_kategori_budget(id) {
			if(id != undefined){
				$('#sys-modal-default-helper-1').val(id);
			}

			var keyword = $('#sys-modal-default-keyword').val();
			var helper 	= $('#sys-modal-default-helper-1').val();

			$('#v_loading').show();

			var keyword = $('#sys-modal-default-keyword').val();

			$.ajax({
				type 	: 'POST',
				url 	: 'entry_budget/search_kategori_budget',
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
													'<th>#</th>'+
												'</tr>'+
											'</thead>'+
											'<tbody>'+
					'';

					$.each(msg, function(x, y){
						tbl += ''+
												'<tr onclick="search_kategori_set(\''+helper+'\', \''+y.kd_kategori+'\', \''+y.nm_kategori+'\')">'+
													'<td>'+y.kd_kategori+'</td>'+
													'<td>'+y.nm_kategori+'</td>'+
													'<td>'+
														'<button type="button" class="btn btn-info btn-sm" onclick="">Pilih</button>'+
													'</td>'+
												'</tr>'+
						'';
					});
					
					// $.each(msg['data'], function(x, y){
						// var param_nm_barang = y.nm_barang;
						// param_nm_barang = param_nm_barang.replace('"', '&quot;');
						// param_nm_barang = param_nm_barang.replace("'", '&apos;');

						// tbl += ''+
												// '<tr onclick="search_barang_set(\''+helper+'\', \''+kd_kategori_budget+'\', \''+nm_kategori_budget+'\', \''+y.kd_barang+'\', \''+param_nm_barang+'\', \''+y.kd_kategori+'\', \''+y.nm_kategori+'\', \''+y.kd_jenis+'\', \''+y.nm_jenis+'\')">'+
													// '<td>'+y.kd_barang+' - '+y.nm_barang+'</td>'+
													// '<td>'+y.nm_jenis+'</td>'+
													// '<td>'+y.nm_kategori+'</td>'+
													// '<td>'+
														// '<button type="button" class="btn btn-info btn-sm" onclick="">Pilih</button>'+
													// '</td>'+
												// '</tr>'+
						// '';
					// });

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
		
		function search_kategori_set(id, kd_kategori, nm_kategori) {
			$('#add_kd_kategori_budget_'+id).val(kd_kategori);
			$('#add_nm_kategori_budget_'+id).val(nm_kategori);

			$('#label_kategori_'+id).remove();
			$('#input_kategori_'+id).show();

			$('#sys-modal-default').modal("hide");
		}

		function search_jenis(id) {
			if(id != undefined){
				$('#sys-modal-default-helper-1').val(id);
				// $('#sys-modal-default-helper-2').val(id_kategori_budget);
				// $('#sys-modal-default-helper-3').val(nm_kategori_budget);
			}

			var keyword 			= $('#sys-modal-default-keyword').val();
			var helper 				= $('#sys-modal-default-helper-1').val();
			//var kd_kategori_budget 	= $('#sys-modal-default-helper-2').val();
			// var nm_kategori_budget 	= $('#sys-modal-default-helper-3').val();
			var kd_kategori_budget 	= $('#add_kd_kategori_budget_'+id).val();
			var nm_kategori_budget 	= $('#add_nm_kategori_budget_'+id).val();

			$('#v_loading').show();
			$.ajax({
				type 	: 'POST',
				url 	: 'entry_budget/search_jenis',
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
												'<tr onclick= "search_jenis_set(\''+helper+'\', \''+y.kd_jenis+'\', \''+y.nm_jenis+'\', \''+y.kd_kategori+'\')">'+
													'<td>'+y.kd_jenis+'</td>'+
													'<td>'+y.nm_jenis+'</td>'+
													'<td>'+
														'<button type="button" class="btn btn-info btn-sm" onclick="">Pilih</button>'+
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
		
		function search_jenis_set(id, kd_jenis, nm_jenis, kd_kategori) {
			$('#add_kd_kategori_'+id).val(kd_kategori);

			$('#add_kd_jenis_'+id).val(kd_jenis);
			$('#add_nm_jenis_'+id).val(nm_jenis);

			$('#label_jenis_'+id).remove();
			$('#input_jenis_'+id).show();

			$('#sys-modal-default').modal("hide");
		}

		function search_barang(id, id_kategori_budget, nm_kategori_budget, id_jenis, id_kategori) {
			if(id != undefined){
				$('#sys-modal-default-helper-1').val(id);
				$('#sys-modal-default-helper-2').val(id_kategori_budget);
				$('#sys-modal-default-helper-3').val(nm_kategori_budget);
				$('#sys-modal-default-helper-4').val(id_jenis);
				$('#sys-modal-default-helper-5').val(id_kategori);
			}

			var keyword 			= $('#sys-modal-default-keyword').val();
			var helper 				= $('#sys-modal-default-helper-1').val();
			// var kd_kategori_budget 	= $('#sys-modal-default-helper-2').val();
			// var nm_kategori_budget 	= $('#sys-modal-default-helper-3').val();
			// var kd_jenis 			= $('#sys-modal-default-helper-4').val();
			// var kd_kategori 		= $('#sys-modal-default-helper-5').val();
			var kd_kategori_budget 	= $('#add_kd_kategori_budget_'+id).val();
			var nm_kategori_budget 	= $('#add_nm_kategori_budget_'+id).val();
			var kd_jenis 			= $('#add_kd_jenis_'+id).val();
			var kd_kategori 		= $('#add_kd_kategori_'+id).val();
			
			$('#v_loading').show();
			$.ajax({
				type 	: 'POST',
				url 	: 'entry_budget/search_barang?page='+search_barang_page,
				data 	: {
					"_token"		: '{{ csrf_token() }}',
					"keyword"		: keyword,
					"helper"		: helper,
					"kd_kategori" 	: kd_kategori,
					"kd_jenis"		: kd_jenis
				},
				success : function(msg) {
					$('#v_loading').hide();

					var tbl = ''+
						'<div class="row">'+
				          	'<div class="col-12">'+
				            	'<div class="card">'+
				              		'<div class="card-header">'+
				                		'<h3 class="card-title">Item Barang</h3>'+
				              		'</div>'+

				              		'<div class="card-body table-responsive p-0" style="height: 300px;">'+
										'<table class="table table-sm table-head-fixed text-nowrap">'+
											'<thead>'+
												'<tr>'+
													'<th>Nama</th>'+
													'<th>Jenis</th>'+
													'<th>Kategori</th>'+
													'<th>#</th>'+
												'</tr>'+
											'</thead>'+
											'<tbody>'+
					'';

					$.each(msg['data'], function(x, y){
						var param_nm_barang = y.nm_barang;
						param_nm_barang = param_nm_barang.replace('"', '&quot;');
						param_nm_barang = param_nm_barang.replace("'", '&apos;');

						tbl += ''+
												'<tr onclick="search_barang_set(\''+helper+'\', \''+kd_kategori_budget+'\', \''+nm_kategori_budget+'\', \''+y.kd_barang+'\', \''+param_nm_barang+'\', \''+y.kd_kategori+'\', \''+y.nm_kategori+'\', \''+y.kd_jenis+'\', \''+y.nm_jenis+'\')">'+
													'<td>'+y.kd_barang+' - '+y.nm_barang+'</td>'+
													'<td>'+y.nm_jenis+'</td>'+
													'<td>'+y.nm_kategori+'</td>'+
													'<td>'+
														'<button type="button" class="btn btn-info btn-sm" onclick="">Pilih</button>'+
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
						
						'<div class="row">'+
							'<label class="col-sm-1 col-form-label">Halaman</label>'+
							'<div class="col-sm-3">'+
								'<div class="input-group">'+
									'<span class="input-group-preppend">'+
				                    	'<button type="button" class="btn btn-info btn-flat btn-sm" onclick="search_barang_page--; search_barang();"><<</button>'+
				                  	'</span>'+
				                  	'<input type="number" class="form-control form-control-sm rounded-0" min="1" id="search_barang_page_input" style="text-align: center;" value="'+search_barang_page+'">'+
				                  	'<span class="input-group-append">'+
				                    	'<button type="button" class="btn btn-info btn-flat btn-sm" onclick="search_barang_page++; search_barang();">>></button>'+
				                    	'<button type="button" class="btn btn-default btn-flat btn-sm" onclick="search_barang_page = $(\'#search_barang_page_input\').val(); search_barang();">Go!</button>'+
				                  	'</span>'+
				                '</div>'+
							'</div>'+
						'</div>'+
					'';

					$('#sys-modal-default-body').html(tbl);
					$('#sys-modal-default-btn-search').attr('onclick', 'search_barang()');
					$('#sys-modal-default').modal("show");
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

		function search_barang_set(id, kd_kategori_budget, nm_kategori_budget, kd_barang, nm_barang, kd_kategori, nm_kategori, kd_jenis, nm_jenis) {
			$('#add_kd_barang_'+id).val(kd_barang);
			$('#add_nm_barang_'+id).val(nm_barang);
			// $('#add_kd_kategori_budget_'+id).val(kd_kategori_budget);
			$('#add_nm_kategori_'+id).val(nm_kategori);
			// $('#add_kd_kategori_'+id).val(kd_kategori);
			// $('#add_nm_kategori_'+id).val(nm_kategori);
			// $('#add_kd_jenis_'+id).val(kd_jenis);
			// $('#add_nm_jenis_'+id).val(nm_jenis);

			$('#label_barang_'+id).remove();
			$('#input_barang_'+id).show();

			//$('#label_kategori_'+id).remove();
			// $('#input_kategori_'+id).show();

			//$('#label_jenis_'+id).remove();
			// $('#input_jenis_'+id).show();

			$('#sys-modal-default').modal("hide");
		}

		function get_header() {
			var thn = parseInt($('#thn_anggaran').val()) -1
			var tbl = ''+
				'<div class="row">'+
		          	'<div class="col-12">'+
		            	'<div class="card">'+
		              		'<div class="card-header">'+
		                		'<h3 class="card-title">'+
	                			'<button type="button" class="btn btn-default btn-flat" onclick="add_dtl()" style="margin: 5px;">'+
					                '<i class="fas fa-plus"></i>'+
					            '</button></h3>'+
								'<button type="button" class="btn btn-default btn-flat" onclick="salin_data()" style="margin: 5px;">'+
								'<i class="fas fa-copy"></i><text>copy data '+thn+'</>'+ 
					            '</button></h3>'+
		              		'</div>'+

		              		'<div class="card-body table-responsive p-0" style="height: 400px;">'+
								'<table id="tbl_item" class="table table-sm table-head-fixed text-nowrap">'+
									'<thead>'+
					                    '<tr>'+
					                      	'<th>No</th>'+
					                      	'<th>Kategori</th>'+
					                      	'<th>Jenis</th>'+
											'<th>Nama CAPEX</th>'+
					                      	'<th>Qty</th>'+
					                      	'<th>Harga @ (Rp)</th>'+
					                      	'<th>Jumlah Harga</th>'+
					                      	'<th>Urgency</th>'+
					                      	'<th>Catatan</th>'+
					                      	'<th></th>'+
					                      	'<th>#</th>'+
					                    '</tr>'+
					              	'</thead>'+
									'<tbody>'+
					            	'</tbody>'+
					            '</table>'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div>'+
			'';

			return tbl;
		}

		function get_viewHeaderTahunPrev() {
			var tbl = ''+
				'<div class="row">'+
		          	'<div class="col-12">'+
		            	'<div class="card">'+
		              		
		              		'<div class="card-body table-responsive p-0" style="height: 400px;">'+
								'<table id="tbl_year_prev" class="table table-sm table-head-fixed text-nowrap">'+
									'<thead>'+
					                    '<tr>'+
					                      	'<th>No</th>'+
					                      	'<th>Kategori</th>'+
					                      	'<th>Jenis</th>'+
											'<th>Nama CAPEX</th>'+
					                      	'<th>Qty</th>'+
					                      	'<th>Harga @ (Rp)</th>'+
					                      	'<th>Jumlah Harga</th>'+
					                      	'<th>Urgency</th>'+
					                      	'<th>Catatan</th>'+
					                      	// '<th></th>'+
					                      	// '<th>#</th>'+
					                    '</tr>'+
					              	'</thead>'+
									'<tbody>'+
					            	'</tbody>'+
					            '</table>'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div>'+
			'';

			return tbl;
		}

	// function getData(){
	// 	alert('testing');
	// }	

	$(document).ready(function () {
		show_dtl_yearprev() ;	

    })
	</script>

	
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-12">
			<div class="card card-primary card-outline">
		      	<div class="card-header">
		        	<h3 class="card-title">
		        		{!! $dt['button'] !!}
		        		<button type="button" class="btn btn-default btn-flat" onclick="search_dt()">
		        			<i class="fas fa-search"></i>
		        		</button>
		        		<button type="button" class="btn btn-flat btn-info" onclick="submit_kabag()">
		        			Submit ke Kabag
		        		</button>
		        	</h3>
		      	</div>

		      	<form class="form-horizontal" id="frm_mst_pengajuan" autocomplete="off" onsubmit="return save_process()">
			        <div class="card-body">
			          	<div class="form-group row">
				            <label class="col-sm-1 col-form-label text-nowrap">No. Pengajuan</label>
				            <div class="col-sm-3">
				              	<input type="text" class="form-control form-control-sm" id="no_pengajuan" name="no_pengajuan" placeholder="No. Pengajuan" readonly>
				            </div>

				            <label class="col-sm-1 col-form-label text-nowrap">Thn. Anggaran</label>
				            <div class="col-sm-3">
				              	<select name="thn_anggaran" class="form-control form-control-sm" id="thn_anggaran" name="thn_anggaran" onchange="show_dtl_yearprev(this)";>
				              	</select>
				            </div>

				            <label class="col-sm-1 col-form-label">User</label>
				            <div class="col-sm-3">
				              	<input type="text" class="form-control form-control-sm" id="user_entry_lbl" value="{{ session('user_id') }} - {{ session('nama') }}" readonly>
				            </div>
			          	</div>
			          	<div class="form-group row">
			          		<label class="col-sm-1 col-form-label text-nowrap">Tgl. Pengajuan</label>
				            <div class="col-sm-3">
				              	<input type="text" class="form-control form-control-sm" id="tgl_entry" readonly>
				            </div>
				            
							<label class="col-sm-1 col-form-label">Departemen</label>
				            <div class="col-sm-3">
				              	<input type="text" class="form-control form-control-sm" id="departemen_lbl" readonly>
				              	<input type="hidden" id="kd_departemen" name="kd_departemen">
				            </div>

				            <label class="col-sm-1 col-form-label">Status</label>
				            <div class="col-sm-3">
				              	<input type="text" class="form-control form-control-sm" id="nm_status_approval" readonly>
				              	<input type="hidden" name="status_approval" id="status_approval">
				            </div>
			          	</div>

						<!--div class="form-group row">
				            <div class="col-sm-3">
				              	<input type="text" class="form-control form-control-sm" id="row_entry" value="<?php //return count_data();?>">
				            </div>
			          	</div-->
						
						<div class="form-group row">
							<div class="col-sm-12">
					          	<div class="card card-primary card-outline card-outline-tabs">
				              		<div class="card-header p-0 border-bottom-0">
										<ul class="nav nav-tabs" id="budget-tab" role="tablist">
											<li class="nav-item active">
												<a class="nav-link active" id="budget-tab-barang" data-toggle="pill" href="#budget-tabContent-barang" role="tab" aria-controls="budget-tabContent-barang" aria-selected="false">Item Budget</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" id="budget-tab-view" data-toggle="pill" href="#budget-tabContent-view" role="tab" aria-controls="budget-tabContent_view" aria-selected="false">Item Tahun Sebelumnya</a>
											</li>
										</ul>
				              		</div>
											

					              	<div class="card-body p-0" >
						                <div class="tab-content" id="budget-tabContent">
						                  	<div class="tab-pane fade show active" id="budget-tabContent-barang" role="tabpanel" aria-labelledby="budget-tab-barang">
						                  	</div>
											<div class="tab-pane fade show" id="budget-tabContent-view" role="tabpanel" aria-labelledby="budget-tab-view">
						                  	</div>
						                </div>
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