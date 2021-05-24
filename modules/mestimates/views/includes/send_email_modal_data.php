<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Do you want send email?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" id="form_send_email_mestimate">
            <?php
            echo form_open('', array('id' => 'mestimate_form_send_email'));
            echo form_hidden('mestimate_id', (isset($mestimate->id) ? $mestimate->id : 0));
            ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?php
                        $selected = array();
                        $contacts = $this->clients_model->get_contacts($mestimate->client_id, array('active' => 1));
                        echo render_select('sent_to[]', $contacts, array('email', 'email', 'firstname,lastname'), 'invoice_estimate_sent_to_email', $selected, array('multiple' => true), array(), '', '', false);
                        ?>
                    </div>
                    <?php echo render_input('cc', 'CC'); ?>
                    <hr/>
                    <div class="checkbox checkbox-primary">
                        <input type="checkbox" name="attach_pdf" id="attach_pdf" checked>
                        <label for="attach_pdf">Attach estimate PDF</label>
                    </div>
                    <hr/>
                    <h5 class="bold"><?php echo _l('invoice_send_to_client_preview_template'); ?></h5>
                    <hr/>
                    <?php echo render_textarea('email_template_custom', '', $template->message, [], [], '', 'tinymce'); ?>

                    <?php echo form_hidden('template_name', $template_name); ?>
                    <div class="col-md-12">
                        <p class="bold text-muted">M-Estimate Files</p>
                    </div>
                    <div class="mbot15 row col-md-12" data-attachment-id="3">

                        <table class="table table-bordered table-striped mb-0 table-mestimate-files" data-order-col="7"
                               data-order-type="desc">
                            <thead>
                            <tr>
                                <th style="min-width: 100px" data-orderable="false"><span class="hide"> - </span>
                                    <div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all"
                                                                                      data-to-table="mestimate-files"><label></label>
                                    </div>
                                </th>
                                <th style="min-width: 100px"><?php echo _l('mestimate_file_filename'); ?></th>
                                <th style="min-width: 100px"><?php echo _l('mestimate_file__filetype'); ?></th>
                                <th style="min-width: 100px"><?php echo _l('mestimate_file_dateadded'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($files as $file) {
                                $path = FCPATH . 'uploads/mestimates' . '/' . $file['contact_id'] . '/' . $file['file_name'];
                                ?>
                                <tr>
                                    <td>
                                        <div class="checkbox">
                                            <input type="checkbox" <?= ($mestimate_id != 0 && isset($fileMap[$file['id']]) && $fileMap[$file['id']] == $mestimate_id) ? 'checked' : '' ?>
                                                    name="image_ids[]" value="<?php echo $file['id']; ?>"><label></label>
                                        </div>
                                    </td>
                                    <td data-order="<?php echo $file['file_name']; ?>">
                                        <a href="<?= mestimate_file_url($file, true) ?>">
                                            <?php if (is_image($path) || (!empty($file['external']) && !empty($file['thumbnail_link']))) {
                                                echo '<img class="mestimate-file-image img-table-loading" src="' . mestimate_file_url($file, true) . '" data-orig="' . mestimate_file_url($file, true) . '" width="100">';
                                            }
                                            echo $file['subject']; ?></a>
                                    </td>
                                    <td data-order="<?php echo $file['filetype']; ?>"><?php echo $file['filetype']; ?></td>
                                    <td data-order="<?php echo $file['dateadded']; ?>"><?php echo _dt($file['dateadded']); ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>

                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="id_button_exampleModal" data-dismiss="modal">Cancel
            </button>
            <button type="button" id="button_send_email" class="btn btn-primary">OK</button>
        </div>
    </div>
</div>
<?php
if (isset($rtype) && $rtype === 'json') {
    ?>
    <script type="text/javascript">
        init_selectpicker();
    </script>
    <?php
}
?>