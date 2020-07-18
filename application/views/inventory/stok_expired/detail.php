<?php $this->template->section('content') ?>
    <h1 class="page-header">
        {{detail}} {{stok_expired}}
    </h1>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= $this->form->model($model, null, null) ?>
            <div class="form-group">
                <label>{{gudang}}</label>
                <?= $this->form->text('gudang', null, 'id="gudang" class="form-control" readonly') ?>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{kode_barang}}</label>
                        <?= $this->form->text('kode_barang', null, 'id="kode_barang" class="form-control" readonly') ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{nama_barang}}</label>
                        <?= $this->form->text('nama_barang', null, 'id="nama_barang" class="form-control" readonly') ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{satuan}}</label>
                        <?= $this->form->text('satuan', null, 'id="satuan" class="form-control" readonly') ?>
                    </div>
                </div>
            </div>
            <?= $this->form->close() ?>
            <div class="m-t-15">
            <ul class="nav nav-pills" role="tablist">
                <li class="active"><a href="#detail" role="tab" data-toggle="tab">{{detail}}</a></li>
                <li><a href="#archives" role="tab" data-toggle="tab">{{arsip}}</a></li>
            </ul>
            <div class="tab-content p-r-0 p-l-0 p-b-0">
                <div role="tabpanel" class="tab-pane active" id="detail">
                    <table width="100%" id="data-table" class="table table-bordered table-condensed nowrap">
                        <thead>
                            <tr>
                                <th width="150">{{tanggal_mutasi}}</th>
                                <th>{{expired}}</th>
                                <th width="150" class="text-right">{{jumlah}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($model->detail as $detail) { ?>
                                <tr>
                                    <td><?= $this->localization->human_date($detail->tanggal_mutasi) ?></td>
                                    <td><?= $this->localization->human_date($detail->expired) ?></td>
                                    <td class="text-right"><?= $this->localization->number($detail->jumlah) ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="archives">
                    <table width="100%" id="data-table" class="table table-bordered table-condensed nowrap">
                        <thead>
                        <tr>
                            <th width="150">{{tanggal_mutasi}}</th>
                            <th>{{expired}}</th>
                            <th width="150" class="text-right">{{jumlah}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($model->archives as $archives) { ?>
                            <tr>
                                <td><?= $this->localization->human_date($archives->tanggal_mutasi) ?></td>
                                <td><?= $this->localization->human_date($archives->expired) ?></td>
                                <td class="text-right"><?= $this->localization->number($archives->jumlah) ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>