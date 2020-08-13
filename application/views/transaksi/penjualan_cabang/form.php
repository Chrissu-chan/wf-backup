<div id="frm-message"></div>
<?php $this->template->view('layouts/partials/message') ?>
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">{{penjualan_cabang}}</h4>
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
                    <label>{{cabang}}</label>
                    <?= $this->form->select('id_pelanggan', lists($this->cabang_m->get(), 'id', 'nama', TRUE), null, 'id="id_pelanggan" class="form-control" data-input-type="select2"') ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>{{tanggal}}</label>
                    <div class="input-group input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <?= $this->form->date('tanggal', date('d-m-Y'), 'id="tanggal" class="form-control" readonly') ?>
                    </div>
                </div>
            </div>
        </div>
        <table id="table-barang" class="table table-condensed">
            <thead>
                <tr>
                    <th>{{barang}}</th>
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
                <tr id="form-add-barang">
                    <td>
                        <?= $this->form->hidden('form_add_barang_id_produk', null, 'id="barang-id_barang-0"') ?>
                        <?= $this->form->hidden('form_add_barang_kode_produk', null, 'id="barang-kode_barang-0"') ?>
                        <?= $this->form->hidden('form_add_barang_nama_produk', null, 'id="barang-nama_barang-0"') ?>
                        <div class="input-group">
                            <?= $this->form->text('form_add_barang_barang', null, 'id="barang-barang-0" class="form-control input-sm"') ?>
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-primary input-sm" onclick="browse_barang()">...</button>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?= $this->form->hidden('form_add_barang_barang_id_satuan', null, 'id="barang-barang_id_satuan-0"') ?>
                        <?= $this->form->hidden('form_add_barang_barang_satuan', null, 'id="barang-barang_satuan-0"') ?>
                        <?= $this->form->select('form_add_barang_id_satuan',
                            ($this->form->data('form_add_barang_id_satuan') ? array('0' => $this->localization->lang('pilih'), $this->form->data('form_add_barang_barang_id_satuan') => $this->form->data('form_add_barang_barang_satuan')) + lists($this->satuan_m->view('satuan')->where('id_satuan_tujuan', $this->form->data('form_add_barang_barang_id_satuan'))->get(), 'id', 'satuan') : array('0' => $this->localization->lang('pilih'))),
                            null, 'id="barang-id_satuan-0" onchange="barang_set_harga(0)" class="form-control input-sm"') ?>
                    </td>
                    <td><?= $this->form->number('form_add_barang_jumlah', null, 'id="barang-jumlah-0" class="form-control input-sm text-center" onkeyup="barang_set_subtotal(0)" data-input-type="number-format" data-thousand-separator="false" data-decimal-separator="false" data-precision="0"', "", "", 0) ?></td>
                    <td><?= $this->form->number('form_add_barang_harga', null, 'id="barang-harga-0" class="form-control input-sm text-right" ondblclick="edit_harga(this, 0)" onkeyup="barang_set_subtotal(0)" data-input-type="number-format" readonly', "") ?></td>
	                <td>
		                <?= $this->form->hidden('form_add_barang_diskon', null, 'id="barang-diskon_rp-0"') ?>
		                <?= $this->form->number('form_add_barang_diskon_persen', null, 'id="barang-diskon_persen-0" class="form-control input-sm text-right" ondblclick="edit_harga(this, 0)" onkeyup="barang_set_subtotal(0)" data-input-type="number-format" readonly', "") ?>
	                </td>
	                <td><?= $this->form->number('form_add_barang_potongan', null, 'id="barang-potongan-0" class="form-control input-sm text-right" ondblclick="edit_harga(this, 0)" onkeyup="barang_set_subtotal(0)" data-input-type="number-format" readonly', "") ?></td>
                    <td><?= $this->form->number('form_add_barang_subtotal', null, 'id="barang-subtotal-0" class="form-control input-sm text-right" data-input-type="number-format" readonly', "") ?></td>
	                <td>
		                <?= $this->form->hidden('form_add_barang_ppn', null, 'id="barang-ppn_rp-0"') ?>
		                <?= $this->form->hidden('form_add_barang_ppn_persen', null, 'id="barang-ppn_persen-0" class="form-control input-sm text-right" ondblclick="edit_harga(this, 0)" onkeyup="barang_set_total(0)" data-input-type="number-format" readonly') ?>
		                <?= $this->form->number('form_add_barang_tuslah', null, 'id="barang-tuslah-0" class="form-control input-sm text-right" onkeyup="barang_set_subtotal(0)" data-input-type="number-format"', "") ?>
	                </td>
                    <td><?= $this->form->number('form_add_barang_total', null, 'id="barang-total-0" class="form-control input-sm text-right" data-input-type="number-format" readonly', "") ?></td>
                    <td><button type="button" id="btn-add_barang" class="btn btn-primary btn-sm" onclick="barang_add()"><i class="fa fa-plus"></i></button></td>
                </tr>
                <?php if ($this->form->data('penjualan_barang')) { ?>
                    <?php foreach ($this->form->data('penjualan_barang') as $key => $barang) { ?>
                        <tr data-row-id="<?= $key ?>">
                            <td>
                                <?= $this->form->hidden('penjualan_barang['.$key.'][id_produk]', null, 'id="barang-id_barang-'.$key.'"') ?>
                                <?= $this->form->hidden('penjualan_barang['.$key.'][kode_produk]', null, 'id="barang-kode_barang-'.$key.'"') ?>
                                <?= $this->form->hidden('penjualan_barang['.$key.'][nama_produk]', null, 'id="barang-nama_barang-'.$key.'"') ?>
                                <?= $barang['nama_produk'] ?>
                            </td>
                            <td>
                                <?= $this->form->hidden('penjualan_barang['.$key.'][barang_id_satuan]', null, 'id="barang-barang_id_satuan-'.$key.'"') ?>
                                <?= $this->form->hidden('penjualan_barang['.$key.'][barang_satuan]', null, 'id="barang-barang_satuan-'.$key.'"') ?>
                                <?= $this->form->select('penjualan_barang['.$key.'][id_satuan]', array('0' => $this->localization->lang('pilih'), $barang['barang_id_satuan'] => $barang['barang_satuan']) + lists($this->satuan_m->view('satuan')->where('id_satuan_tujuan', $barang['barang_id_satuan'])->get(), 'id', 'satuan'), null, 'onchange="barang_set_harga('.$key.')" class="form-control input-sm" id="barang-id_satuan-'.$key.'"') ?>
                            </td>
                            <td><?= $this->form->number('penjualan_barang['.$key.'][jumlah]', null, 'id="barang-jumlah-'.$key.'" onkeyup="barang_set_subtotal('.$key.')" class="form-control input-sm text-center" onkeyup="barang_set_subtotal('.$key.')" data-input-type="number-format" data-thousand-separator="false" data-decimal-separator="false" data-precision="0"', "", "", 0) ?></td>
                            <td><?= $this->form->number('penjualan_barang['.$key.'][harga]', null, 'id="barang-harga-'.$key.'" class="form-control input-sm text-right" ondblclick="edit_harga(this, '.$key.')" onkeyup="barang_set_subtotal('.$key.')" data-input-type="number-format" readonly', "") ?></td>
	                        <td>
		                        <?= $this->form->hidden('penjualan_barang['.$key.'][diskon]', null, 'id="barang-diskon_rp-'.$key.'"') ?>
		                        <?= $this->form->text('penjualan_barang['.$key.'][diskon_persen]', null, 'id="barang-diskon_persen-'.$key.'" class="form-control input-sm text-right" ondblclick="edit_harga(this, '.$key.')" onkeyup="barang_set_subtotal('.$key.')" data-input-type="number-format" readonly') ?>
	                        </td>
	                        <td><?= $this->form->number('penjualan_barang['.$key.'][potongan]', null, 'id="barang-potongan-'.$key.'" class="form-control input-sm text-right" onkeyup="barang_set_subtotal('.$key.')" data-input-type="number-format" readonly', "") ?></td>
                            <td><?= $this->form->number('penjualan_barang['.$key.'][subtotal]', null, 'id="barang-subtotal-'.$key.'" class="form-control input-sm text-right" data-input-type="number-format" readonly', "") ?></td>
	                        <td>
		                        <?= $this->form->hidden('penjualan_barang['.$key.'][ppn]', null, 'id="barang-ppn_rp-'.$key.'"') ?>
		                        <?= $this->form->hidden('penjualan_barang['.$key.'][ppn_persen]', null, 'id="barang-ppn_persen-'.$key.'" class="form-control input-sm text-right" ondblclick="edit_harga(this, '.$key.')" onkeyup="barang_set_total('.$key.')" data-input-type="number-format" readonly') ?>
		                        <?= $this->form->number('penjualan_barang['.$key.'][tuslah]', null, 'id="barang-tuslah-'.$key.'" class="form-control input-sm text-right" onkeyup="barang_set_total('.$key.')" data-input-type="number-format"', "") ?>
	                        </td>
                            <td><?= $this->form->number('penjualan_barang['.$key.'][total]', null, 'id="barang-total-'.$key.'" class="form-control input-sm text-right" data-input-type="number-format" readonly', "") ?></td>
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="barang_remove(<?= $key ?>)"><i class="fa fa-trash"></i></button></td>
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

