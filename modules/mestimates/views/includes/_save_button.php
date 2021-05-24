<button type="button" class="btn btn-primary" onclick="backToList()">Back To List</button>

<?php
if (isset($mestimate_id_old) && $mestimate_id_old != 0 && $mestimate_id_old != '') {
    ?>
    <?php
    if ((has_permission('mestimates', '', 'edit'))) {
        ?>
        <button class="btn btn-info only-save customer-form-submiter" onclick="saveMestimate()">
            Update M-Estimate
        </button>
        <?php
    }
    ?>
    <?php
    if ((has_permission('mestimates', '', 'create'))) {
        ?>
        <button type="button" class="btn btn-warning save-and-add-contact customer-form-submiter"
                data-toggle="modal" data-target="#create_new_confirm">
            Create New
        </button>
        <?php
    }
    ?>
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
    if (isset($mestimate_id) && $mestimate_id != 0 && $mestimate_id != '' && (has_permission('mestimates', '', 'delete'))) {
        ?>

        <button class="btn btn-danger only-save customer-form-submiter" onclick="delTemplate()">
            <?php echo _l('delete_this_template'); ?></button>
        <?php
    } elseif((has_permission('mestimates', '', 'edit'))) {
        ?>
        <button type="button" class="btn btn-info save-and-add-contact customer-form-submiter"
                data-toggle="modal" data-target="#exampleModal">
            <?php echo _l('save_template'); ?></button>
        <?php
    }
} else {
    if (isset($mestimate_id) && $mestimate_id != 0 && $mestimate_id != '') {
        if ($mestimate_id != $mestimate_id_old && (has_permission('mestimates', '', 'delete'))) {
            ?>
            <button class="btn btn-danger only-save customer-form-submiter" onclick="delTemplate()">
                <?php echo _l('delete_this_template'); ?></button>
            <?php
        } elseif((has_permission('mestimates', '', 'edit'))) {
            ?>
            <button type="button" class="btn btn-info save-and-add-contact customer-form-submiter"
                    data-toggle="modal" data-target="#exampleModal">
                <?php echo _l('save_template'); ?></button>
            <?php
        }
    }
}
?>