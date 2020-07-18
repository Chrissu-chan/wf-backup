<div id="frm-message"></div>
<table id="table-barang" class="table table-condensed table-bordered">
    <thead>
        <tr>
            <th>{{barang}}</th>
            <th width="250">{{barcode}}</th>
            <th width="150" class="text-center">{{jumlah}}</th>
            <th width="1"></th>
        </tr>
    </thead>
    <tbody>
        <?php if ($this->form->data('pembelian_barang')) { ?>
            <?php foreach ($this->form->data('pembelian_barang') as $key => $barang) { ?>
                <tr data-row-id="<?= $key ?>">
                    <td>
                        <?= $this->form->hidden('barang['.$key.'][id_barang]', null, 'id="barang-id_barang-'.$key.'"') ?>
                        <?= $this->form->hidden('barang['.$key.'][kode_barang]', null, 'id="barang-kode_barang-'.$key.'"') ?>
                        <?= $this->form->hidden('barang['.$key.'][nama_barang]', null, 'id="barang-nama_barang-'.$key.'"') ?>
                        <?= $this->form->hidden('barang['.$key.'][barcode]', null, 'id="barang-barcode-'.$key.'"') ?>
                        <?= $barang['kode_barang'].' - '.$barang['nama_barang'] ?>
                    </td>
                    <td><?= $barang['barcode'] ?></td>
                    <td><?= $this->form->number('barang['.$key.'][jumlah]', null, 'id="barang-jumlah-'.$key.'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false"', "") ?></td>
                    <td><button type="button" class="btn btn-danger btn-sm" onclick="barang_remove(<?= $key ?>)"><i class="fa fa-trash"></i></button></td>
                </tr>
            <?php } ?>
        <?php } ?>
        <tr id="form-add-barang">
            <td>
                <?= $this->form->hidden('form_add_barang_id_barang', null, 'id="barang-id_barang-0"') ?>
                <?= $this->form->hidden('form_add_barang_kode_barang', null, 'id="barang-kode_barang-0"') ?>
                <?= $this->form->hidden('form_add_barang_nama_barang', null, 'id="barang-nama_barang-0"') ?>
                <div class="input-group">
                    <?= $this->form->text('form_add_barang_barang', '', 'id="barang-barang-0" class="form-control input-sm" readonly') ?>
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-primary input-sm" onclick="browse_barang()">...</button>
                    </div>
                </div>
            </td>
            <td><?= $this->form->text('form_add_barang_barcode', NULL, 'id="barang-barcode-0" class="form-control input-sm" readonly') ?></td>
            <td><?= $this->form->number('form_add_barang_jumlah', null, 'id="barang-jumlah-0" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-decimal-separator="false" data-precision="0"', "") ?></td>
            <td><button type="button" class="btn btn-primary btn-sm" onclick="barang_add()"><i class="fa fa-plus"></i></button></td>
        </tr>
    </tbody>
</table>
