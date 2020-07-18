<div class="form-group">
    <label>{{barang}}</label>
	<?= $this->form->hidden('barang_id_satuan', null, 'id="barang_id_satuan"') ?>
	<?= $this->form->hidden('satuan', null, 'id="satuan"') ?>
    <?= $this->form->select('id_barang', lists($this->barang_m->get(), 'id', function($model) { return $model->kode.' - '.$model->nama; }, 'pilih_barang'), null, 'id="id_barang" class="form-control" data-input-type="select2" style="width:100%"') ?>
</div>
<div class="form-group">
    <label>{{nama}}</label>
    <?= $this->form->text('nama', null, 'id="nama" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{satuan_barang}}</label>
    <?= $this->form->select('id_satuan',
	    ($this->form->data('barang_id_satuan') ? array('0' => $this->localization->lang('pilih'), $this->form->data('barang_id_satuan') => $this->form->data('satuan')) + lists($this->satuan_m->view('satuan')->where('id_satuan_asal', $this->form->data('barang_id_satuan'))->get(), 'id', 'satuan') : array('0' => $this->localization->lang('pilih'))),
	    null, 'id="id_satuan" class="form-control"') ?>
</div>
<hr>
<h4>{{bahan_baku}}</h4>
<table id="table-bahan_baku" class="table table-bordered table-condensed">
    <thead>
        <tr>
            <th>{{barang}}</th>
            <th width="150" class="text-center">{{satuan}}</th>
            <th width="150" class="text-center">{{jumlah}}</th>
            <th width="1"></th>
        </tr>
    </thead>
    <tbody>
        <?php if ($this->form->data('bahan_baku')) { ?>
            <?php foreach ($this->form->data('bahan_baku') as $bahan_baku) { ?>
                <tr data-row-id="<?= $bahan_baku['id_barang'] ?>">
                    <td>
                        <?= $this->form->hidden('bahan_baku['.$bahan_baku['id_barang'].'][id_barang]', null, 'id="bahan_baku-id_barang-'.$bahan_baku['id_barang'].'"') ?>
                        <?= $this->form->hidden('bahan_baku['.$bahan_baku['id_barang'].'][kode_barang]', null, 'id="bahan_baku-kode_barang-'.$bahan_baku['id_barang'].'"') ?>
                        <?= $this->form->hidden('bahan_baku['.$bahan_baku['id_barang'].'][nama_barang]', null, 'id="bahan_baku-nama_barang-'.$bahan_baku['id_barang'].'"') ?>
                        <?= $bahan_baku['kode_barang'].' - '.$bahan_baku['nama_barang'] ?>
                    </td>
                    <td>
                        <?= $this->form->hidden('bahan_baku['.$bahan_baku['id_barang'].'][barang_id_satuan]', null, 'id="bahan_baku-barang_id_satuan-'.$bahan_baku['id_barang'].'"') ?>
                        <?= $this->form->hidden('bahan_baku['.$bahan_baku['id_barang'].'][barang_satuan]', null, 'id="bahan_baku-barang_satuan-'.$bahan_baku['id_barang'].'"') ?>
                        <?= $this->form->select('bahan_baku['.$bahan_baku['id_barang'].'][id_satuan]', array('' => $this->localization->lang('pilih'), $bahan_baku['barang_id_satuan'] => $bahan_baku['barang_satuan']) + lists($this->satuan_m->view('satuan')->where('id_satuan_asal', $bahan_baku['barang_id_satuan'])->get(), 'id', 'satuan'), null, 'class="form-control input-sm" id="bahan_baku-id_satuan-'.$bahan_baku['id_barang'].'"') ?>
                    </td>
                    <td>
                        <?= $this->form->number('bahan_baku['.$bahan_baku['id_barang'].'][jumlah]', null, 'id="bahan_baku-jumlah-'.$bahan_baku['id_barang'].'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false"', "") ?>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="bahan_baku_remove(<?= $bahan_baku['id_barang'] ?>)"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
        <tr id="form-add-bahan_baku">
            <td>
                <?= $this->form->hidden('form_add_bahan_baku_id_barang', null, 'id="form-add-bahan_baku-id_barang"') ?>
                <?= $this->form->hidden('form_add_bahan_baku_kode_barang', null, 'id="form-add-bahan_baku-kode_barang"') ?>
                <?= $this->form->hidden('form_add_bahan_baku_nama_barang', null, 'id="form-add-bahan_baku-nama_barang"') ?>
                <div class="input-group">
                    <?= $this->form->text('form_add_bahan_baku_barang', '', 'id="form-add-bahan_baku-barang" class="form-control input-sm" readonly') ?>
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-primary  input-sm" onclick="browse_barang()">...</button>
                    </div>
                </div>
            </td>
            <td>
                <?= $this->form->hidden('form_add_bahan_baku_barang_id_satuan', null, 'id="form-add-bahan_baku-barang_id_satuan"') ?>
                <?= $this->form->hidden('form_add_bahan_baku_barang_satuan', null, 'id="form-add-bahan_baku-barang_satuan"') ?>
                <?= $this->form->select('form_add_bahan_baku_id_satuan', array('' => $this->localization->lang('pilih')), null, 'id="form-add-bahan_baku-id_satuan" class="form-control input-sm"') ?>
            </td>
            <td>
                <?= $this->form->number('form_add_bahan_baku_jumlah', null, 'id="form-add-bahan_baku-jumlah" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false"', "") ?>
            </td>
            <td>
                <button type="button" class="btn btn-primary btn-sm" onclick="bahan_baku_add()"><i class="fa fa-plus"></i></button>
            </td>
        </tr>
    </tbody>
</table>