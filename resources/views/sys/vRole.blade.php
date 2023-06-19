@extends('layouts.template')

@section('js')
	<script type="text/javascript">
		var kd_unit 		= "{{ session('kd_unit') }}";
		var kd_lokasi 		= "{{ session('kd_lokasi') }}";
		var list_modul 		= {!! $dt['list_modul'] !!};
		var no 				= 1;
		
		$(function(){
			$('#btn_mst_delete').hide();
			add_dt();
		});

		function select_modul(id){
			$.each(list_modul, function(x, y) {
				var o = new Option(y.nama_modul + ' => ' +y.controller, y.modul_id);
				$('#'+id).append(o);
			});
		}

		function add_dt() {
			$('#role_id, #nama').val('');
			$('#act').val('add');
			$('#nama').prop('readonly', false);
			$('#first-tabContent-modul').html(get_header());
		}

		function save_dt() {
			$('#btn_save').click();
		}

		function save_process() {
			var role_id = $('#role_id').val();
			var act 	= $('#act').val();
			var dt 		= $('#frm_role').serializeArray();

			$('#v_loading').show();
			$.ajax({
				type 	: 'POST',
				url 	: 'role/save',
				data 	: dt,
				success : function(msg) {
					$('#v_loading').hide();

					if(act == "add"){
						role_id = msg;
						$('#role_id').val(role_id);
					}
					
					$('#nama').prop('readonly', true);
					$('#act').val('edit');
					
					show_modul(role_id);
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
				url 	: 'role/search_dt',
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
				                		'<h3 class="card-title">Daftar Role</h3>'+
				              		'</div>'+

				              		'<div class="card-body table-responsive p-0" style="height: 300px;">'+
										'<table class="table table-sm table-head-fixed text-nowrap">'+
											'<thead>'+
												'<tr>'+
													'<th>Nama</th>'+
													'<td>#</td>'+
												'</tr>'+
											'</thead>'+
											'<tbody>'+
					'';

					$.each(msg, function(x, y){
						tbl += ''+
												'<tr>'+
													'<td>'+y.nama+'</td>'+
													'<td><button type="button" class="btn btn-info btn-sm" onclick="search_dt_set(\''+y.role_id+'\', \''+y.nama+'\')">Pilih</button></td>'+
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

		function search_dt_set(role_id, nama){
			$('#role_id').val(role_id);
			$('#nama').val(nama).prop('readonly', true);
			$('#act').val('edit');
			$('#sys-modal-default').modal("hide");

			show_modul(role_id);
		}

		function add_modul(){
			$('#tbl_modul').append(''+
				'<tr id="add_modul_'+no+'">'+
					'<td>'+
						'<select id="add_modul_id_'+no+'" name="add_modul_id[]" class="form-control form-control-sm">'+
						'</select>'+
					'</td>'+
					'<td>'+
						'<select name="add_akses[]" class="form-control form-control-sm">'+
							'<option value="F">Full Access</option>'+
							'<option value="V">View</option>'+
							'<option value="S">Save</option>'+
							'<option value="D">Delete</option>'+
						'</select>'+
					'</td>'+
					'<td>'+
						'<button type="button" class="btn btn-default btn-flat btn-sm" onclick="$(\'#add_modul_'+no+'\').remove()">'+
							'<i class="fas fa-times"></i>'+
						'</button>'+
					'</td>'+
				'</tr>'+
			'');

			select_modul('add_modul_id_'+no);

			no++;
		}
		
		function show_modul(role_id) {
			$('#v_loading').show();
			$.ajax({
				type 	: 'POST',
				url 	: 'role/show_modul',
				data 	: {
					"_token"	: '{{ csrf_token() }}',
					'role_id'	: role_id
				},
				success : function(msg) {
					$('#v_loading').hide();

					$('#first-tabContent-modul').html(get_header());
					
					$.each(msg, function(x, y){
						var akses = 'View';

						if(y.m_save == "1" && y.m_delete == "1") akses = 'Full Access';
						if(y.m_save == "1" && y.m_delete == "0") akses = 'Save';
						if(y.m_save == "0" && y.m_delete == "1") akses = 'Delete';
						
						var tbl = ''+
							'<tr>'+
								'<td>'+y.nama_modul+' => '+y.controller+'</td>'+
								'<td>'+akses+'</td>'+
								'<td>'+
									'<button type="button" class="btn btn-default btn-flat btn-sm" onclick="delete_modul(\''+role_id+'\', \''+y.role_priv_id+'\');"><i class="fas fa-times"></i></button>'+
								'</td>'+
							'</tr>'+
						'';

						$('#tbl_modul').append(tbl);
					});
				},
				error 	: function(xhr) {
					$('#v_loading').hide();
					read_error(xhr);
				}
			});
		}

		function delete_modul(role_id, role_priv_id) {
			var ans = confirm('Apakah yakin ingin menghapus Modul ini ?');

			if(ans){
				$('#v_loading').show();
				$.ajax({
					type 	: 'POST',
					url 	: 'role/delete_modul',
					data 	: {
						"_token"		: '{{ csrf_token() }}',
						"role_priv_id" 	: role_priv_id
					},
					success : function(msg) {
						$('#v_loading').hide();
						show_modul(role_id);
					},
					error 	: function(xhr) {
						$('#v_loading').hide();
						read_error(xhr);
					}
				});
			}
		}

		function get_header(){
			var tbl = ''+
				'<div class="row">'+
		          	'<div class="col-12">'+
		            	'<div class="card">'+
		              		'<div class="card-header">'+
		                		'<h3 class="card-title">'+
	                			'<button type="button" class="btn btn-default btn-flat btn-sm" onclick="add_modul()" style="margin: 5px;">'+
					                '<i class="fas fa-plus"></i>'+
					            '</button></h3>'+
		              		'</div>'+

		              		'<div class="card-body table-responsive p-0" style="height: 400px;">'+
								'<table id="tbl_modul" class="table">'+
					          		'<thead>'+
					          			'<tr>'+
					          				'<th>Nama</th>'+
											'<th>Akses</th>'+
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

		      	<form class="form-horizontal" id="frm_role" autocomplete="off" onsubmit="return save_process()">
				  	<div class="card-body">
						<div class="form-group row">
                    		<label class="col-sm-2 col-form-label">Nama</label>
                    		<div class="col-sm-10">
                      			<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" required>
                    		</div>
                  		</div>

						<div class="form-group row">
							<div class="col-sm-12">
					          	<div class="card card-primary card-outline card-outline-tabs">
				              		<div class="card-header p-0 border-bottom-0">
										<ul class="nav nav-tabs" id="first-tab" role="tablist">
											<li class="nav-item">
												<a class="nav-link active" id="first-tab-modul" data-toggle="pill" href="#first-tabContent-modul" role="tab" aria-controls="first-tabContent-modul" aria-selected="true">Modul</a>
											</li>
										</ul>
				              		</div>

					              	<div class="card-body p-0" >
						                <div class="tab-content" id="first-tabContent">
						                  	<div class="tab-pane fade show active" id="first-tabContent-modul" role="tabpanel" aria-labelledby="first-tab-modul">
						                     	
						                  	</div>
						                </div>
					              	</div>
				            	</div>
				            </div>
		            	</div>
                	</div>
					<input type="hidden" name="role_id" id="role_id">
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