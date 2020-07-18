<div id="frm-message"></div>
<div class="m-t-15">
    <ul class="nav nav-pills" role="tablist">
        <li class="active"><a href="#detail_paket" role="tab" data-toggle="tab">{{detail_paket}}</a></li>
        <li><a href="#pengaturan_harga" role="tab" data-toggle="tab">{{pengaturan_harga}}</a></li>
        <li><a href="#detail_produk" role="tab" data-toggle="tab">{{detail_produk}}</a></li>
    </ul>
    <div class="tab-content p-r-0 p-l-0 p-b-0">
        <div role="tabpanel" class="tab-pane active" id="detail_paket">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= $this->form->hidden('jenis', 'paket', 'id="jenis"') ?>
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
                        <th class="text-right">{{harga}}</th>
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
        <div role="tabpanel" class="tab-pane" id="detail_produk">
            <table id="table-produk" class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>{{produk}}</th>
                        <th width="200" class="text-center">{{satuan}}</th>
                        <th width="150" class="text-center">{{jumlah}}</th>
                        <th width="200" class="text-right">{{nilai}}</th>
                        <th width="1"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($this->form->data('produk_detail')) { ?>
                        <?php foreach ($this->form->data('produk_detail') as $key => $produk_detail) { ?>
                            <tr data-row-id="<?= $produk_detail['id_produk_detail'] ?>">
                                <td>
                                    <?= $this->form->hidden('produk_detail['.$produk_detail['id_produk_detail'].'][id_produk_detail]', null, 'id="produk-id_produk_detail-'.$produk_detail['id_produk_detail'].'"') ?>
                                    <?= $this->form->hidden('produk_detail['.$produk_detail['id_produk_detail'].'][kode_produk]', null, 'id="produk-kode_produk-'.$produk_detail['id_produk_detail'].'"') ?>
                                    <?= $this->form->hidden('produk_detail['.$produk_detail['id_produk_detail'].'][nama_produk]', null, 'id="produk-nama_produk-'.$produk_detail['id_produk_detail'].'"') ?>
                                    <?= $this->form->hidden('produk_detail['.$produk_detail['id_produk_detail'].'][jenis]', null, 'id="produk-jenis-'.$produk_detail['id_produk_detail'].'"') ?>
                                    <?= $produk_detail['kode_produk'].' - '.$produk_detail['nama_produk'] ?>
                                </td>
                                <td><?= $this->form->select('produk_detail['.$produk_detail['id_produk_detail'].'][id_satuan]', lists($this->produk_m->view('satuan')->where('id_produk', $produk_detail['id_produk_detail'])->get(), 'id', 'satuan', $this->localization->lang('pilih'), 0), null, 'class="form-control input-sm" id="produk-id_satuan-'.$produk_detail['id_produk_detail'].'"') ?></td>
                                <td><?= $this->form->number('produk_detail['.$produk_detail['id_produk_detail'].'][jumlah]', $produk_detail['jumlah'], 'id="produk-jumlah-"'.$produk_detail['id_produk_detail'].' class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"', "") ?></td>
                                <td><?= $this->form->number('produk_detail['.$produk_detail['id_produk_detail'].'][nilai]', $produk_detail['nilai'], 'id="produk-nilai-"'.$produk_detail['id_produk_detail'].' class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
                                <td><button type="button" class="btn btn-danger btn-sm" onclick="produk_remove(<?= $produk_detail['id_produk_detail'] ?>)"><i class="fa fa-trash"></i></button></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    <tr id="form-add-produk">
                        <td>
                            <?= $this->form->hidden('form_add_produk_id_produk_detail', null, 'id="form-add-produk-id_produk_detail"') ?>
                            <?= $this->form->hidden('form_add_produk_kode_produk', null, 'id="form-add-produk-kode_produk"') ?>
                            <?= $this->form->hidden('form_add_produk_nama_produk', null, 'id="form-add-produk-nama_produk"') ?>
                            <?= $this->form->hidden('form_add_produk_jenis', null, 'id="form-add-produk-jenis"') ?>
                            <div class="input-group">
                                <?= $this->form->text('form_add_produk_produk', '', 'id="form-add-produk-produk" class="form-control input-sm"') ?>
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-primary  input-sm" onclick="browse_produk()">...</button>
                                </div>
                            </div>
                        </td>
                        <td><?= $this->form->select('form_add_produk_id_satuan', array('0' => $this->localization->lang('pilih')), null, 'id="form-add-produk-id_satuan" class="form-control input-sm"') ?></td>
                        <td><?= $this->form->number('form_add_produk_jumlah', null, 'id="form-add-produk-jumlah" class="form-control input-sm text-center" data-input-type="number-format" data-thousand-separator="false" data-precision="0"', "") ?></td>
                        <td><?= $this->form->number('form_add_produk_nilai', null, 'id="form-add-produk-nilai" class="form-control input-sm text-right" data-input-type="number-format"', "") ?></td>
                        <td><button type="button" class="btn btn-primary btn-sm" onclick="produk_add()"><i class="fa fa-plus"></i></button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>