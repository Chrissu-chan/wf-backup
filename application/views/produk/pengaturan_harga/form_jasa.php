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
                <?= $this->form->hidden('jenis', 'jasa', 'id="jenis"') ?>
                <label>{{jasa}}</label>
                <?= $this->form->text('jasa', null, 'id="jasa" class="form-control" readonly') ?>
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
            <!--<div class="form-group">
                <label>{{PPn}}</label>
                <div class="input-group">
                    <?/*= $this->form->number('ppn_persen', $this->config->item('default_ppn'), 'id="ppn_persen" class="form-control text-right" data-input-type="number-format"', "") */?>
                    <span class="input-group-addon">%</span>
                </div>
            </div>-->
            <table id="table-harga" class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th width="150" class="text-center">{{urutan}}</th>
                        <th width="150" class="text-center">{{jumlah}}</th>
                        <th class="text-center">{{harga}}</th>
                        <th width="1"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($this->form->data('harga')) { ?>
                        <?php foreach ($this->form->data('harga') as $key => $harga) { ?>
                            <tr data-row-id="<?= $key ?>">
                                <td>
                                    <?= $this->form->hidden('harga['.$key.'][utama]', null, 'id="harga-utama-'.$key.'"') ?>
                                    <?= $this->form->number('harga['.$key.'][urutan]', null, 'id="harga-urutan-'.$key.'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0" '.(($harga['utama']) == 1 ? 'readonly' : '').'', "") ?>
                                </td>
                                <td><?= $this->form->number('harga['.$key.'][jumlah]', null, 'id="harga-jumlah-'.$key.'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0" '.(($harga['utama']) == 1 ? 'readonly' : '').'', "") ?></td>
                                <td><?= $this->form->number('harga['.$key.'][harga]', null, 'id="harga-harga-'.$key.'" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
                                <td><?= ($harga['utama']) != 1 ? '<button type="button" class="btn btn-danger btn-sm" onclick="harga_remove('.$key.')"><i class="fa fa-trash"></i></button>' : '' ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr data-row-id="0">
                            <td>
                                <?= $this->form->hidden('harga[0][utama]', 1, 'id="harga-utama-0"') ?>
                                <?= $this->form->number('harga[0][urutan]', 1, 'id="harga-urutan-0" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0" readonly', "") ?>
                            </td>
                            <td><?= $this->form->number('harga[0][jumlah]', 1, 'id="harga-jumlah-0" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0" readonly', "") ?></td>
                            <td><?= $this->form->number('harga[0][harga]', null, 'id="harga-harga-0" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
                            <td></td>
                        </tr>
                    <?php } ?>
                    <tr id="form-add-harga">
                        <td><?= $this->form->number('form_add_harga_urutan', null, 'id="form-add-harga-urutan" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"', "") ?></td>
                        <td><?= $this->form->number('form_add_harga_jumlah', null, 'id="form-add-harga-jumlah" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"', "") ?></td>
                        <td><?= $this->form->number('form_add_harga_harga', null, 'id="form-add-harga-harga" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
                        <td><button type="button" class="btn btn-primary btn-sm" onclick="harga_add()"><i class="fa fa-plus"></i></button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- HARGA CABANG -->
        <?php if ($this->form->data('produk_cabang')) { ?>
            <?php foreach ($this->form->data('produk_cabang') as $id_cabang => $produk_cabang) { ?>
                <div role="tabpanel" class="tab-pane" id="pengaturan_harga_cabang_<?= $id_cabang ?>">
                    <?= $this->form->checkbox('cabang_harga['.$id_cabang.']', $id_cabang, (isset($this->form->data('cabang_harga')[$id_cabang])) ? true : false, 'id="cabang_harga-id_cabang-'.$id_cabang.'" onclick="harga_cabang_add('.$id_cabang.')"') ?> {{aktifkan_harga_cabang}} <?= $produk_cabang['nama'] ?><br><br>
                    <div id="harga_cabang-<?= $id_cabang ?>" <?= (!isset($this->form->data('cabang_harga')[$id_cabang])) ? 'style="display: none"' : '' ?>>
                        <table id="table-cabang_harga_cabang-<?= $id_cabang ?>" class="table table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th width="150" class="text-center">{{urutan}}</th>
                                    <th width="150" class="text-center">{{jumlah}}</th>
                                    <th class="text-center">{{harga}}</th>
                                    <th width="1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($this->form->data('cabang_harga_cabang')[$id_cabang]) { ?>
                                    <?php foreach ($this->form->data('cabang_harga_cabang')[$id_cabang] as $key => $cabang_harga_cabang) { ?>
                                        <tr data-row-id="<?= $key ?>">
                                            <td>
                                                <?= $this->form->hidden('cabang_harga_cabang['.$id_cabang.']['.$key.'][utama]', null, 'id="cabang_harga_cabang-utama-'.$id_cabang.'-'.$key.'"') ?>
                                                <?= $this->form->number('cabang_harga_cabang['.$id_cabang.']['.$key.'][urutan]', null, 'id="cabang_harga_cabang-urutan-'.$id_cabang.'-'.$key.'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0" '.(($cabang_harga_cabang['utama']) == 1 ? 'readonly' : '').'', "") ?>
                                            </td>
                                            <td><?= $this->form->number('cabang_harga_cabang['.$id_cabang.']['.$key.'][jumlah]', null, 'id="cabang_harga_cabang-jumlah-'.$id_cabang.'-'.$key.'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0" '.(($cabang_harga_cabang['utama']) == 1 ? 'readonly' : '').'', "") ?></td>
                                            <td><?= $this->form->number('cabang_harga_cabang['.$id_cabang.']['.$key.'][harga]', null, 'id="cabang_harga_cabang-harga-'.$id_cabang.'-'.$key.'" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
                                            <td><?= ($cabang_harga_cabang['utama']) != 1 ? '<button type="button" class="btn btn-danger btn-sm" onclick="cabang_harga_cabang_remove('.$id_cabang.','.$key.')"><i class="fa fa-trash"></i></button>' : '' ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr data-row-id="0">
                                        <td>
                                            <?= $this->form->hidden('cabang_harga_cabang['.$id_cabang.'][0][utama]', 1, 'id="cabang_harga_cabang-utama-0"') ?>
                                            <?= $this->form->number('cabang_harga_cabang['.$id_cabang.'][0][urutan]', 1, 'id="cabang_harga_cabang-urutan-0" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0" readonly', "") ?>
                                        </td>
                                        <td><?= $this->form->number('cabang_harga_cabang['.$id_cabang.'][0][jumlah]', 1, 'id="cabang_harga_cabang-jumlah-0" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0" readonly', "") ?></td>
                                        <td><?= $this->form->number('cabang_harga_cabang['.$id_cabang.'][0][harga]', null, 'id="cabang_harga_cabang-harga-0" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
                                        <td></td>
                                    </tr>
                                <?php } ?>
                                <tr id="form-add-cabang_harga_cabang-<?= $id_cabang ?>">
                                    <td><?= $this->form->number('form_add_cabang_harga_cabang_urutan_'.$id_cabang, null, 'id="form-add-cabang_harga_cabang-urutan-'.$id_cabang.'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"', "") ?></td>
                                    <td><?= $this->form->number('form_add_cabang_harga_cabang_jumlah_'.$id_cabang, null, 'id="form-add-cabang_harga_cabang-jumlah-'.$id_cabang.'" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"', "") ?></td>
                                    <td><?= $this->form->number('form_add_cabang_harga_cabang_harga_'.$id_cabang, null, 'id="form-add-cabang_harga_cabang-harga-'.$id_cabang.'" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
                                    <td><button type="button" class="btn btn-primary btn-sm" onclick="cabang_harga_cabang_add(<?= $id_cabang ?>)"><i class="fa fa-plus"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>