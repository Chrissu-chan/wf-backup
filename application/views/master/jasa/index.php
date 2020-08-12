<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">
                {{jasa}}
            </h1>
        </div>
        <div class="col-md-6 text-right">
	        <div class="form-inline">
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
		        <div class="form-group">
			        <?= $this->action->link('create', $this->url_generator->current_url().'/create', 'class="btn btn-primary"') ?>
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
                        <th>{{jasa}}</th>
                        <th width="200">{{kategori_jasa}}</th>
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
            ajax: '<?= $this->url_generator->current_url() ?>',
            columns: [
                {data: 'jasa', name: 'jasa.jasa'},
                {data: 'kategori_jasa', name: 'kategori_jasa.kategori_jasa'},
                {data: '_action', searchable: false, orderable: false, class: 'text-right nowrap'}
            ]
        });
    });

    function view(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/view/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{view}} {{jasa}}',
                    message: response
                });
            }
        });
    }

    function remove(id) {
        swalConfirm('Apakah anda yakin akan menghapus data ini?', function() {
            $.ajax({
                url: '<?= $this->url_generator->current_url() ?>/delete/'+id,
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

    function upload() {
	    $.ajax({
		    url: '<?= $this->url_generator->current_url() ?>/import',
		    success: function(response) {
			    bootbox.dialog({
				    title: '{{import}} {{jasa}}',
				    message: response
			    });
		    }
	    });
    }
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>