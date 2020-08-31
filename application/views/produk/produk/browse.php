<style>
    label{
        margin-right:30px;
    }
    .dataTables_filter{
        display:none!important;
    }
    .search_filter{
        padding:5px 5px;
    }
</style>
<div class="form-inline">
        <input type="text" id="filter-search" class="form-control" style="width:90%;">
        <input type="button" id="btn-filter" style="margin-top:5px;" value="Search">
</div>
    <br>
    <div class="search_filter">
        <span>Filter :</span>
        <label for="kode">
            <input type="checkbox" name="cek" class="filter" id="filter-kode" value="1">
            <b>Kode</b>
        </label>
        <label for="barcode">
            <input type="checkbox" name="cek" class="filter" id="filter-barcode" value="1">
            <b>Barcode</b>
        </label>
        <label for="produk">
            <input type="checkbox" name="cek" class="filter" id="filter-produk" value="1">
            <b>Produk</b>
        </label>
        <label for="rak">
            <input type="checkbox" name="cek" class="filter" id="filter-rak" value="1">
            <b>Rak</b>
        </label>
        <label for="jenis">
            <input type="checkbox" name="cek" class="filter" id="filter-jenis" value="1">
            <b>Jenis</b>
        </label>
        <label for="kategori">
            <input type="checkbox" name="cek" class="filter" id="filter-kategori" value="1">
            <b>Kategori</b>
        </label>
        <label for="kandungan">
            <input type="checkbox" name="cek" class="filter" id="filter-kandungan" value="1">
            <b>Kandungan</b>
        </label>
        <!-- <p id="test"></p> -->
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
            if ($('#filter-kode').is(':checked')) {
                array_filter.push('produk.kode');
            }
            if ($('#filter-barcode').is(':checked')) {
                array_filter.push('produk.barcode');
            }
            if ($('#filter-produk').is(':checked')) {
                array_filter.push('produk.produk');
            }
            if ($('#filter-rak').is(':checked')) {
                array_filter.push('rak_gudang.rak');
            }
            if ($('#filter-jenis').is(':checked')) {
                array_filter.push('produk.jenis');
            }
            if ($('#filter-kategori').is(':checked')) {
                array_filter.push('produk.kategori');
            }
            if ($('#filter-kandungan').is(':checked')) {
                array_filter.push('produk.kandungan');
            }
            
            filter = array_filter.join("|");

            browseDataTable.ajax.url('<?= $this->url_generator->current_url() ?>?load=1&keyword='+$('#filter-search').val()+'&filter='+filter).load();
        });
    });
</script>