<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">
                {{mutasi_kasir}}
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <div class="form-inline">
                <div class="form-group">
                    <label>{{periode}}</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <?= $this->form->date('filter_tanggal_awal', date('01-m-Y'), 'id="filter-tanggal_awal" class="form-control" data-input-type="dateinput"') ?>
                        <span class="input-group-addon">-</i></span>
                        <?= $this->form->date('filter_tanggal_akhir', date('d-m-Y'), 'id="filter-tanggal_akhir" class="form-control" data-input-type="dateinput"') ?>
                    </div>
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
            <table id="data-table" class="table table-bordered table-condensed nowrap">
                <thead>
                    <tr>
                        <th width="150">{{tanggal_mutasi}}</th>
                        <th width="150">{{no_mutasi}}</th>
                        <th width="150">{{no_referensi}}</th>
                        <th width="100">{{tipe}}</th>
                        <th width="150">{{jenis_transaksi}}</th>
                        <th width="150">{{nominal}}</th>
                        <th>{{keterangan}}</th>
                        <th width="1">{{status}}</th>
                        <th width="1"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->section('filter') ?>
    <div class="form-group">
        <label>{{tipe}}</label>
        <?= $this->form->select('filter_tipe', array('' => $this->localization->lang('select'))+$this->mutasi_kasir_m->enum('tipe'), null, 'id="filter-tipe" class="form-control"') ?>
    </div>
    <div id="form-group-filter-jenis_transaksi" class="form-group">

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
            ajax: '<?= $this->route->name('transaksi.mutasi_kasir') ?>?tanggal_awal='+$('#filter-tanggal_awal').val()+'&tanggal_akhir='+$('#filter-tanggal_akhir').val(),
            columns: [
                {data: 'tanggal_mutasi', name: 'mutasi_kasir.tanggal_mutasi'},
                {data: 'no_mutasi', name: 'mutasi_kasir.no_mutasi'},
                {data: 'no_referensi', name: 'mutasi_kasir.no_referensi'},
                {data: 'tipe', name: 'mutasi_kasir.tipe'},
                {data: 'jenis_transaksi', name: 'jenis_transaksi.jenis_transaksi'},
                {data: 'nominal', name: 'mutasi_kasir.nominal', class: 'text-right'},
                {data: 'keterangan', name: 'mutasi_kasir.keterangan'},
                {data: 'batal', name: 'mutasi_kasir.batal', class: 'text-center'},
                {data: '_action', searchable: false, orderable: false, class: 'text-right nowrap'}
            ],
            order : [[0, 'DESC']]
        });
        
        $('#filter-tipe').change(function() {
            var html = '';
            if ($(this).val() == 'pemasukan') {
                html += '<label>{{jenis_transaksi}}</label>';
                html += '<select name="filter_jenis_transaksi" id="filter-jenis_transaksi" class="form-control" data-input-type="select2">';
                html += '<option value="">{{select}}</option>';
                <?php foreach ($this->jenis_transaksi_m->scope('pemasukan')->get() as $jenis_transaksi) { ?>
                    html += '<option value="<?= $jenis_transaksi->id ?>"><?= $jenis_transaksi->jenis_transaksi ?></option>';
                <?php } ?>
                html += '</select>';
            } else if ($(this).val() == 'pengeluaran') {
                html += '<label>{{jenis_transaksi}}</label>';
                html += '<select name="filter_jenis_transaksi" id="filter-jenis_transaksi" class="form-control" data-input-type="select2">';
                html += '<option value="">{{select}}</option>';
                <?php foreach ($this->jenis_transaksi_m->scope('pengeluaran')->get() as $jenis_transaksi) { ?>
                    html += '<option value="<?= $jenis_transaksi->id ?>"><?= $jenis_transaksi->jenis_transaksi ?></option>';
                <?php } ?>
                html += '</select>';
            }
            $('#form-group-filter-jenis_transaksi').html(html);
            $('#form-group-filter-jenis_transaksi').buildForm();
        });
    });

    function view(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/view/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{view}} {{mutasi_kasir}}',
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
                    title: '{{create}} {{mutasi_kasir}}',
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
            contentType:false,
            success: function(response) {
                if (response.success) {
                    $.growl.notice({message: response.message});
                    bootbox.hideAll();
                    dataTable.ajax.reload();
                } else {
                    $.growl.error({message: response.message});
                    if(response.validation) {
                        $.each(response.validation, function(key, message) {
                            $('#'+key).closest('.form-group').append('<p class="text-danger validation-message">'+message+'</p>');
                        });
                    }
                }
            }
        });
    }

    function edit(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/edit/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{edit}} {{mutasi_kasir}}',
                    message: response
                });
            }
        });
    }

    function update(id) {
        $('.validation-message').remove();
        var form = $('#frm-edit')[0];
        var formData = new FormData(form);
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/update/'+id,
            type: 'post',
            enctype: 'multipart/form-data',
            data: formData,
            processData: false,
            contentType:false,
            success: function(response) {
                if (response.success) {
                    $.growl.notice({message: response.message});
                    bootbox.hideAll();
                    dataTable.ajax.reload();
                } else {
                    $.growl.error({message: response.message});
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
        swalConfirm('Apakah anda yakin akan membatalkan transaksi ini?', function() {
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

    $('#btn-filter').click(function() {
        dataTable.ajax.url('<?= $this->url_generator->current_url() ?>?tanggal_awal=' + $('#filter-tanggal_awal').val() + '&tanggal_akhir=' + $('#filter-tanggal_akhir').val()).load();
    });

    function upload(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/upload/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{upload_file}} {{mutasi_kasir}}',
                    message: response
                });
            }
        });
    }

    function filter() {
        dataTable.ajax.url('<?= $this->url_generator->current_url() ?>?tipe='+$('#filter-tipe').val()+'&tanggal_awal=' + $('#filter-tanggal_awal').val() + '&tanggal_akhir=' + $('#filter-tanggal_akhir').val()).load();
    }
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>