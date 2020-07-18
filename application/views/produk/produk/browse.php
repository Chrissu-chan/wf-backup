<table id="browse-data-table" class="table table-bordered table-condensed ">
    <thead>
        <tr>
            <th>{{kode}}</th>
            <th>{{barcode}}</th>
            <th>{{produk}}</th>
            <th>{{rak}}</th>
            <th>{{jenis}}</th>
            <th>{{kategori}}</th>
            <th>{{kandungan}}</th>
            <th width="1">{{stok}}</th>
            <th width="1">{{harga}}</th>
        </tr>
    </thead>
</table>

<script>
    var browseDataTable;
    $(function() {
        browseDataTable = $('#browse-data-table').DataTable({
            processing: true,
            serverSide: true,
	        searchDelay: 1500,
            ajax: '<?= $this->url_generator->current_url() ?>?load=1&tanggal_mutasi=<?= $tanggal_mutasi ?>',
            columns: [
                {data: 'kode', name: 'produk.kode'},
                {data: 'barcode', name: 'produk.barcode'},
                {data: 'produk', name: 'produk.produk'},
                {data: 'rak', name: 'rak_gudang.rak'},
                {data: 'jenis', name: 'produk.jenis'},
                {data: 'kategori', name: 'produk.kategori'},
                {data: 'kandungan', name: 'produk.kandungan'},
                {data: 'stok', searchable: false, class: 'text-center nowrap'},
                {data: 'harga', searchable: false, class: 'text-right nowrap'}
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