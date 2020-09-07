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
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>{{satuan}}</th>
                <th width="100" class="text-center">{{konversi}}</th>
                <th width="100" class="text-center">{{satuan_beli}}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <?= $this->form->hidden('satuan_barang[1][id_satuan]', null, 'id="satuan_barang-id_satuan-1" class="form-control input-sm"') ?>
                    <?= $this->form->text('satuan_barang[1][satuan]', null, 'id="satuan_barang-satuan-1" class="form-control input-sm"') ?>
                </td>
                <td><?= $this->form->text('satuan_barang[1][konversi]', 1, 'id="satuan_barang-konversi-1" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-decimal-separator="false" data-precision="0" readonly', "") ?></td>
                <td><?= $this->form->checkbox('satuan_barang[1][satuan_beli]', 1, false, 'id="satuan_barang-satuan_beli-1" class="form-control"') ?></td>
            </tr>
            <tr>
                <td>
                    <?= $this->form->hidden('satuan_barang[2][id_satuan]', null, 'id="satuan_barang-id_satuan-2" class="form-control input-sm"') ?>
                    <?= $this->form->text('satuan_barang[2][satuan]', null, 'id="satuan_barang-satuan-2" class="form-control input-sm"') ?>
                </td>
                <td><?= $this->form->text('satuan_barang[2][konversi]', null, 'id="satuan_barang-konversi-2" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-decimal-separator="false" data-precision="0"', "") ?></td>
                <td><?= $this->form->checkbox('satuan_barang[2][satuan_beli]', 1, false, 'id="satuan_barang-satuan_beli-2" class="form-control"') ?></td>
            </tr>
            <tr>
                <td>
                    <?= $this->form->hidden('satuan_barang[3][id_satuan]', null, 'id="satuan_barang-id_satuan-3" class="form-control input-sm"') ?>
                    <?= $this->form->text('satuan_barang[3][satuan]', null, 'id="satuan_barang-satuan-3" class="form-control input-sm"') ?>
                </td>
                <td><?= $this->form->text('satuan_barang[3][konversi]', null, 'id="satuan_barang-konversi-3" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-decimal-separator="false" data-precision="0"', "") ?></td>
                <td><?= $this->form->checkbox('satuan_barang[3][satuan_beli]', 1, false, 'id="satuan_barang-satuan_beli-3" class="form-control"') ?></td>
            </tr>
            <tr>
                <td>
                    <?= $this->form->hidden('satuan_barang[4][id_satuan]', null, 'id="satuan_barang-id_satuan-4" class="form-control input-sm"') ?>
                    <?= $this->form->text('satuan_barang[4][satuan]', null, 'id="satuan_barang-satuan-4" class="form-control input-sm"') ?>
                </td>
                <td><?= $this->form->text('satuan_barang[4][konversi]', null, 'id="satuan_barang-konversi-4" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-decimal-separator="false" data-precision="0"', "") ?></td>
                <td><?= $this->form->checkbox('satuan_barang[4][satuan_beli]', 1, false, 'id="satuan_barang-satuan_beli-4" class="form-control"') ?></td>
            </tr>
            <tr>
                <td>
                    <?= $this->form->hidden('satuan_barang[5][id_satuan]', null, 'id="satuan_barang-id_satuan-5" class="form-control input-sm"') ?>
                    <?= $this->form->text('satuan_barang[5][satuan]', null, 'id="satuan_barang-satuan-5" class="form-control input-sm"') ?>
                </td>
                <td><?= $this->form->text('satuan_barang[5][konversi]', null, 'id="satuan_barang-konversi-5" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-decimal-separator="false" data-precision="0"', "") ?></td>
                <td><?= $this->form->checkbox('satuan_barang[5][satuan_beli]', 1, false, 'id="satuan_barang-satuan_beli-5" class="form-control"') ?></td>
            </tr>
        </tbody>
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