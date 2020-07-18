<div id="frm-message"></div>
<div class="form-group">
    <label>{{satuan_konversi}}</label>
    <?= $this->form->hidden('id_satuan_tujuan', $satuan_asal->id, 'id="id_satuan_asal"') ?>
    <?= $this->form->select('id_satuan_asal', lists($this->satuan_m->where('grup', $satuan_asal->grup)->get(), 'id', 'satuan'), null, 'id="id_satuan_tujuan" data-input-type="select2" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{konversi}}</label>
    <div class="input-group">
        <span class="input-group-addon">1 <span id="satuan_tujuan"></span></span>
        <span class="input-group-addon">=</span>
        <?= $this->form->number('konversi', null, 'id="konversi" class="form-control" data-input-type="number-format" data-thousand-separator="false" data-precision="16"', "") ?>
        <span class="input-group-addon"><?= $satuan_asal->satuan ?></span>
    </div>
</div>
<?= $this->form->checkbox('invers', 1, true) ?><label>{{invers}}</label>
<script>
    $(function() {
        $('#id_satuan_tujuan').change(function() {
            $('#satuan_tujuan').html($('#id_satuan_tujuan option:selected').text());
        }).change();
    });
</script>