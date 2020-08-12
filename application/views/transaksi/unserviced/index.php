<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">
                {{unserviced}}
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <div class="form-group">
                <?= $this->action->link('create', $this->url_generator->current_url().'/create', 'class="btn btn-primary"') ?>
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
                        <th>{{no_servis}}</th>
	                    <th width="1" class="text-center">{{status}}</th>
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
                {data: 'tanggal', name: 'unserviced.tanggal'},
                {data: 'no_servis', name: 'unserviced.no_servis'},
                {data: 'batal', name: 'unserviced.batal', class: 'text-center'},
                {data:'_action', searchable: false, orderable: false, class: 'text-right nowrap'}
            ],
	        order: [[0, 'DESC']]
        });
    });

    function view(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/view/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{view}} {{unserviced}}',
                    size: 'large',
                    message: response
                });
            }
        });
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