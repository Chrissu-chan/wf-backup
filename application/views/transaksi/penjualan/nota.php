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

        .table-produk{
            font-size: 10px;
        }

        .table-produk tbody::after {
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

	        .table-produk{
		        font-size: 10px;
	        }

	        .table-produk tbody::after {
		        content: '';
		        display: block;
		        height: 20px;
	        }
        }
    </style>
</head>
<body>
    <h4 class="text-center"><?= $model->cabang->nama ?></h4>
    <h6 class="text-center"><?= $model->cabang->alamat ?></h6>
    <h6 class="text-center">HP/WA : <?= $model->cabang->telepon ?></h6>
    <br>
    <table>
        <tbody>
            <tr>
                <td width="75">{{no_nota}}</td>
                <td width="20">:</td>
                <td><?= $model->no_penjualan ?></td>
            </tr>
            <tr>
                <td>{{tanggal}}</td>
                <td>:</td>
                <td><?= $this->localization->human_date($model->tanggal) ?></td>
            </tr>
            <tr>
                <td>{{pelanggan}}</td>
                <td>:</td>
                <td><?= $model->pelanggan ?></td>
            </tr>
            <tr>
                <td>{{kasir}}</td>
                <td>:</td>
                <td><?= $model->created_by ?></td>
            </tr>
        </tbody>
    </table>
    <br>
    <table class="table-produk">
        <thead>
            <tr style="border-top: 1px solid; border-bottom: 1px solid;">
	            <th width="50">{{no}}</th>
                <th colspan="3">{{nama}}</th>
                <th width="100" class="text-right">{{harga}}</th>
            </tr>
        </thead>
        <tbody>
	        <?php
		        $no = 1;
		        $grand_total = 0;
	        ?>
            <?php if ($model->penjualan_produk) { ?>
                <?php foreach ($model->penjualan_produk as $penjualan_produk) { ?>
                    <tr>
	                    <td><?= $no ?></td>
                        <td colspan="4"><?= $penjualan_produk->nama_produk ?></td>
	                    <td>&nbsp;</td>
                    </tr>
		            <tr>
			            <td>&nbsp;</td>
			            <td width="200"><?= $this->localization->number($penjualan_produk->jumlah) ?> <?= ($penjualan_produk->satuan ? $penjualan_produk->satuan : '') ?></td>
			            <td width="200">
				            <?php $harga = $penjualan_produk->total / $penjualan_produk->jumlah; ?>
				            <?= $this->localization->number($harga) ?>
			            </td>
			            <td width="1">=</td>
			            <td class="text-right"><?= $this->localization->number($penjualan_produk->total) ?></td>
		            </tr>
                    <?php
		                $grand_total += $penjualan_produk->total;
		                $no++;
		            ?>
                <?php } ?>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr style="border-top: 1px solid;">
                <th colspan="4" style="margin-top: 10cm;">Total Bayar</th>
                <th class="text-right"><?= $this->localization->number($grand_total) ?></th>
            </tr>
            <tr>
                <th colspan="4">Bayar</th>
                <th class="text-right"><?= $this->localization->number($model->bayar) ?></th>
            </tr>
            <tr>
                <th colspan="4">Kembali</th>
                <th class="text-right"><?= $this->localization->number($model->kembali) ?></th>
            </tr>
        </tfoot>
    </table>
    <br>
    <p class="text-center"><?= $model->cabang->keterangan ?></p>

    <script type="text/javascript">
	    window.onload = function() {
		    window.print();
	    }
    </script>
</body>
</html>