<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">
                {{produk}}
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <div class="form-group">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{create}} <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><?= $this->action->link('create', $this->url_generator->current_url().'/create/barang', null, $this->localization->lang('produk_barang')) ?></li>
                        <li><?= $this->action->link('create', $this->url_generator->current_url().'/create/jasa', null, $this->localization->lang('produk_jasa')) ?></li>
                        <li><?= $this->action->link('create', $this->url_generator->current_url().'/create/paket', null, $this->localization->lang('produk_paket')) ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <table width="100%" id="data-table" class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th width="150">{{kode}}</th>
                        <th width="150">{{barcode}}</th>
                        <th>{{produk}}</th>
                        <th width="100">{{jenis}}</th>
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
            columns: [
                {data: 'kode', name: 'produk.kode'},
                {data: 'barcode', name: 'produk.barcode'},
                {data: 'produk', name: 'produk.produk'},
                {data: 'jenis', name: 'produk.jenis'},
                {data:'_action', searchable: false, orderable: false, class: 'text-right nowrap'}
            ]
        });
    });

    function view(id) {
        $.ajax({
            url: '<?= $this->url_generator->current_url() ?>/view/'+id,
            success: function(response) {
                bootbox.dialog({
                    title: '{{view}} {{produk}}',
                    message: response
                });
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
</script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>