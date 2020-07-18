<table cellspacing="14" style="margin-top: -37px;">
    <?php if ($barang) { ?>
        <tr>
            <?php for ($i=0; $i<count($barang); $i++) { ?>
                <?php if (($i % 4) == 0) { ?>
                    </tr>
                    <tr>
                        <td style="border: 1px solid; padding-left: 5px; padding-right: 5px; padding-bottom: 9px;">
                            <?= $barang[$i]['kode_barang'] ?> - <?=$barang[$i]['nama_barang'] ?>
                            <br>

                            <img src="data:image/png;base64, <?= $barang[$i]['barcode'] ?>">
                        </td>
                <?php } else { ?>
                    <td style="border: 1px solid; padding-left: 5px; padding-right: 5px; padding-bottom: 9px;">
                        <?= $barang[$i]['kode_barang'] ?> - <?=$barang[$i]['nama_barang'] ?>
                        <br>
                        <img src="data:image/png;base64, <?= $barang[$i]['barcode'] ?>">
                    </td>
                <?php } ?>
            <?php } ?>
        </tr>
    <?php } ?>
</table>