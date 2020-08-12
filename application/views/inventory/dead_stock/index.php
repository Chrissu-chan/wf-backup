<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-4">
            <h1 class="page-header">
                {{dead_stock}}
            </h1>
        </div>
        <div class="col-md-8 text-right">
            <div class="form-inline">
                <div class="form-group">
                    <label>{{gudang}}</label>
                    <?= $this->form->select('filter_gudang', lists($this->cabang_gudang_m->scope('aktif_cabang')->scope('utama')->view('cabang_gudang')->get(), 'id', 'gudang'), null, 'id="filter-gudang" class="form-control"') ?>
                </div>
                <div class="form-group">
                    <label>{{range}}</label>
                    <?= $this->form->select('filter_range', $this->barang_stok_m->enum('range_stok'), 30, 'id="filter-range" class="form-control"') ?>
                </div>
                <div class="form-group">
                    <button type="button" id="btn-filter" class="btn btn-default">{{filter}}</button>
                </div>
            </div>
        </div>
    </div>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <table width="100%" id="data-table" class="table table-bordered table-condensed nowrap">
                <thead>
                    <tr>
                        <th width="150">{{gudang}}</th>
                        <th width="100">{{kode_barang}}</th>
                        <th>{{nama_barang}}</th>
                        <th width="100">{{satuan}}</th>
                        <th width="100">{{jumlah}}</th>
                        <th width="100">{{tanggal_keluar_terakhir}}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<script>
    var dataTable;
    $(function() {
        dataTable = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            searchDelay:1000,
            scrollX: true,
            ajax: '<?= $this->url_generator->current_url() ?>?gudang='+$('#filter-gudang').val()+'&range='+$('#filter-range').val(),
            columns: [
                {data: 'gudang', name: 'gudang.gudang'},
                {data: 'kode_barang', name: 'barang.kode'},
                {data: 'nama_barang', name: 'barang.nama'},
                {data: 'satuan', name: 'satuan.satuan'},
                {data: 'jumlah', name: 'barang_stok.jumlah', class: 'text-right'},
                {data: 'tanggal_keluar_terakhir', name: 'barang_stok.tanggal_keluar_terakhir'}
            ],
	        order: [[1, 'ASC'], [5, 'ASC']]
        });

        $('#btn-filter').click(function() {
            dataTable.ajax.url('<?= $this->url_generator->current_url() ?>?gudang=' + $('#filter-gudang').val()+'&range='+$('#filter-range').val()).load();
        });
    });
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>