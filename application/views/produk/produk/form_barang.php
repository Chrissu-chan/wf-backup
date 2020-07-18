<div id="frm-message"></div>
<div class="m-t-15">
    <ul class="nav nav-pills" role="tablist">
        <li class="active"><a href="#detail_barang" role="tab" data-toggle="tab">{{detail_barang}}</a></li>
        <li><a href="#pengaturan_harga" role="tab" data-toggle="tab">{{pengaturan_harga}}</a></li>
    </ul>
    <div class="tab-content p-r-0 p-l-0 p-b-0">
        <div role="tabpanel" class="tab-pane active" id="detail_barang">
            <div class="form-group">
                <?= $this->form->hidden('id_ref', null, 'id="id_barang"') ?>
                <?= $this->form->hidden('kode_barang', null, 'id="kode_barang"') ?>
                <?= $this->form->hidden('nama_barang', null, 'id="nama_barang"') ?>
                <?= $this->form->hidden('jenis', 'barang', 'id="jenis"') ?>
                <label>{{barang}}</label>
                <div class="input-group">
                    <?= $this->form->text('barang', '', 'id="barang" class="form-control" readonly') ?>
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-primary" onclick="browse_barang()">...</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{kode}}</label>
                        <?= $this->form->text('kode', null, 'id="kode" class="form-control"') ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{barcode}}</label>
                        <?= $this->form->text('barcode', null, 'id="barcode" class="form-control"') ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{produk}}</label>
                        <?= $this->form->text('produk', null, 'id="produk" class="form-control"') ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{cabang}}</label>
                        <?= $this->form->select('produk_cabang[]', lists($this->cabang_m->get(), 'id', 'nama'), null, 'id="cabang" class="form-control" data-input-type="select2" multiple') ?>
                    </div>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="pengaturan_harga">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>{{harga_minimal}}</label>
                        <?= $this->form->number('harga_min', null, 'id="harga_min" class="form-control text-right" data-input-type="number-format" readonly', "") ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>{{HPP}}</label>
                        <?= $this->form->number('hpp', null, 'id="hpp" class="form-control text-right" data-input-type="number-format" readonly', "") ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>{{harga_maksimal}}</label>
                        <?= $this->form->number('harga_max', null, 'id="harga_max" class="form-control text-right" data-input-type="number-format" readonly', "") ?>
                    </div>
                </div>
	            <div class="col-md-3">
		            <div class="form-group">
			            <label>{{harga_beli_terakhir}}</label>
			            <?= $this->form->number('harga_beli_terakhir', null, 'id="harga_beli_terakhir" class="form-control text-right" data-input-type="number-format" readonly', "") ?>
		            </div>
	            </div>
            </div>
            <!--<div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{PPn}}</label>
                        <div class="input-group">
                            <?/*= $this->form->number('ppn_persen', null, 'id="ppn_persen" class="form-control text-right" data-input-type="number-format"', "") */?>
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{laba}}</label>
                        <div class="input-group">
                            <?/*= $this->form->number('laba_persen', null, 'id="laba_persen" class="form-control text-right" data-input-type="number-format" onkeyup="set_harga_utama()"') */?>
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>
                </div>
            </div>-->

            <div id="pengaturan_harga_satuan">
                <?php if ($rs_harga_satuan_utama = $this->form->data('harga_satuan_utama')) { ?>
                    <hr>
	                <?= $this->form->hidden('satuan', null, 'id="satuan"') ?>
	                <?= $this->form->hidden('id_satuan', null, 'id="id_satuan"') ?>
                    <i class="fa fa-check-square fa-lg"></i> <?= $this->form->data('satuan') ?><br><br>
                    <table id="table-harga_satuan_utama" class="table table-bordered table-condensed">
                        <thead>
	                        <tr>
		                        <th width="150" class="text-center">{{urutan}}</th>
		                        <th width="150" class="text-center">{{jumlah}}</th>
		                        <th width="150" class="text-center">{{margin}}%</th>
		                        <th width="150" class="text-center">{{laba}}%</th>
		                        <th class="text-right">{{harga}}</th>
		                        <th width="1"></th>
	                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rs_harga_satuan_utama as $key => $harga_satuan_utama) { ?>
	                            <tr data-row-id="<?= $key ?>">
		                            <td>
			                            <?= $this->form->hidden('harga_satuan_utama['.$key.'][utama]', null, 'id="harga_satuan_utama-utama-'.$key.'"') ?>
			                            <?= $this->form->hidden('harga_satuan_utama['.$key.'][id_satuan]', null, 'id="harga_satuan_utama-id_satuan-'.$key.'"') ?>
			                            <?= $this->form->hidden('harga_satuan_utama['.$key.'][satuan]', null, 'id="harga_satuan_utama-satuan-'.$key.'"') ?>
			                            <?= $this->form->number('harga_satuan_utama['.$key.'][urutan]', null, 'id="harga_satuan_utama-urutan-'.$key.'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0" '.(($harga_satuan_utama['utama']) == 1 ? 'readonly' : '').'', "") ?>
		                            </td>
		                            <td><?= $this->form->number('harga_satuan_utama['.$key.'][jumlah]', null, 'id="harga_satuan_utama-jumlah-'.$key.'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0" '.(($harga_satuan_utama['utama']) == 1 ? 'readonly' : '').'', "") ?></td>
		                            <td><?= $this->form->number('harga_satuan_utama['.$key.'][margin_persen]', null, 'id="harga_satuan_utama-margin_persen-'.$key.'" onkeyup="set_harga_utama('.$key.')" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
		                            <td id="harga_satuan_utama-laba_persen-<?= $key ?>" class="text-center">
			                            <?php
				                            $harga_beli_terakhir = $this->localization->number_value($this->form->data('harga_beli_terakhir'));
				                            $harga = $this->localization->number_value($harga_satuan_utama['harga']);
				                            $laba = (($harga - $harga_beli_terakhir) / $harga_beli_terakhir) * 100;
			                            ?>
			                            <?= $this->localization->number($laba, 2) ?>
		                            </td>
		                            <td><?= $this->form->number('harga_satuan_utama['.$key.'][harga]', null, 'id="harga_satuan_utama-harga-'.$key.'" onkeyup="set_laba_utama('.$key.')" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
		                            <td><?= ($harga_satuan_utama['utama']) != 1 ? '<button type="button" class="btn btn-danger btn-sm" onclick="harga_satuan_utama_remove('.$key.')"><i class="fa fa-trash"></i></button>' : '' ?></td>
	                            </tr>
                            <?php } ?>
                            <tr id="form-add-harga_satuan_utama">
                                <td>
		                            <?= $this->form->hidden('form_add_harga_satuan_utama_id_satuan', $this->form->data('id_satuan'), 'id="harga_satuan_utama-id_satuan-0"') ?>
		                            <?= $this->form->hidden('form_add_harga_satuan_utama_satuan', $this->form->data('satuan'), 'id="harga_satuan_utama-satuan-0"') ?>
		                            <?= $this->form->number('form_add_harga_satuan_utama_urutan', null, 'id="harga_satuan_utama-urutan-0" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"', "") ?>
	                            </td>
	                            <td><?= $this->form->number('form_add_harga_satuan_utama_jumlah', null, 'id="harga_satuan_utama-jumlah-0" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"', "") ?></td>
	                            <td><?= $this->form->number('form_add_harga_satuan_utama_margin_persen', null, 'id="harga_satuan_utama-margin_persen-0" onkeyup="set_harga_utama(0)" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
	                            <td id="harga_satuan_utama-laba_persen-0" class="text-center"></td>
	                            <td><?= $this->form->number('form_add_harga_satuan_utama_harga', null, 'id="harga_satuan_utama-harga-0" onkeyup="set_laba_utama(0)" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
	                            <td><button type="button" class="btn btn-primary btn-sm" onclick="harga_satuan_utama_add()"><i class="fa fa-plus"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                <?php } ?>

                <?php if ($this->form->data('satuan_konversi')) { ?>
                    <?php foreach ($this->form->data('satuan_konversi') as $satuan_konversi) { ?>
                        <hr>
                        <?= $this->form->hidden('satuan_konversi['.$satuan_konversi['id'].'][id]', $satuan_konversi['id'], 'id="satuan_konversi-id-'.$satuan_konversi['id'].'"') ?>
                        <?= $this->form->hidden('satuan_konversi['.$satuan_konversi['id'].'][satuan]', $satuan_konversi['satuan'], 'id="satuan_konversi-satuan-'.$satuan_konversi['id'].'"') ?>
		                <?= $this->form->hidden('satuan_konversi['.$satuan_konversi['id'].'][grup]', $satuan_konversi['grup'], 'id="satuan_konversi-grup-'.$satuan_konversi['id'].'"') ?>
		                <?= $this->form->hidden('satuan_konversi['.$satuan_konversi['id'].'][konversi]', $satuan_konversi['konversi'], 'id="satuan_konversi-konversi-'.$satuan_konversi['id'].'"') ?>
                        <?= $this->form->checkbox('satuan_konversi['.$satuan_konversi['id'].'][satuan_konversi]', $satuan_konversi['id'], (isset($this->form->data('harga_satuan')[$satuan_konversi['id']])) ? true : false, 'id="satuan_konversi-satuan_konversi-'.$satuan_konversi['id'].'" onclick="harga_add('.$satuan_konversi['id'].')"') ?> <label><?= $satuan_konversi['satuan'] ?></label><br><br>
                        <table id="table-harga_satuan-<?= $satuan_konversi['id'] ?>" class="table table-bordered table-condensed">
                            <thead>
	                            <tr>
		                            <th width="150" class="text-center">{{urutan}}</th>
		                            <th width="150" class="text-center">{{jumlah}}</th>
		                            <th width="150" class="text-center">{{margin}}%</th>
		                            <th width="150" class="text-center">{{laba}}%</th>
		                            <th class="text-right">{{harga}}</th>
		                            <th width="1"></th>
	                            </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($this->form->data('harga_satuan')[$satuan_konversi['id']])) { ?>
                                    <?php foreach ($this->form->data('harga_satuan')[$satuan_konversi['id']] as $key => $harga_satuan) { ?>
		                                <tr data-row-id="<?= $key ?>">
			                                <td>
				                                <?= $this->form->hidden('harga_satuan['.$satuan_konversi['id'].']['.$key.'][utama]', null, 'id="harga_satuan-utama-'.$satuan_konversi['id'].'-'.$key.'"') ?>
				                                <?= $this->form->hidden('harga_satuan['.$satuan_konversi['id'].']['.$key.'][id_satuan]', null, 'id="harga_satuan-id_satuan-'.$satuan_konversi['id'].'-'.$key.'"') ?>
				                                <?= $this->form->hidden('harga_satuan['.$satuan_konversi['id'].']['.$key.'][satuan]', null, 'id="harga_satuan-satuan-'.$satuan_konversi['id'].'-'.$key.'"') ?>
				                                <?= $this->form->hidden('harga_satuan['.$satuan_konversi['id'].']['.$key.'][konversi]', null, 'id="harga_satuan-konversi-'.$satuan_konversi['id'].'-'.$key.'"') ?>
				                                <?= $this->form->number('harga_satuan['.$satuan_konversi['id'].']['.$key.'][urutan]', null, 'id="harga_satuan-urutan-'.$satuan_konversi['id'].'-'.$key.'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"', "") ?>
			                                </td>
			                                <td><?= $this->form->number('harga_satuan['.$satuan_konversi['id'].']['.$key.'][jumlah]', null, 'id="harga_satuan-jumlah-'.$satuan_konversi['id'].'-'.$key.'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"', "") ?></td>
			                                <td><?= $this->form->number('harga_satuan['.$satuan_konversi['id'].']['.$key.'][margin_persen]', null, 'id="harga_satuan-margin_persen-'.$satuan_konversi['id'].'-'.$key.'" onkeyup="set_harga_satuan('.$satuan_konversi['id'].', '.$key.')" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
			                                <td id="harga_satuan-laba_persen-<?= $satuan_konversi['id'] ?>-<?= $key ?>" class="text-center">
				                                <?php
					                                $harga_beli_terakhir = $this->localization->number_value($this->form->data('harga_beli_terakhir'));
					                                $harga = $this->localization->number_value($harga_satuan['harga']);
					                                $konversi = $this->localization->number_value($harga_satuan['konversi']);
					                                $laba = ((($harga / $konversi) - $harga_beli_terakhir) / $harga_beli_terakhir) * 100;
				                                ?>
				                                <?= $this->localization->number($laba, 2) ?>
			                                </td>
			                                <td><?= $this->form->number('harga_satuan['.$satuan_konversi['id'].']['.$key.'][harga]', null, 'id="harga_satuan-harga-'.$satuan_konversi['id'].'-'.$key.'" onkeyup="set_laba_satuan('.$satuan_konversi['id'].', '.$key.')" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
			                                <td><button type="button" class="btn btn-danger btn-sm" onclick="harga_satuan_remove(<?= $satuan_konversi['id'] ?>, <?= $key ?>)"><i class="fa fa-trash"></i></button></td>
		                                </tr>
                                    <?php } ?>
                                    <tr id="form-add-harga_satuan-<?= $satuan_konversi['id'] ?>">
	                                    <td>
		                                    <?= $this->form->hidden('form_add_harga_satuan_id_satuan_'.$satuan_konversi['id'], $satuan_konversi['id'], 'id="harga_satuan-id_satuan-'.$satuan_konversi['id'].'-0"') ?>
		                                    <?= $this->form->hidden('form_add_harga_satuan_satuan_'.$satuan_konversi['id'], $satuan_konversi['satuan'], 'id="harga_satuan-satuan-'.$satuan_konversi['id'].'-0"') ?>
		                                    <?= $this->form->hidden('form_add_harga_satuan_konversi_'.$satuan_konversi['id'], $satuan_konversi['konversi'], 'id="harga_satuan-konversi-'.$satuan_konversi['id'].'-0"') ?>
		                                    <?= $this->form->number('form_add_harga_satuan_urutan_'.$satuan_konversi['id'], null, 'id="harga_satuan-urutan-'.$satuan_konversi['id'].'-0" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"', "") ?>
	                                    </td>
	                                    <td><?= $this->form->number('form_add_harga_satuan_jumlah_'.$satuan_konversi['id'], null, 'id="harga_satuan-jumlah-'.$satuan_konversi['id'].'-0" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"', "") ?></td>
	                                    <td><?= $this->form->number('form_add_harga_satuan_margin_persen_'.$satuan_konversi['id'], null, 'id="harga_satuan-margin_persen-'.$satuan_konversi['id'].'-0" onkeyup="set_harga_satuan('.$satuan_konversi['id'].', 0)" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
	                                    <td id="harga_satuan-laba_persen-<?= $satuan_konversi['id'] ?>-0" class="text-center"></td>
	                                    <td><?= $this->form->number('form_add_harga_satuan_harga_'.$satuan_konversi['id'], null, 'id="harga_satuan-harga-'.$satuan_konversi['id'].'-0" onkeyup="set_laba_satuan('.$satuan_konversi['id'].', 0)" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
	                                    <td><button type="button" class="btn btn-primary btn-sm" onclick="harga_satuan_add(<?= $satuan_konversi['id'] ?>)"><i class="fa fa-plus"></i></button></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>