<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">
                {{petugas}}
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <div class="form-inline">
                <div class="form-group">
                    <label>{{cabang}}</label>
                    <?= $this->form->select('filter_jenis', lists($this->cabang_m->scope('auth')->get(), 'id', 'nama'), $this->session->userdata('cabang')->id, 'class="form-control" id="filter-cabang"') ?>
                </div>
                <div class="form-group">
                    <label>{{jenis_petugas}}</label>
                    <?= $this->form->select('filter_jenis_petugas', lists($this->jenis_petugas_m->get(), 'id', 'jenis_petugas', $this->localization->lang('pilih_jenis_petugas')), null, 'class="form-control" id="filter-jenis_petugas"') ?>
                </div>
                <div class="form-group">
                    <button type="button" id="btn-filter" class="btn btn-default">{{filter}}</button>
                </div>
                <div class="form-group">
                    <?= $this->action->button('create', 'class="btn btn-primary" onclick="create()"') ?>
                </div>
            </div>
        </div>
    </div>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <table width="100%" id="data-table" class="table table-bordered table-condensed ">
                <thead>
                    <tr>
                        <th>{{nama}}</th>
                        <th width="300">{{jenis_petugas}}</th>
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
            ajax: '<?= $this->url_generator->current_url() ?>?cabang=' + $('#filter-cabang').val(),
            columns: [
                {data: 'nama', name: 'petugas.nama'},
                {data: 'jenis_petugas', name: 'petugas.id_jenis_petugas'},
                {data: '_action', searchable: false, orderable: false, class: 'text-right nowrap'}
            ]
        });


        $('#btn-filter').click(function() {
            dataTable.ajax.url('<?= $this->url_generator->current_url() ?>?cabang=' + $('#filter-cabang').val() + '&jenis=' + $('#filter-jenis_petugas').val()).load();
        });
    });

    function view(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/view/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{view}} {{petugas}}',
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
                    title: '{{create}} {{petugas}}',
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
                    title: '{{edit}} {{petugas}}',
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
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>