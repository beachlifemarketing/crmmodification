<div class="row">
    <?php
    echo form_open($this->uri->uri_string(), array('id' => 'estimate-form', 'class' => '_transaction_form'));
    if (isset($mestimate)) {
        echo form_hidden('isedit');
    }
    ?>
    <div class="col-md-12">
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
                        <?php echo _l('submit'); ?> </button>
                    <button class="btn btn-info save-and-add-contact customer-form-submiter"
                            onclick="saveTemplateMestimate()">
                        <?php echo _l('save_template'); ?>      </button>
                </div>

            </div>
        </div>
        <div class="btn-bottom-pusher"></div>
    </div>
</div>
