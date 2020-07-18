<?= $this->form->open(null, 'id="frm-filter"') ?>
<div class="form-group">
	<label>{{kategori_obat}}</label>
    <?= $this->form->select('kategori_obat', lists($this->kategori_obat_m->get(), 'id', 'kategori_obat'), null, 'id="filter-kategori_obat" class="form-control"') ?>
</div>
<div class="form-group">
	<label>{{fungsi_obat}}</label>
    <?= $this->form->select('fungsi_obat', lists($this->fungsi_obat_m->get(), 'id', 'fungsi'), null, 'id="filter-fungsi_obat" class="form-control"') ?>
</div>
<div class="form-group">
    <button type="button" class="btn btn-success" onclick="filter()">{{filter}}</button> <button type="button" class="btn btn-default" onclick="cancel()">{{cancel}}</button>
</div>
<?= $this->form->close() ?>

