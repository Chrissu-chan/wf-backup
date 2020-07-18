<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport"/>
    <link href="<?= base_url('public/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet"/>
    <style>
	    body{
		    font-size: 11px;
		    width: 6cm;
		    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
	    }

	    .table-barang{
		    font-size: 10px;
	    }

	    .table-barang tbody::after {
		    content: '';
		    display: block;
		    height: 20px;
	    }

	    @media print {
		    body{
			    font-size: 11px;
			    width: 6cm;
			    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
		    }

		    .table-barang{
			    font-size: 10px;
		    }

		    .table-barang tbody::after {
			    content: '';
			    display: block;
			    height: 20px;
		    }
	    }
    </style>
</head>
<body>
    <h4 class="text-center">WISNU FARMA</h4>
    <h6 class="text-center">Tulungagung</h6>
    <br>
    <table>
        <tbody>
            <tr>
                <td width="75">{{no_nota}}</td>
                <td width="20">:</td>
                <td><?= $model->no_pembelian ?></td>
            </tr>
            <tr>
                <td>{{tanggal}}</td>
                <td>:</td>
                <td><?= $this->localization->human_date($model->tanggal) ?></td>
            </tr>
            <tr>
                <td>{{supplier}}</td>
                <td>:</td>
                <td><?= $model->supplier ?></td>
            </tr>
            <tr>
                <td>{{kasir}}</td>
                <td>:</td>
                <td><?= $model->created_by ?></td>
            </tr>
        </tbody>
    </table>
    <br>
    <table class="table-barang">
        <thead>
            <tr style="border-top: 1px solid; border-bottom: 1px solid;">
                <th width="50">{{no}}</th>
	            <th colspan="3">{{nama}}</th>
	            <th width="100" class="text-right">{{harga}}</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($model->pembelian_barang) { ?>
	            <?php
		            $no = 1;
		            $grand_total = 0;
	            ?>
                <?php foreach ($model->pembelian_barang as $pembelian_barang) { ?>
                    <tr>
                        <td><?= $pembelian_barang->nama_barang ?> (<?= $pembelian_barang->satuan ?>)</td>
                        <td class="text-right">
                            <?php
                                $harga = $pembelian_barang->harga;
                                if ($pembelian_barang->diskon_persen) {
                                    $harga = $pembelian_barang->harga - (($pembelian_barang->harga * $pembelian_barang->diskon_persen) / 100);
                                }
                                if ($pembelian_barang->potongan) {
                                    $harga = $pembelian_barang->harga - $pembelian_barang->potongan;
                                }
                            ?>
                            <?= ($pembelian_barang->harga != $harga) ? '<font style="text-decoration: line-through;" >'.$this->localization->number($pembelian_barang->harga).'</font><br>'.$this->localization->number($harga) : $this->localization->number($pembelian_barang->harga) ?>
                        </td>
                        <td class="text-center"><?= $this->localization->number($pembelian_barang->jumlah) ?></td>
                        <td class="text-right"><?= $this->localization->number($pembelian_barang->ppn_persen) ?>%</td>
                        <td class="text-right"><?= $this->localization->number($pembelian_barang->total) ?></td>
                    </tr>
                    <?php $grand_total += $pembelian_barang->total; ?>
                <?php } ?>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr style="border-top: 1px solid;">
                <td colspan="4">Total Bayar</td>
                <td class="text-right"><?= $this->localization->number($grand_total) ?></td>
            </tr>
        </tfoot>
    </table>

    <script type="text/javascript">
	    window.onload = function() {
		    window.print();
	    }
    </script>
</body>
</html>