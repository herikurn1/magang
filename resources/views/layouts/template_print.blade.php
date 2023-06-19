<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<title>{{ SysController::title(Request::segment(1).'/'.Request::segment(2)) }}</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="{{ url('adminlte/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="{{ url('adminlte/dist/css/adminlte.min.css') }}" rel="stylesheet">
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	<!-- Include Bootstrap Datepicker -->
	<link rel="stylesheet" href="{{ url('bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css') }}"/>

	<style type="text/css">
	.link_cursor{
	    cursor: pointer;
	    color: #007bff;
	}
	</style>
	@yield('css')
</head>
<body>

@yield('content')
@yield('js')
</body>
</html>