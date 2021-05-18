<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content" id="id_content_mestimate">
        <?php $this->load->view('mestimates/includes/mestimate_data'); ?>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Save as template?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="template-name" class="col-form-label">Template name:</label>
                        <input type="text" class="form-control" id="template-name" onkeyup="updateTemplateName()"
                               onchange="updateTemplateName()">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="id_button_exampleModal" data-dismiss="modal">Close
                </button>
                <button type="button" class="btn btn-primary" onclick="saveTemplateMestimate()">Save</button>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script src="<?php echo base_url('modules/mestimates/assets/blm.js'); ?>"></script>
<script src="<?php echo base_url('modules/mestimates/assets/mestimates.js'); ?>"></script>
<script type="text/javascript">
    function updateTemplateName() {
        var name = $('#template-name').val();
        $('#template_name').val(name);
    }

    function selectTemplate() {
        var template_id = $('select#template_id').val();
        $("#hid_mestimate_id").val(template_id);
        $("#mestimate_id").val(template_id);
        $('input[name="mestimate_id"]').val(template_id);
        var url = admin_url + 'mestimates/mestimate?rtype=json&change=template';
        simpleAjaxPostUpload(
            url,
            '#id_content_mestimate',
            function (res) {
                $('#id_content_mestimate').html(res.data_template);
                alert_float('success', res.errorMessage);
            },
            function (res) {
                alert_float('danger', res.errorMessage);
            },
            function (res) {
                alert_float('danger', res.errorMessage);
            }
        );
        //window.location.href = admin_url + 'mestimates/mestimate/' + $('input[name="mestimate_id"]').val() + '/' + clientId;
    }

    function selectClient() {
        var clientId = $('#client_id').val();
        $("#hid_client_id").val(clientId);
        $("#client_id").val(clientId);
        $('input[name="client_id"]').val(clientId);
        simpleAjaxPostUpload(
            admin_url + 'mestimates/mestimate?rtype=json&change=client',
            '#id_content_mestimate',
            function (res) {
                $('#div_address').html(res.view_address);
                $('#row_file_mestimates').html(res.view_file);
                alert_float('success', res.errorMessage);
            },
            function (res) {
                alert_float('danger', res.errorMessage);
            },
            function (res) {
                alert_float('danger', res.errorMessage);
            }
        );
        //window.location.href = admin_url + 'mestimates/mestimate/' + $('input[name="mestimate_id"]').val() + '/' + clientId;
    }

    function saveMestimate() {
        var url = admin_url + 'mestimates/estimate_do?rtype=json';
        simpleAjaxPostUpload(
            url, '#id_content_mestimate',
            function (res) {
                alert_float('success', res.errorMessage);
            },
            function (res) {
                alert_float('danger', res.errorMessage);
            },
            function (res) {
                alert_float('danger', res.errorMessage);
            }
        );
    }

    function saveTemplateMestimate() {
        var url = admin_url + 'mestimates/estimate_do?rtype=json&sat=template';
        $('#exampleModal').modal("toggle");
        simpleAjaxPostUpload(
            url, '#id_content_mestimate',
            function (res) {
                $('#div_address').html(res.view_address);
                $('#row_file_mestimates').html(res.view_file);
                alert_float('success', res.errorMessage);
            },
            function (res) {
                alert_float('danger', res.errorMessage);
            },
            function (res) {
                alert_float('danger', res.errorMessage);
            }
        );
    }

    function computeLineAmount() {
        var sub_total = 0;
        var discount = $('#discount').val();
        var paid_by_customer_percent = $('#paid_by_customer_percent').val();
        $('input[name="detail[amount][]"]').each(function () {
            var qty = $(this).closest('tr').children('td:eq(3)').children('input').val();
            var unix = $(this).closest('tr').children('td:eq(4)').children('input').val();
            var duration = $(this).closest('tr').children('td:eq(5)').children('input').val();
            var amount = qty * unix * duration;
            $(this).val(amount);
            sub_total += amount;
        });
        $('#sub_total').val(parseFloat(sub_total).toFixed(2));
        var total_discount = (sub_total * discount) / 100;
        var total_affter_discount = sub_total - total_discount;
        var total_due_amount = (total_affter_discount * paid_by_customer_percent) / 100;

        $('#balance_due').val(parseFloat(total_due_amount).toFixed(2));
        $('#discount_val').val(parseFloat(total_discount).toFixed(2));
        $('#total').val(parseFloat(total_affter_discount).toFixed(2));
        $('#balance_due_val').val(parseFloat(total_affter_discount - total_due_amount).toFixed(2));
    }

    function addRow(element) {
        var $tr = element.closest('.tr_parent');
        var $clone = $tr.clone();
        $clone.find('.button_clone').removeClass('hidden');
        $clone.find(':text').val('');
        $tr.after($clone);
    }

    function removeRow(element) {
        var $tr = element.closest('.tr_parent');
        $tr.remove();
    }

</script>
<script type="text/javascript">
    function doRemoveFile(fileId) {
        simpleAjaxPostUpload(
            admin_url + 'mestimates/remove_file/?rtype=json&id=' + fileId,
            '#estimate-form',
            function (res) {
                $('#row_file_mestimates').html(res.view_file);
                alert_float('success', '<?=_l('remove_file_success')?>');
            },
            function (res) {
                alert_float('danger', res.errorMessage);
            },
            function (res) {
                alert_float('danger', res.errorMessage);
            }
        );
    }

    function saveFile() {
        simpleAjaxPostUpload(
            admin_url + 'mestimates/upload_file/?rtype=json',
            '#estimate-form',
            function (res) {
                $('#row_file_mestimates').html(res.view_file);
                alert_float('success', '<?=_l('save_file_success')?>');
            },
            function (res) {
                alert_float('danger', res.errorMessage);
            },
            function (res) {
                alert_float('danger', res.errorMessage);
            }
        );
    }


    function view_mestimate_file(id) {
        $('#mestimate_file_data').empty();
        $("#mestimate_file_data").load(admin_url + 'mestimates/file/' + id, function (response, status, xhr) {
            if (status == "error") {
                alert_float('danger', xhr.statusText);
            }
        });
    }

</script>

</body>
</html>