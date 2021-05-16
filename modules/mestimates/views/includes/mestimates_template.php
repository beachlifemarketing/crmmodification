<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$client_id = (isset($estimate) ? $estimate->client_id : '');
$client_id = (isset($client) ? $client->userid : '');
?>
<div class="panel_s accounting-template estimate">
    <div class="panel-body">
        <div class="row">
            <?php echo form_hidden('mestimate_id', (isset($estimate)) ? $estimate->id : 0); ?>
            <?php if (isset($estimate_request_id) && $estimate_request_id != '') {
                echo form_hidden('estimate_request_id', $estimate_request_id);
            }
            ?>
            <div class="col-md-6">
                <div class="f_client_id">
                    <div class="form-group select-placeholder">
                        <label for="tenplate_id"
                               class="control-label"><?php echo _l('estimate_select_template'); ?></label>
                        <select id="template_id" name="template_id" class="selectpicker" data-live-search="true"
                                data-width="100%">
                            <?php
                            $templates = (isset($templates)) ? $templates : [];
                            for ($i = 0; $i < count($templates); $i++) {
                                $selected = (isset($estimate) && $estimate->id == $templates[$i]->id) ? 'selected' : '';
                                ?>
                                <option <?= $selected ?>><?= $templates[$i]->job_number ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="f_client_id">
                    <div class="form-group select-placeholder">
                        <label for="client_id"
                               class="control-label"><?php echo _l('or_select_client'); ?></label>
                        <select id="client_id" name="client_id" class="selectpicker" data-live-search="true"
                                data-width="100%" onchange="selectClient()">
                            <?php
                            if (isset($clients)) {
                                echo ' <option></option>';
                                for ($i = 0; $i < count($clients); $i++) {
                                    $selected = ($client_id == $clients[$i]['userid']) ? 'selected' : '';
                                    ?>
                                    <option value="<?= $clients[$i]['userid'] ?>" <?= $selected ?>><?= $clients[$i]['company'] ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div id="div_address">
                    <?php $this->load->view('mestimates/includes/info_company'); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel_s no-shadow">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group" app-field-wrapper="job_number">
                                <label for="job_number" class="control-label"><?php echo _l('job_number'); ?></label>
                                <input type="text" id="job_number" name="job_number" class="form-control"
                                       value="<?= (isset($estimate) ? $estimate->job_number : ''); ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group" app-field-wrapper="lic_number">
                                <label for="lic_number" class="control-label"><?php echo _l('lic_number'); ?></label>
                                <input type="text" id="lic_number" name="lic_number" class="form-control"
                                       value="<?= (isset($estimate) ? $estimate->lic_number : ''); ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="f_client_id">
                                <div class="form-group select-placeholder">
                                    <label for="tenplate_id"
                                           class="control-label"><?php echo _l('categories'); ?></label>
                                    <select name="group_id" class="selectpicker" name="group_id"
                                            data-live-search="true"
                                            data-width="100%">
                                        <?php
                                        for ($i = 0; $i < count($groups); $i++) {
                                            $selected = (isset($estimate) && $estimate->group_id == $groups[$i]['id']) ? 'selected' : '';
                                            ?>
                                            <option value="<?= $groups[$i]['id'] ?>" <?= $selected ?>><?= $groups[$i]['name'] ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group" app-field-wrapper="reference_no">
                                <label for="reference_no"
                                       class="control-label"><?php echo _l('reference_no'); ?></label>
                                <input type="text" id="reference_no" name="reference_no" class="form-control"
                                       value="<?= (isset($estimate) ? $estimate->reference_no : ''); ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group" app-field-wrapper="pymt_option">
                                <label for="pymt_option" class="control-label"><?php echo _l('pymt_option'); ?></label>
                                <input type="text" id="pymt_option" name="pymt_option" class="form-control"
                                       value="<?= (isset($estimate) ? $estimate->pymt_option : ''); ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group" app-field-wrapper="representative">
                                <label for="representative"
                                       class="control-label"><?php echo _l('representative'); ?></label>
                                <input type="text" id="representative" name="representative" class="form-control"
                                       value="<?= (isset($estimate) ? $estimate->representative : ''); ?>">
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group" app-field-wrapper="claim_number">
                                <label for="claim_number"
                                       class="control-label"><?php echo _l('claim_number'); ?></label>
                                <input type="text" id="claim_number" name="claim_number" class="form-control"
                                       value="<?= (isset($estimate) ? $estimate->claim_number : ''); ?>">
                            </div>
                        </div>


                        <div class="col-md-6">
                            <?php $value = (isset($estimate) ? _d($estimate->date) : _d(date('Y-m-d'))); ?>
                            <?php echo render_date_input('date', 'estimate_add_edit_date', $value); ?>
                        </div>
                        <div class="col-md-6">
                            <?php
                            $value = '';
                            if (isset($estimate)) {
                                $value = _d($estimate->due_date);
                            } else {
                                if (get_option('estimate_due_after') != 0) {
                                    $value = _d(date('Y-m-d', strtotime('+' . get_option('estimate_due_after') . ' DAY', strtotime(date('Y-m-d')))));
                                }
                            }
                            echo render_date_input('due_date', 'due_date', $value); ?>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group" app-field-wrapper="adminnote">
                                <label for="adminnote" class="control-label"><?php echo _l('adminnote'); ?></label>
                                <input type="text" id="adminnote" name="adminnote" class="form-control"
                                       value="<?= (isset($estimate) ? $estimate->adminnote : ''); ?>">
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('mestimates/includes/_add_edit_items'); ?>
</div>
