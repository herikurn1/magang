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


// Route::get('/budget', 'Budget\TestController@index');

Route::group(['middleware' => ['portalAuth']], function() {
	Route::group(['prefix' => 'budget'], function() {
		Route::get('/', 'Budget\TestController@index');
	});

	Route::group(['middleware' => ['portalAuth.view']], function() {
		Route::group(['prefix' => 'budget/entry_budget'], function() {
			Route::get('/', 'Budget\CEntryBudget@index');
			Route::post('/search_dt', 'Budget\CEntryBudget@search_dt');
			Route::post('/search_kategori_budget', 'Budget\CEntryBudget@search_kategori_budget');
			Route::post('/search_jenis', 'Budget\CEntryBudget@search_jenis');
			Route::post('/search_barang', 'Budget\CEntryBudget@search_barang');
			Route::post('/show_dtl', 'Budget\CEntryBudget@show_dtl');
			Route::post('/show_yearprev', 'Budget\CEntryBudget@show_yearprev');
			Route::post('/salin_data', 'Budget\CEntryBudget@salin_data');
			Route::post('/view_history', 'Budget\CEntryBudget@view_history');
		});

		Route::group(['prefix' => 'budget/rev_budget'], function() {
			Route::get('/', 'Budget\CRevBudget@index');
			Route::post('/search_dt', 'Budget\CRevBudget@search_dt');
			Route::post('/search_kategori_budget', 'Budget\CRevBudget@search_kategori_budget');
			Route::post('/search_jenis', 'Budget\CRevBudget@search_jenis');
			Route::post('/search_barang', 'Budget\CRevBudget@search_barang');
			Route::post('/show_dtl', 'Budget\CRevBudget@show_dtl');
			Route::post('/show_yearprev', 'Budget\CRevBudget@show_yearprev');
			Route::post('/salin_data', 'Budget\CRevBudget@salin_data');
			Route::post('/view_history', 'Budget\CRevBudget@view_history');
		});

		Route::group(['prefix' => 'budget/approval_kabag'], function() {
			Route::get('/', 'Budget\CApprovalKabag@index');
			Route::post('/show_kabag', 'Budget\CApprovalKabag@show_kabag');
			Route::post('/show_finance', 'Budget\CApprovalKabag@show_finance');
			Route::post('/show_bod', 'Budget\CApprovalKabag@show_bod');
			Route::post('/show_complete', 'Budget\CApprovalKabag@show_complete');
			Route::post('/show_barang', 'Budget\CApprovalKabag@show_barang');
			Route::post('/save_barang', 'Budget\CApprovalKabag@save_barang');
		});

		Route::group(['prefix' => 'budget/approval_finance'], function() {
			Route::get('/', 'Budget\CApprovalFinance@index');
			Route::post('/show_finance', 'Budget\CApprovalFinance@show_finance');
			Route::post('/show_bod', 'Budget\CApprovalFinance@show_bod');
			Route::post('/show_complete', 'Budget\CApprovalFinance@show_complete');
			Route::post('/show_barang', 'Budget\CApprovalFinance@show_barang');
		});

		Route::group(['prefix' => 'budget/approval_bod'], function() {
			Route::get('/', 'Budget\CApprovalBod@index');
			Route::post('/show_bod', 'Budget\CApprovalBod@show_bod');
			Route::post('/show_complete', 'Budget\CApprovalBod@show_complete');
			Route::post('/show_barang', 'Budget\CApprovalBod@show_barang');
		});

		Route::group(['prefix' => 'budget/lap_capex_dtl'], function() {
			Route::get('/', 'Budget\CLapCapexDtl@index');
			Route::post('/check_role_budget', 'Budget\CLapCapexDtl@check_role_budget');
			Route::post('/search_kategori_budget', 'Budget\CLapCapexDtl@search_kategori_budget');
			Route::post('/search_jenis', 'Budget\CLapCapexDtl@search_jenis');
			Route::post('/search_unit_budget', 'Budget\CLapCapexDtl@search_unit_budget');
			Route::post('/search_departemen_budget', 'Budget\CLapCapexDtl@search_departemen_budget');
			Route::post('/search_detail', 'Budget\CLapCapexDtl@search_detail');
		});

		Route::group(['prefix' => 'budget/rek_capex_cat_dept'], function() {
			Route::get('/', 'Budget\CRekCapexCatDept@index');
			Route::post('/check_role_budget', 'Budget\CRekCapexCatDept@check_role_budget');
			Route::post('/search_kategori_budget', 'Budget\CRekCapexCatDept@search_kategori_budget');
			Route::post('/search_jenis', 'Budget\CRekCapexCatDept@search_jenis');
			Route::post('/search_unit_budget', 'Budget\CRekCapexCatDept@search_unit_budget');
			Route::post('/search_departemen_budget', 'Budget\CRekCapexCatDept@search_departemen_budget');
			Route::post('/search_detail', 'Budget\CRekCapexCatDept@search_detail');
			Route::get('/search_chart', 'Budget\CRekCapexCatDept@search_chart');
		});

		Route::group(['prefix' => 'budget/rek_capex_cat_unit'], function() {
			Route::get('/', 'Budget\CRekCapexCatUnit@index');
			Route::post('/check_role_budget', 'Budget\CRekCapexCatUnit@check_role_budget');
			Route::post('/search_kategori_budget', 'Budget\CRekCapexCatUnit@search_kategori_budget');
			Route::post('/search_jenis', 'Budget\CRekCapexCatUnit@search_jenis');
			Route::post('/search_unit_budget', 'Budget\CRekCapexCatUnit@search_unit_budget');
			Route::post('/search_departemen_budget', 'Budget\CRekCapexCatUnit@search_departemen_budget');
			Route::post('/search_detail', 'Budget\CRekCapexCatUnit@search_detail');
			Route::get('/search_chart', 'Budget\CRekCapexCatUnit@search_chart');
		});

		Route::group(['prefix' => 'budget/rek_capex_vs_actual'], function() {
			Route::get('/', 'Budget\CRekCapexVsActual@index');
			Route::post('/check_role_budget', 'Budget\CRekCapexVsActual@check_role_budget');
			Route::post('/search_kategori_budget', 'Budget\CRekCapexVsActual@search_kategori_budget');
			Route::post('/search_jenis', 'Budget\CRekCapexVsActual@search_jenis');
			Route::post('/search_unit_budget', 'Budget\CRekCapexVsActual@search_unit_budget');
			Route::post('/search_departemen_budget', 'Budget\CRekCapexVsActual@search_departemen_budget');
			Route::post('/search_detail', 'Budget\CRekCapexVsActual@search_detail');
			Route::get('/search_chart', 'Budget\CRekCapexVsActual@search_chart');
		});

		Route::group(['prefix' => 'budget/capex_vs_actual_dtl'], function() {
			Route::get('/', 'Budget\CCapexVsActualDtl@index');
			Route::post('/check_role_budget', 'Budget\CCapexVsActualDtl@check_role_budget');
			Route::post('/search_kategori_budget', 'Budget\CCapexVsActualDtl@search_kategori_budget');
			Route::post('/search_jenis', 'Budget\CCapexVsActualDtl@search_jenis');
			Route::post('/search_unit_budget', 'Budget\CCapexVsActualDtl@search_unit_budget');
			Route::post('/search_departemen_budget', 'Budget\CCapexVsActualDtl@search_departemen_budget');
			Route::post('/search_detail', 'Budget\CCapexVsActualDtl@search_detail');
			Route::get('/search_chart', 'Budget\CCapexVsActualDtl@search_chart');
		});

	});

	Route::group(['middleware' => ['portalAuth.save']], function() {
		Route::group(['prefix' => 'budget/entry_budget'], function() {
			Route::post('/save', 'Budget\CEntryBudget@save');
			Route::post('/submit_kabag', 'Budget\CEntryBudget@submit_kabag');
		});

		Route::group(['prefix' => 'budget/rev_budget'], function() {
			Route::post('/save', 'Budget\CRevBudget@save');
			Route::post('/submit_kabag', 'Budget\CRevBudget@submit_kabag');
		});

		Route::group(['prefix' => 'budget/approval_kabag'], function() {
			Route::post('/save', 'Budget\CApprovalKabag@save');
		});

		Route::group(['prefix' => 'budget/approval_finance'], function() {
			Route::post('/save', 'Budget\CApprovalFinance@save');
			Route::post('/save_barang', 'Budget\CApprovalFinance@save_barang');
		});

		Route::group(['prefix' => 'budget/approval_bod'], function() {
			Route::post('/save', 'Budget\CApprovalBod@save');
			Route::post('/save_barang', 'Budget\CApprovalBod@save_barang');
		});
	});

	Route::group(['middleware' => ['portalAuth.delete']], function() {
		Route::group(['prefix' => 'budget/entry_budget'], function() {
			Route::post('/delete_dt', 'Budget\CEntryBudget@delete_dt');
			Route::post('/delete_barang', 'Budget\CEntryBudget@delete_barang');
		});
	});
});