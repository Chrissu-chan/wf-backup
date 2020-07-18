<div id="frm-message"></div>
<div class="form-group">
    <label>{{jenis_member}}</label>
    <?= $this->form->text('jenis_member', null, 'id="jenis_member" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{biaya}}</label>
    <div class="input-group">
        <span class="input-group-addon"><?= $this->localization->lang('rp') ?></span>
    	<?= $this->form->text('biaya', null, 'id="biaya" class="form-control text-right" data-input-type="number-format" onkeyup="set_ppn()"') ?>
    </div>
</div>
<div class="form-group">
    <label>{{ppn}}</label>
    <div class="row">
    	<div class="col-md-6">
    		<div class="input-group">
		        <span class="input-group-addon"><?= $this->localization->lang('rp') ?></span>
    			<?= $this->form->text('ppn', null, 'id="ppn" class="form-control text-right" data-input-type="number-format" onkeyup="set_ppn_persen()"') ?>
		    </div>
    	</div>
    	<div class="col-md-6">
    		<div class="input-group">
    			<?= $this->form->text('ppn_persen', null, 'id="ppn_persen" class="form-control" data-input-type="number-format" onkeyup="set_ppn()"') ?>
		        <span class="input-group-addon">%</span>
		    </div>
    	</div>
    </div>
</div>
<div class="form-group">
    <label>{{total}}</label>
    <div class="input-group">
        <span class="input-group-addon"><?= $this->localization->lang('rp') ?></span>
        <?= $this->form->text('total', null, 'id="total" class="form-control text-right" data-input-type="number-format" readonly') ?>
    </div>
</div>
<div class="form-group">
    <label>{{masa_aktif}}</label>
    <div class="input-group">
    	<?= $this->form->text('masa_aktif', null, 'id="masa_aktif" class="form-control"') ?>
        <span class="input-group-addon"><?= $this->localization->lang('hari') ?></span>
    </div>
</div>

<script>
	function set_ppn() {
		var biaya = $('#biaya').val();
		var ppn_persen = $('#ppn_persen').val();
		$('#ppn').val((toFloat(ppn_persen)*toFloat(biaya)/100));
        count_total();  
	}

	function set_ppn_persen() {
		var biaya = $('#biaya').val();
		var ppn = $('#ppn').val();
		$('#ppn_persen').val(parseInt((toFloat(ppn)/toFloat(biaya))*100));	
        count_total();	
	}

    function count_total() {
        var biaya = $('#biaya').val();
        var ppn = $('#ppn').val();
        $('#total').val(toFloat(biaya)+toFloat(ppn));
    }
</script>