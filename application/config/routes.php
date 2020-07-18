<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'dashboard';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['logout'] = 'login/logout';

$route['developers/modules/features/(:num)'] = 'developers/module_features/index/$1';
$route['developers/modules/features/(:num)/view/(:num)'] = 'developers/module_features/view/$1/$2';
$route['developers/modules/features/(:num)/create'] = 'developers/module_features/create/$1';
$route['developers/modules/features/(:num)/store'] = 'developers/module_features/store/$1';
$route['developers/modules/features/(:num)/edit/(:num)'] = 'developers/module_features/edit/$1/$2';
$route['developers/modules/features/(:num)/update/(:num)'] = 'developers/module_features/update/$1/$2';
$route['developers/modules/features/(:num)/delete/(:num)'] = 'developers/module_features/delete/$1/$2';

$route['developers/modules/features/(:num)/actions/(:num)'] = 'developers/module_feature_actions/index/$1/$2';
$route['developers/modules/features/(:num)/actions/(:num)/view/(:num)'] = 'developers/module_feature_actions/view/$1/$2/$3';
$route['developers/modules/features/(:num)/actions/(:num)/create'] = 'developers/module_feature_actions/create/$1/$2';
$route['developers/modules/features/(:num)/actions/(:num)/store'] = 'developers/module_feature_actions/store/$1/$2';
$route['developers/modules/features/(:num)/actions/(:num)/edit/(:num)'] = 'developers/module_feature_actions/edit/$1/$2/$3';
$route['developers/modules/features/(:num)/actions/(:num)/update/(:num)'] = 'developers/module_feature_actions/update/$1/$2/$3';
$route['developers/modules/features/(:num)/actions/(:num)/delete/(:num)'] = 'developers/module_feature_actions/delete/$1/$2/$3';

$route['developers/applications/modules/(:num)'] = 'developers/application_modules/index/$1';
$route['developers/applications/modules/(:num)/view/(:num)'] = 'developers/application_modules/view/$1/$2';
$route['developers/applications/modules/(:num)/create'] = 'developers/application_modules/create/$1';
$route['developers/applications/modules/(:num)/store'] = 'developers/application_modules/store/$1';
$route['developers/applications/modules/(:num)/edit/(:num)'] = 'developers/application_modules/edit/$1/$2';
$route['developers/applications/modules/(:num)/update/(:num)'] = 'developers/application_modules/update/$1/$2';
$route['developers/applications/modules/(:num)/delete/(:num)'] = 'developers/application_modules/delete/$1/$2';

$route['developers/applications/menus/(:num)'] = 'developers/application_menus/index/$1';
$route['developers/applications/menus/(:num)/view/(:num)'] = 'developers/application_menus/view/$1/$2';
$route['developers/applications/menus/(:num)/create'] = 'developers/application_menus/create/$1';
$route['developers/applications/menus/(:num)/store'] = 'developers/application_menus/store/$1';
$route['developers/applications/menus/(:num)/edit/(:num)'] = 'developers/application_menus/edit/$1/$2';
$route['developers/applications/menus/(:num)/update/(:num)'] = 'developers/application_menus/update/$1/$2';
$route['developers/applications/menus/(:num)/update_sequence'] = 'developers/application_menus/update_sequence/$1';
$route['developers/applications/menus/(:num)/delete/(:num)'] = 'developers/application_menus/delete/$1/$2';

$route['developers/applications/config/(:num)'] = 'developers/application_config/index/$1';
$route['developers/applications/config/(:num)/save'] = 'developers/application_config/save/$1';

$route['member/jenis_member/masa_aktif/(:num)'] = 'member/jenis_member_masa_aktif/index/$1';
$route['member/jenis_member/masa_aktif/(:num)/create'] = 'member/jenis_member_masa_aktif/create/$1';
$route['member/jenis_member/masa_aktif/(:num)/store'] = 'member/jenis_member_masa_aktif/store/$1';
$route['member/jenis_member/masa_aktif/(:num)/view/(:num)'] = 'member/jenis_member_masa_aktif/view/$1/$2';
$route['member/jenis_member/masa_aktif/(:num)/edit/(:num)'] = 'member/jenis_member_masa_aktif/edit/$1/$2';
$route['member/jenis_member/masa_aktif/(:num)/update/(:num)'] = 'member/jenis_member_masa_aktif/update/$1/$2';
$route['member/jenis_member/masa_aktif/(:num)/delete/(:num)'] = 'member/jenis_member_masa_aktif/delete/$1/$2';

$route['master/satuan/konversi/(:num)'] = 'master/konversi_satuan/index/$1';
$route['master/satuan/konversi/(:num)/create'] = 'master/konversi_satuan/create/$1';
$route['master/satuan/konversi/(:num)/store'] = 'master/konversi_satuan/store/$1';
$route['master/satuan/konversi/(:num)/view/(:num)'] = 'master/konversi_satuan/view/$1/$2';
$route['master/satuan/konversi/(:num)/edit/(:num)'] = 'master/konversi_satuan/edit/$1/$2';
$route['master/satuan/konversi/(:num)/update/(:num)'] = 'master/konversi_satuan/update/$1/$2';
$route['master/satuan/konversi/(:num)/delete/(:num)'] = 'master/konversi_satuan/delete/$1/$2';

$config['routes'] = array(
    'dashboard' => 'dashboard',
    'users.roles.permissions_save' => 'users/roles/permissions_save/{id}',
    'aktif_cabang.set' => 'aktif_cabang/set/{id}',
    'master.satuan_barang.konversi' => 'master/satuan_barang/konversi/{id}',
    'master.shift.edit' => 'master/shift/edit/{id}',
    'master.shift.update' => 'master/shift/update/{id}',
    'master.shift.delete' => 'master/shift/delete/{id}',
    'master.jasa.edit' => 'master/jasa/edit/{id}',
    'master.jasa.update' => 'master/jasa/update/{id}',
    'master.jasa.delete' => 'master/jasa/delete/{id}',
    'master.barang_produksi.edit' => 'master/barang_produksi/edit/{id}',
    'master.barang_produksi.update' => 'master/barang_produksi/update/{id}',
    'master.barang_produksi.delete' => 'master/barang_produksi/delete/{id}',
    'produk.produk.edit' => 'produk/produk/edit/{id}',
    'produk.produk.update' => 'produk/produk/update/{id}',
    'produk.produk.delete' => 'produk/produk/delete/{id}',
    'produk.pengaturan_harga.edit' => 'produk/pengaturan_harga/edit/{id}',
    'produk.pengaturan_harga.update' => 'produk/pengaturan_harga/update/{id}',
    'produk.pengaturan_harga.delete' => 'produk/pengaturan_harga/delete/{id}',
    'produk.perubahan_harga.edit' => 'produk/perubahan_harga/edit/{id}',
    'produk.perubahan_harga.update' => 'produk/perubahan_harga/update/{id}',
    'produk.perubahan_harga.delete' => 'produk/perubahan_harga/delete/{id}',
    'produk.diskon.edit' => 'produk/diskon/edit/{id}',
    'produk.diskon.update' => 'produk/diskon/update/{id}',
    'produk.diskon.delete' => 'produk/diskon/delete/{id}',
    'produksi.produksi.edit' => 'produksi/produksi/edit/{id}',
    'produksi.produksi.update' => 'produksi/produksi/update/{id}',
    'produksi.produksi.delete' => 'produksi/produksi/delete/{id}',
    'rekam_medis.pasien.export' => 'rekam_medis/pasien/export',
    'transaksi.utang.bayar_store' => 'transaksi/utang/bayar_store',
    'transaksi.utang.download_file' => 'transaksi/utang/download_file/{id}',
    'transaksi.piutang.download_file' => 'transaksi/piutang/download_file/{id}',
    'transaksi.mutasi_kasir.download_file' => 'transaksi/mutasi_kasir/download_file/{id}',
    'transaksi.pembayaran_utang.download_file' => 'transaksi/pembayaran_utang/download_file/{id}',
    'transaksi.pembayaran_piutang.download_file' => 'transaksi/pembayaran_piutang/download_file/{id}',
    'transaksi.pembelian.edit' => 'transaksi/pembelian/edit/{id}',
    'transaksi.pembelian.update' => 'transaksi/pembelian/update/{id}',
    'transaksi.pembelian.delete' => 'transaksi/pembelian/delete/{id}',
    'transaksi.penjualan.edit' => 'transaksi/penjualan/edit/{id}',
    'transaksi.penjualan.update' => 'transaksi/penjualan/update/{id}',
    'transaksi.penjualan.delete' => 'transaksi/penjualan/delete/{id}',
	'transaksi.penjualan.nota' => 'transaksi/penjualan/nota/{id}',
    'transaksi.shift_aktif.close_save' => 'transaksi/shift_aktif/close_save/{id}',
    'master.jasa.store' => 'master/jasa/store',
    'inventory.stock_opname.finish' => 'inventory/stock_opname/finish/{?id}',
	'transaksi.monitoring_shift.detail' => 'transaksi/monitoring_shift/detail/{id}',
	'inventory.stok.export' => 'inventory/stok/export/{?id}',
	'transaksi.unserviced.edit' => 'transaksi/unserviced/edit/{id}',
	'transaksi.unserviced.update' => 'transaksi/unserviced/update/{id}',
	'transaksi.unserviced.delete' => 'transaksi/unserviced/delete/{id}',
);