<table id="browse-data-table" class="table table-bordered table-condensed ">
    <thead>
        <tr>
            <th>{{kode}}</th>
            <th>{{barcode}}</th>
            <th>{{produk}}</th>
            <th>{{jenis}}</th>
            <th width="1">{{stok_aktual}}</th>
            <th width="1">{{stok}}</th>
        </tr>
    </thead>
</table>

<script>
    var browseDataTable;
    $(function() {
        browseDataTable = $('#browse-data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '<?= $this->url_generator->current_url() ?>?load=1&tanggal_mutasi=<?= $tanggal_mutasi ?>',
            columns: [
                {data: 'kode_produk', name: 'view_produk.kode_produk'},
                {data: 'barcode', name: 'view_produk.barcode'},
                {data: 'produk', name: 'view_produk.produk'},
                {data: 'jenis_produk', name: 'view_produk.jenis_produk'},
                {data: 'stok_akhir', searchable: false, class: 'nowrap'},
                {data: 'stok', searchable: false, class: 'nowrap'}
            ],
            select: true,
            rowCallback: function(row, data) {
                $(row).dblclick(function() {
                    if (typeof(browse_produk_on_dblclick_callback) == 'function') {
                        browse_produk_on_dblclick_callback(data);
                    }
                    bootbox.hideAll();
                });
            }
        });
    });
</script>