<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel_s accounting-template estimate">
    <div class="panel-body">
        <div class="row">
            <?php echo form_hidden('mestimate_id', (isset($mestimate)) ? $mestimate->id : 0); ?>
            <div class="col-md-6">

                <div class="f_client_id">
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
                </div>


                <div class="f_client_id">
                    <div class="form-group select-placeholder">
                        <label for="client_id"
                               class="control-label"><?php echo _l('estimate_select_template'); ?></label>
                        <select id="client_id" name="client_id" class="selectpicker" data-live-search="true"
                                onchange="selectClient()"
                                data-width="100%">

                            <?php
                            if (isset($clients)) {
                                echo ' <option value="0"></option>';
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
            <div class="col-md-6" id="div_info">
                <?php $this->load->view('mestimates/includes/info_mestimate'); ?>
            </div>
        </div>
    </div>
    <div class="panel-body mtop10" id="div_list_details">
        <?php $this->load->view('mestimates/includes/_add_edit_items'); ?>
    </div>

</div>