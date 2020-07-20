-- ----------------------------
-- View structure for __view_hpp
-- ----------------------------
DROP VIEW IF EXISTS `__view_hpp`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `__view_hpp` AS select `barang_stok`.`id_gudang` AS `id_gudang`,`barang_stok`.`id_barang` AS `id_barang`,`barang_stok`.`id_satuan` AS `id_satuan`,`barang_stok`.`index_awal` AS `stok_index_awal`,`barang_stok`.`index_akhir` AS `stok_index_akhir`,`barang_stok_mutasi`.`index_awal` AS `mutasi_index_awal`,`barang_stok_mutasi`.`index_akhir` AS `mutasi_index_akhir`,`barang_stok_mutasi`.`nilai` AS `nilai` from (`barang_stok` join `barang_stok_mutasi` on(((`barang_stok_mutasi`.`id_gudang` = `barang_stok`.`id_gudang`) and (`barang_stok_mutasi`.`id_barang` = `barang_stok`.`id_barang`) and (`barang_stok_mutasi`.`index_akhir` >= `barang_stok`.`index_awal`) and (`barang_stok_mutasi`.`tipe_mutasi` = 'masuk'))));

-- ----------------------------
-- View structure for _view_hpp
-- ----------------------------
DROP VIEW IF EXISTS `_view_hpp`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `_view_hpp` AS select `__view_hpp`.`id_gudang` AS `id_gudang`,`__view_hpp`.`id_barang` AS `id_barang`,`__view_hpp`.`id_satuan` AS `id_satuan`,`__view_hpp`.`nilai` AS `nilai`,(case when (`__view_hpp`.`stok_index_awal` >= `__view_hpp`.`mutasi_index_awal`) then ((`__view_hpp`.`mutasi_index_akhir` - `__view_hpp`.`stok_index_awal`) + 1) else ((`__view_hpp`.`mutasi_index_akhir` - `__view_hpp`.`mutasi_index_awal`) + 1) end) AS `jumlah` from `__view_hpp`;

-- ----------------------------
-- View structure for view_hpp
-- ----------------------------
DROP VIEW IF EXISTS `view_hpp`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `view_hpp` AS select `_view_hpp`.`id_gudang` AS `id_gudang`,`_view_hpp`.`id_barang` AS `id_barang`,`_view_hpp`.`id_satuan` AS `id_satuan`,min(`_view_hpp`.`nilai`) AS `harga_min`,max(`_view_hpp`.`nilai`) AS `harga_max`,sum(`_view_hpp`.`jumlah`) AS `jumlah`,sum((`_view_hpp`.`nilai` * `_view_hpp`.`jumlah`)) AS `total`,(sum((`_view_hpp`.`nilai` * `_view_hpp`.`jumlah`)) / sum(`_view_hpp`.`jumlah`)) AS `hpp` from `_view_hpp` group by `_view_hpp`.`id_barang`,`_view_hpp`.`id_gudang`;

-- ----------------------------
-- View structure for _view_hpp_keluar
-- ----------------------------
DROP VIEW IF EXISTS `_view_hpp_keluar`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `_view_hpp_keluar` AS (select `barang_stok_mutasi`.`id` AS `id`,`barang_stok_mutasi`.`tanggal_mutasi` AS `tanggal_mutasi`,`barang_stok_mutasi`.`tipe_mutasi` AS `tipe_mutasi`,`barang_stok_mutasi`.`jenis_mutasi` AS `jenis_mutasi`,`barang_stok_mutasi`.`id_ref` AS `id_ref`,`barang_stok_mutasi`.`id_gudang` AS `id_gudang`,`barang_stok_mutasi`.`id_rak_gudang` AS `id_rak_gudang`,`barang_stok_mutasi`.`id_barang` AS `id_barang`,`barang_stok_mutasi`.`id_satuan` AS `id_satuan`,`barang_stok_mutasi`.`index_awal` AS `index_awal`,`barang_stok_mutasi`.`index_akhir` AS `index_akhir`,`barang_stok_mutasi`.`jumlah` AS `jumlah`,`barang_stok_mutasi`.`nilai` AS `nilai`,`barang_stok_mutasi`.`total` AS `total`,`barang_stok_mutasi`.`expired` AS `expired`,`barang_stok_mutasi`.`created_by` AS `created_by`,`barang_stok_mutasi`.`created_at` AS `created_at`,`barang_stok_mutasi`.`updated_by` AS `updated_by`,`barang_stok_mutasi`.`updated_at` AS `updated_at` from `barang_stok_mutasi` where (`barang_stok_mutasi`.`tipe_mutasi` = 'masuk'));

-- ----------------------------
-- View structure for view_hpp_keluar
-- ----------------------------
DROP VIEW IF EXISTS `view_hpp_keluar`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `view_hpp_keluar` AS select `keluar`.`id_barang` AS `id_barang`,`keluar`.`id_gudang` AS `id_gudang`,`keluar`.`id_ref` AS `id_ref`,`keluar`.`jenis_mutasi` AS `jenis_mutasi`,`keluar`.`index_awal` AS `index_awal`,`keluar`.`index_akhir` AS `index_akhir`,`masuk`.`index_awal` AS `masuk_index_awal`,`masuk`.`index_akhir` AS `masuk_index_akhir`,`masuk`.`nilai` AS `nilai`,(case when ((`keluar`.`index_awal` > `masuk`.`index_awal`) and (`keluar`.`index_akhir` < `masuk`.`index_akhir`)) then ((`keluar`.`index_akhir` - `keluar`.`index_awal`) + 1) when ((`keluar`.`index_awal` < `masuk`.`index_akhir`) and (`keluar`.`index_akhir` < `masuk`.`index_akhir`)) then ((`keluar`.`index_akhir` - `masuk`.`index_awal`) + 1) when ((`keluar`.`index_awal` < `masuk`.`index_akhir`) and (`keluar`.`index_akhir` > `masuk`.`index_akhir`)) then (`masuk`.`index_akhir` - `keluar`.`index_awal`) when (`keluar`.`index_akhir` > `masuk`.`index_akhir`) then ((`masuk`.`index_akhir` - `keluar`.`index_awal`) + 1) else ((`keluar`.`index_akhir` - `masuk`.`index_awal`) + 1) end) AS `jumlah` from (`barang_stok_mutasi` `keluar` join `_view_hpp_keluar` `masuk` on(((`masuk`.`id_barang` = `keluar`.`id_barang`) and (`masuk`.`id_gudang` = `keluar`.`id_gudang`) and (((`masuk`.`index_awal` <= `keluar`.`index_awal`) and (`masuk`.`index_akhir` >= `keluar`.`index_akhir`)) or ((`masuk`.`index_awal` >= `keluar`.`index_awal`) and (`masuk`.`index_akhir` <= `keluar`.`index_akhir`)) or ((`masuk`.`index_awal` <= `keluar`.`index_akhir`) and (`masuk`.`index_akhir` >= `keluar`.`index_akhir`)) or ((`masuk`.`index_akhir` >= `keluar`.`index_awal`) and (`masuk`.`index_akhir` <= `keluar`.`index_akhir`)))))) where (`keluar`.`tipe_mutasi` = 'keluar') order by `keluar`.`index_awal`,`masuk`.`index_awal`;

-- ----------------------------
-- View structure for view_barang_fungsi_obat
-- ----------------------------
DROP VIEW IF EXISTS `view_barang_fungsi_obat`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `view_barang_fungsi_obat` AS (select `barang_fungsi_obat`.`id_barang` AS `id_barang`,group_concat(`fungsi_obat`.`id` separator ', ') AS `id_fungsi_obat`,group_concat(`fungsi_obat`.`fungsi_obat` separator ', ') AS `fungsi_obat` from (`barang_fungsi_obat` join `fungsi_obat` on((`fungsi_obat`.`id` = `barang_fungsi_obat`.`id_fungsi_obat`))) group by `barang_fungsi_obat`.`id_barang`);

-- ----------------------------
-- View structure for view_barang_kategori_obat
-- ----------------------------
DROP VIEW IF EXISTS `view_barang_kategori_obat`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `view_barang_kategori_obat` AS (select `barang_kategori_obat`.`id_barang` AS `id_barang`,group_concat(`kategori_obat`.`id` separator ', ') AS `id_kategori_obat`,group_concat(`kategori_obat`.`kategori_obat` separator ', ') AS `kategori_obat` from (`barang_kategori_obat` join `kategori_obat` on((`kategori_obat`.`id` = `barang_kategori_obat`.`id_kategori_obat`))) group by `barang_kategori_obat`.`id_barang`);

-- ----------------------------
-- View structure for view_kategori
-- ----------------------------
DROP VIEW IF EXISTS `view_kategori`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `view_kategori` AS select `kategori_jasa`.`id` AS `id`,`kategori_jasa`.`kategori_jasa` AS `kategori` from `kategori_jasa` union select `kategori_obat`.`id` AS `id`,`kategori_obat`.`kategori_obat` AS `kategori` from `kategori_obat`;

-- ----------------------------
-- View structure for view_produk
-- ----------------------------
DROP VIEW IF EXISTS `view_produk`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `view_produk` AS select `produk`.`id` AS `id_produk`,`produk`.`kode` AS `kode_produk`,`produk`.`barcode` AS `barcode`,`produk`.`produk` AS `produk`,`produk`.`jenis` AS `jenis_produk`,`produk`.`id_ref` AS `id_ref`,`obat`.`id_jenis_obat` AS `jenis`,`barang_kategori_obat`.`id_kategori_obat` AS `kategori`,`barang_kategori_obat`.`kategori_obat` AS `kategori_desc`,`produk_harga`.`id_cabang` AS `id_cabang`,`produk_harga`.`id_satuan` AS `id_satuan`,`satuan`.`satuan` AS `satuan`,`obat`.`kandungan_obat` AS `kandungan`,`produk_harga`.`utama` AS `utama`,`produk_harga`.`jumlah` AS `jumlah`,`produk_harga`.`harga` AS `harga` from ((((((`produk` join `produk_harga` on((`produk_harga`.`id_produk` = `produk`.`id`))) join `barang` on(((`barang`.`id` = `produk`.`id_ref`) and (`produk`.`jenis` = 'barang')))) join `satuan` on((`satuan`.`id` = `produk_harga`.`id_satuan`))) join `obat` on((`obat`.`id_barang` = `barang`.`id`))) left join `view_barang_kategori_obat` `barang_kategori_obat` on((`barang_kategori_obat`.`id_barang` = `barang`.`id`))) left join `jenis_obat` on((`jenis_obat`.`id` = `obat`.`id_jenis_obat`))) union select `produk`.`id` AS `id_produk`,`produk`.`kode` AS `kode_produk`,`produk`.`barcode` AS `barcode`,`produk`.`produk` AS `produk`,`produk`.`jenis` AS `jenis_produk`,`produk`.`id_ref` AS `id_ref`,'' AS `jenis`,`jasa`.`id_kategori_jasa` AS `kategori`,`kategori_jasa`.`kategori_jasa` AS `kategori_desc`,`produk_harga`.`id_cabang` AS `id_cabang`,`produk_harga`.`id_satuan` AS `id_satuan`,'' AS `satuan`,'' AS `kandungan`,`produk_harga`.`utama` AS `utama`,`produk_harga`.`jumlah` AS `jumlah`,`produk_harga`.`harga` AS `harga` from (((`produk` join `produk_harga` on((`produk_harga`.`id_produk` = `produk`.`id`))) join `jasa` on(((`jasa`.`id` = `produk`.`id_ref`) and (`produk`.`jenis` = 'jasa')))) join `kategori_jasa` on((`kategori_jasa`.`id` = `jasa`.`id_kategori_jasa`))) union select `produk`.`id` AS `id_produk`,`produk`.`kode` AS `kode_produk`,`produk`.`barcode` AS `barcode`,`produk`.`produk` AS `produk`,`produk`.`jenis` AS `jenis_produk`,`produk`.`id_ref` AS `id_ref`,'' AS `jenis`,'' AS `kategori`,'' AS `kategori_desc`,`produk_harga`.`id_cabang` AS `id_cabang`,`produk_harga`.`id_satuan` AS `id_satuan`,'' AS `satuan`,'' AS `kandungan`,`produk_harga`.`jumlah` AS `jumlah`,`produk_harga`.`utama` AS `utama`,`produk_harga`.`harga` AS `harga` from (`produk` join `produk_harga` on((`produk_harga`.`id_produk` = `produk`.`id`))) where (`produk`.`jenis` = 'paket');

-- ----------------------------
-- View structure for view_produk_browse
-- ----------------------------
DROP VIEW IF EXISTS `view_produk_browse`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `view_produk_browse` AS select `produk`.`id` AS `id`,`produk`.`kode` AS `kode`,`produk`.`barcode` AS `barcode`,`produk`.`produk` AS `produk`,`produk`.`jenis` AS `jenis_produk`,`produk`.`id_ref` AS `id_ref`,`jenis_obat`.`jenis_obat` AS `jenis`,`barang_kategori_obat`.`kategori_obat` AS `kategori`,`satuan`.`satuan` AS `satuan`,`obat`.`kandungan_obat` AS `kandungan` from (((((`produk` join `barang` on(((`barang`.`id` = `produk`.`id_ref`) and (`produk`.`jenis` = 'barang')))) join `satuan` on((`satuan`.`id` = `barang`.`id_satuan`))) join `obat` on((`obat`.`id_barang` = `barang`.`id`))) left join `view_barang_kategori_obat` `barang_kategori_obat` on((`barang_kategori_obat`.`id_barang` = `barang`.`id`))) left join `jenis_obat` on((`jenis_obat`.`id` = `obat`.`id_jenis_obat`))) union select `produk`.`id` AS `id_produk`,`produk`.`kode` AS `kode_produk`,`produk`.`barcode` AS `barcode`,`produk`.`produk` AS `produk`,`produk`.`jenis` AS `jenis_produk`,`produk`.`id_ref` AS `id_ref`,'' AS `jenis`,`kategori_jasa`.`kategori_jasa` AS `kategori`,'' AS `satuan`,'' AS `kandungan` from ((`produk` join `jasa` on(((`jasa`.`id` = `produk`.`id_ref`) and (`produk`.`jenis` = 'jasa')))) join `kategori_jasa` on((`kategori_jasa`.`id` = `jasa`.`id_kategori_jasa`))) union select `produk`.`id` AS `id_produk`,`produk`.`kode` AS `kode_produk`,`produk`.`barcode` AS `barcode`,`produk`.`produk` AS `produk`,`produk`.`jenis` AS `jenis_produk`,`produk`.`id_ref` AS `id_ref`,'' AS `jenis`,'' AS `kategori`,'' AS `satuan`,'' AS `kandungan` from `produk` where (`produk`.`jenis` = 'paket');

-- ----------------------------
-- View structure for view_unserviced
-- ----------------------------
DROP VIEW IF EXISTS `view_unserviced`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `view_unserviced` AS select `barang`.`id` AS `id_barang`,`barang`.`nama` AS `nama_barang` from `barang` union select `unserviced_detail`.`id_barang` AS `id_barang`,`unserviced_detail`.`nama_barang` AS `nama_barang` from `unserviced_detail`;