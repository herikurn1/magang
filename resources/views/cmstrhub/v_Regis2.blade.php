@extends('layouts.template_pp')

@section('css')
<link rel="stylesheet" href="{{ url('adminlte/plugins/summernote/summernote-bs4.css') }}">
@endsection
@section('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ url('adminlte/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script>
    var kd_unit   = "{{ session('kd_unit') }}";
    var kd_lokasi = "{{ session('kd_lokasi') }}";
    var lot       = "{{ $dt['number'] }}";
    var unique    = "{{ $dt['code'] }}";

    $(document).ajaxStart(function() {
        $("#loading").show();
        }).ajaxStop(function() {
        $("#loading").hide();
    });

    $(function(){
        //edtor summernote
       
        $.ajax({
            type 	: 'POST',
            url 	: '/cmstrhub/regis/check-tenant',
            data 	: {
                'lot'   : lot,
                'unique': unique,
                "_token": '{{ csrf_token() }}',
            },
            success : function(msg) {
                $.each(msg, function(i, val){
                    var tenant = val.NAMA_TENANT;
                    var nomor  = val.NOMOR;

                    $('#nama_tenant').val(tenant);
                    $('#lot_number').val(nomor);
                    $('#kd_perusahaan').val(val.KD_PERUSAHAAN);
                    $('#kd_zona').val(val.ZONE_CD);
                    $('#blok').val(val.BLOK);
                    $('#no_pjs').val(val.NO_PJS);
                    $('#id_nasabah').val(val.NASABAH_ID);
                    $('#stok_id').val(val.STOK_ID);
                })
            }
        });
    });

    $(document).on('click', '#btn_back', function(e){
        e.preventDefault();
        window.history.back();
    });

    $(document).on('click', '#btn_save', function(e){
        e.preventDefault();
        var dt = new FormData($('#frm_regis')[0]);
        var pic = $('#pic').val();
        var email = $('#email').val();
        var phone = $('#phone').val();
        var password = $('#password').val();
        var password_confirmation = $('#password_confirmation').val();

        // if ($('#pic').val() == '') {
        //     Swal.fire({
        //         position: 'center',
        //         icon: 'warning',
        //         title: 'PIC must be filled',
        //         showConfirmButton: false,
        //         timer: 3000
        //     });
        //     return false;
        // }

        // if ($('#email').val() == '') {
        //     Swal.fire({
        //         position: 'center',
        //         icon: 'warning',
        //         title: 'Email must be filled',
        //         showConfirmButton: false,
        //         timer: 3000
        //     });
        //     return false;
        // }

        // if ($('#phone').val() == '') {
        //     Swal.fire({
        //         position: 'center',
        //         icon: 'warning',
        //         title: 'Phone must be filled',
        //         showConfirmButton: false,
        //         timer: 3000
        //     });
        //     return false;
        // }

        // if ($('#password').val() == '') {
        //     Swal.fire({
        //         position: 'center',
        //         icon: 'warning',
        //         title: 'Password must be filled',
        //         showConfirmButton: false,
        //         timer: 3000
        //     });
        //     return false;
        // }

        // if ($('#password_confirmation').val() == '') {
        //     Swal.fire({
        //         position: 'center',
        //         icon: 'warning',
        //         title: 'Password Confirmation must be filled',
        //         showConfirmButton: false,
        //         timer: 3000
        //     });
        //     return false;
        // }
        $('.form-control').removeClass('is-invalid');
        $('.text-danger').html('');
        $.ajax({
            type       : 'POST',
            url        : '../save',
            data       : dt,
            dataType   : "JSON",
            processData: false,
            contentType: false,
            success    : function(msg) {
                if (msg.code == 400) {
                    var dt = msg.data;
                    $.each(dt, function(i, val){
                        $('#'+i).addClass('is-invalid');
                        $('.'+i).html(val);
                    });
                } else {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Data berhasil disimpan.',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    window.location.href = '/cmstrhub/regis';
                }
            }
        });

        return false;
    });
</script>
@endsection
@section('content')
    <div class="card" style="margin-top: 10px;">
		<div class="card-header">
            <h4 class="text-center">Form Registrasi Tenant</h4>
		</div>
		<div class="card-body">
			<form autocomplete="off" id="frm_regis">
            @csrf
            <div class="row">
                
				<div class="form-group col-md-6">
					<label>Tenant Name</label>
					<input type="text" class="form-control" id="nama_tenant" name="nama_tenant" readonly>
				</div>

				<div class="form-group col-md-6">
                    <label>Lot Number / No. Unit</label>
                    <input type="text" class="form-control" id="lot_number" name="lot_number" readonly>
				</div>
                
                <div class="form-group col-md-6">
                    <label>PIC Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="pic" name="pic" required>
                    <span class='pic text-danger'></span>
				</div>
                
                <div class="form-group col-md-6">
                    <label>Email <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="email" name="email" required>
                    <span class='email text-danger'></span>
				</div>

                <div class="form-group col-md-6">
                    <label>Phone Number <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="phone" name="phone" required>
                    <span class='phone text-danger'></span>
				</div>

                <div class="form-group col-md-6">
                    <label>Upload Photo</label>
                    <input type="file" class="form-control" id="photo" name="photo">
                    <span class='photo text-danger'></span>
				</div>

                <div class="form-group col-md-6">
                    <label>Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <span class='password text-danger'></span>
				</div>

                <div class="form-group col-md-6">
                    <label>Confirmation Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    <span class='password_confirmation text-danger'></span>
				</div>
            </div>

            <input type="hidden" class="form-control" id="kd_perusahaan" name="kd_perusahaan">
            <input type="hidden" class="form-control" id="kd_zona" name="kd_zona">
            <input type="hidden" class="form-control" id="blok" name="blok">
            <input type="hidden" class="form-control" id="no_pjs" name="no_pjs">
            <input type="hidden" class="form-control" id="id_nasabah" name="id_nasabah">
            <input type="hidden" class="form-control" id="stok_id" name="stok_id">
            
           
			</form>
            <button type="button" id="btn_back" class="btn btn-info">Back</button>
            <button type="button" id="btn_save" class="btn btn-primary">Submit</button>
		</div>
		<div id="v_loading" class="overlay" style="display: none;">
			<i class="fa fa-refresh fa-spin"></i>
		</div>
	</div>
@endsection