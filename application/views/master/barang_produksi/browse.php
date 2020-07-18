<table id="browse-data-table" class="table table-bordered table-condensed ">
    <thead>
        <tr>
            <th>{{barang}}</th>
            <th>{{nama}}</th>
            <th>{{satuan}}</th>
        </tr>
    </thead>
</table>

<script>
    var browseDataTable;
    $(function() {
        browseDataTable = $('#browse-data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '<?= $this->url_generator->current_url() ?>?load=1',
            columns: [
                {data: 'id_barang', name: 'barang_produksi.id_barang', render: function(data, type, row) {
                    return row.kode_barang + ' - ' + row.nama_barang;
                }},
                {data: 'nama', name: 'barang_produksi.nama'},
                {data: 'satuan', name: 'satuan.satuan'}
            ],
            select: true,
            rowCallback: function(row, data) {
                $(row).dblclick(function() {
                    if (typeof(browse_barang_produksi_on_dblclick_callback) == 'function') {
                        browse_barang_produksi_on_dblclick_callback(data);
                    }
                    bootbox.hideAll();
                });
            }
        });
    });
</script>