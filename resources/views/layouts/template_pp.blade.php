<!DOCTYPE html>
<html>
	<head>
		<title>@yield('title', '')</title>

		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />

		@yield('css')
	</head>

	<body>
		<div class="container">
			@yield('content')
		</div>
	</body>
</html>

<div id="modal_id" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-dialog-centered modal-lg">
	    <div id="modal_content" class="modal-content">
	      	...
	    </div>
  	</div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://getbootstrap.com/docs/4.6/assets/js/docs.min.js"></script>

<script type="text/javascript">
	
</script>

@yield('js')