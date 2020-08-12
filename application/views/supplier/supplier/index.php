<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-4">
            <h1 class="page-header">
                {{supplier}}
            </h1>
        </div>
        <div class="col-md-8 text-right">
            <div class="form-inline">
                <div class="form-group">
                    <label>{{kategori}}</label>
                    <?= $this->form->select('filter_kategori', tree_lists($this->kategori_supplier_m->get(), 'id', 'parent_id', 0, 'id', 'kategori_supplier', $this->localization->lang('pilih_kategori_supplier')), null, 'id="filter-kategori" class="form-control"') ?>
                </div>
                <div class="form-group">
                    <label>{{jenis}}</label>
                    <?= $this->form->select('filter_jenis', lists($this->jenis_supplier_m->get(), 'id', 'jenis_supplier', $this->localization->lang('pilih_jenis_supplier')), null, 'class="form-control" id="filter-jenis"') ?>
                </div>
                <div class="form-group">
                    <button type="button" id="btn-filter" class="btn btn-default">{{filter}}</button>
                </div>
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
                <div class="form-group">
                    <?= $this->action->button('create', 'class="btn btn-primary" onclick="create()"') ?>
                </div>
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
                        <th>{{nama_pemilik}}</th>
                        <th width="150">{{kategori_supplier}}</th>
                        <th width="150">{{jenis_supplier}}</th>
                        <th width="150">{{supplier}}</th>
                        <th width="100">{{jenis_kelamin}}</th>
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
                {data: 'nama', name: 'nama'},
                {data: 'kategori_supplier', name: 'supplier.id_kategori_supplier'},
                {data: 'jenis_supplier', name: 'supplier.id_jenis_supplier'},
                {data: 'supplier', name: 'supplier'},
                {data: 'jenis_kelamin', name: 'jenis_kelamin'},
                {data: '_action', searchable: false, orderable: false, class: 'text-right nowrap'}
            ]
        });

        $('#btn-filter').click(function() {
            dataTable.ajax.url('<?= $this->url_generator->current_url() ?>?kategori=' + $('#filter-kategori').val() + '&jenis=' + $('#filter-jenis').val()).load();
        });

    });

    function view(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/view/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{view}} {{supplier}}',
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
                    title: '{{create}} {{supplier}}',
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
                    title: '{{edit}} {{supplier}}',
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
	                $.growl.error({message: response.message});
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
                        dataTable.ajax().reload();
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
                    title: '{{import}} {{supplier}}',
                    message: response
                });
            }
        });
    }
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>