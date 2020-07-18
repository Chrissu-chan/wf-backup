<?php $this->template->section('content') ?>
    <h1 class="page-header">
        {{pasien}}
        <div class="pull-right">
            <div class="btn-group">
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{import_export}} <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#" onclick="upload()">{{import}}</a></li>
                    <li><?= $this->action->link('rekam_medis.pasien.export', $this->url_generator->current_url().'/export') ?></li>
                    <li><?= $this->action->link('rekam_medis.pasien.download_format', $this->url_generator->current_url().'/download_format') ?></li>
                </ul>
            </div>
            <?= $this->action->button('create', 'class="btn btn-primary" onclick="create()"') ?>
        </div>
    </h1>
    <?php $this->template->view('layouts/partials/message') ?>
    <?php $this->template->view('layouts/partials/import_message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <table width="100%" id="data-table" class="table table-bordered table-condensed  nowrap">
                <thead>
                    <tr>
                        <th>{{nama}}</th>
                        <th width="85">{{jenis_identitas}}</th>
                        <th width="90">{{no_identitas}}</th>
                        <th width="80">{{jenis_kelamin}}</th>
                        <th width="75">{{telepon}}</th>
                        <th width="75">{{hp}}</th>
                        <th width="80">{{kota}}</th>
                        <td width="150"></td>
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
            scrollX: true,
            ajax: '<?= $this->url_generator->current_url() ?>',
            columns: [
                {data: 'nama', name: 'pasien.nama'},
                {data: 'jenis_identitas', name: 'pasien.id_jenis_identitas'},
                {data: 'no_identitas', name: 'pasien.no_identitas'},
                {data: 'jenis_kelamin', name: 'pasien.jenis_kelamin'},
                {data: 'telepon', name: 'pasien.telepon'},
                {data: 'hp', name: 'pasien.hp'},
                {data: 'kota', name: 'pasien.id_kota'},
                {data: '_action', searchable: false, orderable: false, class: 'text-right'}
            ]
        });
    });

    function view(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/view/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{view}} {{pasien}}',
                    message: response
                });
            }
        });
    }

    function create() {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/create',
            success: function(response) {
                bootbox.dialog({
                    size: 'large',
                    title: '{{create}} {{pasien}}',
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
                    title: '{{edit}} {{pasien}}',
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
                    $.each(response.validation, function(key, message) {
                        $('#'+key).closest('.form-group').append('<p class="text-danger validation-message">'+message+'</p>');
                    });
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
                        $.growl.notice({message: response.message});
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
                    title: '{{import}} {{pasien}}',
                    message: response
                });
            }
        });
    }
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>