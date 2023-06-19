@extends('layouts.template')

@section('css')
<!-- DataTables --> 
<link rel="stylesheet" href="{{ url('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

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

    .odd:hover, .even:hover{
        background: #e0e0d1;
    }
</style>
@endsection
@section('js')
<!-- DataTables -->
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
<script>
    var kd_unit 	= "{{ session('kd_unit') }}";
	var kd_lokasi 	= "{{ session('kd_lokasi') }}";	

    $(document).ajaxStart(function() {
        $("#loading").show();
        }).ajaxStop(function() {
        $("#loading").hide();
    });

    $(function(){
        layanan();
        status();

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

    function show_data(title_prog, kd_layanan, kd_tujuan, kd_jenis){
        $('.table-daftar-pengajuan').DataTable({
            processing: true,
            serverside: true,
            searching: true,
            ordering: true,
            paging: true,
            destroy: true,
            scrollX: true,
            dom: 'lBfrtip',
            buttons: [{ extend: 'excel', text: ' Export Excel' }],
            ajax: {
                url:'daftarpengajuan/get_data',
                type: "POST",
                data: {
                    "title_prog"    : title_prog,
                    "kd_layanan"    : kd_layanan,
                    "kd_tujuan"     : kd_tujuan,
                    "kd_jenis"      : kd_jenis,
                    "_token" 		: '{{ csrf_token() }}',
                },
            },
            columns:
            [
                {
                    data: 'KD_PERUSAHAAN'
                },
                {
                    data: 'NO_DOKUMEN'
                },
                {
                    "data": null,
                    "render": function(data, type, row){
                        var d = new Date(data.TGL_DOKUMEN);
                        var bulan = ["January","February","March","April","May","June","July","August","September","October","November","December"];
                        var date = d.getDate();
                        var month = bulan[d.getMonth()];
                        var year = d.getFullYear();
                        return date+" "+month+" "+year;
                    }
                },
                {
                    data: 'KD_USER'
                },
                {
                    data: 'NM_LAYANAN'
                },
                {
                    "data": null,
                    "render": function(data, type, row){
                        if (data.NM_JENIS == null) {
                            var jenis = "-";
                        } else {
                            var jenis = data.NM_JENIS;
                        }
                        return jenis;
                    }
                },
                {
                    data: 'BLOKNO'
                },
                {
                    data: 'NM_PROGRESS'
                }
            ],
            columnDefs:[
                {
                    "className" : "text-center", 
                    "targets"   : [0, 2, 5],
                }
            ],
        });
    };

    function layanan() {  
        $.ajax({
            url:'daftarpengajuan/get_layanan',
            type: "POST",
            data: {
                "_token" 		: '{{ csrf_token() }}',
            },
            success: function (msg) { 
                $.each(msg, function(i, val){
                    $('#layanan').append('<option value="'+val.KD_LAYANAN+'">'+val.NM_LAYANAN+'</option>')
                })
            }
        }); 

        $('#layanan').change(function(e){
            e.preventDefault();            
            $('#layanan_item').find('option').remove();
            var title_prog = $('#status').val();
            var kd_layanan = $('#layanan').val();
            var kd_tujuan = $('#layanan_dtl').val();
            var kd_jenis = $('#layanan_item').val();

            show_data(title_prog, kd_layanan, kd_tujuan, kd_jenis);

            $.ajax({
                url:'daftarpengajuan/get_layanan_dtl',
                type: "POST",
                data: {
                    "kd_unit"       : kd_unit,
                    "kd_layanan"    : kd_layanan,
                    "_token" 		: '{{ csrf_token() }}',
                },
                success: function (msg) {
                    var q = msg.length;
                    if(q > 0){ 
                        $('#layanan_dtl').append('<option id="s_d_l" disabled selected value>Pilih Detail Layanan</option>')
                        $.each(msg, function(i, val){
                            $("#layanan_dtl").append('<option value="'+val.KD_LAYANAN+'/'+val.KD_TUJUAN+'">'+val.NM_TUJUAN+'</option>')
                        })
                        return false;
                    }
                    $('#layanan_dtl').find('option').remove();
                }
            }); 
        })

        $('#layanan_dtl').change(function(e){
            e.preventDefault();
            var text = $('#layanan_dtl').val();
            var arr = text.split("/");

            var kd_layanan = arr[0];
            var kd_tujuan  = arr[1];

            var title_prog = $('#status').val();
            var kd_jenis = $('#layanan_item').val();

            show_data(title_prog, kd_layanan, kd_tujuan, kd_jenis);

            $.ajax({
                url:'daftarpengajuan/get_layanan_item',
                type: "POST",
                data: {
                    "kd_unit"       : kd_unit,
                    "kd_layanan"    : kd_layanan,
                    "kd_tujuan"     : kd_tujuan,
                    "_token" 		: '{{ csrf_token() }}',
                },
                success: function (msg) { 
                    var qq = msg.length;
                    if(qq > 0){ 
                        $('#layanan_item').append('<option id="s_d_l" disabled selected value>Pilih Detail Layanan</option>')
                        $.each(msg, function(i, val){
                            $("#layanan_item").append('<option value="'+val.KD_LAYANAN+'/'+val.KD_TUJUAN+'/'+val.KD_JENIS+'">'+val.NM_JENIS+'</option>')
                        })
                        return false;
                    }
                    $('#layanan_item').find('option').remove();
                }
            }); 
        })

        $('#layanan_item').change(function(e){
            e.preventDefault();
            var text = $('#layanan_item').val();
            var arr = text.split("/");

            var kd_layanan = arr[0];
            var kd_tujuan  = arr[1];
            var kd_jenis   = arr[2];
            var title_prog = $('#status').val();

            show_data(title_prog, kd_layanan, kd_tujuan, kd_jenis);
        })
    };

    function status(){
        $.ajax({
            url:'daftarpengajuan/get_status',
            type: "POST",
            data: {
                "_token" 		: '{{ csrf_token() }}',
            },
            success: function (msg) { 
                $.each(msg, function(i, val){
                    $('#status').append('<option value="'+val.KD_FUNGSI+'">'+val.KETERANGAN+'</option>')


                    if ($('#status').val('0')) {
                        $(this).prop('selected', true);
                    };
                })

                var status = $('#status').val();

                show_data(status);

            }
        }); 
    }

    $('#status').change(function(e){
        e.preventDefault();
        var title_prog = $('#status').val();
        var kd_layanan = $('#layanan').val();
        var kd_tujuan = $('#layanan_dtl').val();
        var kd_jenis = $('#layanan_item').val();

        show_data(title_prog, kd_layanan, kd_tujuan, kd_jenis);
    })
</script>
@endsection
@section('content')
    <div class="card">
		<div class="card-header">
        <div class="row">
            <div class="col-md-3">
                <label for="layanan">Nama Layanan</label>
                <select class="form-control" name="layanan" id="layanan">
                    <option value="">ALL</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="layanan_dtl">Detail Layanan</label>
                <select class="form-control" name="layanan_dtl" id="layanan_dtl">
                    <option value="">ALL</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="layanan_item">Item Layanan</label>
                <select class="form-control" name="layanan_item" id="layanan_item">
                </select>
            </div>
            <!-- <div class="col-md-6 btn-ftr">
                <button type="button" class="btn btn-primary btn-flat" onclick="search_dt()">
                    Filter <i class="far fa-filter"></i>
                </button>
            </div> -->
        </div>
        <div class="row ftr-bottom">
            <div class="col-md-3">
                <label for="status">Status</label>
                <select class="form-control" name="status" id="status">
                    <option value="">ALL</option>
                </select>
            </div>
        </div>
		</div>
		<div class="card-body">
            <table class="table table-sm table-head-fixed text-nowrap table-daftar-pengajuan" style="width: 100%;">
                <thead>
                    <th class="text-center">Unit</th>
                    <th class="text-center">No Pengajuan</th>
                    <th class="text-center">Tgl Pengajuan</th>
                    <th class="text-center">Pemohon</th>
                    <th class="text-center">Layanan</th>
                    <th class="text-center">Jenis Layanan</th>
                    <th class="text-center">Blok No</th>
                    <th class="text-center">Status</th>
                </thead>
            </table>
		</div>
		<div id="v_loading" class="overlay" style="display: none;">
			<i class="fa fa-refresh fa-spin"></i>
		</div>
	</div>
@endsection