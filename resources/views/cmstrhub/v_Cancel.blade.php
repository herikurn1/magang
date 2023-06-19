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
<script src="{{ url('bower_components/autoNumeric/autoNumeric.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var kd_unit 	= "{{ session('kd_unit') }}";
	var kd_lokasi 	= "{{ session('kd_lokasi') }}";	

    $(document).ajaxStart(function() {
        $("#loading").show();
        }).ajaxStop(function() {
        $("#loading").hide();
    });

    $(function(){
        show_data();

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

        $('.datepicker').datepicker({
            format          : 'dd/mm/yyyy',
            todayHighlight  : true,
            autoclose       : true
        });

        setTimeout(function(){
            $('.alert-success').remove();
        }, 3000);
    });

    function show_data(){
        $('.table-batal').DataTable({
            processing: true,
            serverside: true,
            searching: true,
            ordering: true,
            paging: true,
            destroy: true,
            scrollX: true,
            dom: 'lBfrtip',
            buttons: [{ 
                        extend: 'excel', 
                        text: ' Export Excel',
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }],
            ajax: {
                url:'data-cancel',
                type: "GET",
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
                },
                {
                    "data": null,
                    "render": function(data, type, row){
                        if (data.STATUS == 'W') {
                            var btn = "<button class='btn btn-success btn-confirm btn-sm' id='"+data.NO_DOKUMEN+"' title='Konfirm Pengajuan'><i class='fas fa-check'></i></i></button> <button class='btn btn-danger btn-sm btn-void' id='"+data.NO_DOKUMEN+"' title='Tolak Pengajuan'><i class='fas fa-ban'></i></button> <button class='btn btn-primary btn-history btn-sm' id='"+data.NO_DOKUMEN+"' title='History'><i class='fas fa-history'></i></button>";
                        }else if(data.STATUS == 'V'){
                            var btn = "<button class='btn btn-primary btn-history btn-sm' id='"+data.NO_DOKUMEN+"' title='History'><i class='fas fa-history'></i></button>";
                        }else if(data.STATUS == 'C'){
                            var btn = "<button class='btn btn-primary btn-history btn-sm' id='"+data.NO_DOKUMEN+"' title='History'><i class='fas fa-history'></i></button>";
                        }else{
                            var btn = "<button class='btn btn-warning btn-proses btn-sm' id='"+data.NO_DOKUMEN+"' title='Proses Pengajuan'><i class='fas fa-spinner'></i></button> <button class='btn btn-danger btn-sm btn-void' id='"+data.NO_DOKUMEN+"' title='Tolak Pengajuan'><i class='fas fa-ban'></i></button> <button class='btn btn-primary btn-history btn-sm' id='"+data.NO_DOKUMEN+"' title='History'><i class='fas fa-history'></i></button>";
                        }

                        return btn;
                    }
                },
            ],
            columnDefs:[
                {
                    "className" : "text-center", 
                    "targets"   : [0, 2, 5, 8],
                }
            ],
        });
    };

    $(document).on('click', '.btn-confirm', function(e){
        e.preventDefault();

        var no_dokumen = $(this).attr('id');
        var title_prog = $('#status').val();
        var kd_layanan1 = $('#layanan').val();
        var kd_tujuan1 = $('#layanan_dtl').val();
        var kd_jenis1 = $('#layanan_item').val();

        Swal.fire({
            title: 'Anda yakin ingin Konfirmasi data ini?',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            confirmButtonColor: '#FA003F'
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed){
                $.ajax({
                    url:'/cmstrhub/prosespengajuan/get_dtl_data',
                    type: "POST",
                    data: {
                        "no_dokumen"    : no_dokumen,
                        "_token" 		: '{{ csrf_token() }}',
                    },
                    success: function (msg) { 
                        $.each(msg, function(i, val){
                            var kd_layanan  = val.KD_LAYANAN;
                            var kd_tujuan   = val.KD_TUJUAN;
                            var kd_jenis    = val.KD_JENIS;

                            $.ajax({
                                url:'/cmstrhub/prosespengajuan/confirm_data',
                                type: "POST",
                                data: {
                                    "kd_unit"       : kd_unit,
                                    "no_dokumen"    : no_dokumen,
                                    "kd_layanan"    : kd_layanan,
                                    "kd_tujuan"     : kd_tujuan,
                                    "kd_jenis"      : kd_jenis,
                                    "_token" 		: '{{ csrf_token() }}',
                                },
                                success: function (data){ 
                                    Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'Data berhasil di Konfirm.',
                                        showConfirmButton: false,
                                        timer: 3000
                                    });

                                    show_data();
                                }
                            })
                        })
                    }
                }); 
            }
        });
    })

    $(document).on('click', '.btn-void', function(e){
        e.preventDefault();

        var no_dokumen = $(this).attr('id');
        var title_prog = $('#status').val();
        var kd_layanan1 = $('#layanan').val();
        var kd_tujuan1 = $('#layanan_dtl').val();
        var kd_jenis1 = $('#layanan_item').val();

            var tbl = '<div class="row">';
            tbl +=      '<div class="form-group col-md-3">'+
                            '<label for="tgl_tolak">Tanggal <span class="text-danger">*</span></label>'+
                            '<input type="date" class="form-control form-control-sm datepicker tgl_tolak" name="tgl_tolak" id="tgl_tolak" required>'+
                        '</div>'+
                        '<div class="form-group col-md-3">'+
                            '<label for="status_tolak">Status Pengajuan <span class="text-danger">*</span></label>'+
                            '<select class="form-control form-control-sm status_tolak" name="status_tolak" id="status_tolak">'+
                                '<option value="batal">DIBATALKAN</option>'+ 
                            '</select>'+
                        '</div>'+
                        '<div class="form-group col-md-6">'+
                            '<label for="catatan_tolak">Catatan</label>'+
                            '<input type="text" class="form-control form-control-sm catatan_tolak" name="catatan_tolak" id="catatan_tolak">'+
                        '</div>'+
                        '<div class="form-group col-md-12" style="text-align: right;">'+
                            '<button type="button" class="btn btn-primary btn-tolak" name="btn-tolak" id="'+no_dokumen+'">Simpan</button>'+
                        '</div>'+
                    '</div>';
        
        $('#tgl_tolak').attr('readonly', true);
        $('#sys-modal-title').html('Tolak Pengajuan');
        $('#sys-modal-datatable-body').html(tbl);
        $('#sys-modal-datatable').modal("show");
    })

    $(document).on('click', '.btn-tolak', function(e){
        e.preventDefault();
        var id = $(this).attr('id');
        var tgl = $('#tgl_tolak').val();
        var catatan = $('#catatan_tolak').val();
        
        if (tgl == '') {
            Swal.fire({
                position: 'center',
                icon: 'warning',
                title: 'Tanggal tolak harus di isi.',
                showConfirmButton: true
            })
            return false;
        }

        $.ajax({
            url:'/cmstrhub/prosespengajuan/get_dtl_data',
            type: "POST",
            data: {
                "no_dokumen"    : id,
                "_token" 		: '{{ csrf_token() }}',
            },
            success: function (msg) { 
                $.each(msg, function(i, val){
                    var kd_layanan  = val.KD_LAYANAN;
                    var kd_tujuan   = val.KD_TUJUAN;
                    var kd_jenis    = val.KD_JENIS;

                    $.ajax({
                        url:'/cmstrhub/prosespengajuan/void_data',
                        type: "POST",
                        data: {
                            "kd_unit"       : kd_unit,
                            "no_dokumen"    : id,
                            "kd_layanan"    : kd_layanan,
                            "kd_tujuan"     : kd_tujuan,
                            "kd_jenis"      : kd_jenis,
                            "catatan"       : catatan,
                            "tgl_tolak"     : tgl,
                            "_token" 		: '{{ csrf_token() }}',
                        },
                        success: function (data){ 
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Data berhasil di Batalkan.',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            show_data();
                            $('#sys-modal-datatable').modal("hide");
                        }
                    })
                })
            }
        }); 
    })

    $(document).on('click', '.btn-proses', function(e){
        e.preventDefault(); 

        var no_dokumen = $(this).attr('id');
        $.ajax({
            url:'/cmstrhub/prosespengajuan/get_dtl_data',
            type: "POST",
            dataType: "JSON",
            data: {
                "no_dokumen"    : no_dokumen,
                "_token" 		: '{{ csrf_token() }}',
            },
            success: function (msg) { 
                $.each(msg, function(i, val){
                var tbl = '<form class="form-horizontal" id="frm_master" method="POST" action="/cmstrhub/prosespengajuan/progress_data" enctype="multipart/form-data">';
                    tbl +=      '<div class="row">'+
                                '<input type="hidden" class="form-control form-control-sm no_dokumen" name="no_dokumen" id="no_dokumen" value="'+no_dokumen+'">'+
                                '<input type="hidden" class="form-control form-control-sm kd_unit" name="kd_unit" id="kd_unit" value="'+val.KD_PERUSAHAAN+'">'+
                                '<input type="hidden" class="form-control form-control-sm kd_layanan" name="kd_layanan" id="kd_layanan" value="'+val.KD_LAYANAN+'">'+
                                '<input type="hidden" class="form-control form-control-sm kd_tujuan" name="kd_tujuan" id="kd_tujuan" value="'+val.KD_TUJUAN+'">'+
                                '<input type="hidden" class="form-control form-control-sm kd_jenis" name="kd_jenis" id="kd_jenis" value="'+val.KD_JENIS+'">'+
                                    '<div class="form-group col-md-3">'+
                                        '<label for="tgl_progress">Tanggal <span class="text-danger">*</span></label>'+
                                        '<input type="date" class="form-control form-control-sm datepicker tgl_progress" name="tgl_progress" id="tgl_progress" required>'+
                                    '</div>'+
                                    '<div class="form-group col-md-3">'+
                                        '<label for="status_progress">Status Pengajuan <span class="text-danger">*</span></label>'+
                                        '<select class="form-control form-control-sm status_progress" name="status_progress" id="status_progress">'+
                                            '<option value="I|2">Sedang Dikerjakan</option>'+ 
                                            '<option value="P|2">Pending</option>'+ 
                                            '<option value="C|3">Selesai Dikerjakan</option>'+ 
                                        '</select>'+
                                    '</div>'+
                                    '<div class="form-group col-md-6">'+
                                        '<label for="file">Upload Image</label>'+
                                        '<input type="file" class="form-control form-control-sm file" name="file" id="file">'+
                                        '<span style="color: #828489;">Max. 2Mb, JPEG Only</span>'+
                                    '</div>'+
                                    '<div class="form-group col-md-6">'+
                                        '<label for="catatan_progress">Catatan</label>'+
                                        '<textarea class="form-control form-control-sm" name="catatan_progress" id="catatan_progress" cols="30" rows="4" style="resize:none;"></textarea>'+
                                    // '<input type="text" class="form-control form-control-sm catatan_progress" name="catatan_progress" id="catatan_progress">'+
                                    '</div>'+
                                    
                                    '<div class="form-group col-md-12" style="text-align: right;">'+
                                        '<button type="submit" class="btn btn-primary btn-progress" name="btn-progress" id="'+no_dokumen+'">Simpan</button>'+
                                    '</div>'+
                                '</div>'+
                                '@csrf'+
                '</form>';
                $('#tgl_tolak').attr('readonly', true);
                $('#sys-modal-title').html('Detail Progress');
                $('#sys-modal-datatable-body').html(tbl);
                $('#sys-modal-datatable').modal("show");
                }) 
            }
        })
       

    })

    function actual(data){
        var d = new Date(data);
        var day = d.getDate();
        var month = d.getMonth()+1;
        var year = d.getFullYear();

        return day+'/'+month+'/'+year;
    };

    function tgl(data){
        var d = new Date(data);
        var day = d.getDate();
        var bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        var month = bulan[d.getMonth()];
        var year = d.getFullYear();

        return day+' '+month+' '+year;
    };

    function jam(data){
        var d = new Date(data);
        var day = d.getDate();
        var bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        var month = bulan[d.getMonth()];
        var year = d.getFullYear();

        var h = d.getHours();
        var m = d.getMinutes();

        return day+' '+month+' '+year+' '+h+':'+m;
    };

    function x(data){
        if (data != null) {
            return data;
        } else {
            return '-';
        }
    };

    $(document).on('click', '.btn-history', function(e){
        e.preventDefault();

        var no_dokumen = $(this).attr('id');
        $.ajax({
            url:'/cmstrhub/prosespengajuan/history_data',
            type: "POST",
            data: {
                "no_dokumen"    : no_dokumen,
                "_token" 		: '{{ csrf_token() }}',
            },
            success: function (msg) { 
                var hub = msg.hub[0];
                if (hub.STATUS == "I") {
                    var status = "Sedang Diproses";
                }else if(hub.STATUS == "W"){
                    var status = "Menunggu Konfirmasi";
                }else if(hub.STATUS == "V"){
                    var status = "Dibatalkan";
                }else if(hub.STATUS == "C"){
                    var status = "Selesai Dikerjakan";
                }else{
                    var status = "Pending";
                }

                if (hub.STATUS == 'I') {
                    var bg = 'bgcolor="#e3ac14"';
                }else if(hub.STATUS == 'W'){
                    var bg = 'bgcolor="#277cf2"';
                }else if(hub.STATUS == 'V'){
                    var bg = 'bgcolor="#fa1b1b"';
                }else if(hub.STATUS == 'C'){
                    var bg = 'bgcolor="#70db70"';
                }else{
                    var bg = 'bgcolor="#e3ac14"';
                }

                if (hub.KD_LAYANAN == 'L0005') {
                    var detail = '<tr>'+
                                    '<td colspan="2" align="center" '+bg+'"><b>'+status+'</b></td>'+
                                '</tr>'+
                                '<tr>'+
                                    '<td>'+
                                        'Tanggal Dokumen : <br><b>'+jam(hub.TGL_DOKUMEN)+'</b><br>'+
                                    '</td>'+
                                    '<td>No. Dokumen : <br><b>'+hub.NO_DOKUMEN+'</b></td>'+
                                '</tr>'+
                                '<tr>'+
                                    '<td>'+
                                        'Nama Karyawan : <br><b>'+nl(hub.NM_KARYAWAN)+'</b><br>'+
                                        'Email Karyawan : <br><b>'+nl(hub.EMAIL_KARYAWAN)+'</b><br>'+
                                        'Telephone Karyawan : <br><b>'+nl(hub.NO_HP_KARYAWAN)+'</b><br>'+
                                    '</td>'+
                                    '<td>No Unit : <br><b>'+nl(hub.BLOKNO)+'  '+nl(hub.ALAMAT)+'</b></td>'+
                                '</tr>';
                }else if(hub.KD_LAYANAN == 'L0002'){
                    var detail = '<tr>'+
                                    '<td colspan="2" align="center" '+bg+'"><b>'+status+'</b></td>'+
                                '</tr>'+
                                '<tr>'+
                                    '<td>'+
                                        'Tanggal Dokumen : <br><b>'+jam(hub.TGL_DOKUMEN)+'</b><br>'+
                                    '</td>'+
                                    '<td>No. Dokumen : <br><b>'+hub.NO_DOKUMEN+'</b></td>'+
                                '</tr>'+
                                '<tr>'+
                                    '<td>'+
                                        'Nama PIC : <br><b>'+nl(hub.NM_PIC)+'</b><br>'+
                                        'Email PIC : <br><b>'+nl(hub.EMAIL_PIC)+'</b><br>'+
                                        'Telephone PIC : <br><b>'+nl(hub.NO_HP_PIC)+'</b><br>'+
                                        'Tanggal Mulai : <br><b>'+nl(actual(hub.TGL_ACTUAL_FR))+' '+nl(hub.JAM_ACTUAL_FR)+'</b><br>'+
                                        'Tanggal Akhir : <br><b>'+nl(actual(hub.TGL_ACTUAL_TO))+' '+nl(hub.JAM_ACTUAL_TO)+'</b><br>'+
                                    '</td>'+
                                    '<td>'+
                                        'No Unit : <br><b>'+nl(hub.BLOKNO)+'  '+nl(hub.ALAMAT)+'</b><br>'+
                                        'Keterangan : <br><b>'+nl(hub.KET_JENIS_PEKERJAAN)+'</b>'+
                                    '</td>'+
                                '</tr>';
                }else{
                    var detail = '<tr>'+
                                    '<td colspan="2" align="center" '+bg+'"><b>'+status+'</b></td>'+
                                '</tr>'+
                                '<tr>'+
                                    '<td>'+
                                        'Tanggal Dokumen : <br><b>'+jam(hub.TGL_DOKUMEN)+'</b><br>'+
                                    '</td>'+
                                    '<td>No. Dokumen : <br><b>'+hub.NO_DOKUMEN+'</b></td>'+
                                '</tr>'+
                                '<tr>'+
                                    '<td>'+
                                        'Nama PIC : <br><b>'+nl(hub.NM_PIC)+'</b><br>'+
                                        'Email PIC : <br><b>'+nl(hub.EMAIL_PIC)+'</b><br>'+
                                        'Telephone PIC : <br><b>'+nl(hub.NO_HP_PIC)+'</b><br>'+
                                    '</td>'+
                                    '<td>'+
                                        'No Unit : <br><b>'+nl(hub.BLOKNO)+'  '+nl(hub.ALAMAT)+'</b><br>'+
                                        'Keterangan : <br><b>'+nl(hub.KETERANGAN_KOMPLAIN)+'</b>'+
                                    '</td>'+
                                '</tr>';
                };
                
                var tbl = '';
                tbl += '<style>'+
                        '#vl {'+
                            'height: 60px;'+
                            'margin: 10px 0 0 5px;'+
                        '}'+
                    '</style>'+
                        
                '<table id="tbl_lacak" class="table table-bordered"><thead>'+
                '</thead><tbody>'
                            +detail+
                '</tbody></table><br>'+
                '<style>'+
                    '#vl {'+
                        'height: 60px;'+
                        'margin: 10px 0 0 5px;'+
                    '}'+
                '</style>'+
                        
                '<table id="tbl_lacak_dtl" class="table table-bordered"><thead>'+
                '</thead><tbody id="lacak_dtl">'+

                '</tbody></table>';

                $('#sys-modal-title').html('Detail History');
                $('#sys-modal-datatable-body').html(tbl);
                            
                var dtl = msg.dtl;

                
                $.each(dtl, function(i, val){
                    if (val.TITLE_PROGRESS == "1") {
                        var title = "<span class='badge badge-primary'>MENUNGGU KONFIRMASI</span>";
                    }else if(val.TITLE_PROGRESS == "2"){
                        var title = "<span class='badge badge-warning'>SEDANG DIPROSES</span>";
                    }else if(val.TITLE_PROGRESS == "3"){
                        var title = "<span class='badge badge-success'>SELESAI</span>";
                    }else{
                        var title = "<span class='badge badge-danger'>DIBATALKAN</span>";
                    }

                    if (val.KD_LAYANAN == 'L0001') {
                        $path = 'https://tenant.gadingemerald.com/trhub/keluhan/';
                    }else if(val.KD_LAYANAN == 'L0002'){
                        $path = 'https://tenant.gadingemerald.com/trhub/surat-ijin/';
                    }else{
                        $path = 'https://tenant.gadingemerald.com/trhub/id-karyawan/';
                    };

                    if (val.FOTO_PEKERJAAN == null) {
                        var img = '';
                    } else {
                        var img = '<img src="'+$path+''+val.FOTO_PEKERJAAN+'" alt="" width="100">'
                    }
                    $('#lacak_dtl').append('<tr>'+
                    '<td><b>'+val.USER_PROGRESS+' - '+tgl(val.TGL_PROGRESS)+'</b></td>'+
                    '<td rowspan="2">Keterangan : '+x(val.URAIAN_PROGRESS)+'</td>'+
                    '<td rowspan="2">'+img+'</td>'+
                    '</tr>'+
                    '<tr><td>'+title+'</td></tr>');
                })
                
                $('#sys-modal-datatable').modal("show");
            }
        }); 
    })

</script>
@endsection
@section('content')
    <div class="card">
		<div class="card-header">
        @if (\Session::has('success'))
            <div class="alert alert-success">
                <!-- <ul>
                    <li>{!! \Session::get('success') !!}</li>
                </ul> -->
                <span class="message-update">{!! \Session::get('success') !!}</span>
            </div>
        @endif
        <h4>Dibatalkan</h4>
		</div>
		<div class="card-body">
            <table class="table table-sm text-nowrap table-batal" style="width: 100%;">
                <thead>
                    <th class="text-center">Unit</th>
                    <th class="text-center">No Pengajuan</th>
                    <th class="text-center">Tgl Pengajuan</th>
                    <th class="text-center">Pemohon</th>
                    <th class="text-center">Layanan</th>
                    <th class="text-center">Jenis Layanan</th>
                    <th class="text-center">Blok No</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </thead>
            </table>
		</div>
		<div id="v_loading" class="overlay" style="display: none;">
			<i class="fa fa-refresh fa-spin"></i>
		</div>

	</div>
@endsection