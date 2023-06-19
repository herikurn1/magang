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

    $(function() {
        search_dt();
    });

    function tgl(date){
        var d     = new Date(date);
        var bln   = ["January","February","March","April","May","June","July","August","September","October","November","December"];
        var day   = d.getDate();
        var month = bln[d.getMonth()];
        var years = d.getFullYear();

        return day+' '+month+' '+years;
    }

    function search_dt(){
        var from = $('#date_from').val();
        var to   = $('#date_to').val();

        $('.table-sales').DataTable({
            processing: true,
            serverside: true,
            searching: true,
            ordering: false,
            paging: false,
            destroy: true,
            dom: 'lBfrtip',
            buttons: [{ extend: 'excel', 
                        text: ' Export Excel',
                        exportOptions: {
                            columns: [1, 2, 3, 4]
                        } 
            }],
            ajax: {
                url:'sales/get_data',
                type: "POST",
                data: {
                    "date_from": from,
                    "date_to"  : to,
                    "_token"   : '{{ csrf_token() }}',
                },
            },
            columns:
            [
                {
                    "data": null,
                    "render": function(data, type, row){
                        var path = "https://tenant.gadingemerald.com/trhub/sales-today/"+data.FOTO_SALES;
                        var image = "<a href='"+path+"' target='_blank'><img class='image-rounded' src='"+path+"' alt='Image' style='width: 50px; height: 50px; border-radius: 50%;'></a>";
                        return image;
                    }
                },
                {
                    data: 'NO_DOKUMEN'
                },
                {
                    data: 'NAME'
                },
                {
                    "data": null,
                    "render": function(data, type, row){
                        return tgl(data.TGL_SALES);
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, row){
                        return formatNumber(data.JUMLAH);
                    }
                },
            ],
            columnDefs:[
                {
                    "className" : "text-center", 
                    "targets"   : [0, 1, 2, 3, 4],
                }
            ],
        });
    }

</script>
@endsection
@section('content')
    <div class="card" style="padding: 15px;">
        <div style="padding: 15px;">
            <div class="row">
                <div class="col-md-3">
                    <label for="date_from">Tanggal Awal</label>
                    <input type="date" class="form-control form-control-sm date_from" name="date_from" id="date_from">
                </div>
                <div class="col-md-3">
                    <label for="date_to">Tanggal Akhir</label>
                    <input type="date" class="form-control form-control-sm date_to" name="date_to" id="date_to">
                </div>

                <div class="col-md-3" style="display: flex; flex-wrap: wrap; align-content: end;">
                    <button type="button" class="btn btn-sm btn-default btn-flat" onclick="search_dt();"><i class="fas fa-sync-alt"></i></button>
                </div>
            </div>
        </div>
        <table class="table table-sm table-head-fixed text-nowrap table-sales">
            <thead>
                <th class="text-center">Foto Sales</th>
                <th class="text-center">No Dokumen</th>
                <th class="text-center">Tenant</th>
                <th class="text-center">Tgl Transaksi</th>
                <th class="text-center">Jumlah Transaksi</th>
            </thead>
        </table>
		<div id="v_loading" class="overlay" style="display: none;">
			<i class="fa fa-refresh fa-spin"></i>
		</div>
	</div>
@endsection