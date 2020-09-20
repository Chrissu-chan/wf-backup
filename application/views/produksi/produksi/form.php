<div class="form-group">
    <?= $this->form->hidden('id_barang_produksi', null, 'id="id_barang_produksi"') ?>
    <?= $this->form->hidden('id_barang', null, 'id="id_barang"') ?>
    <?= $this->form->hidden('kode_barang', null, 'id="kode_barang"') ?>
    <?= $this->form->hidden('nama_barang', null, 'id="nama_barang"') ?>
    <?= $this->form->hidden('id_satuan', null, 'id="id_satuan"') ?>
    <label>{{barang}}</label>
    <div class="input-group">
        <?= $this->form->text('barang', '', 'id="barang" class="form-control" readonly') ?>
        <div class="input-group-btn">
            <button type="button" class="btn btn-primary" onclick="browse_barang_produksi()">...</button>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>{{nama}}</label>
            <?= $this->form->text('nama', null, 'id="nama" class="form-control" readonly') ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>{{satuan}}</label>
            <?= $this->form->text('satuan', null, 'id="satuan" class="form-control" readonly') ?>
        </div>
    </div>
</div>

<div id="detail_produksi" <?= (is_null($this->form->data('produksi_bahan_baku'))) ? 'style="display: none"' : '' ?>>
    <hr>
    <h4>{{bahan_baku}}</h4>
    <table id="table-barang_produksi_bahan_baku" class="table table-bordered table-condensed">
        <thead>
        <tr>
            <th>{{barang}}</th>
            <th width="200">{{satuan}}</th>
            <th width="150" class="text-center">{{jumlah}}</th>
        </tr>
        </thead>
        <tbody>
            <?php if ($this->form->data('bahan_baku')) { ?>
                <?php foreach ($this->form->data('bahan_baku') as $bahan_baku) { ?>
                    <tr data-row-id="<?= $bahan_baku['id_barang'] ?>">
                        <td>
                            <?= $this->form->hidden('bahan_baku['.$bahan_baku['id_barang'].'][id_barang]', null, 'id="bahan_baku-id_barang-'.$bahan_baku['id_barang'].'"') ?>
                            <?= $this->form->hidden('bahan_baku['.$bahan_baku['id_barang'].'][kode_barang]', null, 'id="bahan_baku-kode_barang-'.$bahan_baku['id_barang'].'"') ?>
                            <?= $this->form->hidden('bahan_baku['.$bahan_baku['id_barang'].'][nama_barang]', null, 'id="bahan_baku-nama_barang-'.$bahan_baku['id_barang'].'"') ?>
                            <?= $bahan_baku['kode_barang'].' - '.$bahan_baku['nama_barang'] ?>
                        </td>
                        <td>
                            <?= $this->form->hidden('bahan_baku['.$bahan_baku['id_barang'].'][satuan]', null, 'id="bahan_baku-satuan-'.$bahan_baku['id_barang'].'"') ?>
                            <?= $bahan_baku['satuan'] ?>
                        </td>
                        <td class="text-center">
                            <?= $this->form->hidden('bahan_baku['.$bahan_baku['id_barang'].'][jumlah]', null, 'id="bahan_baku-jumlah-'.$bahan_baku['id_barang'].'"') ?>
                            <?= $bahan_baku['jumlah'] ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>{{no_produksi}}</label>
                <?= $this->form->text('no_produksi', null, 'id="no_produksi" class="form-control"') ?>
            </div>
        </div>
        <div class="col-md-6">
            <label>{{tanggal_produksi}}</label>
            <div class="input-group input-group-sm">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <?= $this->form->date('tanggal_produksi', date('d-m-Y'), 'id="tanggal_produksi" class="form-control" data-input-type="dateinput"') ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label>{{jumlah}}</label>
        <?= $this->form->number('jumlah', 1, 'id="jumlah" class="form-control" onkeyup="get_bahan_baku()" data-input-type="number-format" data-thousand-separator="false"', "") ?>
    </div>

    <hr>
    <h4>{{produksi_bahan_baku}}</h4>
    <table id="table-produksi_bahan_baku" class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>{{barang}}</th>
                <th width="200">{{satuan}}</th>
                <th width="150" class="text-center">{{jumlah}}</th>
                <th width="200" class="text-right">{{hpp}}</th>
                <th width="200" class="text-right">{{total}}</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($this->form->data('produksi_bahan_baku')) { ?>
                <?php foreach ($this->form->data('produksi_bahan_baku') as $produksi_bahan_baku) { ?>
                    <tr data-row-id="<?= $produksi_bahan_baku['id_barang'] ?>">
                        <td>
                            <?= $this->form->hidden('produksi_bahan_baku['.$produksi_bahan_baku['id_barang'].'][id_barang]', null, 'id="produksi_bahan_baku-id_barang-'.$produksi_bahan_baku['id_barang'].'"') ?>
                            <?= $this->form->hidden('produksi_bahan_baku['.$produksi_bahan_baku['id_barang'].'][kode_barang]', null, 'id="produksi_bahan_baku-kode_barang-'.$produksi_bahan_baku['id_barang'].'"') ?>
                            <?= $this->form->hidden('produksi_bahan_baku['.$produksi_bahan_baku['id_barang'].'][nama_barang]', null, 'id="produksi_bahan_baku-nama_barang-'.$produksi_bahan_baku['id_barang'].'"') ?>
                            <?= $produksi_bahan_baku['kode_barang'].' - '.$produksi_bahan_baku['nama_barang'] ?>
                        </td>
                        <td>
                            <?= $this->form->hidden('produksi_bahan_baku['.$produksi_bahan_baku['id_barang'].'][barang_id_satuan]', null, 'id="produksi_bahan_baku-barang_id_satuan-'.$produksi_bahan_baku['id_barang'].'"') ?>
                            <?= $this->form->hidden('produksi_bahan_baku['.$produksi_bahan_baku['id_barang'].'][barang_satuan]', null, 'id="produksi_bahan_baku-barang_satuan-'.$produksi_bahan_baku['id_barang'].'"') ?>
                            <?= $this->form->select('produksi_bahan_baku['.$produksi_bahan_baku['id_barang'].'][id_satuan]', array('' => $this->localization->lang('pilih'), $produksi_bahan_baku['barang_id_satuan'] => $produksi_bahan_baku['barang_satuan']) + lists($this->satuan_m->view('satuan')->where('id_satuan_asal', $produksi_bahan_baku['barang_id_satuan'])->get(), 'id', 'satuan'), null, 'class="form-control input-sm" onchange="get_hpp('.$produksi_bahan_baku['id_barang'].')" id="produksi_bahan_baku-id_satuan-'.$produksi_bahan_baku['id_barang'].'"') ?>
                        </td>
                        <td><?= $this->form->number('produksi_bahan_baku['.$produksi_bahan_baku['id_barang'].'][jumlah]', null, 'id="produksi_bahan_baku-jumlah-'.$produksi_bahan_baku['id_barang'].'" class="form-control input-sm text-center" onkeyup="get_hpp('.$produksi_bahan_baku['id_barang'].')" data-input-type="number-format" data-thousand-separator="false"', "") ?></td>
                        <td><?= $this->form->number('produksi_bahan_baku['.$produksi_bahan_baku['id_barang'].'][hpp]', null, 'id="produksi_bahan_baku-hpp-'.$produksi_bahan_baku['id_barang'].'" class="form-control input-sm text-right" data-input-type="number-format" readonly', "") ?></td>
                        <td><?= $this->form->number('produksi_bahan_baku['.$produksi_bahan_baku['id_barang'].'][total]', null, 'id="produksi_bahan_baku-total-'.$produksi_bahan_baku['id_barang'].'" class="form-control input-sm text-right" data-input-type="number-format" readonly', "") ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">{{total_biaya_bahan_baku}}</td>
                <td><?= $this->form->number('total_bahan_baku', null, 'id="total_bahan_baku" class="form-control input-sm text-right" data-input-type="number-format" readonly', "") ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="form-group">
        <label>{{total_biaya_lainnya}}</label>
        <?= $this->form->number('total_biaya_lainnya', null, 'id="total_biaya_lainnya" class="form-control text-right" onkeyup="total_biaya()" data-input-type="number-format"', "") ?>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>{{total_biaya_produksi}}</label>
                <?= $this->form->number('total_biaya_produksi', null, 'id="total_biaya_produksi" class="form-control text-right" data-input-type="number-format" readonly', "") ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>{{hpp}}</label>
                <?= $this->form->number('hpp', null, 'id="hpp" class="form-control text-right" data-input-type="number-format" readonly', "") ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label>{{keterangan}}</label>
        <?= $this->form->textarea('keterangan', null, 'id="keterangan" class="form-control"') ?>
    </div>
</div>