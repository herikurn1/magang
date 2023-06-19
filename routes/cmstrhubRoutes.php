<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::post('/upload-surat-ijin', 'Cmstrhub\prosespengajuan@upload_surat_ijin');
Route::post('/upload-keluhan', 'Cmstrhub\prosespengajuan@upload_keluhan');
Route::post('/upload-sales-today', 'Cmstrhub\prosespengajuan@upload_sales_today');
Route::post('/upload-ik', 'Cmstrhub\prosespengajuan@upload_ik');
Route::post('/upload-profile', 'Cmstrhub\prosespengajuan@upload_profile');



Route::group(['middleware' => ['portalAuth']], function() {
	Route::group(['prefix' => 'cmstrhub'], function() {
		Route::get('/', 'Cmstrhub\TestController@index');
	});
 
	/* === VIEW ROUTING === */
	Route::group(['middleware' => ['portalAuth.view']], function() {
		/* === View Dashboard === */
		Route::group(['prefix' => 'cmstrhub/dashboard'], function() {
			Route::get('/', 'Cmstrhub\dashboard@index');	
			Route::post('/wait', 'Cmstrhub\dashboard@waiting');	
			Route::post('/progress', 'Cmstrhub\dashboard@progress');	
			Route::post('/done', 'Cmstrhub\dashboard@done');	
			Route::post('/cancel', 'Cmstrhub\dashboard@cancel');
			
			Route::get('/data-wait', 'Cmstrhub\dashboard@data_waiting');
			Route::get('/data-progress', 'Cmstrhub\dashboard@data_progress');
			Route::get('/data-done', 'Cmstrhub\dashboard@data_done');
			Route::get('/data-cancel', 'Cmstrhub\dashboard@data_cancel');	
			
			Route::get('/menunggu-konfirmasi', 'Cmstrhub\dashboard@page_waiting');
			Route::get('/sedang-diproses', 'Cmstrhub\dashboard@page_progress');
			Route::get('/selesai', 'Cmstrhub\dashboard@page_done');
			Route::get('/dibatalkan', 'Cmstrhub\dashboard@page_cancel');
		});

		Route::group(['prefix' => 'cmstrhub/masterpromo'], function() {
			Route::get('/', 'Cmstrhub\masterpromo@index');	
			// Route::post('/get_dt_nominal', 'Vouchermall\cDashboardFa@get_dt_nominal');
			// Route::post('/get_panel', 'Vouchermall\cDashboardFa@get_panel');	
			// Route::post('/get_data_pengajuan', 'Vouchermall\cDashboardFa@get_data_pengajuan');
			// Route::post('/get_data_pengajuan_issued', 'Vouchermall\cDashboardFa@get_data_pengajuan_issued');
			// Route::post('/get_data_grafik', 'Vouchermall\cDashboardFa@get_data_grafik');
		}); 

		Route::group(['prefix' => 'cmstrhub/prosespengajuan'], function() {
			Route::get('/', 'Cmstrhub\prosespengajuan@index');	
			Route::post('/get_layanan', 'Cmstrhub\prosespengajuan@get_layanan');
			Route::post('/get_layanan_dtl', 'Cmstrhub\prosespengajuan@get_layanan_dtl');
			Route::post('/get_layanan_item', 'Cmstrhub\prosespengajuan@get_layanan_item');
			Route::post('/get_status', 'Cmstrhub\prosespengajuan@get_status');	
			Route::post('/get_data', 'Cmstrhub\prosespengajuan@get_data');
			Route::post('/get_dtl_data', 'Cmstrhub\prosespengajuan@get_dtl_data');
			Route::post('/confirm_data', 'Cmstrhub\prosespengajuan@confirm_data');
			Route::post('/progress_data', 'Cmstrhub\prosespengajuan@progress_data');
			Route::post('/void_data', 'Cmstrhub\prosespengajuan@void_data');
			Route::post('/history_data', 'Cmstrhub\prosespengajuan@history_data');
			
			Route::post('/get-token-confirm', 'Cmstrhub\prosespengajuan@get_token_confirm');
			Route::post('/get-token-void', 'Cmstrhub\prosespengajuan@get_token_void');
			Route::post('/get-token-device', 'Cmstrhub\prosespengajuan@get_token_device');

			Route::get('/idcard', 'Cmstrhub\prosespengajuan@idcard');
			Route::post('/idcard', 'Cmstrhub\prosespengajuan@generate_idcard');	
			Route::post('/insert-idcard', 'Cmstrhub\prosespengajuan@insert_idcard');
			Route::post('/resend-mail', 'Cmstrhub\prosespengajuan@resend_mail');
			Route::get('/get_tipe_idkaryawan', 'Cmstrhub\prosespengajuan@get_tipe_idkaryawan');	
		});

		Route::group(['prefix' => 'cmstrhub/sales'], function() {
			Route::get('/', 'Cmstrhub\sales@index');	
			Route::post('/get_data', 'Cmstrhub\sales@get_data');
		});

		Route::group(['prefix' => 'cmstrhub/daftarpengajuan'], function() {
			Route::get('/', 'Cmstrhub\daftarpengajuan@index');	
			Route::post('/get_layanan', 'Cmstrhub\daftarpengajuan@get_layanan');
			Route::post('/get_layanan_dtl', 'Cmstrhub\daftarpengajuan@get_layanan_dtl');
			Route::post('/get_layanan_item', 'Cmstrhub\daftarpengajuan@get_layanan_item');
			Route::post('/get_status', 'Cmstrhub\daftarpengajuan@get_status');	
			Route::post('/get_data', 'Cmstrhub\daftarpengajuan@get_data');
			Route::post('/get_dtl_data', 'Cmstrhub\daftarpengajuan@get_dtl_data');
		});

		Route::group(['prefix' => 'cmstrhub/user'], function() {
			Route::get('/', 'Cmstrhub\usertr@index');	
			Route::post('/get-unit', 'Cmstrhub\usertr@get_unit');
			Route::post('/get-zona', 'Cmstrhub\usertr@get_zona');
			Route::post('/get-stok', 'Cmstrhub\usertr@get_stok');
			Route::post('/get-blok', 'Cmstrhub\usertr@get_blok');
			Route::post('/show-unit', 'Cmstrhub\usertr@show_unit');
			Route::post('/show-zona', 'Cmstrhub\usertr@show_zona');
			Route::post('/search-dt', 'Cmstrhub\usertr@search_dt');

			Route::post('/get_layanan_item', 'Cmstrhub\usertr@get_layanan_item');
			Route::post('/get_status', 'Cmstrhub\usertr@get_status');	
			Route::post('/get_data', 'Cmstrhub\usertr@get_data');
			Route::post('/get_dtl_data', 'Cmstrhub\usertr@get_dtl_data');
		});

		
    });

	/* === SAVE ROUTING === */
	Route::group(['middleware' => ['portalAuth.save']], function() {
		Route::group(['prefix' => 'cmstrhub/user'], function() {
			Route::post('/save', 'Cmstrhub\usertr@insert');
		});
	});

	/* === DELETE ROUTING === */
	Route::group(['middleware' => ['portalAuth.delete']], function() {
		Route::group(['prefix' => 'cmstrhub/user'], function() {
			Route::post('/delete-unit', 'Cmstrhub\usertr@delete_unit');	
			Route::post('/delete-zona', 'Cmstrhub\usertr@delete_zona');	
		});
	});

});

Route::group(['prefix' => 'cmstrhub/regis'], function() {
	Route::get('/', 'Cmstrhub\regis@index');	
	Route::post('/check-code', 'Cmstrhub\regis@check_code');
	Route::post('/check-tenant', 'Cmstrhub\regis@check_tenant');
	Route::get('/form-regis/{data}', 'Cmstrhub\regis@form_regis');
	Route::post('/regis', 'Cmstrhub\regis@regis');	
	Route::post('/save', 'Cmstrhub\regis@save');	
}); 
