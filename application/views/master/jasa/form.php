<div id="frm-message"></div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>{{jasa}}</label>
            <?= $this->form->text('jasa', null, 'id="jasa" class="form-control"') ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>{{kategori_jasa}}</label>
            <?= $this->form->select('id_kategori_jasa', tree_lists($this->kategori_jasa_m->get(), 'id', 'parent_id', 0, 'id', 'kategori_jasa'), null, 'id="id_kategori_jasa" class="form-control"') ?>
        </div>
    </div>
</div>

<table id="table-pemakaian_barang" class="table table-bordered table-condensed">
    <thead>
    <tr>
        <th>{{barang}}</th>
        <th width="150" class="text-center">{{satuan}}</th>
        <th width="100" class="text-center">{{jumlah}}</th>
        <th width="1"></th>
    </tr>
    </thead>
    <tbody>
    <?php if ($this->form->data('pemakaian_barang')) { ?>
        <?php foreach ($this->form->data('pemakaian_barang') as $pemakaian_barang) { ?>
            <tr data-row-id="<?= $pemakaian_barang['id_barang'] ?>">
                <td>
                    <?= $this->form->hidden('pemakaian_barang['.$pemakaian_barang['id_barang'].'][id_barang]', null, 'id="pemakaian_barang-id_barang-'.$pemakaian_barang['id_barang'].'"') ?>
                    <?= $this->form->hidden('pemakaian_barang['.$pemakaian_barang['id_barang'].'][kode_barang]', null, 'id="pemakaian_barang-kode_barang-'.$pemakaian_barang['id_barang'].'"') ?>
                    <?= $this->form->hidden('pemakaian_barang['.$pemakaian_barang['id_barang'].'][nama_barang]', null, 'id="pemakaian_barang-nama_barang-'.$pemakaian_barang['id_barang'].'"') ?>
                    <?= $pemakaian_barang['kode_barang'].' - '.$pemakaian_barang['nama_barang'] ?>
                </td>
                <td>
                    <?= $this->form->hidden('pemakaian_barang['.$pemakaian_barang['id_barang'].'][barang_id_satuan]', null, 'id="pemakaian_barang-barang_id_satuan-'.$pemakaian_barang['id_barang'].'"') ?>
                    <?= $this->form->hidden('pemakaian_barang['.$pemakaian_barang['id_barang'].'][barang_satuan]', null, 'id="pemakaian_barang-barang_satuan-'.$pemakaian_barang['id_barang'].'"') ?>
                    <?= $this->form->select('pemakaian_barang['.$pemakaian_barang['id_barang'].'][id_satuan]', array('' => $this->localization->lang('pilih'), $pemakaian_barang['barang_id_satuan'] => $pemakaian_barang['barang_satuan']) + lists($this->satuan_m->view('satuan')->where('id_satuan_tujuan', $pemakaian_barang['barang_id_satuan'])->get(), 'id', 'satuan'), null, 'class="form-control input-sm" id="pemakaian_barang-id_satuan-'.$pemakaian_barang['id_barang'].'"') ?>
                </td>
                <td>
                    <?= $this->form->number('pemakaian_barang['.$pemakaian_barang['id_barang'].'][jumlah]', null, 'id="pemakaian_barang-jumlah-'.$pemakaian_barang['id_barang'].'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false"', "") ?>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="pemakaian_barang_remove(<?= $pemakaian_barang['id_barang'] ?>)"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        <?php } ?>
    <?php } ?>
    <tr id="form-add-pemakaian_barang">
        <td>
            <?= $this->form->hidden('form_add_pemakaian_barang_id_barang', null, 'id="form-add-pemakaian_barang-id_barang"') ?>
            <?= $this->form->hidden('form_add_pemakaian_barang_kode_barang', null, 'id="form-add-pemakaian_barang-kode_barang"') ?>
            <?= $this->form->hidden('form_add_pemakaian_barang_nama_barang', null, 'id="form-add-pemakaian_barang-nama_barang"') ?>
            <div class="input-group">
                <?= $this->form->text('form_add_pemakaian_barang_barang', '', 'id="form-add-pemakaian_barang-barang" class="form-control input-sm" readonly') ?>
                <div class="input-group-btn">
                    <button type="button" class="btn btn-primary  input-sm" onclick="browse_barang()">...</button>
                </div>
            </div>
        </td>
        <td>
            <?= $this->form->hidden('form_add_pemakaian_barang_barang_id_satuan', null, 'id="form-add-pemakaian_barang-barang_id_satuan"') ?>
            <?= $this->form->hidden('form_add_pemakaian_barang_barang_satuan', null, 'id="form-add-pemakaian_barang-barang_satuan"') ?>
            <?= $this->form->select('form_add_pemakaian_barang_id_satuan', array('' => $this->localization->lang('pilih')), null, 'id="form-add-pemakaian_barang-id_satuan" class="form-control input-sm"') ?>
        </td>
        <td>
            <?= $this->form->number('form_add_pemakaian_barang_jumlah', null, 'id="form-add-pemakaian_barang-jumlah" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false"', "") ?>
        </td>
        <td>
            <button type="button" class="btn btn-primary btn-sm" onclick="pemakaian_barang_add()"><i class="fa fa-plus"></i></button>
        </td>
    </tr>
    </tbody>
</table>