@extends('layouts.template')

@section('css')
	<!-- DataTables --> 
	<link rel="stylesheet" href="{{ url('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
	<link rel="stylesheet" href="{{ url('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="{{ url('adminlte/plugins/summernote/summernote-bs4.css') }}">

	<style>
		.btn-ftr{
			margin-top: 1rem;
			text-align: center;
		}
		.btn-ftr button{
			border-radius: 8px!important;
			background-color: #A2D6F9 !important;
			color: #000000 !important;
			border: none !important;
		}
		.ftr-bottom{
			margin-top: 1rem;
		}

		.p-15{
			padding: 15px;
		}
	</style>
@endsection

@section('js') 
	<script src="{{ url('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ url('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
	<script src="{{ url('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
	<script src="{{ url('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

	<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

	<script src="{{ url('adminlte/plugins/summernote/summernote-bs4.min.js') }}"></script>
	<script src="{{ url('bower_components/autoNumeric/autoNumeric.js') }}"></script>
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script type="text/javascript">

		var no 				= 1;
		var kd_unit         = "{{ session('kd_unit') }}";
		var kd_lokasi       = "{{ session('kd_lokasi') }}";

		$(function() {
			$('#btn_mst_delete').hide();
			add_dt();

			$('#tbl-unit').DataTable({
				"processing": false,
				"serverSide": false,
				"searching": false,
				"paging": false,
				"ordering": false,
				"info": false,
			});
			$("#tbl-unit").find('tbody').empty();

			$('#tbl-zona').DataTable({
				"processing": false,
				"serverSide": false,
				"searching" : false,
				"paging"    : false,
				"ordering"  : true,
				"info"      : false,
			});
			$("#tbl-zona").find('tbody').empty();
		})

		function add_dt() {
			$('#kd_user, #nm_user, #password, #no_hp, #email, #tradename').val('');
			$('#flag_aktif').prop('checked', 1);
			$('#act').val('add');
			$('#body-zona').html("");
			$('#body-unit').html("");
		}

		function save_dt() {
			$('#btn_save').click();
		}

		function save_process() {
			var user_id = $('#kd_user').val();
			var dt = $('#frm_user').serializeArray();

			$.ajax({
				type 	: 'POST',
				url 	: 'user/save',
				data 	: dt,
				success : function(msg) {
					$('#act').val('edit');

					var data = JSON.parse(msg);
					$.each(data, function(i, val){						
						if (val.code == 'S200') {
							Swal.fire({
								position: 'center',
								icon: 'success',
								title: val.Message,
								showConfirmButton: false,
								timer: 3000
							}) 
							$('#kd_user').val(val.kd_user);
						} else {
							Swal.fire({
								position: 'center',
								icon: 'success',
								title: val.Message,
								showConfirmButton: false,
								timer: 3000
							}) 
						}
					})

					
					show_zona(user_id);
					show_unit(user_id);
				},
				error 	: function(xhr) {										
					read_error(xhr);
				}
			});

			return false;
		}

		function search_dt() {
			var keyword = $('#sys-modal-default-keyword').val();

			$.ajax({
				type 	: 'POST',
				url 	: 'user/search-dt',
				data 	: {
					"_token" 	: '{{ csrf_token() }}',
					"keyword"	: keyword
				},
				success : function(msg) {
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
													'<td>'+y.kd_user+'</td>'+
													'<td>'+y.name+'</td>'+
													'<td><button type="button" class="btn btn-info btn-sm" onclick="search_dt_set(\''+y.id+'\',\''+y.kd_user+'\', \''+y.name+'\', \''+y.email+'\', \''+y.no_hp+'\', \''+y.flag_aktif+'\', \''+y.tradename+'\')">Pilih</button></td>'+
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
					read_error(xhr);
				}
			});
		}

		function search_dt_set(id, kd_user, name, email, no_hp, flag_aktif, tradename){
			$('#id_user').val(id);
			$('#kd_user').val(kd_user).prop('readonly', true);
			$('#nm_user').val(name);
			$('#email').val(email);
			$('#tradename').val(tradename);
			if (flag_aktif == "Y") {
				$('#flag_aktif').prop('checked', true);
			}else{
				$('#flag_aktif').prop('checked', false);
			}
			$('#password').val('123456789abcefghij');
			$('#no_hp').val(no_hp);

			$('#act').val('edit');
			$('#sys-modal-default').modal("hide");

			show_zona(kd_user);
			show_unit(kd_user);
		}

		function add_dtl_unit() {
			$('#body-unit').append(''+
				'<tr id="add_unit_'+no+'">'+
					'<td>'+
						'<select id="add_select_unit_'+no+'" name="add_unit[]" class="form-control form-control-sm">'+
						'</select>'+
					'</td>'+
					'<td class="text-center">'+
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
			$.ajax({
				type 	: 'POST',
				url 	: 'user/show-unit',
				data 	: {
					"_token"	: '{{ csrf_token() }}',
					'user_id'	: user_id
				},
				success : function(msg) {
					$('#body-unit').html("");
					//$('#first-tabContent-unit').html(get_header_unit());
					
					$.each(msg, function(x, y){
						var tbl = ''+
							'<tr>'+
								'<td>'+y.UNIT_ID+' => ' + y.NAMA + '</td>'+
								'<td class="text-center">'+
									'<button type="button" class="btn btn-default btn-flat btn-sm" onclick="delete_unit(\''+y.UNIT_ID+'\', \''+user_id+'\');"><i class="fas fa-trash"></i></button>'+
								'</td>'+
							'</tr>'+
						'';

						$('#body-unit').append(tbl);
					});
				},
				error 	: function(xhr) {
					read_error(xhr);
				}
			});
		}

		function delete_unit(unit_id, user_id) {
			var ans = confirm('Apakah yakin ingin menghapus Unit ini ?');

			if(ans){
				$.ajax({
					type 	: 'POST',
					url 	: 'user/delete-unit',
					data 	: {
						"_token"			: '{{ csrf_token() }}',
						"user_id" 			: user_id,
						"kd_unit"			: unit_id
					},
					success : function(msg) {
						show_unit(user_id);
					},
					error 	: function(xhr) {
						read_error(xhr);
					}
				});
			}
		}

		function select_unit(id){
			$.ajax({
				type 	: 'POST',
				url 	: 'user/get-unit',
				data 	: {
					"_token"	: '{{ csrf_token() }}'
				},
				success : function(msg) {
					$.each(msg, function(x, y) {
						var o = new Option(y.UNIT_ID + ' => ' + y.NAMA, y.UNIT_ID);
						$('#'+id).append(o);
					});
				}
			});
			
		}

		function add_dtl_zona() {
			$('#body-zona').append(''+
				'<tr id="add_zona_'+no+'">'+
					'<td>'+
						'<select id="add_select_unit_zona_'+no+'" name="add_unit_zona[]" class="form-control form-control-sm unit_zona">'+
						'</select>'+
					'</td>'+
					'<td>'+
						'<select id="add_select_zona_'+no+'" name="add_zona[]" class="form-control form-control-sm zona">'+
						'</select>'+
					'</td>'+
					'<td>'+
						'<select id="add_select_stok_'+no+'" name="add_stok[]" class="form-control form-control-sm stok">'+
						'</select>'+
					'</td>'+
					'<td style="display: none;">'+
						'<input type="text" class="form-control form-control-sm" id="add_blok_'+no+'" name="add_blok[]" required>'+
					'</td>'+
					'<td style="display: none;">'+
						'<input type="text" class="form-control form-control-sm" id="add_nomer_'+no+'" name="add_nomer[]" required>'+
					'</td>'+
					'<td>'+
						'<input type="text" class="form-control form-control-sm" id="add_pjs_'+no+'" name="add_pjs[]" readonly>'+
						'<input type="hidden" class="form-control form-control-sm" id="add_nasabah_id_'+no+'" name="add_nasabah_id[]" readonly>'+
						'<input type="hidden" class="form-control form-control-sm" id="add_stok_id_'+no+'" name="add_stok_id[]" readonly>'+
					'</td>'+
					'<td>'+
						'<select id="add_pemilik_'+no+'" name="add_pemilik[]" class="form-control form-control-sm">'+
							'<option value="Y">Ya</option>'+
							'<option value="N">Tidak</option>'+
						'</select>'+
					'</td>'+
					'<td class="text-center">'+
						'<select id="add_default_'+no+'" name="add_default[]" class="form-control form-control-sm">'+
							'<option value="Y">Ya</option>'+
							'<option value="N" selected>Tidak</option>'+
						'</select>'+
					'</td>'+
					'<td class="text-center">'+
						'<button type="button" class="btn btn-default btn-flat btn-sm" onclick="$(\'#add_zona_'+no+'\').remove()">'+
							'<i class="fas fa-times"></i>'+
						'</button>'+
					'</td>'+
				'</tr>'+
			'');

			$(function () {
				select_unit_zona('add_select_unit_zona_'+no, 'add_select_zona_'+no, 'add_select_stok_'+no, 'add_blok_'+no, 'add_nomer_'+no, 'add_pjs_'+no, 'add_nasabah_id_'+no, 'add_stok_id_'+no);
				//select_zona('add_select_zona_'+no,);
				// var unit = '#add_select_unit_zona_'+no;

				
				// $(unit).change(function(e){
				// 	e.preventDefault();
				// 	var kd_perusahaan = $(unit).val();

					
				// });
				no++;
			});
		}

		function show_zona(user_id) {
			$.ajax({
				type 	: 'POST',
				url 	: 'user/show-zona',
				data 	: {
					"_token"	: '{{ csrf_token() }}',
					'user_id'	: user_id
				},
				success : function(msg) {
					$('#body-zona').html("");
					//$('#first-tabContent-unit').html(get_header_unit());
					
					$.each(msg, function(x, y){
						if (y.FLAG_PEMILIK == 'Y') {
							var pemilik = 'YA';
						}else{
							var pemilik = 'TIDAK';
						};

						if (y.FLAG_DEFAULT == 'Y') {
							var kd_default = 'YA';
						}else{
							var kd_default = 'TIDAK';
						};
						var tbl = ''+
							'<tr>'+
								'<td class="text-center">'+
									y.KD_PERUSAHAAN+
								'</td>'+
								'<td class="text-center">'+
									y.NM_ZONA+
								'</td>'+
								'<td class="text-center">'+
									y.BLOKNO+
								'</td>'+
								// '<td class="text-center">'+
								// 	y.BLOK+
								// '</td>'+
								// '<td class="text-center">'+
								// 	y.NOMOR+
								// '</td>'+
								'<td>'+
									y.NO_PJS+
								'</td>'+
								'<td class="text-center">'+
									pemilik+
								'</td>'+
								'<td class="text-center">'+
									kd_default+
								'</td>'+
								'<td class="text-center">'+
									'<button type="button" class="btn btn-default btn-flat btn-sm" onclick="delete_zona(\''+y.KD_PERUSAHAAN+'\', \''+y.KD_USER+'\', \''+y.KD_ZONA+'\', \''+y.NO_PJS+'\');"><i class="fas fa-trash"></i></button>'+
								'</td>'+
							'</tr>'+
						'';

						$('#body-zona').append(tbl);
					});
				},
				error 	: function(xhr) {
					read_error(xhr);
				}
			});
		}

		function delete_zona(kd_perusahaan, kd_user, kd_zona, no_pjs) {
			var ans = confirm('Apakah yakin ingin menghapus Unit ini ?');

			if(ans){
				$.ajax({
					type 	: 'POST',
					url 	: 'user/delete-zona',
					data 	: {
						"_token"			: '{{ csrf_token() }}',
						"kd_unit" 			: kd_perusahaan,
						"kd_user"			: kd_user,
						"kd_zona"			: kd_zona,
						"no_pjs"			: no_pjs,
					},
					success : function(msg) {
						show_zona(kd_user);
					},
					error 	: function(xhr) {
						read_error(xhr);
					}
				});
			}
		}

		function select_unit_zona(unit, zona, stok, blok, nomer, pjs, nasabah, stok_id){
			$('#'+unit).html("");
			var u = '<option DISABLED SELECTED VALUE>Pilih Unit</option>';
			$('#'+unit).append(u);
			$.ajax({
				type 	: 'POST',
				url 	: 'user/get-unit',
				data 	: {
					"_token"	: '{{ csrf_token() }}'
				},
				success : function(msg) {
					$.each(msg, function(x, y) {
						var o = '<option value="'+y.UNIT_ID+'">'+y.UNIT_ID+'</option>';
						//new Option(y.UNIT_ID, y.UNIT_ID);
						$('#'+unit).append(o);
					});
				}
			});

			$('#'+unit).change(function(e){
            	e.preventDefault();          
				var kd_unit = $('#'+unit).val();
				$('#'+zona).html("");
				var z = '<option DISABLED SELECTED VALUE>Pilih Zona</option>';
				$('#'+zona).append(z);
				$.ajax({
					type 	: 'POST',
					url 	: 'user/get-zona',
					data 	: {
						"_token"	: '{{ csrf_token() }}',
						"kd_unit"	: kd_unit,
					},
					success : function(msg) {
						$.each(msg, function(x, y) {
							var o = '<option value="'+y.ZONE_CD+'">'+y.NM_ZONA+'</option>';
							//new Option(y.NM_ZONA, y.KD_ZONA);
							$('#'+zona).append(o);
						});
					}
				});
			})

			$('#'+zona).change(function(e){
            	e.preventDefault();

				var kd_unit = $('#'+unit).val();          
				var kd_zona = $('#'+zona).val();

				$('#'+stok).html("");
				var s = '<option DISABLED SELECTED VALUE>Pilih Nomor</option>';
				$('#'+stok).append(s);
				$.ajax({
					type 	: 'POST',
					url 	: 'user/get-stok',
					data 	: {
						"_token"	: '{{ csrf_token() }}',
						"kd_unit"	: kd_unit,
						"kd_zona"	: kd_zona,
					},
					success : function(msg) {
						$.each(msg, function(x, y) {
							var o = '<option value="'+y.NOMOR+'">'+y.NOMOR+'</option>';
							//new Option(y.NM_ZONA, y.KD_ZONA);
							$('#'+stok).append(o);
						});
					}
				});
			})

			$('#'+stok).change(function(e){
            	e.preventDefault();

				var kd_unit = $('#'+unit).val();          
				var kd_zona = $('#'+zona).val();
				var kd_stok = $('#'+stok).val();

				$('#'+blok).val("");
				$('#'+nomer).val("");
				$('#'+nasabah).val("");
				$('#'+stok_id).val("");
				$.ajax({
					type 	: 'POST',
					url 	: 'user/get-blok',
					data 	: {
						"_token"	: '{{ csrf_token() }}',
						"kd_unit"	: kd_unit,
						"kd_zona"	: kd_zona,
						"kd_stok"	: kd_stok
					},
					success : function(msg) {
						$.each(msg, function(x, y) {
							$('#'+pjs).val(y.NO_PJS);
							$('#'+blok).val(y.BLOK);
							$('#'+nomer).val(y.NOMOR);
							$('#'+nasabah).val(y.NASABAH_ID);
							$('#'+stok_id).val(y.STOK_ID);
						});
					}
				});
			})
			
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
							<div class="col-md-6">
							<input type="hidden" class="form-control form-control-sm" id="id_user" name="id_user">
								<div class="form-group">
                        			<label>Name</label>
                        			<input type="text" class="form-control form-control-sm" id="nm_user" name="nm_user" placeholder="e.g: Jhon" required>
                     			</div>
								<div class="form-group">
                        			<label>Email</label>
                        			<input type="text" class="form-control form-control-sm" id="email" name="email" placeholder="e.g: example@example.com" required>
                     			</div>
								<div class="form-group">
                        			<label>Password</label>
                        			<input type="password" class="form-control form-control-sm" id="password" name="password" required>
                     			</div>

								<div class="form-group">
                        			<label>Trade Name</label>
                        			<input type="text" class="form-control form-control-sm" id="tradename" name="tradename" placeholder="Optional">
                     			</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
                        			<label>User ID</label>
                        			<input type="text" class="form-control form-control-sm" id="kd_user" name="kd_user" placeholder="[Auto Generate]" required readonly>
                     			</div>
								<div class="form-group">
                        			<label>No Telephone</label>
                        			<input type="number" class="form-control form-control-sm" id="no_hp" name="no_hp" placeholder="e.g: 08xxxxxxx" required>
                     			</div>
								<div class="form-group">
                        			<label>Flag Aktif</label>
                        			<input type="checkbox" class="form-control form-control-sm" id="flag_aktif" name="flag_aktif" value="Y" style="width: 15px;">
                     			</div>
							</div>
						</div>

						<div class="form-group row">
							<div class="col-sm-12">
					          	<div class="card card-primary card-outline card-outline-tabs">
				              		<div class="card-header p-0 border-bottom-0">
										<ul class="nav nav-tabs" id="first-tab" role="tablist">
											<li class="nav-item">
												<a class="nav-link active" id="first-tab-role" data-toggle="pill" href="#first-tabContent-unit" role="tab" aria-controls="first-tabContent-unit" aria-selected="true">Unit</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" id="first-tab-unit" data-toggle="pill" href="#first-tabContent-zona" role="tab" aria-controls="first-tabContent-zona" aria-selected="true">Zona</a>
											</li>
										</ul>
				              		</div>

					              	<div class="card-body p-0" >
						                <div class="tab-content" id="first-tabContent">
						                  	<div class="tab-pane fade show active" id="first-tabContent-unit" role="tabpanel" aria-labelledby="first-tab-unit">
												<div class="form-group">
													<div class="p-15">
														<button type="button" class="btn btn-default add_unit" id="add_unit" onclick="add_dtl_unit()"><i class="fas fa-plus"></i></button>
													</div>
													<table class="table table-bordered tbl-unit" id="tbl-unit">
														<thead>
															<th class="text-center">Nama Unit</th>
															<th class="text-center">#</th>
														</thead>
														<tbody id="body-unit">

														</tbody>
													</table>
												</div>
						                  	</div>
											<div class="tab-pane fade show" id="first-tabContent-zona" role="tabpanel" aria-labelledby="first-tab-zona">
												<div class="form-group">
													<div class="p-15">
														<button type="button" class="btn btn-default add_zona" id="add_zona" onclick="add_dtl_zona()"><i class="fas fa-plus"></i></button>
													</div>
													<table class="table table-bordered tbl-zona" id="tbl-zona" style="width: 100%;">
														<thead>
															<th class="text-center">Kode Unit</th>
															<th class="text-center">Kode Zona</th>
															<th class="text-center">Blok No</th>
															<!-- <th class="text-center">Blok</th>
															<th class="text-center">Nomer</th> -->
															<th class="text-center">No PJS</th>
															<th class="text-center">Pemilik</th>
															<th class="text-center">Default</th>
															<th class="text-center">#</th>
														</thead>
														<tbody id="body-zona">

														</tbody>
													</table>
												</div>
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