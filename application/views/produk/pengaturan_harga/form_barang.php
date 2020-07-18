<div id="frm-message"></div>
<div class="m-t-15">
    <ul class="nav nav-pills" role="tablist">
        <li class="active"><a href="#detail_produk" role="tab" data-toggle="tab">{{detail_produk}}</a></li>
        <li><a href="#pengaturan_harga_general" role="tab" data-toggle="tab">{{pengaturan_harga_general}}</a></li>
        <?php if ($this->form->data('produk_cabang')) { ?>
            <?php foreach ($this->form->data('produk_cabang') as $id_cabang => $produk_cabang) { ?>
                <?= $this->form->hidden('produk_cabang['.$id_cabang.'][id]', $produk_cabang['id'], 'id="cabang_harga-id-'.$id_cabang.'"') ?>
                <?= $this->form->hidden('produk_cabang['.$id_cabang.'][nama]', $produk_cabang['nama'], 'id="cabang_harga-nama-'.$id_cabang.'"') ?>
                <li><a href="#pengaturan_harga_cabang_<?= $id_cabang ?>" role="tab" data-toggle="tab">{{pengaturan_harga_cabang}} <?= $produk_cabang['nama'] ?></a></li>
            <?php } ?>
        <?php } ?>
    </ul>
    <div class="tab-content p-r-0 p-l-0 p-b-0">
        <div role="tabpanel" class="tab-pane active" id="detail_produk">
            <div class="form-group">
                <?= $this->form->hidden('id_ref', null, 'id="id_barang"') ?>
                <?= $this->form->hidden('kode_barang', null, 'id="kode_barang"') ?>
                <?= $this->form->hidden('nama_barang', null, 'id="nama_barang"') ?>
                <?= $this->form->hidden('jenis', 'barang', 'id="jenis"') ?>
                <label>{{barang}}</label>
                <?= $this->form->text('barang', null, 'id="barang" class="form-control" readonly') ?>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{kode}}</label>
                        <?= $this->form->text('kode', null, 'id="kode" class="form-control" readonly') ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{barcode}}</label>
                        <?= $this->form->text('barcode', null, 'id="barcode" class="form-control" readonly') ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>{{produk}}</label>
                <?= $this->form->text('produk', null, 'id="produk" class="form-control" readonly') ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="pengaturan_harga_general">
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
	        <!--<div class="form-group">
		        <label>{{PPn}}</label>
		        <div class="input-group">
			        <?/*= $this->form->number('ppn_persen', null, 'id="ppn_persen" class="form-control text-right" data-input-type="number-format"', "") */?>
			        <span class="input-group-addon">%</span>
		        </div>
	        </div>-->

            <?php if ($this->form->data('harga_satuan_utama')) { ?>
                <hr>
                <i class="fa fa-check-square fa-lg"></i> <?= $model->satuan ?><br><br>
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
                        <?php foreach ($this->form->data('harga_satuan_utama') as $key => $harga_satuan_utama) { ?>
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
                                <?= $this->form->hidden('form_add_harga_satuan_utama_id_satuan', $model->id_satuan, 'id="harga_satuan_utama-id_satuan-0"') ?>
                                <?= $this->form->hidden('form_add_harga_satuan_utama_satuan', $model->satuan, 'id="harga_satuan_utama-satuan-0"') ?>
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
                    <?= $this->form->checkbox('satuan_konversi['.$satuan_konversi['id'].'][satuan_konversi]', $satuan_konversi['id'], ($this->form->data('harga_satuan')[$satuan_konversi['id']]) ? true : false, 'id="satuan_konversi-satuan_konversi-'.$satuan_konversi['id'].'" onclick="harga_add('.$satuan_konversi['id'].')"') ?> <?= $satuan_konversi['satuan'] ?><br><br>
                    <table id="table-harga_satuan-<?= $satuan_konversi['id'] ?>" data-harga_satuan="<?= $satuan_konversi['id'] ?>" class="table table-bordered table-condensed">
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
                            <?php if ($this->form->data('harga_satuan')[$satuan_konversi['id']]) { ?>
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

        <!--HARGA CABANG-->
        <?php if ($this->form->data('produk_cabang')) { ?>
            <?php foreach ($this->form->data('produk_cabang') as $id_cabang => $produk_cabang) { ?>
                <div role="tabpanel" class="tab-pane" id="pengaturan_harga_cabang_<?= $id_cabang ?>">
                    <?= $this->form->checkbox('cabang_harga['.$id_cabang.']', $id_cabang, (isset($this->form->data('cabang_harga')[$id_cabang])) ? true : false, 'id="cabang_harga-id_cabang-'.$id_cabang.'" onclick="harga_cabang_add('.$id_cabang.')"') ?> {{aktifkan_harga_cabang}} <?= $produk_cabang['nama'] ?><br><br>
                    <div id="harga_cabang-<?= $id_cabang ?>" <?= (!isset($this->form->data('cabang_harga')[$id_cabang])) ? 'style="display: none"' : '' ?>>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{harga_minimal}}</label>
                                    <?= $this->form->number('cabang_hpp['.$id_cabang.'][harga_min]', null, 'id="cabang-harga_min-'.$id_cabang.'" class="form-control text-right" data-input-type="number-format" readonly', "") ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{HPP}}</label>
                                    <?= $this->form->number('cabang_hpp['.$id_cabang.'][hpp]', null, 'id="cabang-hpp-'.$id_cabang.'" class="form-control text-right" data-input-type="number-format" readonly', "") ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{harga_maksimal}}</label>
                                    <?= $this->form->number('cabang_hpp['.$id_cabang.'][harga_max]', null, 'id="cabang-harga_max-'.$id_cabang.'" class="form-control text-right" data-input-type="number-format" readonly', "") ?>
                                </div>
                            </div>
	                        <div class="col-md-3">
		                        <div class="form-group">
			                        <label>{{harga_beli_terakhir}}</label>
			                        <?= $this->form->number('cabang_hpp['.$id_cabang.'][harga_beli_terakhir]', null, 'id="cabang-harga_beli_terakhir-'.$id_cabang.'" class="form-control text-right" data-input-type="number-format" readonly', "") ?>
		                        </div>
	                        </div>
                        </div>

                        <hr>
                        <i class="fa fa-check-square fa-lg"></i> <?= $model->satuan ?><br><br>
                        <table id="table-cabang-harga_satuan_utama-<?= $id_cabang ?>" class="table table-bordered table-condensed">
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
	                            <?php if (isset($this->form->data('cabang_harga_satuan_utama')[$id_cabang])) { ?>
	                                <?php foreach ($this->form->data('cabang_harga_satuan_utama')[$id_cabang] as $key => $cabang_harga_satuan_utama) { ?>
	                                    <tr data-row-id="<?= $key ?>">
	                                        <td>
	                                            <?= $this->form->hidden('cabang_harga_satuan_utama['.$id_cabang.']['.$key.'][utama]', null, 'id="cabang-harga_satuan_utama-utama-'.$id_cabang.'-'.$key.'"') ?>
	                                            <?= $this->form->hidden('cabang_harga_satuan_utama['.$id_cabang.']['.$key.'][id_satuan]', null, 'id="cabang-harga_satuan_utama-id_satuan-'.$id_cabang.'-'.$key.'"') ?>
	                                            <?= $this->form->hidden('cabang_harga_satuan_utama['.$id_cabang.']['.$key.'][satuan]', null, 'id="cabang-harga_satuan_utama-satuan-'.$id_cabang.'-'.$key.'"') ?>
	                                            <?= $this->form->number('cabang_harga_satuan_utama['.$id_cabang.']['.$key.'][urutan]', null, 'id="cabang-harga_satuan_utama-urutan-'.$id_cabang.'-'.$key.'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0" '.(($cabang_harga_satuan_utama['utama']) == 1 ? 'readonly' : '').'', "") ?>
	                                        </td>
	                                        <td><?= $this->form->number('cabang_harga_satuan_utama['.$id_cabang.']['.$key.'][jumlah]', null, 'id="cabang-harga_satuan_utama-jumlah-'.$id_cabang.'-'.$key.'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0" '.(($cabang_harga_satuan_utama['utama']) == 1 ? 'readonly' : '').'', "") ?></td>
	                                        <td><?= $this->form->number('cabang_harga_satuan_utama['.$id_cabang.']['.$key.'][margin_persen]', null, 'id="cabang-harga_satuan_utama-margin_persen-'.$id_cabang.'-'.$key.'" onkeyup="set_cabang_harga_utama('.$id_cabang.', '.$key.')" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
		                                    <td id="cabang-harga_satuan_utama-laba_persen-<?= $id_cabang ?>-<?= $key ?>" class="text-center">
			                                    <?php
				                                    $harga_beli_terakhir = $this->localization->number_value($this->form->data('cabang_hpp')[$id_cabang]['harga_beli_terakhir']);
				                                    $harga = $this->localization->number_value($cabang_harga_satuan_utama['harga']);
				                                    $laba = (($harga - $harga_beli_terakhir) / $harga_beli_terakhir) * 100;
			                                    ?>
			                                    <?= $this->localization->number($laba, 2) ?>
		                                    </td>
	                                        <td><?= $this->form->number('cabang_harga_satuan_utama['.$id_cabang.']['.$key.'][harga]', null, 'id="cabang-harga_satuan_utama-harga-'.$id_cabang.'-'.$key.'" onkeyup="set_cabang_laba_utama('.$id_cabang.', '.$key.')" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
	                                        <td><?= ($cabang_harga_satuan_utama['utama']) != 1 ? '<button type="button" class="btn btn-danger btn-sm" onclick="cabang_harga_satuan_utama_remove('.$id_cabang.','.$key.')"><i class="fa fa-trash"></i></button>' : '' ?></td>
	                                    </tr>
	                                <?php } ?>
	                            <?php } else { ?>
		                            <?php $key = time(); ?>
		                            <tr data-row-id="<?= $key ?>">
			                            <td>
				                            <?= $this->form->hidden('cabang_harga_satuan_utama['.$id_cabang.']['.$key.'][utama]', 1, 'id="cabang-harga_satuan_utama-utama-'.$id_cabang.'-'.$key.'"') ?>
				                            <?= $this->form->hidden('cabang_harga_satuan_utama['.$id_cabang.']['.$key.'][id_satuan]', $model->id_satuan, 'id="cabang-harga_satuan_utama-id_satuan-'.$id_cabang.'-'.$key.'"') ?>
				                            <?= $this->form->hidden('cabang_harga_satuan_utama['.$id_cabang.']['.$key.'][satuan]', $model->satuan, 'id="cabang-harga_satuan_utama-satuan-'.$id_cabang.'-'.$key.'"') ?>
				                            <?= $this->form->number('cabang_harga_satuan_utama['.$id_cabang.']['.$key.'][urutan]', 1, 'id="cabang-harga_satuan_utama-urutan-'.$id_cabang.'-'.$key.'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0" readonly', "") ?>
			                            </td>
			                            <td><?= $this->form->number('cabang_harga_satuan_utama['.$id_cabang.']['.$key.'][jumlah]', 1, 'id="cabang-harga_satuan_utama-jumlah-'.$id_cabang.'-'.$key.'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0" readonly', "") ?></td>
			                            <td><?= $this->form->number('cabang_harga_satuan_utama['.$id_cabang.']['.$key.'][margin_persen]', null, 'id="cabang-harga_satuan_utama-margin_persen-'.$id_cabang.'-'.$key.'" onkeyup="set_cabang_harga_utama('.$id_cabang.', '.$key.')" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
			                            <td id="cabang-harga_satuan_utama-laba_persen-<?= $id_cabang ?>-<?= $key ?>" class="text-center"></td>
			                            <td><?= $this->form->number('cabang_harga_satuan_utama['.$id_cabang.']['.$key.'][harga]', null, 'id="cabang-harga_satuan_utama-harga-'.$id_cabang.'-'.$key.'" onkeyup="set_cabang_laba_utama('.$id_cabang.', '.$key.')" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
			                            <td><?= (isset($cabang_harga_satuan_utama['utama']) == 1 ? '<button type="button" class="btn btn-danger btn-sm" onclick="cabang_harga_satuan_utama_remove('.$id_cabang.','.$key.')"><i class="fa fa-trash"></i></button>' : '') ?></td>
		                            </tr>
			                    <?php } ?>
	                            <tr id="form-add-cabang_harga_satuan_utama-<?= $id_cabang ?>">
		                            <td>
			                            <?= $this->form->hidden('form_add_cabang_harga_satuan_utama_id_satuan_'.$id_cabang, $model->id_satuan, 'id="cabang-harga_satuan_utama-id_satuan-'.$id_cabang.'-0"') ?>
			                            <?= $this->form->hidden('form_add_cabang_harga_satuan_utama_satuan_'.$id_cabang, $model->satuan, 'id="cabang-harga_satuan_utama-satuan-'.$id_cabang.'-0"') ?>
			                            <?= $this->form->number('form_add_cabang_harga_satuan_utama_urutan_'.$id_cabang, null, 'id="cabang-harga_satuan_utama-urutan-'.$id_cabang.'-0" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"', "") ?>
		                            </td>
		                            <td><?= $this->form->number('form_add_cabang_harga_satuan_utama_jumlah_'.$id_cabang, null, 'id="cabang-harga_satuan_utama-jumlah-'.$id_cabang.'-0" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"', "") ?></td>
		                            <td><?= $this->form->number('form_add_cabang_harga_satuan_utama_margin_persen_'.$id_cabang, null, 'id="cabang-harga_satuan_utama-margin_persen-'.$id_cabang.'-0" onkeyup="set_cabang_harga_utama('.$id_cabang.', 0)" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
		                            <td id="cabang-harga_satuan_utama-laba_persen-<?= $id_cabang ?>-0" class="text-center"></td>
		                            <td><?= $this->form->number('form_add_cabang_harga_satuan_utama_harga_'.$id_cabang, null, 'id="cabang-harga_satuan_utama-harga-'.$id_cabang.'-0" onkeyup="set_cabang_laba_utama('.$id_cabang.', 0)" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
		                            <td><button type="button" class="btn btn-primary btn-sm" onclick="cabang_harga_satuan_utama_add(<?= $id_cabang ?>)"><i class="fa fa-plus"></i></button></td>
	                            </tr>
                            </tbody>
                        </table>

                        <?php if ($this->form->data('satuan_konversi')) { ?>
                            <?php foreach ($this->form->data('satuan_konversi') as $satuan_konversi) { ?>
                                <hr>
		                        <?= $this->form->hidden('cabang_satuan_konversi['.$id_cabang.']['.$satuan_konversi['id'].'][id]', $satuan_konversi['id'], 'id="satuan_konversi-id-'.$id_cabang.'-'.$satuan_konversi['id'].'"') ?>
		                        <?= $this->form->hidden('cabang_satuan_konversi['.$id_cabang.']['.$satuan_konversi['id'].'][satuan]', $satuan_konversi['satuan'], 'id="satuan_konversi-satuan-'.$id_cabang.'-'.$satuan_konversi['id'].'"') ?>
		                        <?= $this->form->hidden('cabang_satuan_konversi['.$id_cabang.']['.$satuan_konversi['id'].'][grup]', $satuan_konversi['grup'], 'id="satuan_konversi-grup-'.$id_cabang.'-'.$satuan_konversi['id'].'"') ?>
		                        <?= $this->form->hidden('cabang_satuan_konversi['.$id_cabang.']['.$satuan_konversi['id'].'][konversi]', $satuan_konversi['konversi'], 'id="satuan_konversi-konversi-'.$id_cabang.'-'.$satuan_konversi['id'].'"') ?>
                                <?= $this->form->checkbox('cabang_satuan_konversi['.$id_cabang.']['.$satuan_konversi['id'].'][satuan_konversi]', $satuan_konversi['id'], (isset($this->form->data('cabang_harga_satuan')[$id_cabang][$satuan_konversi['id']])) ? true : false, 'id="satuan_konversi-satuan_konversi-'.$id_cabang.'-'.$satuan_konversi['id'].'" onclick="cabang_harga_add('.$id_cabang.','.$satuan_konversi['id'].')"') ?> <?= $satuan_konversi['satuan'] ?><br><br>
                                <table id="table-cabang-harga_satuan-<?= $id_cabang ?>-<?= $satuan_konversi['id'] ?>" class="table table-bordered table-condensed">
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
                                    <?php if (isset($this->form->data('cabang_harga_satuan')[$id_cabang][$satuan_konversi['id']])) { ?>
                                        <?php foreach ($this->form->data('cabang_harga_satuan')[$id_cabang][$satuan_konversi['id']] as $key => $cabang_harga_satuan) { ?>
                                            <tr data-row-id="<?= $key ?>">
                                                <td>
                                                    <?= $this->form->hidden('cabang_harga_satuan['.$id_cabang.']['.$satuan_konversi['id'].']['.$key.'][utama]', null, 'id="cabang-harga_satuan-utama-'.$id_cabang.'-'.$satuan_konversi['id'].'-'.$key.'"') ?>
                                                    <?= $this->form->hidden('cabang_harga_satuan['.$id_cabang.']['.$satuan_konversi['id'].']['.$key.'][id_satuan]', null, 'id="cabang-harga_satuan-id_satuan-'.$id_cabang.'-'.$satuan_konversi['id'].'-'.$key.'"') ?>
                                                    <?= $this->form->hidden('cabang_harga_satuan['.$id_cabang.']['.$satuan_konversi['id'].']['.$key.'][satuan]', null, 'id="cabang-harga_satuan-satuan-'.$id_cabang.'-'.$satuan_konversi['id'].'-'.$key.'"') ?>
                                                    <?= $this->form->hidden('cabang_harga_satuan['.$id_cabang.']['.$satuan_konversi['id'].']['.$key.'][konversi]', NULL, 'id="cabang-harga_satuan-konversi-'.$id_cabang.'-'.$satuan_konversi['id'].'-'.$key.'"') ?>
                                                    <?= $this->form->number('cabang_harga_satuan['.$id_cabang.']['.$satuan_konversi['id'].']['.$key.'][urutan]', null, 'id="cabang-harga_satuan-urutan-'.$id_cabang.'-'.$satuan_konversi['id'].'-'.$key.'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"', "") ?>
                                                </td>
                                                <td><?= $this->form->number('cabang_harga_satuan['.$id_cabang.']['.$satuan_konversi['id'].']['.$key.'][jumlah]', null, 'id="cabang-harga_satuan-jumlah-'.$id_cabang.'-'.$satuan_konversi['id'].'-'.$key.'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"', "") ?></td>
                                                <td><?= $this->form->number('cabang_harga_satuan['.$id_cabang.']['.$satuan_konversi['id'].']['.$key.'][margin_persen]', null, 'id="cabang-harga_satuan-margin_persen-'.$id_cabang.'-'.$satuan_konversi['id'].'-'.$key.'" onkeyup="set_cabang_harga_satuan('.$id_cabang.', '.$satuan_konversi['id'].', '.$key.')" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
	                                            <td id="cabang-harga_satuan-laba_persen-<?= $id_cabang ?>-<?= $satuan_konversi['id'] ?>-<?= $key ?>" class="text-center">
		                                            <?php
			                                            $harga_beli_terakhir = $this->localization->number_value($this->form->data('cabang_hpp')[$id_cabang]['harga_beli_terakhir']);
			                                            $konversi = $this->localization->number_value($cabang_harga_satuan['konversi']);
			                                            $harga = $this->localization->number_value($cabang_harga_satuan['harga']);
			                                            $laba = ((($harga / $konversi) - $harga_beli_terakhir) / $harga_beli_terakhir) * 100;
		                                            ?>
		                                            <?= $this->localization->number($laba, 2) ?>
	                                            </td>
                                                <td><?= $this->form->number('cabang_harga_satuan['.$id_cabang.']['.$satuan_konversi['id'].']['.$key.'][harga]', null, 'id="cabang-harga_satuan-harga-'.$id_cabang.'-'.$satuan_konversi['id'].'-'.$key.'" onkeyup="set_cabang_laba_satuan('.$id_cabang.', '.$satuan_konversi['id'].', '.$key.')" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
                                                <td><button type="button" class="btn btn-danger btn-sm" onclick="cabang_harga_satuan_remove(<?= $id_cabang ?>,<?= $satuan_konversi['id'] ?>,<?= $key ?>)"><i class="fa fa-trash"></i></button></td>
                                            </tr>
                                        <?php } ?>
                                        <tr id="form-add-cabang_harga_satuan-<?= $id_cabang ?>-<?= $satuan_konversi['id'] ?>">
                                            <td>
                                                <?= $this->form->hidden('form_add_cabang_harga_satuan_id_satuan_'.$id_cabang.'_'.$satuan_konversi['id'], $satuan_konversi['id'], 'id="cabang-harga_satuan-id_satuan-'.$id_cabang.'-'.$satuan_konversi['id'].'-0"') ?>
                                                <?= $this->form->hidden('form_add_cabang_harga_satuan_satuan_'.$id_cabang.'_'.$satuan_konversi['id'], $satuan_konversi['satuan'], 'id="cabang-harga_satuan-satuan-'.$id_cabang.'-'.$satuan_konversi['id'].'-0"') ?>
                                                <?= $this->form->hidden('form_add_cabang_harga_satuan_konversi_'.$id_cabang.'_'.$satuan_konversi['id'], $satuan_konversi['konversi'], 'id="cabang-harga_satuan-konversi-'.$id_cabang.'-'.$satuan_konversi['id'].'-0"') ?>
                                                <?= $this->form->number('form_add_cabang_harga_satuan_urutan_'.$id_cabang.'_'.$satuan_konversi['id'], null, 'id="cabang-harga_satuan-urutan-'.$id_cabang.'-'.$satuan_konversi['id'].'-0" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"', "") ?>
                                            </td>
                                            <td><?= $this->form->number('form_add_cabang_harga_satuan_jumlah_'.$id_cabang.'_'.$satuan_konversi['id'], null, 'id="cabang-harga_satuan-jumlah-'.$id_cabang.'-'.$satuan_konversi['id'].'-0" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"', "") ?></td>
                                            <td><?= $this->form->number('form_add_cabang_harga_satuan_margin_persen_'.$id_cabang.'_'.$satuan_konversi['id'], null, 'id="cabang-harga_satuan-margin_persen-'.$id_cabang.'-'.$satuan_konversi['id'].'-0" onkeyup="set_cabang_harga_satuan('.$id_cabang.', '.$satuan_konversi['id'].', 0)" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
	                                        <td id="cabang-harga_satuan-laba_persen-<?= $id_cabang ?>-<?= $satuan_konversi['id'] ?>-0" class="text-center"></td>
                                            <td><?= $this->form->number('form_add_cabang_harga_satuan_harga_'.$id_cabang.'_'.$satuan_konversi['id'], null, 'id="cabang-harga_satuan-harga-'.$id_cabang.'-'.$satuan_konversi['id'].'-0" onkeyup="set_cabang_laba_satuan('.$id_cabang.', '.$satuan_konversi['id'].', 0)" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
                                            <td><button type="button" class="btn btn-primary btn-sm" onclick="cabang_harga_satuan_add(<?= $id_cabang ?>,<?= $satuan_konversi['id'] ?>)"><i class="fa fa-plus"></i></button></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>