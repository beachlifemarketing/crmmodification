<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php
            echo form_open($this->uri->uri_string(), array('id' => 'estimate-form', 'class' => '_transaction_form'));
            if (isset($mestimate)) {
                echo form_hidden('isedit');
            }
            ?>
            <div class="col-md-12">
                <?php $this->load->view('mestimates/mestimates_template'); ?>
            </div>
            <?php echo form_close(); ?>
            <div class="col-md-12" id="row_file_mestimates">
                <?php
                include('mestimate_files.php');
                ?>
            </div>
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
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function () {
        validate_estimate_form();
        // Init accountacy currency symbol
        init_ajax_project_search_by_customer_id();
        // Maybe items ajax search
        init_ajax_search('items', '#item_select.ajax-search', undefined, admin_url + 'items/search');
    });

    function saveMestimate() {
        var url = admin_url + 'mestimates/mestimate/' + $('input[name="mestimate_id"]').val() + '/' + $('#client_id').val();
        simpleAjaxPostUpload(
            url, '#estimate-form', function (res) {
                console.log(res.errorCode);
            }
        );
    }

    function saveTemplateMestimate() {

    }

    function computeLineAmount(element) {

    }

</script>
</body>
</html>
