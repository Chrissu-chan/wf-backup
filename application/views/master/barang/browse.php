<table id="browse-data-table" class="table table-bordered table-condensed ">
    <thead>
        <tr>
            <th>{{kode}}</th>
            <th>{{barcode}}</th>
            <th>{{nama}}</th>
            <th>{{satuan}}</th>
            <th>{{stok}}</th>
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
            ajax: '<?= $this->url_generator->current_url() ?>?load=1',
            columns: [
                {data: 'kode', name: 'barang.kode'},
                {data: 'barcode', name: 'barang.barcode'},
                {data: 'nama', name: 'barang.nama'},
                {data: 'satuan', name: 'satuan.satuan'},
                {data: 'stok', name: 'barang_stok.jumlah'}
            ],
            select: true,
            rowCallback: function(row, data) {
                $(row).dblclick(function() {
                    if (typeof(browse_barang_on_dblclick_callback) == 'function') {
                        browse_barang_on_dblclick_callback(data);
                    }
                    bootbox.hideAll();
                });
            }
        });
    });
</script>