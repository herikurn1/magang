@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit 		= "{{ session('kd_unit') }}";
		var kd_lokasi 		= "{{ session('kd_lokasi') }}";
		var list_parent 	= {!! $dt['list_parent'] !!};
		var no 				= 1;

		$(function() {
			var o = new Option("Tidak Ada", "0");
				$('#parent_id').append(o);

			$.each(list_parent, function(x, y) {
				var o = new Option(y.nama_modul, y.modul_id);
				$('#parent_id').append(o);
			});

			add_dt();
		});

		function add_dt() {
			$('#modul_id, #nama, #controller, #parent_id, #order').val('');
			$('#nama, #controller').prop('readonly', false);
			$('#flag_aktif').prop('checked', 1);
			$('#act').val('add');
		}

		function save_dt() {
			$('#btn_save').click();
		}

		function save_process() {
			var dt = $('#frm_modul').serializeArray();

			$('#v_loading').show();
			$.ajax({
				type 	: 'POST',
				url 	: 'modul/save',
				data 	: dt,
				success : function(msg) {
					$('#v_loading').hide();
					$('#modul_id').val(msg);
					$('#nama, #controller').prop('readonly', true);
					$('#act').val('edit');
				},
				error 	: function(xhr) {
					$('#v_loading').hide();
					read_error(xhr);
				}
			});

			return false;
		}

		function search_dt() {
			$('#v_loading').show();

			var keyword = $('#sys-modal-default-keyword').val();

			$.ajax({
				type 	: 'POST',
				url 	: 'modul/search_dt',
				data 	: {
					"_token" 	: '{{ csrf_token() }}',
					"keyword"	: keyword
				},
				success : function(msg) {
					$('#v_loading').hide();
					
					var tbl = ''+
						'<div class="row">'+
				          	'<div class="col-12">'+
				            	'<div class="card">'+
				              		'<div class="card-header">'+
				                		'<h3 class="card-title">Daftar Modul</h3>'+
				              		'</div>'+

				              		'<div class="card-body table-responsive p-0" style="height: 300px;">'+
										'<table class="table table-sm table-head-fixed text-nowrap">'+
											'<thead>'+
												'<tr>'+
													'<th>Modul</th>'+
													'<th>Controller</th>'+
													'<td>#</td>'+
												'</tr>'+
											'</thead>'+
											'<tbody>'+
					'';

					$.each(msg, function(x, y){
						tbl += ''+
												'<tr>'+
													'<td>'+y.nama+'</td>'+
													'<td>'+y.controller+'</td>'+
													'<td><button type="button" class="btn btn-info btn-sm" onclick="search_dt_set(\''+y.modul_id+'\', \''+y.nama+'\', \''+y.controller+'\', \''+y.parent_id+'\', \''+y.order+'\', \''+y.pembuat+'\', \''+y.flag_aktif+'\')">Pilih</button></td>'+
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
		}

		function search_dt_set(modul_id, nama, controller, parent_id, order, pembuat, flag_aktif){
			$('#modul_id').val(modul_id);
			$('#nama').val(nama).prop('readonly', true);
			$('#controller').val(controller).prop('readonly', true);
			$('#parent_id').val(parent_id);
			$('#order').val(order);
			$('#pembuat').val(pembuat);
			$('#flag_aktif').prop('checked', parseInt(flag_aktif));
			$('#act').val('edit');
			$('#sys-modal-default').modal("hide");
		}
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
		        	</h3>
		      	</div>

		      	<form class="form-horizontal" id="frm_modul" autocomplete="off" onsubmit="return save_process()">
				  	<div class="card-body">
                  		<div class="form-group row">
                    		<label class="col-sm-2 col-form-label">Nama</label>
                    		<div class="col-sm-10">
                      			<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Modul" required>
                    		</div>
                  		</div>
						<div class="form-group row">
                    		<label class="col-sm-2 col-form-label">Controller</label>
                    		<div class="col-sm-10">
                      			<input type="text" class="form-control" id="controller" name="controller" placeholder="Controller" required>
                    		</div>
                  		</div>
						<div class="form-group row">
                    		<label class="col-sm-2 col-form-label">Parent</label>
                    		<div class="col-sm-10">
								<select name="parent_id" id="parent_id" class="form-control form-control-sm" required></select>
                    		</div>
                  		</div>
                  		<div class="form-group row">
                    		<label class="col-sm-2 col-form-label">Urut</label>
                    		<div class="col-sm-10">
                      			<input type="number" class="form-control" id="order" name="order" placeholder="Urut" required>
                    		</div>
                  		</div>
						<div class="form-group row">
                    		<label class="col-sm-2 col-form-label">Pembuat</label>
                    		<div class="col-sm-10">
                      			<input type="text" class="form-control" id="pembuat" readonly>
                    		</div>
                  		</div>
                  		<div class="form-group row">
						  	<label class="col-sm-2 col-form-label">Flag Aktif</label>
                    		<div class="col-sm-10">
                      			<div class="form-check">
                        			<input type="checkbox" class="form-check-input" id="flag_aktif" name="flag_aktif" value="1" checked>
                      			</div>
                    		</div>
                  		</div>
                	</div>
					<input type="hidden" name="modul_id" id="modul_id">
					<input type="hidden" name="act" id="act" value="add">
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