<div class="form-group select-placeholder">
    <label for="tenplate_id"
           class="control-label"><?php echo _l('estimate_select_template'); ?></label>
    <select id="template_id" name="template_id" class="selectpicker" data-live-search="true"
            onchange="selectTemplate()"
            data-width="100%">

        <?php
        $templates = (isset($templates)) ? $templates : [];
        echo ' <option value="0">Emty template</option>';
        for ($i = 0; $i < count($templates); $i++) {
            $selected = (isset($mestimate_id) && $mestimate_id == $templates[$i]['id']) ? 'selected' : '';
            ?>
            <option value="<?= $templates[$i]['id'] ?>" <?= $selected ?>><?= $templates[$i]['template_name'] ?></option>
            <?php
        }
        ?>
    </select>
</div>
<?php
if (isset($rtype) && $rtype === 'json') {
    ?>
    <script type="text/javascript">
        init_selectpicker();
    </script>
    <?php
}
?>
