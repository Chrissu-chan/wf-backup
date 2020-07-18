<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-2">
            <h1 class="page-header">
                {{member}}
            </h1>
        </div>
        <div class="col-md-10 text-right">
            <div class="form-inline">
                <div class="form-group">
                    <label>{{jenis}}</label>
                    <?= $this->form->select('filter_jenis', lists($this->jenis_member_m->get(), 'id', 'jenis_member', $this->localization->lang('pilih_jenis_member')), null, 'class="form-control" id="filter-jenis"') ?>
                </div>
                <div class="form-group">
                    <label>{{bulan_tahun_daftar}}</label>
                    <?= $this->form->select('filter_bulan', $this->localization->months(), null, 'class="form-control" id="filter-bulan"') ?>
                    <?= $this->form->select('filter_tahun', $this->localization->years(), null, 'class="form-control" id="filter-tahun"') ?>
                </div>
                <div class="form-group">
                    <label>{{status}}</label>
                    <?= $this->form->select('filter_status', $this->member_m->enum('status'), null, 'class="form-control" id="filter-status"') ?>
                </div>
                <div class="form-group">
                    <button type="button" id="btn-filter" class="btn btn-default">{{filter}}</button>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{import_export}} <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="#" onclick="upload()">{{import}}</a></li>
                        <li><?= $this->action->link('member.member.export', $this->url_generator->current_url().'/export') ?></li>
                        <li><?= $this->action->link('member.member.download_format', $this->url_generator->current_url().'/download_format') ?></li>
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
            <table width="100%" id="data-table" class="table table-bordered table-condensed  nowrap">
                <thead>
                    <tr>
                        <th>{{kode}}</th>
                        <th>{{nama}}</th>
                        <th>{{tanggal_expired}}</th>
                        <th>{{jenis_identitas}}</th>
                        <th>{{no_identitas}}</th>
                        <th>{{jenis_kelamin}}</th>
                        <th>{{telepon}}</th>
                        <th>{{hp}}</th>
                        <th>{{kota}}</th>
                        <td></td>
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
                {data: 'kode', name: 'member.kode'},
                {data: 'nama', name: 'pelanggan.nama'},
                {data: 'tanggal_expired', name: 'member.tanggal_expired'},
                {data: 'jenis_identitas', name: 'pelanggan.jenis_identitas'},
                {data: 'no_identitas', name: 'pelanggan.no_identitas'},
                {data: 'jenis_kelamin', name: 'pelanggan.jenis_kelamin'},
                {data: 'telepon', name: 'pelanggan.telepon'},
                {data: 'hp', name: 'pelanggan.hp'},
                {data: 'kota', name: 'pelanggan.kota'},
                {data: '_action', searchable: false, orderable: false, class: 'text-right'}
            ]
        });

        $('#btn-filter').click(function() {
            dataTable.ajax.url('<?= $this->url_generator->current_url() ?>?jenis=' + $('#filter-jenis').val() + '&tahun_bulan=' + $('#filter-tahun').val() + '-' + $('#filter-bulan').val() + '&status=' + $('#filter-status').val()).load();
        });
    });

    function view(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/view/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{view}} {{member}}',
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
                    title: '{{create}} {{member}}',
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
                    title: '{{edit}} {{member}}',
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
                    title: '{{import}} {{member}}',
                    message: response
                });
            }
        });
    }
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>