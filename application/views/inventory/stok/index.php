<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-4">
            <h1 class="page-header">
                {{stok}}
            </h1>
        </div>
        <div class="col-md-8 text-right">
            <div class="form-inline">
                <div class="form-group">
                    <label>{{gudang}}</label>
                    <?= $this->form->select('filter_gudang', lists($this->cabang_gudang_m->scope('utama')->view('cabang_gudang')->get(), 'id', 'gudang'), null, 'id="filter-gudang" class="form-control"') ?>
                </div>
                <div class="form-group">
                    <label>{{periode}}</label>
	                <div class="input-group input-group-sm">
		                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
		                <?= $this->form->date('filter_tanggal_awal', date('01-m-Y'), 'id="filter-tanggal_awal" class="form-control" data-input-type="dateinput"') ?>
		                <span class="input-group-addon">-</i></span>
		                <?= $this->form->date('filter_tanggal_akhir', date('d-m-Y'), 'id="filter-tanggal_akhir" class="form-control" data-input-type="dateinput"') ?>
	                </div>
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
                        <th width="100">{{stok_awal}}</th>
                        <th width="100">{{mutasi}}</th>
                        <th width="100">{{stok_akhir}}</th>
                        <th width="1"></th>
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
            searchDelay:1000,
            ajax: '<?= $this->url_generator->current_url() ?>?gudang='+$('#filter-gudang').val()+'&tanggal_awal='+$('#filter-tanggal_awal').val()+'&tanggal_akhir='+$('#filter-tanggal_akhir').val(),
            columns: [
                {data: 'gudang', name: 'gudang.gudang'},
                {data: 'kode_barang', name: 'barang.kode'},
                {data: 'nama_barang', name: 'barang.nama'},
                {data: 'satuan', name: 'satuan.satuan'},
                {data: 'stok_awal', name: 'barang_stok.stok_awal', searchable: false, orderable: false, class: 'text-right'},
                {data: 'mutasi', name: 'barang_stok.mutasi', searchable: false, orderable: false, class: 'text-right'},
                {data: 'stok_akhir', name: 'barang_stok.stok_akhir', searchable: false, orderable: false, class: 'text-right'},
                {data: '_action', searchable: false, orderable: false, class: 'text-right nowrap'}
            ],
            order: [[1, 'ASC']]
        });
        
        $('#btn-filter').click(function() {
            dataTable.ajax.url('<?= $this->url_generator->current_url() ?>?gudang=' + $('#filter-gudang').val()+'&tanggal_awal='+$('#filter-tanggal_awal').val()+'&tanggal_akhir='+$('#filter-tanggal_akhir').val()).load();
        });
    });

    function cancel() {
        bootbox.hideAll();
    }

    function expand_filter() {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/expand_filter',
            success: function(response) {
                bootbox.dialog({
                    title: '{{filter}} {{barang_stok}}',
                    message: response
                });
            }
        });
    }
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>