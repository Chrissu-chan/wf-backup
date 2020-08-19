<?php $this->template->section('content') ?>
<h1 class="page-header">
    {{piutang}}
    <div class="pull-right">
        <?= $this->action->button('create', 'class="btn btn-primary" onclick="create()"') ?>
    </div>
</h1>
<?php $this->template->view('layouts/partials/message') ?>
<div class="panel panel-default">
    <div class="panel-body">
        <table id="data-table" class="table table-bordered table-condensed nowrap" width="100%">
            <thead>
                <tr>
                    <th>{{no_piutang}}</th>
                    <th>{{jenis_piutang}}</th>
                    <th>{{no_refrensi}}</th>
                    <th>{{nama}}</th>
                    <th>{{tanggal_piutang}}</th>
                    <th>{{tanggal_jatuh_tempo}}</th>
                    <th>{{jumlah_piutang}}</th>
                    <th>{{sisa_piutang}}</th>
                    <th width="1">{{lunas}}</th>
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
            columns: [{
                    data: 'no_piutang',
                    name: 'piutang.no_piutang'
                },
                {
                    data: 'jenis_piutang',
                    name: 'piutang.jenis_piutang'
                },
                {
                    data: 'no_ref',
                    name: 'piutang.no_ref'
                },
                {
                    data: 'nama',
                    name: 'piutang.nama'
                },
                {
                    data: 'tanggal_piutang',
                    name: 'piutang.tanggal_piutang'
                },
                {
                    data: 'tanggal_jatuh_tempo',
                    name: 'piutang.tanggal_jatuh_tempo'
                },
                {
                    data: 'jumlah_piutang',
                    name: 'piutang.jumlah_piutang',
                    class: 'text-right'
                },
                {
                    data: 'sisa_piutang',
                    name: 'piutang.sisa_piutang',
                    class: 'text-right'
                },
                {
                    data: 'lunas',
                    name: 'piutang.lunas',
                    class: 'text-center'
                },
                {
                    data: '_action',
                    searchable: false,
                    orderable: false,
                    class: 'text-right'
                }
            ]
        });
    });

    function create() {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/create',
            success: function(response) {
                bootbox.dialog({
                    title: '{{create}} {{piutang}}',
                    message: response
                });
            }
        });
    }

    function store() {
        $('.validation-message').remove();
        var form = $('#frm-create')[0];
        var formData = new FormData(form);
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/store',
            type: 'post',
            enctype: 'multipart/form-data',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $.growl.notice({
                        message: response.message
                    });
                    bootbox.hideAll();
                    dataTable.ajax.reload();
                } else {
                    $.growl.error({
                        message: response.message
                    });
                    $.each(response.validation, function(key, message) {
                        $('#' + key).closest('.form-group').append('<p class="text-danger validation-message">' + message + '</p>');
                    });
                }
            }
        });
    }

    function edit(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/edit/' + id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{edit}} {{piutang}}',
                    message: response
                });
            }
        });
    }

    function update(id) {
        $('.validation-message').remove();
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/update/' + id,
            type: 'post',
            data: $('#frm-edit').serialize(),
            success: function(response) {
                if (response.success) {
                    $.growl.notice({
                        message: response.message
                    });
                    bootbox.hideAll();
                    dataTable.ajax.reload();
                } else {
                    $.growl.error({
                        message: response.message
                    });
                    $.each(response.validation, function(key, message) {
                        $('#' + key).closest('.form-group').append('<p class="text-danger validation-message">' + message + '</p>');
                    });
                }
            }
        });
    }

    function remove(id) {
        swalConfirm('Apakah anda yakin akan menghapus data ini?', function() {
            $.ajax({
                url: '<?= $this->url_generator->current_url() ?>/delete/' + id,
                success: function(response) {
                    if (response.success) {
                        $.growl.notice({
                            message: response.message
                        });
                        dataTable.ajax.reload();
                    } else {
                        $.growl.error({
                            message: response.message
                        });
                    }
                }
            });
        });
    }

    function cancel() {
        bootbox.hideAll();
    }

    function upload(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/upload/' + id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{upload_file}} {{piutang}}',
                    message: response
                });
            }
        });
    }

    function bayar(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/bayar/' + id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{bayar}} {{piutang}}',
                    message: response
                });
            }
        });
    }

    function pelunasan(id) {
        swalConfirm('Apakah anda yakin akan melunasi data piutang ini?', function() {
            $.ajax({
                url: '<?= $this->url_generator->current_url() ?>/pelunasan/' + id,
                success: function(response) {
                    if (response.success) {
                        $.growl.notice({
                            message: response.message
                        });
                        dataTable.ajax.reload();
                    } else {
                        $.growl.error({
                            message: response.message
                        });
                    }
                }
            });
        });
    }

    function batal_pelunasan(id) {
        swalConfirm('Apakah anda yakin akan membatalkan pelunasan data piutang ini?', function() {
            $.ajax({
                url: '<?= $this->url_generator->current_url() ?>/batal_pelunasan/' + id,
                success: function(response) {
                    if (response.success) {
                        $.growl.notice({
                            message: response.message
                        });
                        dataTable.ajax.reload();
                    } else {
                        $.growl.error({
                            message: response.message
                        });
                    }
                }
            });
        });
    }
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>