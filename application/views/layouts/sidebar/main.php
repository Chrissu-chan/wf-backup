<ul class="nav">
    <li class="nav-header">{{navigation}}</li>
    <?php foreach ($menus as $menu) { ?>
        <?php if ($menu->number_of_child <> 0) { ?>
            <?php if (isset($menu->childs)) { ?>
                <li class="has-sub">
                    <a href="javascript:;">
                        <b class="caret pull-right"></b>
                        <i class="fa <?= $menu->icon ?> fa-fw"></i>
                        <span><?= $menu->menu ?></span>
                    </a>
                    <ul class="sub-menu">
                        <?php foreach ($menu->childs as $child) { ?>
                            <li><a href="<?= base_url($child->url) ?>"><span><?= $child->menu ?></span></a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
        <?php } else { ?>
            <li><a href="<?= base_url($menu->url) ?>"><i class="fa <?= $menu->icon ?> ?> fa-fw"></i>  <span><?= $menu->menu ?></span></a></li>
        <?php } ?>
    <?php } ?>
    <li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
</ul>