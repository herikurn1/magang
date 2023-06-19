@extends('layouts.template')

@section('css')
<link rel="stylesheet" href="{{ url('adminlte/plugins/summernote/summernote-bs4.css') }}">
@endsection
@section('js')
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
</script>
@endsection
@section('content')
    <div class="card">
		<div class="card-header">
		    {!! $dt['button'] !!}	
            <button type="button" class="btn btn-default btn-flat" onclick="search_dt()">
                <i class="fas fa-search"></i>
            </button>
        <!-- <button class="btn btn-default"><i class="far fa-save"></i></button> -->
		</div>
		<div class="card-body">
			<form autocomplete="off" id="frm_master" onsubmit="return save_process()" enctype="multipart/form-data">
            <div class="row">
                
				<div class="form-group col-md-12">
					<label>Nama Promo/Event</label>
					<input type="text" class="form-control" id="nm_event" name="nm_event" placeholder="e.g : Promo Tahun Baru" required>
				</div>

                <div class="form-group col-md-6">
                    <label for="exampleInputFile">Photo Banner</label>
                    <input type="file" id="photo" name="photo" required>
                    <p class="help-block">Max. 5MB; JPG,JPEG Only</p>						
				</div>
				<div class="form-group col-md-6">
                    <label>Others</label>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="fg_aktif" name="fg_aktif" value="Y" checked> Flag Aktif
                        </label>
                    </div>
				</div>
				<div class="form-group col-md-12">
                    <label>Keterangan</label>
                    <textarea class="form-control" rows="5" cols="9" id="keterangan" name="keterangan" style="resize:none"></textarea>
				</div>
            </div>
				
				
				<input type="hidden" id="act" name="act" value="add">
				<input readonly type="hidden" id="kd_event" class="form-control" name="kd_event" placeholder="Kode">
				<button type="submit" id="btn_save" class="btn btn-default" style="display: none;">Submit</button>
				{{ csrf_field() }}
			</form>
		</div>
		<div id="v_loading" class="overlay" style="display: none;">
			<i class="fa fa-refresh fa-spin"></i>
		</div>
	</div>
@endsection