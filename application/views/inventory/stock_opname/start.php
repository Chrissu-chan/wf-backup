<?php $this->template->section('css') ?>
    <link href="<?= base_url('public/plugins/bootstrap-wizard/css/bwizard.min.css') ?>" rel="stylesheet"/>
<?php $this->template->endsection() ?>
<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">{{stock_opname}}</h1>
        </div>
        <div class="col-md-6 text-right">

        </div>
    </div>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= $this->form->open($this->route->name('inventory.stock_opname.finish', array('id' => $id_stock_opname))) ?>
            <div class="bwizard clearfix">
                <?php $this->template->view('inventory/stock_opname/partials/step_nav', array('active' => 2)) ?>
                <hr>
	            <div class="row">
		            <div class="col-md-offset-4 col-md-4">
			            <div class="form-group form-inline">
				            <?= $this->form->select('filter_status', $this->stock_opname_m->enum('status'), NULL, 'id="filter-status" class="form-control" style="width: 80%;"') ?>
			            </div>
		            </div>
	            </div>
                <table width="100%" id="data-table" class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th width="100">{{id_barang}}</th>
                            <th width="100">{{kode_barang}}</th>
                            <th>{{nama_barang}}</th>
	                        <th width="100" class="text-right">{{harga_beli}}</th>
                            <th width="100" class="text-right">{{stok_awal}}</th>
                            <th width="100" class="text-right">{{stok_akhir}}</th>
                            <th width="100" class="text-right">{{selisih}}</th>
                            <th width="100" class="text-right">{{total}}</th>
	                        <th width="100">{{so_by}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div class="form-group">
                    <?= $this->action->submit('create', 'class="btn btn-success pull-right"', $this->localization->lang('finish')) ?>
                </div>
            </div>
            <?= $this->form->close(); ?>
        </div>
    </div>
<?php $this->template->endsection() ?>
<?php $this->template->section('page_script') ?>
	<script>
		$(function () {
			dataTable = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
				searchDelay: 1000,
                ajax: '<?= $this->url_generator->current_url() ?>?id_stock_opname=<?= $id_stock_opname ?>',
                columns: [
                    {data: 'id_obat', name: 'stock_opname_detail.id_obat'},
                    {data: 'kode_barang', name: 'barang.kode'},
                    {data: 'nama_barang', name: 'barang.nama'},
	                {data: 'harga_beli', name: 'obat.total', searchable: false, orderable: false, class: 'text-right'},
                    {data: 'stok_awal', name: 'stock_opname_detail.stok_awal', searchable: false, orderable: false, class: 'text-right'},
                    {data: 'stok_akhir', name: 'stock_opname_detail.stok_akhir', searchable: false, orderable: false, class: 'text-right'},
                    {data: 'selisih', searchable: false, orderable: false, class: 'text-right'},
                    {data: 'total', searchable: false, orderable: false, class: 'text-right'},
                    {data: 'so_by', nama: 'stock_opname_detail.so_by'}
                ]
            });

			setInterval(function(){
				dataTable.ajax.url('<?= $this->url_generator->current_url() ?>?id_stock_opname=<?= $id_stock_opname ?>&status='+$('#filter-status').val()).load();
			}, 10000);

			$('#filter-status').change(function() {
				dataTable.ajax.url('<?= $this->url_generator->current_url() ?>?id_stock_opname=<?= $id_stock_opname ?>&status='+$('#filter-status').val()).load();
			});
		});
	</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>