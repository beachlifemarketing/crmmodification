<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel_s accounting-template estimate">
    <div class="panel-body">
        <div class="row">
            <?php echo form_hidden('mestimate_id', (isset($mestimate)) ? $mestimate->id : 0); ?>
            <div class="col-md-6">

                <div class="f_client_id" id="template_list">
                    <?php
                    $this->load->view('mestimates/includes/_template_list');
                    ?>
                </div>


                <div class="f_client_id">
                    <div class="form-group select-placeholder">
                        <label for="client_id"
                               class="control-label"><?php echo _l('client_select_template'); ?></label>
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

    <div class="col-md-12 panel-body mtop10" style="margin-bottom: 20px;">
        <div class="col-md-12">
            <div class="form-group" app-field-wrapper="client_note">
                <label for="client_note" class="control-label"><?php echo _l('client_note'); ?></label>
                <textarea rows="6" id="client_note" name="client_note"
                          class="form-control "><?php
                    if (isset($mestimate) && $mestimate->client_note != '') {
                        echo $mestimate->client_note;
                    } elseif (get_option('predefined_clientnote_estimate') != null) {
                        echo get_option('predefined_clientnote_estimate');
                    }
                    ?></textarea>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group" app-field-wrapper="term_and_condition">
                <label for="term_and_condition" class="control-label"><?php echo _l('term_and_condition'); ?></label>
                <textarea rows="6" id="term_and_condition" name="term_and_condition"
                          class="form-control "><?php
                    if (isset($mestimate) && $mestimate->term_and_condition != '') {
                        echo $mestimate->term_and_condition;
                    } elseif (get_option('predefined_terms_estimate') != null) {
                        echo get_option('predefined_terms_estimate');
                    }
                    ?>
                </textarea>
            </div>
        </div>
    </div>

</div>