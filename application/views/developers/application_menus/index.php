<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">
                {{application_menus}}
                <small><i class="fa fa-angle-right"></i> <?= $application->application ?></small>
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <div class="form-inline">
                <div class="form-group">
                    <?= $this->action->button('create', 'class="btn btn-primary btn-block" onclick="create()"') ?>
                </div>
                <div class="form-group">
                    <?= $this->action->button('edit', 'class="btn btn-primary btn-block" onclick="update_sequence()"', $this->localization->lang('update_sequence')) ?>
                </div>
            </div>
        </div>
    </div>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= $this->form->open($this->url_generator->current_url().'/update_sequence', 'id="form-menus"') ?>
            <table id="tree-table" class="table table-bordered table-condensed table-responsive">
                <thead>
                    <tr>
                        <th width="200">{{menu}}</th>
                        <th>{{description}}</th>
                        <th width="200">{{action}}</th>
                        <th width="100" class="text-center">{{sequence}}</th>
                        <th width="1"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($model as $row) { ?>
                        <tr data-tt-id="<?= $row->id ?>" data-tt-parent-id="<?= $row->parent_id ?>">
                            <td><?= $row->menu ?></td>
                            <td><?= $row->description ?></td>
                            <td><?= (!is_null($row->module_feature_action_id) ? trim($row->module.'/'.$row->feature.'/'.$row->action, '/') : '') ?></td>
                            <td><?= $this->form->text('sequence['.$row->id.']', $row->sequence, 'class="form-control input-sm text-center"') ?></td>
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
            <?= $this->form->close() ?>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<script>
    $(function() {
        $('#tree-table').treetable({ expandable: true });
    });
    function view(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/view/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{view}} {{application_menus}}',
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
                    title: '{{create}} {{application_menus}}',
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
                    title: '{{edit}} {{application_menus}}',
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
                    $.each(response.validation, function(key, message) {
                        $('#'+key).closest('.form-group').append('<p class="text-danger validation-message">'+message+'</p>');
                    });
                }
            }
        });
    }

    function update_sequence() {
        $('#form-menus').submit();
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
                        $.growl.notice({message: response.message});
                    }
                }
            });
        });
    }

    function cancel() {
        bootbox.hideAll();
    }
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/developer') ?>