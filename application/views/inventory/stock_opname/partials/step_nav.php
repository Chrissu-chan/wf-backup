<ol class="bwizard-steps clearfix clickable" role="tablist">
    <li role="tab" class="<?= ($active==1) ? 'active' : '' ?>"><span class="label badge-inverse">1</span><a href="#step1" class="hidden-phone">
        {{pilih_gudang}}
        </a><a href="#step1" class="hidden-phone"><small>Pilih gudang yang akan melakukan stock opname</small></a><a href="#step1" class="hidden-phone">
    </a></li>
    <li role="tab" class="<?= ($active==2) ? 'active' : '' ?>"><span class="label">2</span><a href="#step2" class="hidden-phone">
        {{start_stock_opname}}
        </a><a href="#step2" class="hidden-phone"><small>Daftar barang stock opname</small></a><a href="#step2" class="hidden-phone">
    </a></li>
    <li role="tab" class="<?= ($active==3) ? 'active' : '' ?>"><span class="label">3</span><a href="#step3" class="hidden-phone">
        {{finish_stock_opname}}
        </a><a href="#step3" class="hidden-phone"><small>Stock opname selesai</small></a><a href="#step3" class="hidden-phone">
    </a></li>
</ol>     