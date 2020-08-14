<div id="frm-message"></div>
<?php $this->template->view('layouts/partials/message') ?>
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">{{unserviced}}</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{no_servis}}</label>
                    <?= $this->form->text('no_servis', null, 'id="no_servis" class="form-control" placeholder="{{auto}}" readonly') ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{tanggal}}</label>
                    <div class="input-group input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <?= $this->form->date('tanggal', date('d-m-Y'), 'id="tanggal" class="form-control"') ?>
                    </div>
                </div>
            </div>
        </div>
        <table id="table-barang" class="table table-condensed">
            <thead>
                <tr>
                    <th>{{barang}}</th>
	                <th width="300">{{satuan}}</th>
                    <th width="100" class="text-center">{{jumlah}}</th>
                    <th width="1"></th>
                </tr>
            </thead>
            <tbody>
	            <tr id="form-add-barang">
		            <td>
			            <?= $this->form->hidden('form_add_barang_id_barang', null, 'id="barang-id_barang-0"') ?>
			            <?= $this->form->hidden('form_add_barang_nama_barang', null, 'id="barang-nama_barang-0"') ?>
			            <?= $this->form->text('form_add_barang_barang', '', 'id="barang-barang-0" class="form-control input-sm"') ?>
		            </td>
		            <td>
			            <?= $this->form->hidden('form_add_barang_id_satuan', null, 'id="barang-id_satuan-0"') ?>
			            <?= $this->form->text('form_add_barang_satuan', '', 'id="barang-satuan-0" class="form-control input-sm"') ?>
		            </td>
		            <td><?= $this->form->number('form_add_barang_jumlah', null, 'id="barang-jumlah-0" class="form-control input-sm text-center" onkeyup="barang_set_subtotal(0)" data-input-type="number-format" data-thousand-separator="false" data-decimal-separator="false" data-precision="0"', "", "", 0) ?></td>
		            <td><button type="button" class="btn btn-primary btn-sm" onclick="barang_add()"><i class="fa fa-plus"></i></button></td>
	            </tr>
                <?php if ($this->form->data('unserviced_detail')) { ?>
                    <?php foreach ($this->form->data('unserviced_detail') as $key => $barang) { ?>
                        <tr data-row-id="<?= $key ?>">
                            <td>
                                <?= $this->form->hidden('unserviced_detail['.$key.'][id_barang]', null, 'id="barang-id_barang-'.$key.'"') ?>
                                <?= $this->form->hidden('unserviced_detail['.$key.'][nama_barang]', null, 'id="barang-nama_barang-'.$key.'"') ?>
                                <?= $barang['nama_barang'] ?>
                            </td>
	                        <td>
		                        <?= $this->form->hidden('unserviced_detail['.$key.'][id_satuan]', null, 'id="barang-id_satuan-'.$key.'"') ?>
		                        <?= $this->form->hidden('unserviced_detail['.$key.'][satuan]', null, 'id="barang-satuan-'.$key.'"') ?>
		                        <?= $barang['satuan'] ?>
	                        </td>
                            <td><?= $this->form->number('unserviced_detail['.$key.'][jumlah]', null, 'id="barang-jumlah-'.$key.'" class="form-control input-sm text-center" onkeyup="barang_set_subtotal('.$key.')" data-input-type="number-format" data-thousand-separator="false" data-decimal-separator="false" data-precision="0"', "", "", 0) ?></td>
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="barang_remove(<?= $key ?>)"><i class="fa fa-trash"></i></button></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>