<div id="frm-message"></div>
<div class="form-group">
    <label>{{kode}}</label>
	<?= $this->form->text('kode', $data, 'id="kode" class="form-control" readonly '.$this->form->disabled(array('edit'))) ?>
</div>
<div class="form-group">
    <label>{{barcode}}</label>
    <?= $this->form->text('barcode', null, 'id="barcode" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{nama}}</label>
    <?= $this->form->text('nama', null, 'id="nama" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{kategori_barang}}</label>
    <?= $this->form->select('id_kategori_barang', lists($this->kategori_barang_m->get(), 'id', 'kategori_barang', TRUE), null, 'class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{jenis_barang}}</label>
    <?= $this->form->select('id_jenis_barang', lists($this->jenis_barang_m->get(), 'id', 'jenis_barang', TRUE), null, 'id="id_jenis_barang" class="form-control"') ?>
</div>
<div class="form-group">
    <table id="table-satuan_barang" class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>{{satuan}}</th>
                <th width="100" class="text-center">{{konversi}}</th>
                <th width="100" class="text-center">{{satuan_beli}}</th>
                <th width="1"></th>
            </tr>
        </thead>
        <tbody>
            <?php if ($this->form->data('satuan_barang')) { ?>
                <?php foreach ($this->form->data('satuan_barang') as $key => $satuan_barang) { ?>
                    <tr data-row-id="<?= $key ?>">
                        <td>
                            <?= $this->form->hidden('satuan_barang['.$key.'][id_satuan]', NULL, 'id="satuan_barang-id_satuan-'.$key.'" class="form-control input-sm"') ?>
                            <?= $this->form->text('satuan_barang['.$key.'][satuan]', NULL, 'id="satuan_barang-satuan-'.$key.'" class="form-control input-sm"') ?>
                        </td>
                        <td><?= $this->form->text('satuan_barang['.$key.'][konversi]', NULL, 'id="satuan_barang-konversi-'.$key.'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-decimal-separator="false" data-precision="0" '.($key == 0 ? 'readonly' : ''), "") ?></td>
                        <td><?= $this->form->checkbox('satuan_barang['.$key.'][satuan_beli]', 1, FALSE, 'id="satuan_barang-satuan_beli-'.$key.'" class="form-control"') ?></td>
                        <td>
                            <?php if ($key != 0) { ?>
                                <button type="button" class="btn btn-danger btn-sm" onclick="satuan_barang_remove(<?= $key ?>)"><i class="fa fa-trash"></i></button>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr data-row-id="0">
                    <td>
                        <?= $this->form->hidden('satuan_barang[0][id_satuan]', NULL, 'id="satuan_barang-id_satuan-0" class="form-control input-sm"') ?>
                        <?= $this->form->text('satuan_barang[0][satuan]', NULL, 'id="satuan_barang-satuan-0" class="form-control input-sm"') ?>
                    </td>
                    <td><?= $this->form->text('satuan_barang[0][konversi]', 1, 'id="satuan_barang-konversi-0" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-decimal-separator="false" data-precision="0" readonly', "") ?></td>
                    <td><?= $this->form->checkbox('satuan_barang[0][satuan_beli]', 1, FALSE, 'id="satuan_barang-satuan_beli-0" class="form-control"') ?></td>
                    <td></td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr id="form-add-satuan_barang">
                <td><?= $this->form->text('form_add_satuan_barang_satuan', NULL, 'id="form-add-satuan_barang-satuan" class="form-control input-sm"') ?></td>
                <td><?= $this->form->text('form_add_satuan_barang_konversi', NULL, 'id="form-add-satuan_barang-konversi" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-decimal-separator="false" data-precision="0"', "") ?></td>
                <td><?= $this->form->checkbox('form_add_satuan_barang_satuan_beli', 1, FALSE, 'id="form-add-satuan_barang-satuan_beli" class="form-control"') ?></td>
                <td><button type="button" class="btn btn-primary btn-sm" onclick="satuan_barang_add()"><i class="fa fa-plus"></i></button></td>
            </tr>
        </tfoot>
    </table>
</div>
<div class="form-group">
    <label>{{jenis_obat}}</label>
    <?= $this->form->select('id_jenis_obat', lists($this->jenis_obat_m->get(), 'id', 'jenis_obat', TRUE), null, 'id="id_jenis_obat" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{kategori_obat}}</label>
    <?= $this->form->select('kategori_obat[]', lists($this->kategori_obat_m->get(), 'id', 'kategori_obat'), null, 'id="id_kategori_obat" class="form-control" data-input-type="select2" multiple style="width:100%"') ?>
</div>
<div class="form-group">
    <label>{{fungsi_obat}}</label>
    <?= $this->form->select('fungsi_obat[]', lists($this->fungsi_obat_m->get(), 'id', 'fungsi_obat'), null, 'id="id_fungsi_obat" class="form-control" data-input-type="select2" multiple style="width:100%"') ?>
</div>
<div class="form-group">
    <label>{{kandungan_obat}}</label>
    <?= $this->form->textarea('kandungan_obat', null, 'id="kandungan_obat" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{dosis}}</label>
    <?= $this->form->number('dosis', null, 'id="barcode" class="form-control text-right" data-input-type="number-format" data-thousand-separator="false"', "") ?>
</div>
<div class="form-group">
    <?= $this->form->checkbox('minus', 1, TRUE) ?><label>{{stok_minus}}</label>
</div>
<hr>
<div class="form-group">
    <label>{{hna}}</label>
    <?= $this->form->number('hpp', null, 'id="hpp" onkeyup="set_hna()" class="form-control text-right" data-input-type="number-format"', "") ?>
</div>
<div class="form-group">
	<label>{{ppn}}</label>
	<div class="input-group input-group">
		<?= $this->form->number('ppn_persen', 10, 'id="ppn_persen" onkeyup="set_hna()" class="form-control text-right" data-input-type="number-format" readonly', "") ?>
		<span class="input-group-addon">%</span>
	</div>
</div>
<div class="form-group">
	<label>{{hna}}+{{ppn}}</label>
	<?= $this->form->number('hna', null, 'id="hna" class="form-control text-right" data-input-type="number-format"', "") ?>
</div>
<div class="form-group">
    <label>{{diskon}}</label>
    <div class="input-group input-group">
        <?= $this->form->number('diskon_persen', null, 'id="diskon_persen" onkeyup="set_total()" class="form-control text-right" data-input-type="number-format"', "") ?>
        <span class="input-group-addon">%</span>
    </div>
</div>
<div class="form-group">
    <label>{{total}}</label>
    <?= $this->form->number('total', null, 'id="total" onkeyup="set_hpp()" class="form-control text-right" data-input-type="number-format"', "") ?>
</div>