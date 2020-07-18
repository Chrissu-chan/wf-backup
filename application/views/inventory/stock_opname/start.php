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
                <table width="100%" id="table" class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th width="100">{{id_barang}}</th>
                            <th width="150">{{kode_barang}}</th>
                            <th>{{nama_barang}}</th>
                            <td width="100" class="text-right">{{jumlah}}</td>
                            <td width="100" class="text-right">{{stok_awal}}</td>
                            <td width="100" class="text-right">{{stok_akhir}}</td>
                            <td width="100" class="text-right">{{selisih}}</td>
	                        <td width="100">{{so_by}}</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($stock_opname_detail) { ?>
                            <?php foreach ($stock_opname_detail as $row) { ?>
                                <tr data-row-id="<?= $row->id ?>">
                                    <td><?= $row->id_obat ?></td>
                                    <td><?= $row->kode_barang ?></td>
                                    <td><?= $row->nama_barang ?></td>
                                    <td id="jumlah-<?= $row->id ?>" class="text-right"><?= $row->jumlah ?></td>
                                    <td id="stok_awal-<?= $row->id ?>" class="text-right"><?= $row->stok_awal ?></td>
                                    <td id="stok_akhir-<?= $row->id ?>" class="text-right"><?= $row->stok_akhir ?></td>
                                    <td id="selisih-<?= $row->id ?>" class="text-right"><?= ($row->stok_akhir - $row->stok_awal) ?></td>
	                                <td id="so_by-<?= $row->id ?>"><?= $row->so_by ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
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
			setInterval(function(){
				get_stock_opname_detail();
			}, 5000);
		});

		function get_stock_opname_detail() {
			$.ajax({
				url: '<?= $this->route->name('inventory.stock_opname.get_json') ?>?id_stock_opname=<?= $id_stock_opname ?>',
				success: function(response) {
					if (response.success) {
						$.each(response.data, function(index, row) {
							if ($('tr[data-row-id="'+row.id+'"]').length == 0) {
								var html_row = '<tr data-row-id="'+row.id+'">';
								html_row += '<td>'+row.id_obat+'</td>';
								html_row += '<td>'+row.kode_barang+'</td>';
								html_row += '<td>'+row.nama_barang+'</td>';
								html_row += '<td id="jumlah-'+row.id+'" class="text-right">'+row.jumlah+'</td>';
								html_row += '<td id="stok_awal-'+row.id+'" class="text-right">'+row.stok_awal+'</td>';
								html_row += '<td id="stok_akhir-'+row.id+'" class="text-right">'+row.stok_akhir+'</td>';
								html_row += '<td id="selisih-'+row.id+'" class="text-right">'+(row.stok_akhir - row.stok_awal)+'</td>';
								html_row += '<td id="so_by-'+row.id+'">'+row.so_by+'</td>';
								html_row += '</tr>';
								$('#table tbody').append(html_row);
							} else {
								$('#jumlah-'+row.id).html(row.jumlah);
								$('#stok_awal-'+row.id).html(row.stok_awal);
								$('#stok_akhir-'+row.id).html(row.stok_akhir);
								$('#selisih-'+row.id).html(row.stok_akhir - row.stok_awal);
								$('#so_by-'+row.id).html(row.so_by);
							}
						});
					}
				}
			});
		}
	</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>