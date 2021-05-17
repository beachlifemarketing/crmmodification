<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel_s accounting-template estimate">
    <div class="panel-body">
        <div class="row">
            <?php echo form_hidden('mestimate_id', (isset($mestimate)) ? $mestimate->id : 0); ?>
            <?php if (isset($mestimate_request_id) && $mestimate_request_id != '') {
                echo form_hidden('mestimate_request_id', $mestimate_request_id);
            }
            ?>
            <div class="col-md-6">
                <div class="form-group" app-field-wrapper="title">
                    <label for="template_id" class="control-label"><?php echo _l('Select one template'); ?></label>
                    <select class="form-control" id="template_id" name="template_id"
                            aria-label="<?php echo _l('mestimate_select_template'); ?>" onchange="selectTemplate()"
                            data-width="100%">>
                        <option selected><?php echo _l('Select one template'); ?></option>
                        <?php
                        $templates = (isset($templates)) ? $templates : [];
                        echo ' <option value="0">Emty template</option>';
                        for ($i = 0; $i < count($templates); $i++) {
                            $selected = (isset($mestimate_id) && $mestimate_id == $templates[$i]['id']) ? 'selected' : '';
                            ?>
                            <option value="<?= $templates[$i]['id'] ?>" <?= $selected ?>><?= $templates[$i]['title'] ?></option>
                            <?php
                        }
                        ?>
                    </select>

                </div>

                <div class="form-group" app-field-wrapper="title">
                    <label for="client_id"
                           class="control-label"><?php echo _l('or_select_client'); ?></label>
                    <select id="client_id" name="client_id" class="form-control"
                            data-width="100%" onchange="selectClient()">
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