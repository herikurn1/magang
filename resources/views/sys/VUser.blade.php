@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var list_role 		= {!! $dt['list_role'] !!};
		var list_lokasi_all = {!! $dt['list_lokasi_all'] !!};
		var no 				= 1;

		$(function() {
			$('#btn_mst_delete').hide();
			add_dt();
		})

		function add_dt() {
			$('#user_id, #nama, #password, #deskripsi').val('');
			$('#flag_aktif').prop('checked', 1);
			$('#act').val('add');
			$('#user_id, #nama').prop('readonly', false);

			$('#first-tabContent-role').html(get_header_role());
			$('#first-tabContent-unit').html(get_header_unit());
		}

		function save_dt() {
			$('#btn_save').click();
		}

		function save_process() {
			var user_id = $('#user_id').val();
			var dt = $('#frm_user').serializeArray();

			$('#v_loading').show();
			$.ajax({
				type 	: 'POST',
				url 	: 'user/save',
				data 	: dt,
				success : function(msg) {
					$('#v_loading').hide();
					$('#act').val('edit');

					show_role(user_id);
					show_unit(user_id);
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
				url 	: 'user/search_dt',
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
				                		'<h3 class="card-title">Daftar User</h3>'+
				              		'</div>'+

				              		'<div class="card-body table-responsive p-0" style="height: 300px;">'+
										'<table class="table table-sm table-head-fixed text-nowrap">'+
											'<thead>'+
												'<tr>'+
													'<th>Kode User</th>'+
													'<th>Nama</th>'+
													'<td>#</td>'+
												'</tr>'+
											'</thead>'+
											'<tbody>'+
					'';

					$.each(msg, function(x, y){
						tbl += ''+
												'<tr>'+
													'<td>'+y.user_id+'</td>'+
													'<td>'+y.nama+'</td>'+
													'<td><button type="button" class="btn btn-info btn-sm" onclick="search_dt_set(\''+y.user_id+'\', \''+y.nama+'\', \''+y.deskripsi+'\', \''+y.flag_aktif+'\')">Pilih</button></td>'+
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

		function search_dt_set(user_id, nama, deskripsi, flag_aktif){
			$('#user_id, #nama').val(user_id).prop('readonly', true);
			$('#nama').val(nama);
			$('#flag_aktif').prop('checked', parseInt(flag_aktif));
			$('#password').val('123456789abcefghij');
			$('#deskripsi').val(deskripsi);
			$('#act').val('edit');
			$('#sys-modal-default').modal("hide");

			show_role(user_id);
			show_unit(user_id);
		}

		function add_role() {
			$('#tbl_role').append(''+
				'<tr id="add_role_'+no+'">'+
					'<td>'+
						'<select id="add_role_id_'+no+'" name="add_role_id[]" class="form-control form-control-sm">'+
						'</select>'+
					'</td>'+
					'<td>'+
						'<button type="button" class="btn btn-default btn-flat btn-sm" onclick="$(\'#add_role_'+no+'\').remove()">'+
							'<i class="fas fa-times"></i>'+
						'</button>'+
					'</td>'+
				'</tr>'+
			'');

			select_role('add_role_id_'+no);

			no++;
		}

		function show_role(user_id) {
			$('#v_loading').show();
			$.ajax({
				type 	: 'POST',
				url 	: 'user/show_role',
				data 	: {
					"_token"	: '{{ csrf_token() }}',
					'user_id'	: user_id
				},
				success : function(msg) {
					$('#v_loading').hide();

					$('#first-tabContent-role').html(get_header_role());
					
					$.each(msg, function(x, y){
						var tbl = ''+
							'<tr>'+
								'<td>'+y.nama+'</td>'+
								'<td>'+
									'<button type="button" class="btn btn-default btn-flat btn-sm" onclick="delete_role(\''+user_id+'\', \''+y.user_role_id+'\');"><i class="fas fa-times"></i></button>'+
								'</td>'+
							'</tr>'+
						'';

						$('#tbl_role').append(tbl);
					});
				},
				error 	: function(xhr) {
					$('#v_loading').hide();
					read_error(xhr);
				}
			});
		}

		function delete_role(user_id, user_role_id) {
			var ans = confirm('Apakah yakin ingin menghapus Role ini ?');

			if(ans){
				$('#v_loading').show();
				$.ajax({
					type 	: 'POST',
					url 	: 'user/delete_role',
					data 	: {
						"_token"		: '{{ csrf_token() }}',
						"user_role_id" 	: user_role_id
					},
					success : function(msg) {
						$('#v_loading').hide();
						show_role(user_id);
					},
					error 	: function(xhr) {
						$('#v_loading').hide();
						read_error(xhr);
					}
				});
			}
		}

		function select_role(id){
			$.each(list_role, function(x, y) {
				var o = new Option(y.nama, y.role_id);
				$('#'+id).append(o);
			});
		}

		function add_unit() {
			$('#tbl_unit').append(''+
				'<tr id="add_unit_'+no+'">'+
					'<td>'+
						'<select id="add_select_unit_'+no+'" name="add_unit[]" class="form-control form-control-sm">'+
						'</select>'+
					'</td>'+
					'<td>'+
						'<button type="button" class="btn btn-default btn-flat btn-sm" onclick="$(\'#add_unit_'+no+'\').remove()">'+
							'<i class="fas fa-times"></i>'+
						'</button>'+
					'</td>'+
				'</tr>'+
			'');

			select_unit('add_select_unit_'+no);

			no++;
		}

		function show_unit(user_id) {
			$('#v_loading').show();
			$.ajax({
				type 	: 'POST',
				url 	: 'user/show_unit',
				data 	: {
					"_token"	: '{{ csrf_token() }}',
					'user_id'	: user_id
				},
				success : function(msg) {
					$('#v_loading').hide();

					$('#first-tabContent-unit').html(get_header_unit());
					
					$.each(msg, function(x, y){
						var tbl = ''+
							'<tr>'+
								'<td>'+y.kd_unit+' => ' + y.kd_lokasi + ' - ' + y.nm_lokasi + '</td>'+
								'<td>'+
									'<button type="button" class="btn btn-default btn-flat btn-sm" onclick="delete_unit(\''+y.lokasi_role_id+'\', \''+user_id+'\', \''+y.kd_unit+'\', \''+y.kd_lokasi+'\');"><i class="fas fa-times"></i></button>'+
								'</td>'+
							'</tr>'+
						'';

						$('#tbl_unit').append(tbl);
					});
				},
				error 	: function(xhr) {
					$('#v_loading').hide();
					read_error(xhr);
				}
			});
		}

		function delete_unit(lokasi_role_id, user_id, kd_unit, kd_lokasi) {
			var ans = confirm('Apakah yakin ingin menghapus Unit ini ?');

			if(ans){
				$('#v_loading').show();
				$.ajax({
					type 	: 'POST',
					url 	: 'user/delete_unit',
					data 	: {
						"_token"			: '{{ csrf_token() }}',
						"lokasi_role_id"	: lokasi_role_id,
						"user_id" 			: user_id,
						"kd_unit"			: kd_unit,
						"kd_lokasi"			: kd_lokasi
					},
					success : function(msg) {
						$('#v_loading').hide();
						show_unit(user_id);
					},
					error 	: function(xhr) {
						$('#v_loading').hide();
						read_error(xhr);
					}
				});
			}
		}

		function select_unit(id){
			$.each(list_lokasi_all, function(x, y) {
				var o = new Option(y.kd_unit + ' => ' + y.kd_lokasi + ' - ' + y.nm_lokasi, y.kd_unit+'|'+y.kd_lokasi);
				$('#'+id).append(o);
			});
		}

		function get_header_role(){
			var tbl = ''+
				'<div class="row">'+
		          	'<div class="col-12">'+
		            	'<div class="card">'+
		              		'<div class="card-header">'+
		                		'<h3 class="card-title">'+
	                			'<button type="button" class="btn btn-default btn-flat btn-sm" onclick="add_role()" style="margin: 5px;">'+
					                '<i class="fas fa-plus"></i>'+
					            '</button></h3>'+
		              		'</div>'+

		              		'<div class="card-body table-responsive p-0" style="height: 400px;">'+
								'<table id="tbl_role" class="table">'+
					          		'<thead>'+
					          			'<tr>'+
					          				'<th>Nama</th>'+
					          				'<th>#</th>'+
					          			'</tr>'+
					          		'</thead>'+
					          	'</table>'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div>'+
			'';

			return tbl;
		}

		function get_header_unit(){
			var tbl = ''+
				'<div class="row">'+
		          	'<div class="col-12">'+
		            	'<div class="card">'+
		              		'<div class="card-header">'+
		                		'<h3 class="card-title">'+
	                			'<button type="button" class="btn btn-default btn-flat btn-sm" onclick="add_unit()" style="margin: 5px;">'+
					                '<i class="fas fa-plus"></i>'+
					            '</button></h3>'+
		              		'</div>'+

		              		'<div class="card-body table-responsive p-0" style="height: 400px;">'+
								'<table id="tbl_unit" class="table">'+
					          		'<thead>'+
					          			'<tr>'+
					          				'<th>Nama</th>'+
					          				'<th>#</th>'+
					          			'</tr>'+
					          		'</thead>'+
					          	'</table>'+
							'</div>'+
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
		      	<div class="card-header">
		        	<h3 class="card-title">
		        		{!! $dt['button'] !!}
						<button type="button" class="btn btn-default btn-flat" onclick="search_dt()">
		        			<i class="fas fa-search"></i>
		        		</button>
		        	</h3>
		      	</div>

		      	<form class="form-horizontal" id="frm_user" autocomplete="off" onsubmit="return save_process()">
				  	<div class="card-body">
					  <div class="row">
                    		<div class="col-sm-2">
                      		<!-- text input -->
                      			<div class="form-group">
                        			<label>Kode User</label>
                        			<input type="text" class="form-control" id="user_id" name="user_id" placeholder="User ID" required>
                     			 </div>
                    		</div>
                    		<div class="col-sm-3">
                      			<div class="form-group">
                        			<label>Nama</label>
                        			<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" required>
                      			</div>
                    		</div>
							<div class="col-sm-3">
                      		<!-- text input -->
                      			<div class="form-group">
                        			<label>Password</label>
                        			<input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                     			 </div>
                    		</div>
                    		<div class="col-sm-3">
                      		<!-- text input -->
                      			<div class="form-group">
                        			<label>Deskripsi</label>
                        			<input type="text" class="form-control" id="deskripsi" name="deskripsi" placeholder="Deskripsi" required>
                     			 </div>
                    		</div>
                    		<div class="col-sm-1">
                      			<div class="form-group">
                        			<label>Aktif</label>
									<div class="form-check">
                        				<input type="checkbox" class="form-check-input" id="flag_aktif" name="flag_aktif" value="1" checked>
									</div>
                      			</div>
                    		</div>
                  		</div>

						<div class="form-group row">
							<div class="col-sm-12">
					          	<div class="card card-primary card-outline card-outline-tabs">
				              		<div class="card-header p-0 border-bottom-0">
										<ul class="nav nav-tabs" id="first-tab" role="tablist">
											<li class="nav-item">
												<a class="nav-link active" id="first-tab-role" data-toggle="pill" href="#first-tabContent-role" role="tab" aria-controls="first-tabContent-role" aria-selected="true">Role</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" id="first-tab-unit" data-toggle="pill" href="#first-tabContent-unit" role="tab" aria-controls="first-tabContent-unit" aria-selected="true">Unit</a>
											</li>
										</ul>
				              		</div>

					              	<div class="card-body p-0" >
						                <div class="tab-content" id="first-tabContent">
						                  	<div class="tab-pane fade show active" id="first-tabContent-role" role="tabpanel" aria-labelledby="first-tab-role">
						                     	
						                  	</div>

											<div class="tab-pane fade show" id="first-tabContent-unit" role="tabpanel" aria-labelledby="first-tab-unit">
						                     	
											</div>
						                </div>
					              	</div>
				            	</div>
				            </div>
		            	</div>
                	</div>
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