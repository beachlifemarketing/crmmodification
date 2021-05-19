<button type="button" class="btn btn-primary" onclick="backToList()">Back To List</button>

<?php
if (isset($mestimate_id_old) && $mestimate_id_old != 0 && $mestimate_id_old != '') {
    ?>
    <button class="btn btn-info only-save customer-form-submiter" onclick="saveMestimate()">
        Update M-Estimate
    </button>
    <button type="button" class="btn btn-warning save-and-add-contact customer-form-submiter"
            data-toggle="modal" data-target="#create_new_confirm">
        Create New
    </button>
    <?php
} else {
    ?>
    <button class="btn btn-info only-save customer-form-submiter" onclick="saveMestimate()">
        Create M-Estimate
    </button>
    <?php
}

?>

<?php
if (!isset($mestimate_id_old) || $mestimate_id_old == 0 || $mestimate_id_old == '') {
    if (isset($mestimate_id) && $mestimate_id != 0 && $mestimate_id != '') {
        ?>
        <button class="btn btn-danger only-save customer-form-submiter" onclick="delTemplate()">
            <?php echo _l('delete_this_template'); ?></button>
        <?php
    } else {
        ?>
        <button type="button" class="btn btn-info save-and-add-contact customer-form-submiter"
                data-toggle="modal" data-target="#exampleModal">
            <?php echo _l('save_template'); ?></button>
        <?php
    }
} else {
    if (isset($mestimate_id) && $mestimate_id != 0 && $mestimate_id != '') {
        if ($mestimate_id != $mestimate_id_old) {
            ?>
            <button class="btn btn-danger only-save customer-form-submiter" onclick="delTemplate()">
                <?php echo _l('delete_this_template'); ?></button>
            <?php
        } else {
            ?>
            <button type="button" class="btn btn-info save-and-add-contact customer-form-submiter"
                    data-toggle="modal" data-target="#exampleModal">
                <?php echo _l('save_template'); ?></button>
            <?php
        }
    }
}
?>