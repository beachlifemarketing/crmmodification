<div class="panel_s no-shadow">
    <div class="row">

        <div class="col-md-6">
            <div class="form-group" app-field-wrapper="job_number">
                <label for="job_number" class="control-label"><?php echo _l('job_number'); ?></label>
                <input type="text" id="job_number" name="job_number" class="form-control"
                       value="<?= (isset($mestimate) ? $mestimate->job_number : ''); ?>">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group" app-field-wrapper="lic_number">
                <label for="lic_number" class="control-label"><?php echo _l('lic_number'); ?></label>
                <input type="text" id="lic_number" name="lic_number" class="form-control"
                       value="<?= (isset($mestimate) ? $mestimate->lic_number : ''); ?>">
            </div>
        </div>

        <div class="col-md-6">

            <div class="form-group" app-field-wrapper="title">
                <label for="group_id"
                       class="control-label"><?php echo _l('categories'); ?></label>
                <select id="group_id" name="group_id" class="form-control"
                        data-width="100%" onchange="selectClient()">
                    <?php
                    for ($i = 0; $i < count($groups); $i++) {
                        $selected = (isset($mestimate) && $mestimate->group_id == $groups[$i]['id']) ? 'selected' : '';
                        ?>
                        <option value="<?= $groups[$i]['id'] ?>" <?= $selected ?>><?= $groups[$i]['name'] ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group" app-field-wrapper="pymt_option">
                <label for="pymt_option" class="control-label"><?php echo _l('pymt_option'); ?></label>
                <input type="text" id="pymt_option" name="pymt_option" class="form-control"
                       value="<?= (isset($mestimate) ? $mestimate->pymt_option : ''); ?>">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group" app-field-wrapper="representative">
                <label for="representative"
                       class="control-label"><?php echo _l('representative'); ?></label>
                <input type="text" id="representative" name="representative" class="form-control"
                       value="<?= (isset($mestimate) ? $mestimate->representative : ''); ?>">
            </div>
        </div>


        <div class="col-md-6">
            <div class="form-group" app-field-wrapper="claim_number">
                <label for="claim_number"
                       class="control-label"><?php echo _l('claim_number'); ?></label>
                <input type="text" id="claim_number" name="claim_number" class="form-control"
                       value="<?= (isset($mestimate) ? $mestimate->claim_number : ''); ?>">
            </div>
        </div>

        <div class="col-md-6">
            <?php $value = (isset($mestimate) ? _d($mestimate->date) : _d(date('Y-m-d'))); ?>
            <?php echo render_date_input('date', 'mestimate_add_edit_date', $value); ?>
        </div>
        <div class="col-md-6">
            <?php
            $value = '';
            if (isset($mestimate)) {
                $value = _d($mestimate->due_date);
            } else {
                if (get_option('mestimate_due_after') != 0) {
                    $value = _d(date('Y-m-d', strtotime('+' . get_option('mestimate_due_after') . ' DAY', strtotime(date('Y-m-d')))));
                }
            }
            echo render_date_input('due_date', 'due_date', $value); ?>
        </div>


        <div class="col-md-6">
            <div class="form-group" app-field-wrapper="adminnote">
                <label for="adminnote" class="control-label"><?php echo _l('adminnote'); ?></label>
                <textarea id="adminnote" name="adminnote"
                          class="form-control "><?= (isset($mestimate) ? $mestimate->adminnote : ''); ?></textarea>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group" app-field-wrapper="title">
                <label for="title"
                       class="control-label"><?php echo _l('title'); ?></label>
                <input type="text" id="title" name="title" class="form-control"
                       value="<?= (isset($mestimate) ? $mestimate->title : ''); ?>">
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    $(function () {
        $(".datepicker").datepicker({
            autoclose: true,
            todayHighlight: true
        }).datepicker('update', new Date());
    });
</script>