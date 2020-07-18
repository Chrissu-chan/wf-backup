<?php $this->template->section('css') ?>
    <link href="<?= base_url('public/plugins/bootstrap-wizard/css/bwizard.min.css') ?>" rel="stylesheet"/>
<?php $this->template->endsection() ?>
<?php $this->template->section('content') ?>
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">{{stock_opname}}</h1>
        </div>
        <div class="col-md-6 text-right">

        </div>
    </div>
    <?php $this->template->view('layouts/partials/message') ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="bwizard clearfix">
                <?php $this->template->view('inventory/stock_opname/partials/step_nav', array('active' => 3)) ?>
                <hr>
                <table width="100%" id="table" class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th width="100">{{id_barang}}</th>
                            <th width="150">{{kode_barang}}</th>
                            <th>{{nama_barang}}</th>
                            <td width="1">{{jumlah}}</td>
                            <td width="1">{{so}}</td>
                            <td width="1">{{stock_akhir}}</td>
                            <td width="100">{{so_by}}</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($stock_opname_detail) { ?>
                            <?php foreach ($stock_opname_detail as $row) { ?>
                                <tr>
                                    <td><?= $row->id_obat ?></td>
                                    <td><?= $row->kode ?></td>
                                    <td><?= $row->nama ?></td>
                                    <td><?= $row->jumlah ?></td>
                                    <td><?= $row->so ?></td>
                                    <td><?= ($row->jumlah + $row->so) ?></td>
                                    <td><?= $row->so_by ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>