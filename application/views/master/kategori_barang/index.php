<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">
                {{kategori_barang}}
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <div class="form-group">
                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{import_export}} <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><?= $this->action->link('import', $this->url_generator->current_url().'/download_format', null, $this->localization->lang('download_format')) ?></li>
                        <li><?= $this->action->link('import', 'javascript:void(0)', 'onclick="upload()"') ?></li>
                        <li><?= $this->action->link('export', $this->url_generator->current_url().'/export') ?></li>
                    </ul>
                </div>
                <?= $this->action->button('create', 'class="btn btn-primary" onclick="create(0)"') ?>
            </div>
        </div>
    </div>
    <?php $this->template->view('layouts/partials/message') ?>
    <?php $this->template->view('layouts/partials/import_message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <table width="100%" id="data-table" class="table table-bordered table-condensed ">
                <thead>
                    <tr>
                        <th>{{kategori_barang}}</th>
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
            ajax: '<?= $this->url_generator->current_url() ?>',
            columns: [
                {data: 'kategori_barang', name: 'kategori_barang.kategori_barang'},
                {data: '_action', searchable: false, orderable: false, class: 'text-right nowrap'}
            ]
        });
    });
    function view(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/view/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{view}} {{kategori_barang}}',
                    message: response
                });
            }
        });
    }

    function create(parent_id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/create?parent_id='+parent_id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{create}} {{kategori_barang}}',
                    message: response
                });
            }
        });
    }

    function store() {
        $('.validation-message').remove();
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/store',
            type: 'post',
            data: $('#frm-create').serialize(),
            success: function(response) {
                if (response.success) {
                    $.growl.notice({message: response.message});
                    bootbox.hideAll();
                    dataTable.ajax.reload();
                } else {
	                $.growl.error({message: response.message});
                    $.each(response.validation, function(key, message) {
                        $('#'+key).closest('.form-group').append('<p class="text-danger validation-message">'+message+'</p>');
                    });
                }
            }
        });
    }

    function edit(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/edit/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{edit}} {{kategori_barang}}',
                    message: response
                });
            }
        });
    }

    function update(id) {
        $('.validation-message').remove();
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/update/'+id,
            type: 'post',
            data: $('#frm-edit').serialize(),
            success: function(response) {
                if (response.success) {
                    $.growl.notice({message: response.message});
                    bootbox.hideAll();
                    dataTable.ajax.reload();
                } else {
                    if(response.message) {
                        bootbox.hideAll();
                        $.growl.error({message: response.message});
                    }
                    if(response.validation) {
                        $.each(response.validation, function(key, message) {
                            $('#'+key).closest('.form-group').append('<p class="text-danger validation-message">'+message+'</p>');
                        });
                    }
                }
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

    function cancel() {
        bootbox.hideAll();
    }

    function upload() {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/import',
            success: function(response) {
                bootbox.dialog({
                    title: '{{import}} {{kategori_barang}}',
                    message: response
                });
            }
        });
    }
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>