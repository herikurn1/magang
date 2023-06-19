<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<title>{{ SysController::title(Request::segment(1).'/'.Request::segment(2)) }}</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Font Awesome -->
	<!-- <link rel="stylesheet" href="{{ url('adminlte/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet"> -->
	<!-- <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.1.1/css/all.css" /> -->
	<link rel="stylesheet" href="{{ url('adminlte/plugins/fontawesome-free/css/all.css') }}" rel="stylesheet">

	<!-- overlayScrollbars -->
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	<!-- Include Bootstrap Datepicker -->
	<link rel="stylesheet" href="{{ url('bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css') }}"/>
	
	<link rel="apple-touch-icon" sizes="180x180" href="{{ url('img/favicon/apple-touch-icon.png') }}">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ url('img/favicon/favicon-32x32.png') }}">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ url('img/favicon/favicon-16x16.png') }}">
	<link rel="manifest" href="{{ url('img/favicon/site.webmanifest') }}">

	<style type="text/css">
		.link_cursor{
		    cursor: pointer;
		    color: #007bff;
		}

		#loading{
            position: fixed;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            background-color: #171515;
            z-index: 2000;
            opacity: 0.6;
            display: none;
        }

        .loader{
            position: absolute;
            top: 40%;
            left: 50%;
        }

        #loading img{
            width: 50px;
        }
	</style>

	@yield('css')

	<!-- Theme style -->
	<link rel="stylesheet" href="{{ url('adminlte/dist/css/adminlte.css') }}" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini sidebar-collapse">
<!-- Site wrapper -->
<div class="wrapper">
	<!-- Navbar -->
	<nav class="main-header navbar navbar-expand navbar-white navbar-light">
		<!-- Left navbar links -->
		<ul class="navbar-nav">
			<li class="nav-item">
				<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
			</li>
			<li class="nav-item d-none d-sm-inline-block">
				<a href="/" class="nav-link">Home</a>
			</li>
		</ul>

		<!-- SEARCH FORM -->
		<form class="form-inline ml-3">
			<div class="input-group input-group-sm">
				<input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search" style="width: 230px;" value="Unit : {{ session('kd_unit') }} & Lokasi : {{ session('kd_lokasi') }} - {{ session('nm_lokasi') }}" readonly onclick="sys_search_unit();">
				<div class="input-group-append">
					<button class="btn btn-navbar" type="button" onclick="sys_search_unit();">
						<i class="fas fa-search"></i>
					</button>
				</div>
			</div>
		</form>

		<div class="ml-3">
			@include('partials.button_bookmark')
		</div>

		<!-- Right navbar links -->
		<ul class="navbar-nav ml-auto">
			<!-- Messages Dropdown Menu -->
			<li class="nav-item d-none d-sm-inline-block">
				<a href="#" class="nav-link">Hi, {{ session('nama') }} ({{ session('user_id') }})</a>
			</li>
			<li class="nav-item d-none d-sm-inline-block">
				<a href="../signout" class="nav-link">Logout</a>
			</li>
			<!-- <li class="nav-item">
				<a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
					<i class="fas fa-th-large"></i>
				</a>
			</li> -->
		</ul>
	</nav>
	<!-- /.navbar -->

	<!-- Main Sidebar Container -->
	<aside class="main-sidebar sidebar-dark-primary elevation-4">
		<!-- Brand Logo -->
		<a href="{{ route('home') }}" class="brand-link">
			<img src="{{ url('img/summarecon.png') }}"
				 alt="AdminLTE Logo"
				 class="brand-image img-circle elevation-3"
				 style="opacity: .8">
			<span class="brand-text font-weight-light">
				{{ SysController::title(Request::segment(1).'/'.Request::segment(2)) }}
			</span>
		</a>

		<!-- Sidebar -->
		<div class="sidebar"><!-- Sidebar Menu -->
			<div class="mt-3">
				<div class="form-inline">
					<div class="input-group" data-widget="sidebar-search">
						<input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
						<div class="input-group-append">
							<button class="btn btn-sidebar">
								<i class="fas fa-search fa-fw"></i>
							</button>
						</div>
					</div>
				</div>
			</div>

			<nav class="mt-2">
				<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
					<!-- Add icons to the links using the .nav-icon class
						 with font-awesome or any other icon font library -->
					{{ SysController::menu(session('user_id')) }}
				</ul>
			</nav>
			<!-- /.sidebar-menu -->
		</div>
		<!-- /.sidebar -->
	</aside>

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Main content -->
		<section class="content" style="padding-top: 10px; font-size: 14px;">
			<div class="container-fluid">
				@yield('content')
			</div>
		</section>
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->

	<footer class="main-footer">
		<div class="float-right d-none d-sm-block">
			<b>PT. Summarecon Agung Tbk.</b>
		</div>
		<strong>Copyright &copy; 2019</strong>
	</footer>

	<!-- Control Sidebar -->
	<aside class="control-sidebar control-sidebar-dark">
	<!-- Control sidebar content goes here -->
	</aside>
	<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<div id="loading"> 
	<div class="loader">
		<img src="{{ url('img/sp-loading2.gif') }}" alt="loading">
	</div>
</div>

<div class="modal fade" id="sys-modal-default">
	<div class="modal-dialog modal-lg modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header" style="display: none;">
	        	<h5 class="modal-title"></h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
	     	</div>
			<div class="modal-body" id="sys-modal-default-body" style="font-size: 14px;">
				<p>One fine body&hellip;</p>
			</div>
			<div class="modal-footer justify-content-between" id="sys-modal-default-footer">
				<div class="input-group">
                 <input type="text" class="form-control form-control-sm rounded-0" id="sys-modal-default-keyword" name="keyword" placeholder="Keyword">
					<input type="hidden" id="sys-modal-default-helper-1" name="helper_1">
					<input type="hidden" id="sys-modal-default-helper-2" name="helper_2">
					<input type="hidden" id="sys-modal-default-helper-3" name="helper_3">
					<input type="hidden" id="sys-modal-default-helper-4" name="helper_4">
					<input type="hidden" id="sys-modal-default-helper-5" name="helper_5">

                  	<span class="input-group-append">
	                    <button type="button" id="sys-modal-default-btn-search" class="btn btn-info btn-flat btn-sm" onclick="">Search</button>
	                    <button type="button" class="btn btn-default btn-flat btn-sm" data-dismiss="modal">Close</button>
                  	</span>
                </div>
			</div>
		</div>
	<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- Modal For Datatable-->
<div class="modal fade" id="sys-modal-datatable" tabindex="-1" role="dialog" aria-labelledby="sysModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
	        	<h5 id="sys-modal-title" class="modal-title"></h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
	     	</div>
			<div class="modal-body" id="sys-modal-datatable-body" style="font-size: 14px;">
				<p>One fine body&hellip;</p>
			</div>
			<div class="modal-footer" id="sys-modal-datatable-footer">
				<div class="input-group justify-content-start">
					<span class="input-group-btn">
						<button type="button" id="bClose" class="btn btn-default" data-dismiss="modal">Close</button>
					</span>
                </div>

                <div id="sys-modal-datatable-button" class="input-group justify-content-end">
					
                </div>
			</div>
		</div>
	<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<!-- jQuery -->
<script src="{{ url('adminlte/plugins/jquery/jquery.min.js') }}"></script>

<!-- Bootstrap 4 -->
<script src="{{ url('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ url('adminlte/dist/js/adminlte.min.js') }}"></script>

<script src="{{ url('js/sys.js') }}"></script>

<!-- bootstrap datepicker -->
<script src="{{ url('bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js') }}"></script>

<!-- Sweet Alert JS -->
<script src="https://unpkg.com/sweetalert2@7.12.10/dist/sweetalert2.all.js"></script>

<script type="text/javascript">
	check_session_location()
	
	$(document).ready(function() {
		$('[data-widget="sidebar-search"]').SidebarSearch({ notFoundText: 'No menu found.' })
		$('[data-toggle="tooltip"]').tooltip()

		$('#btn_bookmark').click(function (e) {
			e.stopPropagation()
			bookmark_page($(this))
		})
	})

	var sys_search_unit_page = 1;

	function sys_search_unit() {
		var keyword = $('#sys-modal-default-keyword').val();

		$.ajax({
			type	: 'POST',
			url 	: '{{ url("sys/search_unit?page='+sys_search_unit_page+'" ) }}',
			data 	: {
				"_token"	: '{{ csrf_token() }}',
				"keyword"	: keyword,
				"controller": location.pathname.substr(1)
			},
			success : function(msg){
				var tbl = ''+
					'<div class="row">'+
			          	'<div class="col-12">'+
			            	'<div class="card">'+
			              		'<div class="card-header">'+
			                		'<h3 class="card-title">Pilih Unit</h3>'+
			              		'</div>'+

			              		'<div class="card-body table-responsive p-0" style="height: 300px;">'+
									'<table class="table table-sm table-head-fixed text-nowrap">'+
										'<thead>'+
											'<tr>'+
												'<th>Unit</th>'+
												'<th>Lokasi</th>'+
												'<th>#</th>'+
											'</tr>'+
										'</thead>'+
										'<tbody>'+
				'';

				$.each(msg['data'], function(x, y){
					tbl += ''+
											'<tr>'+
												'<td>'+y.kd_unit+' - '+y.nm_unit+'</td>'+
												'<td>'+y.kd_lokasi+' - '+y.nm_lokasi+'</td>'+
												'<td><button type="button" class="btn btn-info btn-sm" onclick="sys_ganti_unit(\''+y.kd_unit+'\', \''+y.nm_unit+'\', \''+y.kd_lokasi+'\', \''+y.nm_lokasi+'\')">Pilih</button></td>'+
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
			                    	'<button type="button" class="btn btn-info btn-flat btn-sm" onclick="sys_search_unit_page--; sys_search_unit();"><<</button>'+
			                  	'</span>'+
			                  	'<input type="number" class="form-control form-control-sm rounded-0" min="1" id="sys_search_unit_page_input" style="text-align: center;" value="'+sys_search_unit_page+'">'+
			                  	'<span class="input-group-append">'+
			                    	'<button type="button" class="btn btn-info btn-flat btn-sm" onclick="sys_search_unit_page++; sys_search_unit();">>></button>'+
			                    	'<button type="button" class="btn btn-default btn-flat btn-sm" onclick="sys_search_unit_page = $(\'#sys_search_unit_page_input\').val(); sys_search_unit();">Go!</button>'+
			                  	'</span>'+
			                '</div>'+
						'</div>'+
					'</div>'+
				'';

				$('#sys-modal-default-body').html(tbl);
				$('#sys-modal-default-btn-search').attr('onclick', 'sys_search_unit()');
				$('#sys-modal-default').modal("show");
			},
			error 	: function(xhr){
				read_error(xhr);
			}
		});
	}

	function sys_ganti_unit(kd_unit, nm_unit, kd_lokasi, nm_lokasi) {
		$.ajax({
			type	: 'POST',
			url 	: '{{ url("sys/ganti_unit" ) }}',
			data 	: {
				"_token"	: "{{ csrf_token() }}",
				"kd_unit"	: kd_unit,
				"nm_unit"	: nm_unit,
				"kd_lokasi" : kd_lokasi,
				"nm_lokasi"	: nm_lokasi
			},
			success	: function(msg){
				location.reload();
			},
			error 	: function(xhr) {
				read_error(xhr);
			}
		});
	}

	function check_session_location() {
		$.ajax({
			type	: 'POST',
			url 	: '{{ url("sys/check_session_location" ) }}',
			async	: false,
			data 	: {
				"_token"	: "{{ csrf_token() }}",
				"controller": location.pathname.substr(1)
			},
			success	: function(res){
				if (!res.data) {
					sys_search_unit()
					$.holdReady(true);

					setTimeout(() => {
						$('.dataTables_wrapper').remove()
					}, 1000);

					$('#btn_bookmark').click(function (e) {
						e.stopPropagation()
						bookmark_page($(this))
					})
				}
			}
		});
	}

	function bookmark_page(el) {
		let marked = '<h5 class="m-0"><i class="fas fa-bookmark"></i></h5>'
		let unmarked = '<h5 class="m-0"><i class="far fa-bookmark"></i></h5>'
		let value = el.val()
		el.attr('disabled', true)

		$.ajax({
			type	: 'POST',
			url 	: '{{ url("sys/bookmark_page" ) }}',
			data 	: {
				"_token"	: "{{ csrf_token() }}",
				"controller": location.pathname.substr(1),
				"module_name" : "{{ SysController::title(Request::segment(1).'/'.Request::segment(2)) }}"
			},
			success	: function(res) {
				if (value == 'marked') {
					el.val('unmarked')
					el.removeClass('text-secondary').addClass('text-warning')
					el.html(marked)
				} else {
					el.val('marked')
					el.removeClass('text-warning').addClass('text-secondary')
					el.html(unmarked)
				}

				el.removeAttr('disabled')
			}
		});
	}
</script>

@yield('js')

</body>
</html>