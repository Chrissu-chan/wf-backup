<div id="frm-message"></div>
<div class="m-t-15">
    <ul class="nav nav-pills" role="tablist">
        <li class="active"><a href="#detail_jasa" role="tab" data-toggle="tab">{{detail_jasa}}</a></li>
        <li><a href="#pengaturan_harga" role="tab" data-toggle="tab">{{pengaturan_harga}}</a></li>
        <li><a href="#komisi" role="tab" data-toggle="tab">{{komisi}}</a></li>
    </ul>
    <div class="tab-content p-r-0 p-l-0 p-b-0">
        <div role="tabpanel" class="tab-pane active" id="detail_jasa">
            <div class="form-group">
                <?= $this->form->hidden('jenis', 'jasa', 'id="jenis"') ?>
                <label>{{jasa}}</label>
                <?= $this->form->select('id_ref', lists($this->jasa_m->get(), 'id', 'jasa', $this->localization->lang('pilih_jasa')), null, 'id="jasa" class="form-control" data-input-type="select2"') ?>
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
        <div role="tabpanel" class="tab-pane" id="komisi">
            <div class="form-group">
                <label>{{komisi}}</label>
                <div class="input-group">
                    <?= $this->form->number('komisi', null, 'id="komisi" class="form-control text-right" data-input-type="number-format"', "") ?>
                    <span class="input-group-addon">%</span>
                </div>
            </div>
            <?php foreach ($this->cabang_m->scope('auth')->get() as $cabang) { ?>
                <hr>
                <?= $this->form->checkbox('cabang['.$cabang->id.'][nama]', $cabang->nama, ($this->form->data('komisi_petugas')[$cabang->id]) ? true : false, 'id="cabang-cabang-'.$cabang->id.'" onclick="cabang_add('.$cabang->id.')"') ?> <?= $cabang->nama ?><br><br>
                <table id="table-komisi_petugas-<?= $cabang->id ?>" class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th>{{petugas}}</th>
                            <th width="200" class="text-center">{{komisi}} (%)</th>
                            <th width="1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($this->form->data('komisi_petugas')[$cabang->id]) { ?>
                            <?php foreach ($this->form->data('komisi_petugas')[$cabang->id] as $komisi_petugas) { ?>
                                <tr data-row-id="<?= $komisi_petugas['id_petugas'] ?>">
                                    <td>
                                        <?= $this->form->hidden('komisi_petugas['.$cabang->id.']['.$komisi_petugas['id_petugas'].'][id_cabang]', $cabang->id, 'id="komisi_petugas-id_cabang-'.$cabang->id.'-'.$komisi_petugas['id_petugas'].'"') ?>
                                        <?= $this->form->hidden('komisi_petugas['.$cabang->id.']['.$komisi_petugas['id_petugas'].'][id_petugas]', null, 'id="komisi_petugas-id_petugas-'.$cabang->id.'-'.$komisi_petugas['id_petugas'].'"') ?>
                                        <?= $this->form->hidden('komisi_petugas['.$cabang->id.']['.$komisi_petugas['id_petugas'].'][petugas]', null, 'id="komisi_petugas-petugas-'.$cabang->id.'-'.$komisi_petugas['id_petugas'].'"') ?>
                                        <?= $komisi_petugas['petugas'] ?>
                                    </td>
                                    <td><?= $this->form->number('komisi_petugas['.$cabang->id.']['.$komisi_petugas['id_petugas'].'][komisi]', null, 'id="komisi_petugas-komisi-'.$cabang->id.'-'.$komisi_petugas['id_petugas'].'" class="form-control input-sm text-center" data-input-type="number-format"', "") ?></td>
                                    <td><button type="button" class="btn btn-danger btn-sm" onclick="komisi_remove(<?= $cabang->id ?>,<?= $komisi_petugas['id_petugas'] ?>)"><i class="fa fa-trash"></i></button></td>
                                </tr>
                            <?php } ?>
                            <tr id="form-add-komisi_petugas-<?= $cabang->id ?>">
                                <td>
                                    <?= $this->form->hidden('form_add_komisi_petugas_id_cabang_'.$cabang->id, $cabang->id, 'id="form-add-komisi_petugas-id_cabang-'.$cabang->id.'"') ?>
                                    <?= $this->form->select('form_add_komisi_petugas_id_petugas_'.$cabang->id, lists($this->petugas_m->view('petugas')->where('id_cabang', $cabang->id)->get(), 'id', 'nama', 'semua_petugas', 0), null, 'class="form-control input-sm" id="form-add-komisi_petugas-id_petugas-'.$cabang->id.'"') ?>
                                </td>
                                <td><?= $this->form->number('form_add_komisi_petugas_komisi_'.$cabang->id, null, 'id="form-add-komisi_petugas-komisi-'.$cabang->id.'" class="form-control input-sm text-center" data-input-type="number-format"', "") ?></td>
                                <td><button type="button" class="btn btn-primary btn-sm" onclick="komisi_add(<?= $cabang->id ?>)"><i class="fa fa-plus"></i></button></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>
</div>