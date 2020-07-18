<div id="frm-message"></div>
<div class="form-group">
    <label>{{feature}}</label>
    <?= $this->form->text('feature', null, 'id="feature" class="form-control"') ?>
</div>
<div class="form-group">
    <label>{{class}}</label>
    <?= $this->form->select('class', lists(function() {
            $directory_map = directory_map(APPPATH.'controllers');
            $lists = array();
            foreach ($directory_map as $i => $file) {
                if (is_array($file)) {
                    foreach ($file as $j) {
                        $path = trim($i, '\\').'/'.$j;
                        $lists[] = array(
                            'value' => $path,
                            'label' => $path
                        );
                    }
                } else {
                    $lists[] = array(
                        'value' => $file,
                        'label' => $file
                    );
                }
            }
            return $lists;
        }, 'value', 'label', $this->localization->lang('select')),
        null, 'id="class" class="form-control" data-input-type="select2" ')
    ?>
</div>