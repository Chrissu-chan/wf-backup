<?php $this->template->section('content') ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="m-t-15">
            <ul class="nav nav-pills" role="tablist">
                <li class="active"><a href="#general" role="tab" data-toggle="tab">{{shift}}</a></li>
                <li><a href="#stok" role="tab" data-toggle="tab">{{stok}}</a></li>
            </ul>
            <div class="tab-content p-r-0 p-l-0 p-b-0">
                <div role="tabpanel" class="tab-pane active" id="general">
                    <h1 class="text-center"><?= date('H:i:s') ?></h1>
                    <hr>
                    <?= $this->form->open($this->route->name('transaksi.shift_aktif.open_save'), 'id="frm-shift_aktif"') ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{name}}</label>
                                <?= $this->form->text('name', $this->auth->name, 'id="name" class="form-control" readonly') ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{shift}}</label>
                                <?= $this->form->select('id_shift_waktu', lists($rs_shift_waktu, 'id', 'shift_waktu'), ($r_shift_aktif ? $r_shift_aktif->id : ''), 'id="id_shift_waktu" class="form-control"') ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{uang_awal}}</label>
                        <?= $this->form->text('uang_awal', null, 'id="uang_awal" class="form-control input-lg text-right" data-input-type="number-format" style="font-size: 20px"') ?>
                    </div>
                    <div class="form-group">
                        <button type="button" id="btn-open_shift" onclick="open_shift()" class="btn btn-success btn-block">{{open_shift}}</button>
                    </div>
                    <?= $this->form->close() ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="stok">
                    <table id="stok" class="table table-bordered table-condedsed table-responsive">
                        <thead>
                            <tr>
                                <th width="150">{{gudang}}</th>
                                <th width="150">{{kode_barang}}</th>
                                <th>{{nama_barang}}</th>
                                <th width="150">{{satuan}}</th>
                                <th width="150">{{stok}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($rs_barang_stok) { ?>
                                <?php foreach ($rs_barang_stok as $r_barang_stok) { ?>
                                    <tr>
                                        <td><?= $r_barang_stok->gudang ?></td>
                                        <td><?= $r_barang_stok->kode_barang ?></td>
                                        <td><?= $r_barang_stok->nama_barang ?></td>
                                        <td><?= $r_barang_stok->satuan ?></td>
                                        <td class="text-right"><?= $this->localization->number($r_barang_stok->jumlah) ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->template->endsection() ?>

<?php $this->template->section('page_script') ?>
    <script>
        function open_shift() {
            swalConfirm('Apakah anda yakin akan memulai shift?', function() {
                $('#frm-shift_aktif').submit();
            });
        }
    </script>
<?php $this->template->endsection() ?>

<?php $this->template->view('layouts/main') ?>