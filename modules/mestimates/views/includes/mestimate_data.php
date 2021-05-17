<div class="row">
    <?php
    echo form_open($this->uri->uri_string(), array('id' => 'estimate-form', 'class' => '_transaction_form'));
    echo form_hidden('mestimate_id_old', (isset($mestimate_id_old) ? $mestimate_id_old : 0));
    ?>
    <input type="hidden" name="template_name" id="template_name"
           value="<?= (isset($template_name) ? $template_name : '') ?>"/>
    <div class="col-md-12" id="row_mestimates_template">
        <?php $this->load->view('mestimates/includes/mestimates_template'); ?>
    </div>
    <div class="col-md-12" id="row_file_mestimates">
        <?php $this->load->view('mestimates/includes/mestimate_files'); ?>
    </div>
    <?php echo form_close(); ?>
    <div class="clearfix"></div>
    <div class="col-md-12 mtop15">
        <div class="panel-body bottom-transaction">
            <div class="btn-bottom-toolbar text-right btn-toolbar-container-out">
                <div class="btn-bottom-toolbar btn-toolbar-container-out text-right">
                    <button class="btn btn-info only-save customer-form-submiter" onclick="saveMestimate()">
                        <?php
                        if (isset($mestimate_id_old) && $mestimate_id_old != 0 && $mestimate_id_old != '') {
                            echo 'Update';
                        } else {
                            echo 'Create';
                        }

                        ?> </button>
                    <button type="button" class="btn btn-info save-and-add-contact customer-form-submiter"
                            data-toggle="modal" data-target="#exampleModal"> <?php echo _l('save_template'); ?></button>
                </div>

            </div>
        </div>
        <div class="btn-bottom-pusher"></div>
    </div>
</div>