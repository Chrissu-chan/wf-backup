<div id="frm-message"></div>
<?php $this->template->view('layouts/partials/message') ?>
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">{{penjualan}}</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>{{no_penjualan}}</label>
                    <?= $this->form->hidden('jenis_penjualan', $jenis_penjualan, 'id="jenis_penjualan" class="form-control" readonly') ?>
                    <?= $this->form->text('no_penjualan', null, 'id="no_penjualan" class="form-control" placeholder="{{auto}}" readonly') ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>{{pelanggan}}</label>
                    <?= $this->form->select('id_pelanggan', lists($this->pelanggan_m->get(), 'id', 'nama', 0), null, 'id="id_pelanggan" class="form-control" data-input-type="select2"') ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>{{tanggal}}</label>
                    <div class="input-group input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <?= $this->form->date('tanggal', date('d-m-Y'), 'id="tanggal" class="form-control" data-input-type="datepicker"') ?>
                    </div>
                </div>
            </div>
        </div>
        <table id="table-produk" class="table table-condensed">
            <thead>
                <tr>
                    <th>{{produk}}</th>
                    <th width="150" class="text-center">{{satuan}}</th>
                    <th width="100" class="text-center">{{jumlah}}</th>
                    <th width="150" class="text-right">{{harga}}</th>
                    <th width="150" class="text-right">{{diskon}}</th>
                    <th width="150" class="text-right">{{potongan}}</th>
                    <th width="150" class="text-right">{{subtotal}}</th>
                    <th width="150" class="text-right">{{tuslah}}</th>
                    <th width="150" class="text-right">{{total}}</th>
                    <th width="1"></th>
                </tr>
            </thead>
            <tbody>
                <tr id="form-add-produk">
                    <td>
                        <?= $this->form->hidden('form_add_produk_id_produk', null, 'id="produk-id_produk-0"') ?>
                        <?= $this->form->hidden('form_add_produk_kode_produk', null, 'id="produk-kode_produk-0"') ?>
                        <?= $this->form->hidden('form_add_produk_nama_produk', null, 'id="produk-nama_produk-0"') ?>
                        <?= $this->form->hidden('form_add_produk_jenis_produk', null, 'id="produk-jenis_produk-0"') ?>
                        <div class="input-group">
                            <?= $this->form->text('form_add_produk_produk', null, 'id="produk-produk-0" class="form-control input-sm"') ?>
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-primary input-sm" onclick="browse_produk()">...</button>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?= $this->form->hidden('form_add_produk_satuan', null, 'id="produk-satuan-0"') ?>
                        <?= $this->form->select('form_add_produk_id_satuan',
                            ($this->form->data('form_add_produk_id_satuan') ? array('0' => $this->localization->lang('pilih'), $this->form->data('form_add_produk_id_satuan') => $this->form->data('form_add_produk_satuan')) + lists($this->produk_harga_m->harga_satuan($this->form->data('form_add_produk_id_produk')), 'id_satuan', 'satuan') : array('0' => $this->localization->lang('pilih'))),
                            null, 'id="produk-id_satuan-0" onchange="produk_set_harga(0)" class="form-control input-sm"') ?>
                    </td>
                    <td><?= $this->form->number('form_add_produk_jumlah', null, 'id="produk-jumlah-0" class="form-control input-sm text-center" onkeyup="produk_set_harga(0)" data-input-type="number-format" data-thousand-separator="false" data-decimal-separator="false" data-precision="0"', "", "", 0) ?></td>
                    <td><?= $this->form->number('form_add_produk_harga', null, 'id="produk-harga-0" class="form-control input-sm text-right" ondblclick="edit_harga(this, 0)" onkeyup="produk_set_subtotal(0)" data-input-type="number-format" readonly', "") ?></td>
	                <td>
		                <?= $this->form->hidden('form_add_produk_diskon', null, 'id="produk-diskon_rp-0"') ?>
		                <?= $this->form->number('form_add_produk_diskon_persen', null, 'id="produk-diskon_persen-0" class="form-control input-sm text-right" ondblclick="edit_harga(this, 0)" onkeyup="produk_set_subtotal(0)" data-input-type="number-format" readonly', "") ?>
	                </td>
	                <td><?= $this->form->number('form_add_produk_potongan', null, 'id="produk-potongan-0" class="form-control input-sm text-right" ondblclick="edit_harga(this, 0)" onkeyup="produk_set_subtotal(0)" data-input-type="number-format" readonly', "") ?></td>
                    <td><?= $this->form->number('form_add_produk_subtotal', null, 'id="produk-subtotal-0" class="form-control input-sm text-right" data-input-type="number-format" readonly', "") ?></td>
	                <td>
		                <?= $this->form->hidden('form_add_produk_ppn', null, 'id="produk-ppn_rp-0"') ?>
		                <?= $this->form->hidden('form_add_produk_ppn_persen', null, 'id="produk-ppn_persen-0" class="form-control input-sm text-right" ondblclick="edit_harga(this, 0)" onkeyup="produk_set_total(0)" data-input-type="number-format" readonly') ?>
		                <?= $this->form->number('form_add_produk_tuslah', null, 'id="produk-tuslah-0" class="form-control input-sm text-right" onkeyup="produk_set_subtotal(0)" data-input-type="number-format"', "") ?>
	                </td>
                    <td><?= $this->form->number('form_add_produk_total', null, 'id="produk-total-0" class="form-control input-sm text-right" data-input-type="number-format" readonly', "") ?></td>
                    <td><button type="button" id="btn-add_produk" class="btn btn-primary btn-sm" onclick="produk_add()"><i class="fa fa-plus"></i></button></td>
                </tr>
                <?php if ($this->form->data('penjualan_produk')) { ?>
                    <?php foreach ($this->form->data('penjualan_produk') as $key => $produk) { ?>
                        <tr data-row-id="<?= $key ?>">
                            <td>
                                <?= $this->form->hidden('penjualan_produk['.$key.'][id_produk]', null, 'id="produk-id_produk-'.$key.'"') ?>
                                <?= $this->form->hidden('penjualan_produk['.$key.'][kode_produk]', null, 'id="produk-kode_produk-'.$key.'"') ?>
                                <?= $this->form->hidden('penjualan_produk['.$key.'][nama_produk]', null, 'id="produk-nama_produk-'.$key.'"') ?>
                                <?= $this->form->hidden('penjualan_produk['.$key.'][jenis_produk]', null, 'id="produk-jenis_produk-'.$key.'"') ?>
                                <?= $produk['nama_produk'] ?>
                            </td>
                            <td>
                                <?= $this->form->hidden('penjualan_produk['.$key.'][satuan]', null, 'id="produk-satuan-'.$key.'"') ?>
                                <?= $this->form->select('penjualan_produk['.$key.'][id_satuan]', array('0' => $this->localization->lang('pilih')) + lists($this->produk_harga_m->harga_satuan($produk['id_produk']), 'id_satuan', 'satuan'), null, 'onchange="produk_set_harga('.$key.')" class="form-control input-sm" id="produk-id_satuan-'.$key.'"') ?>
                            </td>
                            <td><?= $this->form->number('penjualan_produk['.$key.'][jumlah]', null, 'id="produk-jumlah-'.$key.'" onkeyup="produk_set_harga('.$key.')" class="form-control input-sm text-center" onkeyup="produk_set_subtotal('.$key.')" data-input-type="number-format" data-thousand-separator="false" data-decimal-separator="false" data-precision="0"', "", "", 0) ?></td>
                            <td><?= $this->form->number('penjualan_produk['.$key.'][harga]', null, 'id="produk-harga-'.$key.'" class="form-control input-sm text-right" ondblclick="edit_harga(this, '.$key.')" onkeyup="produk_set_subtotal('.$key.')" data-input-type="number-format" readonly', "") ?></td>
	                        <td>
		                        <?= $this->form->hidden('penjualan_produk['.$key.'][diskon]', null, 'id="produk-diskon_rp-'.$key.'"') ?>
		                        <?= $this->form->text('penjualan_produk['.$key.'][diskon_persen]', null, 'id="produk-diskon_persen-'.$key.'" class="form-control input-sm text-right" ondblclick="edit_harga(this, '.$key.')" onkeyup="produk_set_subtotal('.$key.')" data-input-type="number-format" readonly') ?>
	                        </td>
	                        <td><?= $this->form->number('penjualan_produk['.$key.'][potongan]', null, 'id="produk-potongan-'.$key.'" class="form-control input-sm text-right" onkeyup="produk_set_subtotal('.$key.')" data-input-type="number-format" readonly', "") ?></td>
                            <td><?= $this->form->number('penjualan_produk['.$key.'][subtotal]', null, 'id="produk-subtotal-'.$key.'" class="form-control input-sm text-right" data-input-type="number-format" readonly', "") ?></td>
	                        <td>
		                        <?= $this->form->hidden('penjualan_produk['.$key.'][ppn]', null, 'id="produk-ppn_rp-'.$key.'"') ?>
		                        <?= $this->form->hidden('penjualan_produk['.$key.'][ppn_persen]', null, 'id="produk-ppn_persen-'.$key.'" class="form-control input-sm text-right" ondblclick="edit_harga(this, '.$key.')" onkeyup="produk_set_total('.$key.')" data-input-type="number-format" readonly') ?>
		                        <?= $this->form->number('penjualan_produk['.$key.'][tuslah]', null, 'id="produk-tuslah-'.$key.'" class="form-control input-sm text-right" onkeyup="produk_set_total('.$key.')" data-input-type="number-format"', "") ?>
	                        </td>
                            <td><?= $this->form->number('penjualan_produk['.$key.'][total]', null, 'id="produk-total-'.$key.'" class="form-control input-sm text-right" data-input-type="number-format" readonly', "") ?></td>
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="produk_remove(<?= $key ?>)"><i class="fa fa-trash"></i></button></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
	    <!--<div class="row">
		    <div class="col-md-6">
			    <div class="form-group">
				    <label>{{diskon}}</label>
				    <div class="input-group input-group">
					    <?/*= $this->form->number('diskon_persen', null, 'id="diskon_persen" class="form-control text-right" onkeyup="set_diskon_persen()" data-input-type="number-format"', "") */?>
					    <span class="input-group-addon">%</span>
				    </div>
			    </div>
		    </div>
		    <div class="col-md-6">
			    <div class="form-group">
				    <label>{{ppn}}</label>
				    <div class="input-group input-group">
					    <?/*= $this->form->number('ppn_persen', null, 'id="ppn_persen" class="form-control text-right" onkeyup="set_ppn_persen()" data-input-type="number-format"', "") */?>
					    <span class="input-group-addon">%</span>
				    </div>
			    </div>
		    </div>
	    </div>-->
        <!--<div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{subtotal}}</label>
                    <div class="input-group input-group">
                        <span class="input-group-addon">Rp</span>
                        <?/*= $this->form->number('subtotal', null, 'id="subtotal" class="form-control text-right" data-input-type="number-format" readonly', "") */?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ppn}}</label>
                    <div class="input-group input-group">
                        <span class="input-group-addon">Rp</span>
                        <?/*= $this->form->number('ppn', null, 'id="ppn" class="form-control text-right" data-input-type="number-format" readonly', "") */?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{tuslah}}</label>
                    <div class="input-group input-group">
                        <span class="input-group-addon">Rp</span>
                        <?/*= $this->form->number('tuslah', null, 'id="tuslah" class="form-control text-right" data-input-type="number-format" readonly', "") */?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{total}}</label>
                    <div class="input-group input-group">
                        <span class="input-group-addon">Rp</span>
                        <?/*= $this->form->number('total', null, 'id="total" class="form-control text-right" data-input-type="number-format" readonly', "") */?>
                    </div>
                </div>
            </div>
        </div>-->
	    <div class="invoice-price">
		    <div class="invoice-price-left">
			    <div class="invoice-price-row">
				    <div class="sub-price">
					    <?= $this->form->hidden('subtotal', null, 'id="subtotal" class="form-control text-right" data-input-type="number-format" readonly') ?>
					    <?= $this->form->hidden('ppn', null, 'id="ppn" class="form-control text-right" data-input-type="number-format" readonly') ?>
					    <?= $this->form->hidden('tuslah', null, 'id="tuslah" class="form-control text-right" data-input-type="number-format" readonly') ?>
					    <?= $this->form->hidden('total', null, 'id="total" class="form-control text-right" data-input-type="number-format" readonly') ?>
					    <small>{{subtotal}}</small>
					    <span id="label-subtotal" class="text-inverse"><?= ($this->form->data('subtotal') ? $this->localization->number($this->form->data('subtotal')) : 0) ?></span>
				    </div>
				    <div class="sub-price">
					    <i class="fa fa-plus text-muted"></i>
				    </div>
				    <div class="sub-price">
					    <small>{{ppn}}</small>
					    <span id="label-ppn" class="text-inverse"><?= ($this->form->data('ppn') ? $this->localization->number($this->form->data('ppn')) : 0) ?></span>
				    </div>
				    <div class="sub-price">
					    <i class="fa fa-plus text-muted"></i>
				    </div>
				    <div class="sub-price">
					    <small>{{tuslah}}</small>
					    <span id="label-tuslah" class="text-inverse"><?= ($this->form->data('tuslah') ? $this->localization->number($this->form->data('tuslah')) : 0) ?></span>
				    </div>
			    </div>
		    </div>
		    <div class="invoice-price-right">
			    <small>{{total}}</small>
			    <span id="label-total" class="f-w-600"><?= ($this->form->data('total') ? $this->localization->number($this->form->data('total')) : 0) ?></span>
		    </div>
	    </div>
    </div>
</div>
<div class="modal fade" id="modal-dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">{{detail_pembayaran}}</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>{{kas_bank}}</label>
							<?= $this->form->select('id_kas_bank', lists($this->kas_bank_cabang_m->view('kas_bank')->scope('cabang_aktif')->get(), 'id', 'nama'), null, 'id="id_kas_bank" class="form-control" data-input-type="select2"') ?>
						</div>
						<div class="form-group">
							<label>{{no_ref}}</label>
							<?= $this->form->text('no_ref', null, 'id="no_ref" class="form-control"') ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>{{metode_pembayaran}}</label>
							<?= $this->form->select('metode_pembayaran', $this->penjualan_m->enum('metode_pembayaran'), null, 'id="metode_pembayaran" class="form-control" onchange="set_metode_pembayaran()"') ?>
						</div>
						<div id="tunai">
							<div class="form-group">
								<label>{{bayar}}</label>
								<div class="input-group input-group">
									<span class="input-group-addon">Rp</span>
									<?= $this->form->number('bayar', null, 'id="bayar" class="form-control input-lg text-right" onkeyup="set_pembayaran()" data-input-type="number-format" style="font-size: 20px"', "") ?>
								</div>
							</div>
							<div class="form-group">
								<label>{{kembali}}</label>
								<div class="input-group input-group">
									<span class="input-group-addon">Rp</span>
									<?= $this->form->number('kembali', null, 'id="kembali" class="form-control input-lg text-right" data-input-type="number-format" style="font-size: 20px" readonly', "") ?>
								</div>
							</div>
						</div>
						<div id="utang" <?= ($this->form->data('metode_pembayaran') == 'utang') ? '' : 'style="display: none;"' ?>>
							<div class="form-group">
								<label>{{jatuh_tempo}}</label>
								<div class="input-group input-group">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									<?= $this->form->date('jatuh_tempo', null, 'id="jatuh_tempo" class="form-control" data-input-type="datepicker"') ?>
								</div>
							</div>
							<div class="form-group">
								<label>{{uang_muka}}</label>
								<div class="input-group input-group">
									<span class="input-group-addon">Rp</span>
									<?= $this->form->number('bayar', null, 'id="uang_muka" class="form-control input-lg text-right" onkeyup="set_pembayaran()" data-input-type="number-format" style="font-size: 20px"', "") ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="form-group text-right">
					<button type="button" onclick="store()" class="btn btn-success">{{store}}</button>
					<a href="javascript:;" class="btn btn-white" data-dismiss="modal">{{cancel}}</a>
				</div>
			</div>
		</div>
	</div>
</div>

