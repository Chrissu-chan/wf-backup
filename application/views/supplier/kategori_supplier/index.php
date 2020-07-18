<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">
                {{kategori_supplier}}
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <div class="form-inline">
                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{import_export}} <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="#" onclick="upload()">{{import}}</a></li>
                        <li><?= $this->action->link('master.kategori_jasa.export', $this->url_generator->current_url().'/export') ?></li>
                        <li><?= $this->action->link('master.kategori_barang.download_format', $this->url_generator->current_url().'/download_format') ?></li>
                    </ul>
                </div>
                <div class="form-group">
                    <?= $this->action->button('create', 'class="btn btn-primary" onclick="create(0)"') ?>
                </div>
            </div>
        </div>
    </div>
    <?php $this->template->view('layouts/partials/message') ?>
    <?php $this->template->view('layouts/partials/import_message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <table id="tree-table" class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>{{kategori_supplier}}</th>
                        <td width="1"></td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($model as $row) { ?>
                        <tr data-tt-id="<?= $row->id ?>" data-tt-parent-id="<?= $row->parent_id ?>">
                            <td><?= $row->kategori_supplier ?></td>
                            <td class="text-right nowrap">
                                <?= $this->action->button('create', 'class="btn btn-primary btn-sm" onclick="create(\''.$row->id.'\')"', $this->localization->lang('create_sub')) ?>
                                <?= $this->action->button('view', 'class="btn btn-info btn-sm" onclick="view(\''.$row->id.'\')"') ?>
                                <?= $this->action->button('edit', 'class="btn btn-warning btn-sm" onclick="edit(\''.$row->id.'\')"') ?>
                                <?= $this->action->button('delete', 'class="btn btn-danger btn-sm" onclick="remove(\''.$row->id.'\')"') ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<script>
    $(function() {
        $('#tree-table').treetable({ expandable: true });
    });

    function add_sub(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/create/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{add_sub}} {{kategori_supplier}}',
                    message: response
                });
            }
        });
    }

    function view(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/view/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{view}} {{kategori_supplier}}',
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
                    title: '{{create}} {{kategori_supplier}}',
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
                    document.location.reload();
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
                    title: '{{edit}} {{kategori_supplier}}',
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
                    document.location.reload();
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
                        document.location.reload();
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
                    title: '{{import}} {{kategori_supplier}}',
                    message: response
                });
            }
        });
    }
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>