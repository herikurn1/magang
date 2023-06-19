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

Route::group(['middleware' => ['portalAuth']], function() {
	Route::group(['prefix' => 'sqii'], function() {
		Route::get('/', 'Sqii\TestController@index');
	});

	Route::group(['middleware' => ['portalAuth.view']], function() {
		Route::group(['prefix' => 'sqii/register_data_kawasan_c'], function() {
			Route::get('/', 'Sqii\RegKawasanC@index');
			Route::post('/search_dt', 'Sqii\RegKawasanC@search_dt');
			Route::post('/show_dtl', 'Sqii\RegKawasanC@show_dtl');
			Route::post('/show_kawasan', 'Sqii\RegKawasanC@show_kawasan');
		});

		Route::group(['prefix' => 'sqii/register_data_cluster_c'], function() {
			Route::get('/', 'Sqii\RegClusterC@index');
			Route::post('/search_dt', 'Sqii\RegClusterC@search_dt');
			Route::post('/show_cluster', 'Sqii\RegClusterC@show_cluster');
			Route::post('/sync_dt', 'Sqii\RegClusterC@sync_dt');
		});

		Route::group(['prefix' => 'sqii/register_data_jns_bangunan_c'], function() {
			Route::get('/', 'Sqii\RegJenisBangunanC@index');
			Route::post('/search_dt', 'Sqii\RegJenisBangunanC@search_dt');
			Route::post('/show_jenis_bangunan', 'Sqii\RegJenisBangunanC@show_jenis_bangunan');
			Route::post('/sync_dt', 'Sqii\RegJenisBangunanC@sync_dt');
		});

		Route::group(['prefix' => 'sqii/register_data_tp_bangunan_c'], function() {
			Route::get('/', 'Sqii\RegTipeBangunanC@index');
			Route::post('/search_dt', 'Sqii\RegTipeBangunanC@search_dt');
			Route::post('/show_jenis_bangunan', 'Sqii\RegTipeBangunanC@show_jenis_bangunan');
			Route::post('/sync_dt', 'Sqii\RegTipeBangunanC@sync_dt');
			Route::post('/data_cluster', 'Sqii\RegTipeBangunanC@data_cluster');
			Route::post('/data_denah', 'Sqii\RegTipeBangunanC@data_denah');
			Route::post('/available_stok', 'Sqii\RegTipeBangunanC@available_stok');
			Route::post('/file_upload', 'Sqii\RegTipeBangunanC@file_upload');
			// Route::post('/data_model', 'Sqii\RegTipeBangunanC@data_model');
		});

		Route::group(['prefix' => 'sqii/register_data_stok_c'], function() {
			Route::get('/', 'Sqii\RegDataStokC@index');
			Route::post('/search_dt', 'Sqii\RegDataStokC@search_dt');
			Route::post('/show_jenis_bangunan', 'Sqii\RegDataStokC@show_jenis_bangunan');
			Route::post('data_cluster', 'Sqii\RegDataStokC@data_cluster');
			Route::post('data_blok_no', 'Sqii\RegDataStokC@data_blok_no');
			Route::post('/data_jenis_bangunan', 'Sqii\RegDataStokC@data_jenis_bangunan');
			Route::post('search_blok_no', 'Sqii\RegDataStokC@search_blok_no');
			Route::post('pop_tipe', 'Sqii\RegDataStokC@pop_tipe');
			Route::post('/sync_dt', 'Sqii\RegDataStokC@sync_dt');
			Route::post('/data_tahap', 'Sqii\RegDataStokC@data_tahap');
		});

		Route::group(['prefix' => 'sqii/generate_qr_code_c'], function() {
			Route::get('/', 'Sqii\GenQrcodeC@index');
			Route::post('/search_dt', 'Sqii\GenQrcodeC@search_dt');
			Route::post('/show_data_item_defect', 'Sqii\GenQrcodeC@show_data_item_defect');
			Route::post('data_cluster', 'Sqii\RegDataStokC@data_cluster');
		});

		Route::group(['prefix' => 'sqii/input_data_lantai_c'], function() {
			Route::get('/', 'Sqii\InputLantaiC@index');
			Route::post('/search_dt', 'Sqii\InputLantaiC@search_dt');
			Route::post('/show_lantai', 'Sqii\InputLantaiC@show_lantai');
		});

		Route::group(['prefix' => 'sqii/input_denah_tipe_lantai_c'], function() {
			Route::get('/', 'Sqii\InputDenahLantaiC@index');
			Route::post('/search_dt', 'Sqii\InputDenahLantaiC@search_dt');
			//Route::post('/show_jenis_bangunan', 'Sqii\InputDenahLantaiC@show_jenis_bangunan');
			Route::post('data_cluster', 'Sqii\InputDenahLantaiC@data_cluster');
			Route::post('denah_lantai', 'Sqii\InputDenahLantaiC@denah_lantai');;
			Route::post('tipe_rumah', 'Sqii\InputDenahLantaiC@tipe_rumah');
			Route::post('data_lantai', 'Sqii\InputDenahLantaiC@data_lantai');
			Route::post('available_stok', 'Sqii\InputDenahLantaiC@available_stok');
			Route::post('/file_upload', 'Sqii\InputDenahLantaiC@file_upload');
		});

		Route::group(['prefix' => 'sqii/input_data_jns_pekerjaan_c'], function() {
			Route::get('/', 'Sqii\InputJenisPekerjanC@index');
			Route::post('/search_dt', 'Sqii\InputJenisPekerjanC@search_dt');
			Route::post('/show_jenis_pekerjaan', 'Sqii\InputJenisPekerjanC@show_jenis_pekerjaan');
		});

		Route::group(['prefix' => 'sqii/input_data_item_defect_c'], function() {
			Route::get('/', 'Sqii\InputItemDefectC@index');
			Route::post('/search_dt', 'Sqii\InputItemDefectC@search_dt');
			Route::post('/show_data_item_defect', 'Sqii\InputItemDefectC@show_data_item_defect');
		}); 

		Route::group(['prefix' => 'sqii/input_tbl_catatan_defect_c'], function() {
			Route::get('/', 'Sqii\InputCatatDefectC@index');
			Route::post('/search_dt', 'Sqii\InputCatatDefectC@search_dt');
			Route::post('/show_data_item_defect', 'Sqii\InputCatatDefectC@show_data_item_defect');
		});       

		Route::group(['prefix' => 'sqii/input_tbl_tahap_pekerjaan_c'], function() {
			Route::get('/', 'Sqii\InputTahapPekerjaanC@index');
			Route::post('/search_dt', 'Sqii\InputTahapPekerjaanC@search_dt');
			Route::post('/show_tahap_pekerjaan', 'Sqii\InputTahapPekerjaanC@show_tahap_pekerjaan');
			Route::post('data_cluster', 'Sqii\InputTahapPekerjaanC@data_cluster');
			Route::post('mst_tahap_pekerjaan', 'Sqii\InputTahapPekerjaanC@mst_tahap_pekerjaan');
		});

		Route::group(['prefix' => 'sqii/input_dt_item_p15_p35_c'], function() {
			Route::get('/', 'Sqii\InputItemPekerjaanC@index');
			Route::post('/search_dt', 'Sqii\InputItemPekerjaanC@search_dt');
			Route::post('/show_jenis_bangunan', 'Sqii\InputItemPekerjaanC@show_jenis_bangunan');
			Route::post('data_cluster', 'Sqii\InputItemPekerjaanC@data_cluster');
			Route::post('data_blok_no', 'Sqii\InputItemPekerjaanC@data_blok_no');
			Route::post('/sync_dt', 'Sqii\InputItemPekerjaanC@sync_dt');
			Route::post('data_tahapan', 'Sqii\InputItemPekerjaanC@data_tahapan');
			Route::post('item_header_detail', 'Sqii\InputItemPekerjaanC@item_header_detail');
			Route::post('cek_total_bobot', 'Sqii\InputItemPekerjaanC@cek_total_bobot');

		});   

		Route::group(['prefix' => 'sqii/input_dt_mst_item_p15_p35_c'], function() {
			Route::get('/', 'Sqii\InputMasterItemPekerjaanC@index');
			Route::post('/search_dt', 'Sqii\InputMasterItemPekerjaanC@search_dt');
			Route::post('data_blok_no', 'Sqii\InputMasterItemPekerjaanC@data_blok_no');
			Route::post('/sync_dt', 'Sqii\InputMasterItemPekerjaanC@sync_dt');
			Route::post('item_header_detail', 'Sqii\InputMasterItemPekerjaanC@item_header_detail');

		});   		    

		Route::group(['prefix' => 'sqii/register_tahap_bangun_c'], function() {
			Route::get('/', 'Sqii\RegTahapBangunC@index');
			Route::post('/search_dt', 'Sqii\RegTahapBangunC@search_dt');
			Route::post('/show_jenis_bangunan', 'Sqii\RegTahapBangunC@show_jenis_bangunan');
			Route::post('data_cluster', 'Sqii\RegTahapBangunC@data_cluster');
			Route::post('data_blok_no', 'Sqii\RegTahapBangunC@data_blok_no');
			Route::post('/data_jenis_bangunan', 'Sqii\RegTahapBangunC@data_jenis_bangunan');
			Route::post('search_blok_no', 'Sqii\RegTahapBangunC@search_blok_no');
			Route::post('pop_tipe', 'Sqii\RegTahapBangunC@pop_tipe');
			Route::post('/sync_dt', 'Sqii\RegTahapBangunC@sync_dt');
			Route::post('/data_tahap', 'Sqii\RegTahapBangunC@data_tahap');
		});

		Route::group(['prefix' => 'sqii/input_tbl_mst_tahap_pekerjaan_c'], function() {
			Route::get('/', 'Sqii\InputMasterTahapPekerjaanC@index');
			Route::post('/search_dt', 'Sqii\InputMasterTahapPekerjaanC@search_dt');
			Route::post('/show_tahap_pekerjaan', 'Sqii\InputMasterTahapPekerjaanC@show_tahap_pekerjaan');
			Route::post('data_cluster', 'Sqii\InputMasterTahapPekerjaanC@data_cluster');
		});

		Route::group(['prefix' => 'sqii/input_rencana_progress_mingguan_c'], function() {
			Route::get('/', 'Sqii\InputRencanaProgMingguC@index');
			Route::post('/search_dt', 'Sqii\InputRencanaProgMingguC@search_dt');
			Route::post('/show_jenis_bangunan', 'Sqii\InputRencanaProgMingguC@show_jenis_bangunan');
			Route::post('data_cluster', 'Sqii\InputRencanaProgMingguC@data_cluster');
			Route::post('data_blok_no', 'Sqii\InputRencanaProgMingguC@data_blok_no');
			Route::post('/sync_dt', 'Sqii\InputRencanaProgMingguC@sync_dt');
			Route::post('data_tahapan', 'Sqii\InputRencanaProgMingguC@data_tahapan');
			Route::post('item_header_detail', 'Sqii\InputRencanaProgMingguC@item_header_detail');
			Route::post('cek_minggu_pertama', 'Sqii\InputRencanaProgMingguC@cek_minggu_pertama');

		}); 
		/* ---------------------------------------- Administrasi ---------------------------------------- */

		Route::group(['prefix' => 'sqii/set_jabatan_struktur_org_c'], function() {
			Route::get('/', 'Sqii\EntryJabatanPetugasC@index');
			Route::post('/search_dt', 'Sqii\EntryJabatanPetugasC@search_dt');
			Route::post('data_cluster', 'Sqii\EntryJabatanPetugasC@data_cluster');
			Route::post('data_blok_no', 'Sqii\EntryJabatanPetugasC@data_blok_no');
			Route::post('get_staff', 'Sqii\EntryJabatanPetugasC@get_staff');
			Route::post('nik_petugas', 'Sqii\EntryJabatanPetugasC@nik_petugas');
			Route::post('nik_karyawan', 'Sqii\EntryJabatanPetugasC@nik_karyawan');
			Route::post('mst_jabatan', 'Sqii\EntryJabatanPetugasC@mst_jabatan');
		});

		Route::group(['prefix' => 'sqii/alokasi_petugas_blok_nomor_c'], function() {
			Route::get('/', 'Sqii\AlokasiPetugasC@index');
			Route::post('/search_dt', 'Sqii\AlokasiPetugasC@search_dt');
			Route::post('data_cluster', 'Sqii\AlokasiPetugasC@data_cluster');
			Route::post('data_blok_no', 'Sqii\AlokasiPetugasC@data_blok_no');
			Route::post('available_stok', 'Sqii\AlokasiPetugasC@available_stok');
			Route::post('nik_petugas', 'Sqii\AlokasiPetugasC@nik_petugas');
		});

		Route::group(['prefix' => 'sqii/list_alokasi_petugas_c'], function() {
			Route::get('/', 'Sqii\ListAlokasiPetugasC@index');
			Route::post('/search_dt', 'Sqii\ListAlokasiPetugasC@search_dt');
			Route::post('/show_jenis_bangunan', 'Sqii\ListAlokasiPetugasC@show_jenis_bangunan');
			Route::post('data_cluster', 'Sqii\ListAlokasiPetugasC@data_cluster');
			Route::post('data_blok_no', 'Sqii\ListAlokasiPetugasC@data_blok_no');
			Route::post('/data_jenis_bangunan', 'Sqii\ListAlokasiPetugasC@data_jenis_bangunan');
			Route::post('data_blok', 'Sqii\ListAlokasiPetugasC@data_blok');
			Route::post('data_tipe', 'Sqii\ListAlokasiPetugasC@data_tipe');
		});

		Route::group(['prefix' => 'sqii/rotasi_blok_no_c'], function() {
			Route::get('/', 'Sqii\RotasiBlokNoC@index');
			Route::post('/search_dt', 'Sqii\RotasiBlokNoC@search_dt');
			Route::post('data_cluster', 'Sqii\RotasiBlokNoC@data_cluster');
			Route::post('data_blok_no', 'Sqii\RotasiBlokNoC@data_blok_no');
			Route::post('available_stok', 'Sqii\RotasiBlokNoC@available_stok');
			Route::post('nik_petugas', 'Sqii\RotasiBlokNoC@nik_petugas');
			Route::post('get_staff', 'Sqii\RotasiBlokNoC@get_staff');
		});

		Route::group(['prefix' => 'sqii/rotasi_atasan_c'], function() {
			Route::get('/', 'Sqii\RotasiAtasanC@index');
			Route::post('/search_dt', 'Sqii\RotasiAtasanC@search_dt');
			Route::post('data_cluster', 'Sqii\RotasiAtasanC@data_cluster');
			Route::post('data_blok_no', 'Sqii\RotasiAtasanC@data_blok_no');
			Route::post('available_stok', 'Sqii\RotasiAtasanC@available_stok');
			Route::post('nik_petugas', 'Sqii\RotasiAtasanC@nik_petugas');
			Route::post('get_staff', 'Sqii\RotasiAtasanC@get_staff');
		});

		/* ---------------------------------------- Laporan ---------------------------------------- */

		Route::group(['prefix' => 'sqii/lap_kinerja_c'], function() {
			Route::get('/', 'Sqii\LapKinerjaC@index');
			Route::post('/search_dt', 'Sqii\LapKinerjaC@search_dt');
			Route::post('/show_data_item_defect', 'Sqii\LapKinerjaC@show_data_item_defect');
			Route::post('data_cluster', 'Sqii\RegDataStokC@data_cluster');		
			Route::post('nik_petugas', 'Sqii\LapKinerjaC@nik_petugas');
			Route::post('/print_dt', 'Sqii\LapKinerjaC@print_dt');
			Route::post('/data_tahap', 'Sqii\LapKinerjaC@data_tahap');
		});

		Route::group(['prefix' => 'sqii/lap_defect_c'], function() {
			Route::get('/{kd_kawasan}/{kd_cluster}/{nm_kawasan}/{nm_cluster}/{nm_sm}/{periode_1}/{periode_2}/{user_id}/{user_id_bawahan}/{nama}/{jml_unit}/{jml_defect}/{total_defect}/{tot_unit}/{session_user_id}/{tahap_bangun}', 'Sqii\LapDefectC@index');
			Route::post('/print_dt', 'Sqii\LapDefectC@print_dt');
		}); // done

		Route::group(['prefix' => 'sqii/lap_detil_kualitas_c'], function() {
			Route::get('/{kd_kawasan}/{kd_cluster}/{nm_kawasan}/{nm_cluster}/{nm_sm}/{periode_1}/{periode_2}/{user_id}/{user_id_bawahan}/{nama}/{jml_unit}/{jml_defect}/{total_defect}/{kd_kategori_defect}/{tot_unit}/{session_user_id}/{tahap_bangun}', 'Sqii\LapDetailKualitasC@index');
			Route::post('/print_dt', 'Sqii\LapDetailKualitasC@print_dt');
		});	// done

		Route::group(['prefix' => 'sqii/lap_form_kualitas_bgn_c'], function() {
			Route::get('/{kd_kawasan}/{kd_cluster}/{nm_kawasan}/{nm_cluster}/{nm_sm}/{periode_1}/{periode_2}/{user_id_bawahan}/{kd_kategori_defect}/{no_formulir}/{nama}/{session_user_id}/{tahap_bangun}', 'Sqii\LapFormKualitasBgnC@index');
			Route::post('/print_dt', 'Sqii\LapFormKualitasBgnC@print_dt');
		});	// done

		Route::group(['prefix' => 'sqii/lap_detil_aging_c'], function() {
			Route::get('/{kd_kawasan}/{kd_cluster}/{nm_kawasan}/{nm_cluster}/{nm_sm}/{periode_1}/{periode_2}/{user_id}/{user_id_bawahan}/{nama}/{jml_unit}/{jml_defect}/{total_defect}/{tot_unit}/{tipe_ageing}/{session_user_id}/{tahap_bangun}', 'Sqii\LapDetailAegingC@index');
			Route::post('/print_dt', 'Sqii\LapDetailAegingC@print_dt');
		});	// done

		Route::group(['prefix' => 'sqii/lap_cycle_time_c'], function() {
			Route::get('/{kd_kawasan}/{kd_cluster}/{nm_kawasan}/{nm_cluster}/{nm_sm}/{periode_1}/{periode_2}/{user_id}/{user_id_bawahan}/{nama}/{jml_unit}/{jml_defect}/{total_defect}/{tot_unit}/{session_user_id}/{tahap_bangun}', 'Sqii\LapCycleTimeC@index');
			Route::post('/print_dt', 'Sqii\LapCycleTimeC@print_dt');
		});	// done

		Route::group(['prefix' => 'sqii/lap_formulasi_cycle_time_c'], function() {
			Route::get('/{kd_kawasan}/{kd_cluster}/{nm_kawasan}/{nm_clusterx}/{nm_sm}/{periode_1x}/{periode_2x}/{user_id}/{user_id_bawahan}/{nama}/{jml_unit}/{jml_defect}/{total_defect}/{tot_unit}/{session_user_id}/{tahap_bangun}', 'Sqii\LapFormulasiCycleTimeC@index');
			Route::post('/print_dt', 'Sqii\LapFormulasiCycleTimeC@print_dt');
		});	// 

		Route::group(['prefix' => 'sqii/lap_patern_cycle_time_c'], function() {
			Route::get('/{kd_kawasan}/{kd_cluster}/{nm_kawasan}/{nm_cluster}/{nm_sm}/{periode_1}/{periode_2}/{user_id}/{user_id_bawahan}/{nama}/{jml_unit}/{jml_defect}/{total_defect}/{tot_unit}/{session_user_id}/{tahap_bangun}', 'Sqii\LapPaternCycleTimeC@index');
			Route::post('/print_dt', 'Sqii\LapPaternCycleTimeC@print_dt');
		});	// 

		Route::group(['prefix' => 'sqii/lap_grafic_defect_c'], function() {
			Route::get('/{kd_kawasan}/{kd_cluster}/{nm_kawasan}/{nm_cluster}/{nik_petugas}/{nm_sm}/{periode_1}/{periode_2}/{kd_kategori}/{chart}/{session_user_id}/{tahap_bangun}', 'Sqii\LapKinerjaC2@index');
			Route::post('/print_dt', 'Sqii\LapKinerjaC2@print_dt');
		}); // done

		Route::group(['prefix' => 'sqii/lap_defect_c'], function() {
			Route::	get('/{kd_kawasan}/{kd_cluster}/{nm_kawasan}/{nm_cluster}/{nm_sm}/{periode_1}/{periode_2}/{user_id}/{user_id_bawahan}/{nama}/{jml_unit}/{jml_defect}/{total_defect}/{tot_unit}/{session_user_id}', 'Sqii\LapDefectC@index');
		}); // done

		// Kinerja P35/P15
		Route::group(['prefix' => 'sqii/lap_kinerja_P3515_c'], function() {
			Route::get('/', 'Sqii\LapKinerjaP3515C@index');
			Route::post('/search_dt', 'Sqii\LapKinerjaP3515C@search_dt');
			Route::post('/show_data_item_defect', 'Sqii\LapKinerjaP3515C@show_data_item_defect');
			Route::post('/data_cluster', 'Sqii\RegDataStokC@data_cluster');
			Route::post('nik_petugas', 'Sqii\LapKinerjaP3515C@nik_petugas');
			Route::post('/print_dt', 'Sqii\LapKinerjaP3515C@print_dt');
			Route::post('/data_tahap', 'Sqii\LapKinerjaP3515C@data_tahap');
		});	// done

		//
		Route::group(['prefix' => 'sqii/lap_detail_kinerja_P3515_orang_c'], function() {
			Route::get('/{kd_kawasan}/{kd_cluster}/{nm_kawasan}/{nm_cluster}/{nm_sm}/{periode_1}/{periode_2}/{user_id}/{user_id_bawahan}/{nama}/{jml_unit}/{jml_defect}/{total_defect}/{tot_unit}/{session_user_id}/{tahap_bangun}', 'Sqii\LapDetailKinerjaP3515OrangC@index');
			Route::post('/print_dt', 'Sqii\LapDetailKinerjaP3515OrangC@print_dt');
		}); // done

		Route::group(['prefix' => 'sqii/lap_detail_kinerja_P3515_tahap_c'], function() {
			Route::get('/{kd_kawasan}/{kd_cluster}/{nm_kawasan}/{nm_cluster}/{nm_sm}/{periode_1}/{periode_2}/{user_id}/{user_id_bawahan}/{nama}/{jml_unit}/{jml_defect}/{total_defect}/{kd_kategori_defect}/{tot_unit}/{session_user_id}/{tahap_bangun}', 'Sqii\LapDetailKinerjaP3515TahapC@index');
			Route::post('/print_dt', 'Sqii\LapDetailKinerjaP3515TahapC@print_dt');
		});	// done

		// Kinerja P35
		Route::group(['prefix' => 'sqii/lap_kinerja_qc_P35_c'], function() {
			Route::get('/', 'Sqii\LapKinerjaQCP35C@index');
			Route::post('/search_dt', 'Sqii\LapKinerjaQCP35C@search_dt');
			Route::post('/show_data_item_defect', 'Sqii\LapKinerjaQCP35C@show_data_item_defect');
			Route::post('/data_cluster', 'Sqii\RegDataStokC@data_cluster');
			Route::post('nik_petugas', 'Sqii\LapKinerjaQCP35C@nik_petugas');
			Route::post('/print_dt', 'Sqii\LapKinerjaQCP35C@print_dt');
			Route::post('/data_tahap', 'Sqii\LapKinerjaQCP35C@data_tahap');
		});		

		//
		Route::group(['prefix' => 'sqii/lap_detail_kinerja_qc_P35_orang_c'], function() {
			Route::get('/{kd_kawasan}/{kd_cluster}/{nm_kawasan}/{nm_cluster}/{nm_sm}/{periode_1}/{periode_2}/{user_id}/{user_id_bawahan}/{nama}/{jml_unit}/{jml_defect}/{total_defect}/{tot_unit}/{session_user_id}/{tahap_bangun}', 'Sqii\LapDetailKinerjaQCP35OrangC@index');
			Route::post('/print_dt', 'Sqii\LapDetailKinerjaQCP35OrangC@print_dt');
		}); // 

		Route::group(['prefix' => 'sqii/lap_detail_kinerja_qc_P35_tahap_c'], function() {
			Route::get('/{kd_kawasan}/{kd_cluster}/{nm_kawasan}/{nm_cluster}/{nm_sm}/{periode_1}/{periode_2}/{user_id}/{user_id_bawahan}/{nama}/{jml_unit}/{jml_defect}/{total_defect}/{kd_kategori_defect}/{tot_unit}/{session_user_id}/{tahap_bangun}', 'Sqii\LapDetailKinerjaQCP35TahapC@index');
			Route::post('/print_dt', 'Sqii\LapDetailKinerjaQCP35TahapC@print_dt');
		});	// 

		// Rekap Detail Temuan
		Route::group(['prefix' => 'sqii/lap_rekap_detail_temuan_c'], function() {
			Route::get('/', 'Sqii\LapRekapDtlTemuanC@index');
			Route::post('/search_dt', 'Sqii\LapRekapDtlTemuanC@search_dt');
			Route::post('/show_data_item_defect', 'Sqii\LapRekapDtlTemuanC@show_data_item_defect');
			Route::post('/data_cluster', 'Sqii\RegDataStokC@data_cluster');		
			Route::post('nik_petugas', 'Sqii\LapRekapDtlTemuanC@nik_petugas');
			Route::post('/print_dt', 'Sqii\LapRekapDtlTemuanC@print_dt');
			Route::post('/data_kontraktor', 'Sqii\LapRekapDtlTemuanC@data_kontraktor');
		});	

		// Rekap progres P35/P15
		Route::group(['prefix' => 'sqii/lap_rekap_progres_c'], function() {
			Route::get('/', 'Sqii\LapRekapProgresC@index');
			Route::post('/search_dt', 'Sqii\LapRekapProgresC@search_dt');
			Route::post('/show_data_item_defect', 'Sqii\LapRekapProgresC@show_data_item_defect');
			Route::post('/data_cluster', 'Sqii\RegDataStokC@data_cluster');
			Route::post('nik_petugas', 'Sqii\LapRekapProgresC@nik_petugas');
			Route::post('/print_dt', 'Sqii\LapRekapProgresC@print_dt');
			Route::post('/data_periode', 'Sqii\LapRekapProgresC@data_periode');
			Route::post('/data_tahap', 'Sqii\LapRekapProgresC@data_tahap');
		});	

		Route::group(['prefix' => 'sqii/lap_detail_progres_kontraktor_c'], function() {
			Route::get('/{kd_kawasan}/{kd_cluster}/{nm_kawasan}/{nm_cluster}/{kd_periode}/{periode2}/{user_id}/{nama}/{session_user_id}', 'Sqii\LapDetailProgresKontraktorC@index');
			Route::post('/print_dt', 'Sqii\LapDetailProgresKontraktorC@print_dt');
		});

		Route::group(['prefix' => 'sqii/lap_detail_aktual_progres_c'], function() {
			Route::get('/{kd_kawasan1}/{kd_cluster1}/{nm_kawasan1}/{nm_cluster1}/{kd_periode1}/{periode21}/{user_id1}/{nama1}/{blok1}/{nomor1}/{nm_tipe1}/{session_user_id}', 'Sqii\LapDetailAktualProgresC@index');
			Route::post('/print_dt', 'Sqii\LapDetailAktualProgresC@print_dt');
		});	// done		

		// Cetakan formulir P15
		Route::group(['prefix' => 'sqii/formulir_item_pekerjaan_p15_c'], function() {
			Route::get('/', 'Sqii\FormulirItmPekP15C@index');
			Route::post('/search_dt', 'Sqii\FormulirItmPekP15C@search_dt');
			Route::post('/show_data_item_defect', 'Sqii\FormulirItmPekP15C@show_data_item_defect');
			Route::post('/data_cluster', 'Sqii\RegDataStokC@data_cluster');		
			Route::post('nik_petugas', 'Sqii\FormulirItmPekP15C@nik_petugas');
			Route::post('/print_dt', 'Sqii\FormulirItmPekP15C@print_dt');
			Route::post('/data_kontraktor', 'Sqii\FormulirItmPekP15C@data_kontraktor');
			Route::post('/data_tahap_pekerjaan', 'Sqii\FormulirItmPekP15C@data_tahap_pekerjaan');
		});	

		/* ---------------------------------------- Utility ---------------------------------------- */

		Route::group(['prefix' => 'sqii/user_management_c'], function() {
			Route::get('/', 'Sqii\ManagementPetugasC@index');
			Route::post('/search_dt', 'Sqii\ManagementPetugasC@search_dt');
			Route::post('data_cluster', 'Sqii\ManagementPetugasC@data_cluster');
			Route::post('data_blok_no', 'Sqii\ManagementPetugasC@data_blok_no');
			Route::post('available_stok', 'Sqii\ManagementPetugasC@available_stok');
			Route::post('nik_petugas', 'Sqii\ManagementPetugasC@nik_petugas');
			Route::post('nik_karyawan', 'Sqii\ManagementPetugasC@nik_karyawan');
			Route::post('mst_jabatan', 'Sqii\ManagementPetugasC@mst_jabatan');
		});
	});

	Route::group(['middleware' => ['portalAuth.save']], function() {
		Route::group(['prefix' => 'sqii/register_data_kawasan_c'], function() {
			Route::post('/save', 'Sqii\RegKawasanC@save');
		});

		Route::group(['prefix' => 'sqii/register_data_stok_c'], function() {
			Route::post('/save', 'Sqii\RegDataStokC@save');
			Route::post('/save_tipe_rumah', 'Sqii\RegDataStokC@save_tipe_rumah');
			Route::post('/save_process_upload', 'Sqii\RegDataStokC@save_process_upload');
			Route::post('/save_process_upload_tp_rmh', 'Sqii\RegDataStokC@save_process_upload_tp_rmh');
		});

		Route::group(['prefix' => 'sqii/generate_qr_code_c'], function() {
			Route::post('/save', 'Sqii\GenQrcodeC@save');
		});

		Route::group(['prefix' => 'sqii/input_data_lantai_c'], function() {
			Route::post('/save', 'Sqii\InputLantaiC@save');
		});

		Route::group(['prefix' => 'sqii/input_denah_tipe_lantai_c'], function() {
			Route::post('/save', 'Sqii\InputDenahLantaiC@save');
		});

		Route::group(['prefix' => 'sqii/input_data_jns_pekerjaan_c'], function() {
			Route::post('/save', 'Sqii\InputJenisPekerjanC@save');
		});

		Route::group(['prefix' => 'sqii/input_data_item_defect_c'], function() {
			Route::post('/save', 'Sqii\InputItemDefectC@save');
		});

		Route::group(['prefix' => 'sqii/input_tbl_catatan_defect_c'], function() {
			Route::post('/save', 'Sqii\InputCatatDefectC@save');
		});

		Route::group(['prefix' => 'sqii/input_tbl_tahap_pekerjaan_c'], function() {
			Route::post('/save', 'Sqii\InputTahapPekerjaanC@save');
			Route::post('/upd_fg', 'Sqii\InputTahapPekerjaanC@upd_fg');
		});

		Route::group(['prefix' => 'sqii/input_dt_item_p15_p35_c'], function() {
			Route::post('/save', 'Sqii\InputItemPekerjaanC@save');
			Route::post('/update_bobot', 'Sqii\InputItemPekerjaanC@update_bobot');
		});

		Route::group(['prefix' => 'sqii/input_dt_mst_item_p15_p35_c'], function() {
			Route::post('/save', 'Sqii\InputMasterItemPekerjaanC@save');
		});

		Route::group(['prefix' => 'sqii/register_tahap_bangun_c'], function() {
			Route::post('/save', 'Sqii\RegTahapBangunC@save');
		});

		Route::group(['prefix' => 'sqii/input_tbl_mst_tahap_pekerjaan_c'], function() {
			Route::post('/save', 'Sqii\InputMasterTahapPekerjaanC@save');
			Route::post('/upd_fg', 'Sqii\InputMasterTahapPekerjaanC@upd_fg');
		});

		Route::group(['prefix' => 'sqii/input_rencana_progress_mingguan_c'], function() {
			Route::post('/save', 'Sqii\InputRencanaProgMingguC@save');
			Route::post('/update_bobot', 'Sqii\InputRencanaProgMingguC@update_bobot');
		});

		/* ---------------------------------------- Administrasi ---------------------------------------- */

		Route::group(['prefix' => 'sqii/set_jabatan_struktur_org_c'], function() {
			Route::post('/save', 'Sqii\EntryJabatanPetugasC@save');
			Route::post('/save_bawahan', 'Sqii\EntryJabatanPetugasC@save_bawahan');
		});

		Route::group(['prefix' => 'sqii/alokasi_petugas_blok_nomor_c'], function() {
			Route::post('/save', 'Sqii\AlokasiPetugasC@save');
		});

		Route::group(['prefix' => 'sqii/rotasi_blok_no_c'], function() {
			Route::post('/save', 'Sqii\RotasiBlokNoC@save');
			Route::post('/save_rotasi', 'Sqii\RotasiBlokNoC@save_rotasi');
		});

		Route::group(['prefix' => 'sqii/rotasi_atasan_c'], function() {
			Route::post('/save', 'Sqii\RotasiAtasanC@save');
			Route::post('/save_rotasi', 'Sqii\RotasiAtasanC@save_rotasi');
		});

		/* ---------------------------------------- Utility ---------------------------------------- */

		Route::group(['prefix' => 'sqii/user_management_c'], function() {
			Route::post('/save', 'Sqii\ManagementPetugasC@save');
			Route::post('/save_bawahan', 'Sqii\ManagementPetugasC@save_bawahan');
		});		

	});

	Route::group(['middleware' => ['portalAuth.delete']], function() {
		Route::group(['prefix' => 'sqii/register_data_kawasan_c'], function() {
			Route::post('/delete_dt', 'Sqii\RegKawasanC@delete_dt');
        });

        Route::group(['prefix' => 'sqii/register_data_tp_bangunan_c'], function() {
			Route::post('/delete_denah', 'Sqii\RegTipeBangunanC@delete_denah');
        });
        
        Route::group(['prefix' => 'sqii/register_data_stok_c'], function() {
			Route::post('/delete_dt', 'Sqii\RegDataStokC@delete_dt');
        });

        Route::group(['prefix' => 'sqii/generate_qr_code_c'], function() {
			Route::post('/delete_dt', 'Sqii\GenQrcodeC@delete_dt');
        });

        Route::group(['prefix' => 'sqii/input_data_lantai_c'], function() {
			Route::post('/delete_dt', 'Sqii\InputLantaiC@delete_dt');
        });

        Route::group(['prefix' => 'sqii/input_denah_tipe_lantai_c'], function() {
			Route::post('/delete_dt', 'Sqii\InputDenahLantaiC@delete_dt');
			Route::post('/delete_denah', 'Sqii\InputDenahLantaiC@delete_denah');
        });

        Route::group(['prefix' => 'sqii/input_data_jns_pekerjaan_c'], function() {
			Route::post('/delete_dt', 'Sqii\InputJenisPekerjanC@delete_dt');
        });

        Route::group(['prefix' => 'sqii/input_tbl_tahap_pekerjaan_c'], function() {
			Route::post('/delete_dt', 'Sqii\InputTahapPekerjaanC@delete_dt');
        });

        Route::group(['prefix' => 'sqii/input_data_item_defect_c'], function() {
			Route::post('/delete_dt', 'Sqii\InputItemDefectC@delete_dt');
        });

        Route::group(['prefix' => 'sqii/input_tbl_catatan_defect_c'], function() {
			Route::post('/delete_dt', 'Sqii\InputCatatDefectC@delete_dt');
        });

		Route::group(['prefix' => 'sqii/input_dt_item_p15_p35_c'], function() {
			Route::post('/delete_item_pekerjaan', 'Sqii\InputItemPekerjaanC@delete_item_pekerjaan');
		});

		Route::group(['prefix' => 'sqii/input_dt_mst_item_p15_p35_c'], function() {
			Route::post('/delete_item_pekerjaan', 'Sqii\InputMasterItemPekerjaanC@delete_item_pekerjaan');
		});

		Route::group(['prefix' => 'sqii/register_tahap_bangun_c'], function() {
			Route::post('/delete_dt', 'Sqii\RegTahapBangunC@delete_dt');
        });

        Route::group(['prefix' => 'sqii/input_tbl_mst_tahap_pekerjaan_c'], function() {
			Route::post('/delete_dt', 'Sqii\InputMasterTahapPekerjaanC@delete_dt');
        });

		Route::group(['prefix' => 'sqii/input_rencana_progress_mingguan_c'], function() {
			Route::post('/delete_item_pekerjaan', 'Sqii\InputRencanaProgMingguC@delete_item_pekerjaan');
		});
        /* ---------------------------------------- Administrasi ---------------------------------------- */

        Route::group(['prefix' => 'sqii/set_jabatan_struktur_org_c'], function() {
			Route::post('/delete_dt', 'Sqii\EntryJabatanPetugasC@delete_dt');
			Route::post('/delete_bawahan', 'Sqii\EntryJabatanPetugasC@delete_bawahan');
		});

		Route::group(['prefix' => 'sqii/alokasi_petugas_blok_nomor_c'], function() {
			Route::post('/delete_dt', 'Sqii\AlokasiPetugasC@delete_dt');
		});

		Route::group(['prefix' => 'sqii/rotasi_blok_no_c'], function() {
			Route::post('/update_dt', 'Sqii\RotasiBlokNoC@update_dt');
		});

		Route::group(['prefix' => 'sqii/rotasi_Atasan_c'], function() {
			Route::post('/update_dt', 'Sqii\RotasiAtasanC@update_dt');
		});

        /* ---------------------------------------- Utility ---------------------------------------- */

        Route::group(['prefix' => 'sqii/user_management_c'], function() {
			Route::post('/delete_dt', 'Sqii\ManagementPetugasC@delete_dt');
		});

	});
});