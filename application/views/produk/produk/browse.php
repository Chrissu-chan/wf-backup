<div class="form-group">
    <div class="form-inline">
        <input type="text" id="keyword" class="form-control" style="width:90%;">
        <input type="button" id="btn-filter" class="btn btn-primary" value="Search">
    </div>
    <div class="form-inline m-t-10">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="kode">
                <label for="kode" class="m-r-20">Kode</label>
            <input type="checkbox" class="form-check-input" id="barcode">
                <label for="barcode" class="m-r-20">Barcode</label>
            <input type="checkbox" class="form-check-input" id="produk">
                <label for="produk" class="m-r-20">Produk</label>
            <input type="checkbox" class="form-check-input" id="rak">
                <label for="rak" class="m-r-20">Rak</label>
            <input type="checkbox" class="form-check-input" id="jenis">
                <label for="jenis" class="m-r-20">Jenis</label>
            <input type="checkbox" class="form-check-input" id="kategori">
                <label for="kategori" class="m-r-20">Kategori</label>
            <input type="checkbox" class="form-check-input" id="kandungan">
                <label for="kandungan" class="m-r-20">Kandungan</label>
        <div>
    </div>
</div>
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
            searching: false,
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

        $('#btn-filter').click(function() {
            array_filter = [];
            if ($('#kode').is(':checked')) {
                array_filter.push('produk.kode');
            }
            if ($('#barcode').is(':checked')) {
                array_filter.push('produk.barcode');
            }
            if ($('#produk').is(':checked')) {
                array_filter.push('produk.produk');
            }
            if ($('#rak').is(':checked')) {
                array_filter.push('rak_gudang.rak');
            }
            if ($('#jenis').is(':checked')) {
                array_filter.push('produk.jenis');
            }
            if ($('#kategori').is(':checked')) {
                array_filter.push('produk.kategori');
            }
            if ($('#kandungan').is(':checked')) {
                array_filter.push('produk.kandungan');
            }
            filters = array_filter.join("|");
            browseDataTable.ajax.url('<?= $this->url_generator->current_url() ?>?load=1&keyword='+$('#keyword').val()+'&filters='+filters).load();
        });
    });
</script>