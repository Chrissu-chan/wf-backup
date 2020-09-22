<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-4">
            <h1 class="page-header">
                {{obat}}
            </h1>
        </div>
        <div class="col-md-8 text-right">
            <div class="form-inline">
                <div class="form-group">
                    <label>{{kategori}}</label>
                    <?= $this->form->select('filter_kategori', lists($this->kategori_barang_m->get(), 'id', 'kategori_barang', $this->localization->lang('pilih_kategori_barang')), null, 'id="filter-kategori" class="form-control"') ?>
                </div>
                <div class="form-group">
                    <label>{{jenis}}</label>
                    <?= $this->form->select('filter_jenis', lists($this->jenis_barang_m->get(), 'id', 'jenis_barang', $this->localization->lang('pilih_jenis_barang')), null, 'class="form-control" id="filter-jenis"') ?>
                </div>
                <div class="form-group">
                    <button type="button" id="btn-filter" class="btn btn-default">{{filter}}</button>
                </div>
                <div class="form-group">
                    <div class="btn-group">
                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{export_import}} <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><?= $this->action->link('import', $this->url_generator->current_url().'/download_format', null, $this->localization->lang('download_format')) ?></li>
                            <li><?= $this->action->link('import', 'javascript:void(0)', 'onclick="upload()"') ?></li>
                            <li><?= $this->action->link('export', $this->url_generator->current_url().'/export') ?></li>
                        </ul>
                    </div>
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
            <table id="data-table" class="table table-bordered table-condensed  nowrap">
                <thead>
                    <tr>
                        <th>{{kode}}</th>
                        <th>{{barcode}}</th>
                        <th>{{nama}}</th>
                        <th>{{kategori_barang}}</th>
                        <th>{{jenis_barang}}</th>
                        <th>{{satuan_barang}}</th>
                        <th>{{satuan_beli}}</th>
                        <th>{{jenis_obat}}</th>
                        <th>{{kategori_obat}}</th>
                        <th>{{fungsi_obat}}</th>
                        <th>{{kandungan_obat}}</th>
                        <th>{{produsen}}</th>
                        <th>{{minus}}</th>
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
	        searchDelay: 1500,
            scrollX: true,
            ajax: '<?= $this->url_generator->current_url() ?>',
            columns: [
                {data: 'kode', name: 'barang.kode'},
                {data: 'barcode', name: 'barang.barcode'},
                {data: 'nama', name: 'barang.nama'},
                {data: 'kategori_barang', name: 'kategori_barang.kategori_barang'},
                {data: 'jenis_barang', name: 'jenis_barang.jenis_barang'},
                {data: 'satuan', name: 'satuan.satuan'},
                {data: 'satuan_beli', name: 'satuan.satuan'},
                {data: 'jenis_obat', name: 'jenis_obat.jenis_obat'},
                {data: 'kategori_obat', name: 'barang.kategori_obat', searchable: false, orderable: false},
                {data: 'fungsi_obat', name: 'fungsi_obat', searchable: false, orderable: false},
                {data: 'kandungan_obat', name: 'obat.kandungan_obat'},
                {data: 'nama_produsen', name: 'produsen.nama_produsen'},
                {data: 'minus', name: 'barang.minus', class: 'text-center'},
                {data: '_action', searchable: false, orderable: false, class: 'text-right'}
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
                    title: '{{view}} {{obat}}',
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
                    title: '{{create}} {{obat}}',
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
                    title: '{{edit}} {{obat}}',
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
                    title: '{{import}} {{barang}}',
                    message: response
                });
            }
        });
    }
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>