<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">
                {{penjualan}} <?= ($jenis_penjualan == 'cabang' ? $this->localization->lang($jenis_penjualan) : '') ?>
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <div class="form-group">
                <?= $this->action->link('create', $this->url_generator->current_url().'/create?jenis_penjualan='.$jenis_penjualan, 'class="btn btn-primary"') ?>
            </div>
        </div>
    </div>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <table width="100%" id="data-table" class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th width="150">{{tanggal}}</th>
                        <th>{{no_penjualan}}</th>
                        <th width="150">{{pelanggan}}</th>
                        <th width="150">{{jatuh_tempo}}</th>
                        <th width="150">{{kas_bank}}</th>
                        <th width="150" class="text-right">{{total}}</th>
                        <th width="1">{{status}}</th>
                        <th width="1"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<script>
	<?php if ($this->session->flashdata('print')) { ?>
		var win = window.open('<?= $this->route->name('transaksi.penjualan.nota', array('id' => $this->session->flashdata('print'))) ?>');
		setTimeout(function() {
			win.close();
		}, 5000);
	<?php } ?>

    var dataTable;
    $(function() {
        dataTable = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            searchDelay:1000,
            ajax: '<?= $this->url_generator->current_url() ?>?jenis_penjualan=<?= $jenis_penjualan ?>',
            columns: [
                {data: 'tanggal', name: 'penjualan.tanggal'},
                {data: 'no_penjualan', name: 'penjualan.no_penjualan'},
                {data: 'pelanggan', name: 'pelanggan.nama'},
                {data: 'jatuh_tempo', name: 'penjualan.jatuh_tempo'},
                {data: 'kas_bank', name: 'kas_bank.nama'},
                {data: 'total', name: 'penjualan.total', class: 'text-right'},
                {data: 'batal', name: 'penjualan.batal', class: 'text-center'},
                {data:'_action', searchable: false, orderable: false, class: 'text-right nowrap'}
            ],
	        order: [[0, 'DESC'], [1, 'DESC']]
        });
    });

    function view(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/view/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{view}} {{penjualan}}',
                    size: 'large',
                    message: response
                });
            }
        });
    }

    function nota(id) {
	    var win = window.open('<?= $this->url_generator->current_url() ?>/nota/'+id);
	    setTimeout(function() {
		    win.close();
	    }, 5000);
    }

    function remove(id) {
        swalConfirm('Apakah anda yakin akan menghapus data ini?', function() {
            $.ajax({
                url: '<?= $this->url_generator->current_url() ?>/delete/'+id,
	            type: 'post',
	            data: 'jenis_batal=cancel',
                success: function(response) {
                    if (response.success) {
                        $.growl.notice({message: response.message});
                        dataTable.ajax.reload();
                    } else {
	                    $.growl.error({message: response.message});
                    }
                }
            });
        });
    }

	function returns(id) {
		swalConfirm('Apakah anda yakin akan melakukan retur pada data ini?', function() {
			$.ajax({
				url: '<?= $this->url_generator->current_url() ?>/delete/'+id,
				type: 'post',
				data: 'jenis_batal=retur',
				success: function(response) {
					if (response.success) {
						$.growl.notice({message: response.message});
						dataTable.ajax.reload();
					} else {
						$.growl.error({message: response.message});
					}
				}
			});
		});
	}
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>