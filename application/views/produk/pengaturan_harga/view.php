<table width="100%" class="table table-profile">
    <?php if ($model->jenis == 'barang') { ?>
    <tr>
        <td class="field">{{barang}}</td>
        <td><?= $model->barang ?></td>
    </tr>
    <?php } ?>
    <?php if ($model->jenis == 'jasa') { ?>
        <tr>
            <td class="field">{{jasa}}</td>
            <td><?= $model->jasa ?></td>
        </tr>
    <?php } ?>
    <tr>
        <td class="field">{{barcode}}</td>
        <td><?= $model->barcode ?></td>
    </tr>
    <tr>
        <td class="field">{{produk}}</td>
        <td><?= $model->kode ?> - <?= $model->produk ?></td>
    </tr>
    <tr>
        <td class="field">{{ppn}}</td>
        <td><?= $model->ppn_persen ?>%</td>
    </tr>
    <?php if ($model->jenis == 'barang') { ?>
        <tr>
            <td class="field">{{laba}}</td>
            <td><?= $model->laba_persen ?>%</td>
        </tr>
    <?php } ?>
    <?php if ($model->jenis == 'jasa') { ?>
        <tr>
            <td class="field">{{komisi}}</td>
            <td><?= $model->komisi ?>%</td>
        </tr>
    <?php } ?>
</table>

<?php if (isset($model->harga_satuan_utama)) { ?>
    <hr>
    <h4>{{harga_satuan}} : <?= $model->harga_satuan_utama[0]->satuan ?></h4>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th width="100" class="text-center">{{urutan}}</th>
                <th width="100" class="text-center">{{jumlah}}</th>
                <th class="text-center">{{harga}}</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($model->harga_satuan_utama as $harga_satuan_utama) { ?>
                <tr>
                    <td class="text-center"><?= $harga_satuan_utama->urutan ?></td>
                    <td class="text-center"><?= $this->localization->number($harga_satuan_utama->jumlah) ?></td>
                    <td class="text-right"><?= $this->localization->number($harga_satuan_utama->harga) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>

<?php if (isset($model->satuan_konversi)) { ?>
    <?php foreach($model->satuan_konversi as $satuan_konversi) { ?>
        <h4>{{harga_satuan}} : <?= $satuan_konversi->satuan ?></h4>
        <table class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th width="100" class="text-center">{{urutan}}</th>
                    <th width="100" class="text-center">{{jumlah}}</th>
                    <th class="text-center">{{harga}}</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($model->harga_satuan[$satuan_konversi->id]) { ?>
                    <?php foreach($model->harga_satuan[$satuan_konversi->id] as $harga_satuan) { ?>
                        <tr>
                            <td class="text-center"><?= $harga_satuan->urutan ?></td>
                            <td class="text-center"><?= $this->localization->number($harga_satuan->jumlah) ?></td>
                            <td class="text-right"><?= $this->localization->number($harga_satuan->harga) ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
<?php } ?>

<?php if (isset($model->harga)) { ?>
    <hr>
    <h4>{{harga}}</h4>
    <table class="table table-bordered table-condensed">
        <thead>
        <tr>
            <th width="100" class="text-center">{{urutan}}</th>
            <th width="100" class="text-center">{{jumlah}}</th>
            <th class="text-center">{{harga}}</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model->harga as $harga) { ?>
            <tr>
                <td class="text-center"><?= $harga->urutan ?></td>
                <td class="text-center"><?= $this->localization->number($harga->jumlah) ?></td>
                <td class="text-right"><?= $this->localization->number($harga->harga) ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } ?>

<?php if ($model->jenis == 'jasa') { ?>
    <hr>
    <h4>{{komisi_petugas}}</h4>
    <?php foreach($this->cabang_m->scope('auth')->get() as $cabang) { ?>
        <h4>{{cabang}} : <?= $cabang->nama ?></h4>
        <table class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th>{{petugas}}</th>
                    <th width="100" class="text-center">{{komisi}} (%)</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($model->komisi_petugas[$cabang->id] as $komisi_petugas) { ?>
                <tr>
                    <td><?= $komisi_petugas->petugas ?></td>
                    <td class="text-center"><?= $komisi_petugas->komisi ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } ?>
<?php } ?>

<?php if (isset($model->produk_detail)) { ?>
    <hr>
    <h4>{{produk_detail}}</h4>
    <table class="table table-bordered table-condensed">
        <thead>
        <tr>
            <th class="text-center">{{produk}}</th>
            <th width="100" class="text-center">{{satuan}}</th>
            <th width="100" class="text-center">{{jumlah}}</th>
            <th width="150" class="text-center">{{nilai}}</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model->produk_detail as $key => $produk_detail) { ?>
            <tr>
                <td><?= $produk_detail->kode_produk ?> - <?= $produk_detail->nama_produk ?></td>
                <td><?= $produk_detail->satuan ?></td>
                <td class="text-center"><?= $this->localization->number($produk_detail->jumlah) ?></td>
                <td class="text-right"><?= $this->localization->number($produk_detail->nilai) ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } ?>
