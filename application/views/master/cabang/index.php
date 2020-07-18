<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">
                {{cabang}}
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <div class="form-group">
                <?= $this->action->button('create', 'class="btn btn-primary" onclick="create()"') ?>
            </div>
        </div>
    </div>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <table id="table" width="100%" class="table table-bordered table-condensed ">
                <thead>
                    <tr>
                        <th>{{nama}}</th>
                        <th width="150">{{npwp}}</th>
                        <th width="150">{{telepon}}</th>
                        <th width="350">{{alamat}}</th>
                        <td width="1"></td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
<script>
    $(function() {
        get();
    });

    function get() {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/index',
            success: function(response) {
                $('#table').replaceWith(response);
            }
        });
    }

    function view(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/view/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{view}} {{cabang}}',
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
                    title: '{{create}} {{cabang}}',
                    message: response
                });
            }
        });
    }

    function store() {
        $('#btn-store').prop('disabled', true);
        $('.validation-message').remove();
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/store',
            type: 'post',
            data: $('#frm-create').serialize(),
            success: function(response) {
                if (response.success) {
                    $.growl.notice({message: response.message});
                    bootbox.hideAll();
                    get();
                } else {
	                $.growl.error({message: response.message});
                    $.each(response.validation, function(key, message) {
                        $('#'+key).closest('.form-group').append('<p class="text-danger validation-message">'+message+'</p>');
                    });
                }
            }
        }).done(function() {
            $('#btn-store').prop('disabled', false);
        });
    }

    function edit(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/edit/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{edit}} {{cabang}}',
                    message: response
                });
            }
        });
    }

    function update(id) {
        $('#btn-update').prop('disabled', true);
        $('.validation-message').remove();
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/update/'+id,
            type: 'post',
            data: $('#frm-edit').serialize(),
            success: function(response) {
                if (response.success) {
                    $.growl.notice({message: response.message});
                    bootbox.hideAll();
                    get();
                } else {
                    if(response.message) {
                        $.growl.error({message: response.message});
                    }
                    if(response.validation) {
                        $.each(response.validation, function(key, message) {
                            $('#'+key).closest('.form-group').append('<p class="text-danger validation-message">'+message+'</p>');
                        });
                    }
                }
            }
        }).done(function() {
            $('#btn-update').prop('disabled', false);
        });
    }

    function remove(id) {
        swalConfirm('Apakah anda yakin akan menghapus data ini?', function() {
            $.ajax({
                url: '<?= $this->url_generator->current_url() ?>/delete/'+id,
                success: function(response) {
                    if (response.success) {
                        $.growl.notice({message: response.message});
                        get();
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
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>