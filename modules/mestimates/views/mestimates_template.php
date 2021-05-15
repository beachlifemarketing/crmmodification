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
                    <?php include_once('_info_company.php') ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel_s no-shadow">
                    <div class="row">
                        <div class="col-md-6">
                            <?php $value = (isset($estimate) ? $estimate->job_number : ''); ?>
                            <?php echo render_input('job_number', 'job_number', $value); ?>
                        </div>


                        <div class="col-md-6">
                            <?php $value = (isset($estimate) ? $estimate->lic_number : ''); ?>
                            <?php echo render_input('lic_number', 'lic_number', $value); ?>
                        </div>

                        <div class="col-md-6">
                            <div class="f_client_id">
                                <div class="form-group select-placeholder">
                                    <label for="tenplate_id"
                                           class="control-label"><?php echo _l('categories'); ?></label>
                                    <select name="group_id" class="selectpicker"
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
                            <?php $value = (isset($estimate) ? $estimate->reference_no : ''); ?>
                            <?php echo render_input('reference_no', 'reference_no', $value); ?>
                        </div>

                        <div class="col-md-6">
                            <?php $value = (isset($estimate) ? $estimate->pymt_ption : ''); ?>
                            <?php echo render_input('pymt_ption', 'pymt_ption', $value); ?>
                        </div>

                        <div class="col-md-6">
                            <?php $value = (isset($estimate) ? $estimate->representative : ''); ?>
                            <?php echo render_input('representative', 'representative', $value); ?>
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
                            <?php $value = (isset($estimate) ? $estimate->title : ''); ?>
                            <?php echo render_input('title', 'title', $value); ?>
                        </div>

                        <div class="col-md-12">
                            <?php $value = (isset($estimate) ? $estimate->adminnote : ''); ?>
                            <?php echo render_textarea('note', 'note', $value); ?>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('mestimates/_add_edit_items'); ?>
</div>
<script type="text/javascript">
    function selectClient() {

        var clientId = $('#client_id').val();
        $("#hid_client_id").val(clientId)
        window.location.href = admin_url + 'mestimates/mestimate/' + $('input[name="mestimate_id"]').val() + '/' + clientId;
    }

</script>