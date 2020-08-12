<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">
                {{pengaturan_harga}}
            </h1>
        </div>
        <div class="col-md-6 text-right">
	        <div class="form-group">
		        <div class="btn-group">
			        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				        {{export_import}} <span class="caret"></span>
			        </button>
			        <ul class="dropdown-menu">
				        <li><?= $this->action->link('import', $this->url_generator->current_url().'/download_format', null, $this->localization->lang('download_format')) ?></li>
				        <li><?= $this->action->link('import', 'javascript:void(0)', 'onclick="upload()"') ?></li>
				        <li><?= $this->action->link('export', $this->url_generator->current_url().'/export') ?></li>
			        </ul>
		        </div>
	        </div>
        </div>
    </div>
    <?php $this->template->view('layouts/partials/message') ?>
	<?php $this->template->view('layouts/partials/import_message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <table width="100%" id="data-table" class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th width="150">{{kode}}</th>
                        <th width="150">{{barcode}}</th>
                        <th>{{produk}}</th>
                        <th width="100">{{jenis}}</th>
                        <td width="1"></td>
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
            ajax: '<?= $this->url_generator->current_url() ?>',
            columns: [
                {data: 'kode', name: 'produk.kode'},
                {data: 'barcode', name: 'produk.barcode'},
                {data: 'produk', name: 'produk.produk'},
                {data: 'jenis', name: 'produk.jenis'},
                {data:'_action', searchable: false, orderable: false, class: 'text-right nowrap'}
            ]
        });
    });

    function view(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/view/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{view}} {{produk}}',
                    message: response
                });
            }
        });
    }

    function upload() {
	    $.ajax({
		    url: '<?= $this->url_generator->current_url() ?>/import',
		    success: function(response) {
			    bootbox.dialog({
				    title: '{{import}} {{harga}}',
				    message: response
			    });
		    }
	    });
    }
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>