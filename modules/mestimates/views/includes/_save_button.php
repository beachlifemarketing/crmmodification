<button class="btn btn-info only-save customer-form-submiter" onclick="saveMestimate()">
    <?php
    if (isset($mestimate_id_old) && $mestimate_id_old != 0 && $mestimate_id_old != '') {
        echo 'Update M-Estimate';
    } else {
        echo 'Create M-Estimate';
    }

    ?> </button>

<?php
if (!isset($mestimate_id_old) || $mestimate_id_old == 0 || $mestimate_id_old == '') {
    if (isset($mestimate_id) && $mestimate_id != 0 && $mestimate_id != '') {
        ?>
        <button class="btn btn-danger only-save customer-form-submiter" onclick="delTemplate()">
            <?php echo _l('delete_this_template'); ?></button>
        <?php
    }else{
        ?>
        <button type="button" class="btn btn-info save-and-add-contact customer-form-submiter"
                data-toggle="modal" data-target="#exampleModal">
            <?php echo _l('save_template'); ?></button>
        <?php
    }
} else {
    if (isset($mestimate_id) && $mestimate_id != 0 && $mestimate_id != '') {
        ?>
        <button type="button" class="btn btn-info save-and-add-contact customer-form-submiter"
                data-toggle="modal" data-target="#exampleModal">
            <?php echo _l('save_template'); ?></button>
        <?php
    }
}
?>