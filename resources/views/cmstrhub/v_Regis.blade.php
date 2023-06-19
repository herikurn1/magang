@extends('layouts.template_pp')

@section('css')
<link rel="stylesheet" href="{{ url('adminlte/plugins/summernote/summernote-bs4.css') }}">
@endsection
@section('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ url('adminlte/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script>
    var kd_unit 	= "{{ session('kd_unit') }}";
	var kd_lokasi 	= "{{ session('kd_lokasi') }}";	

    $(document).ajaxStart(function() {
        $("#loading").show();
        }).ajaxStop(function() {
        $("#loading").hide();
    });

    $(function(){
        //edtor summernote
        $('#keterangan').summernote({
            height	: 250,
            toolbar	: [    
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],       
                ['insert']
            ],
        });
    });

    function save_process(){
        var nomor = $('#lot_number').val();
        var code = $('#code').val();
        var dt = $('#frm_regis').serializeArray();

        $.ajax({
            type 	: 'POST',
            url 	: 'regis/check-code',
            data 	: dt,
            success : function(msg) {
                var code = msg.code;
                if (code == 200) {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Success',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    $.ajax({
                        type 	: 'POST',
                        url 	: 'regis/regis',
                        data 	: dt,
                        success : function(msg) {
                            var data = msg;
                            window.location.href = " /cmstrhub/regis/form-regis/"+data;
                        }
                    });
                }else{
                    Swal.fire({
                        position: 'center',
                        icon: 'warning',
                        title: 'Lot Number / Unique Code wrong !!',
                        showConfirmButton: false,
                        timer: 3000
                    }) 
                }
            },
            error 	: function(xhr) {										
                read_error(xhr);
            }
        });

        return false;
    }
</script>
@endsection
@section('content')
    <div class="card" style="margin-top: 10px;">
		<div class="card-header">
            <h4 class="text-center">Registrasi Tenant</h4>
            <h5 class="text-center">TR HUB MALL</h5>
		</div>
		<div class="card-body">
			<form autocomplete="off" id="frm_regis" onsubmit="return save_process()" enctype="multipart/form-data">
            <div class="row">
                
				<div class="form-group col-md-12">
					<label>Lot Number / No. Unit</label>
					<input type="text" class="form-control" id="lot_number" name="lot_number" required>
				</div>

				<div class="form-group col-md-12">
                    <label>Unique Code</label>
                    <input type="text" class="form-control" id="code" name="code" required>
				</div>
            </div>
            
            <button type="submit" id="btn_save" class="btn btn-primary" style="display: block;">Next</button>
            {{ csrf_field() }}
			</form>
		</div>
		<div id="v_loading" class="overlay" style="display: none;">
			<i class="fa fa-refresh fa-spin"></i>
		</div>
	</div>
@endsection