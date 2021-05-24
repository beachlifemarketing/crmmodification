<div class="row mtop10">
    <div class="col-md-3">
        <?php
        ?>
    </div>
    <div class="col-md-9">
        <div class="visible-xs">
            <div class="mtop10"></div>
        </div>
        <div class="pull-right _buttons">
            <?php if (has_permission('mestimates', '', 'edit')) { ?>
                <a href="<?php echo admin_url('mestimates/mestimate/' . $mestimate->id); ?>"
                   class="btn btn-default btn-with-tooltip" data-toggle="tooltip"
                   title="<?php echo _l('edit_mestimate_tooltip'); ?>" data-placement="bottom"><i
                            class="fa fa-pencil-square-o"></i></a>
            <?php } ?>
            <div class="btn-group">
                <?php
                echo form_open('', array('id' => 'id_to_view_form'));
                echo form_hidden('mestimate_id', $mestimate->id);
                echo form_hidden('load_model_send_email', true);
                echo form_close();
                ?>
                <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                   aria-expanded="false"><i class="fa fa-file-pdf-o"></i><?php if (is_mobile()) {
                        echo ' PDF';
                    } ?> <span class="caret"></span></a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li class="hidden-xs"><a
                                href="<?php echo admin_url('mestimates/pdf/' . $mestimate->id . '?output_type=I'); ?>"><?php echo _l('view_pdf'); ?></a>
                    </li>
                    <li class="hidden-xs"><a
                                href="<?php echo admin_url('mestimates/pdf/' . $mestimate->id . '?output_type=I'); ?>"
                                target="_blank"><?php echo _l('view_pdf_in_new_window'); ?></a></li>
                    <li>
                        <a href="<?php echo admin_url('mestimates/pdf/' . $mestimate->id); ?>"><?php echo _l('download'); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo admin_url('mestimates/pdf/' . $mestimate->id . '?print=true'); ?>"
                           target="_blank">
                            <?php echo _l('print'); ?>
                        </a>
                    </li>
                </ul>
            </div>
            <?php
            $_tooltip = _l('mestimate_sent_to_email_tooltip');
            $_tooltip_already_send = '';
            if ($mestimate->sent == 1) {
                $_tooltip_already_send = _l('mestimate_already_send_to_client_tooltip', time_ago($mestimate->datesend));
            }
            ?>
            <?php if (!empty($mestimate->client_id)) { ?>
                <a href="#" onclick="sendEmailMestimate(); return false;" title="<?php echo $_tooltip; ?>"
                   data-placement="bottom">
                    <span data-toggle="tooltip" data-title="<?php echo $_tooltip_already_send; ?>">
                        <i class="fa fa-envelope"></i></span></a>
            <?php } ?>
            <div class="btn-group">
                <button type="button" class="btn btn-default pull-left dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    <?php echo _l('more'); ?> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li>
                        <a href="<?php echo site_url('mestimate/' . $mestimate->id) ?>"
                           target="_blank">
                            <?php echo _l('view_mestimate_as_client'); ?>
                        </a>
                    </li>
                    <?php hooks()->do_action('after_mestimate_view_as_client_link', $mestimate); ?>
                    <?php if ((!empty($mestimate->due_date) && date('Y-m-d') < $mestimate->due_date && ($mestimate->status == 2 || $mestimate->status == 5)) && is_mestimates_expiry_reminders_enabled()) { ?>
                        <li>
                            <a href="<?php echo admin_url('mestimates/send_expiry_reminder/' . $mestimate->id); ?>">
                                <?php echo _l('send_expiry_reminder'); ?>
                            </a>
                        </li>
                    <?php } ?>
                    <li>
                        <a href="#" data-toggle="modal"
                           data-target="#sales_attach_file"><?php echo _l('invoice_attach_file'); ?></a>
                    </li>
                    <?php if (has_permission('mestimates', '', 'create')) { ?>
                        <li>
                            <a href="<?php echo admin_url('mestimates/copy/' . $mestimate->id); ?>">
                                <?php echo _l('copy_mestimate'); ?>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (!empty($mestimate->signature) && has_permission('mestimates', '', 'delete')) { ?>
                        <li>
                            <a href="<?php echo admin_url('mestimates/clear_signature/' . $mestimate->id); ?>"
                               class="_delete">
                                <?php echo _l('clear_signature'); ?>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (has_permission('mestimates', '', 'delete')) { ?>
                        <?php
                        if ((get_option('delete_only_on_last_mestimate') == 1 && is_last_mestimate($mestimate->id)) || (get_option('delete_only_on_last_mestimate') == 0)) { ?>
                            <li>
                                <a href="<?php echo admin_url('mestimates/delete/' . $mestimate->id); ?>"
                                   class="text-danger delete-text _delete"><?php echo _l('delete_mestimate_tooltip'); ?></a>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<hr class="hr-panel-heading"/>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane ptop10 active" id="tab_mestimate">
        <?php if (isset($mestimate->scheduled_email) && $mestimate->scheduled_email) { ?>
            <div class="alert alert-warning">
                <?php echo _l('invoice_will_be_sent_at', _dt($mestimate->scheduled_email->scheduled_at)); ?>
                <?php if (staff_can('edit', 'mestimates') || $mestimate->addedfrom == get_staff_user_id()) { ?>
                    <a href="#"
                       onclick="edit_mestimate_scheduled_email(<?php echo $mestimate->scheduled_email->id; ?>); return false;">
                        <?php echo _l('edit'); ?>
                    </a>
                <?php } ?>
            </div>
        <?php } ?>
        <div id="mestimate-preview">
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <div class="col-md-6 col-sm-6">
                        <?php get_company_logo(get_admin_uri() . '/') ?><br/>
                        <b><?= get_option('companyname'); ?></b><b/>
                        <b><a href="<?= get_option('main_domain'); ?>"><?= get_option('main_domain'); ?></a> </b>
                    </div>
                    <address>
                    </address>
                </div>
                <div class="col-sm-6 text-right">
                    <span class="bold" style="float: left; font-weight: bold">Customer Information</span>
                    <table border="1" id="tbl_info_company">
                        <tr>
                            <td>Job #:</td>
                            <td><?= (isset($mestimate) ? $mestimate->job_number : '') ?></td>
                        </tr>
                        <tr>
                            <td>Name:</td>
                            <td><?= (isset($contact) ? $contact->name = "firstname" : '') ?> <?= (isset($contact) ? $contact->name = "lastname" : '') ?></td>
                        </tr>
                        <tr>
                            <td>Phone #:</td>
                            <td><?= (isset($contact) ? $contact->phonenumber : '') ?></td>
                        </tr>
                        <tr>
                            <td>Claim #:</td>
                            <td><?= (isset($mestimate) ? $mestimate->job_number : '') ?></td>
                        </tr>
                        <tr>
                            <td>Policy #:</td>
                            <td><?= (isset($mestimate) ? $mestimate->claim_number : '') ?></td>
                        </tr>

                        <tr>
                            <td>Property Address:</td>
                            <td><?= (isset($client) ? $client->company : '') ?></td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><?= (isset($contact) ? $contact->email : '') ?></td>
                        </tr>
                    </table>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-12 " style="margin: 10px 0px 10px 0px">
                    <table border="1" id="tbl_info_company_2">
                        <tr>
                            <td>Representative</td>
                            <td>Date</td>
                            <td>Payment Options</td>
                            <td>Due Date</td>
                        </tr>
                        <tr>
                            <td><?= (isset($mestimate) ? $mestimate->representative : '') ?></td>
                            <td><?= (isset($mestimate) ? $mestimate->date : '') ?></td>
                            <td><?= (isset($mestimate) ? $mestimate->pymt_option : '') ?></td>
                            <td><?= (isset($mestimate) ? $mestimate->due_date : '') ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <div class="clearfix"></div>
                        <div class="col-sm-12 ">
                            <table style="width: 100%">
                                <tr>
                                    <td style="text-align: left; font-weight: bold">Receipt for Services</td>
                                    <td style="text-align: right; font-weight: bold">License No: MRSR2240</td>
                                </tr>
                            </table>
                        </div>
                        <table class="table items items-preview estimate-items-preview" data-type="estimate">
                            <thead>
                            <tr>
                                <th>AREA</th>
                                <th>SIZE</th>
                                <th>DESCRIPTION</th>
                                <th>QUANTITY</th>
                                <th>UNIT PRICE ($)</th>
                                <th>DURATION</th>
                                <th>AMOUNT ($)</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            for ($i = 0; $i < count($details); $i++) {
                                $detail = $details[$i];
                                ?>
                                <tr class="tr_parent">
                                    <td><?= $detail['area'] ?></td>
                                    <td><?= $detail['size'] ?></td>
                                    <td><?= $detail['description'] ?></td>
                                    <td><?= $detail['qty_unit'] ?></td>
                                    <td><?= $detail['px_unit'] ?></td>
                                    <td><?= $detail['duration'] ?></td>
                                    <td><?= $detail['amount'] ?></td>
                                </tr>
                                <?php
                            } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-5 col-md-offset-7">
                    <table class="table text-right">
                        <tbody>
                        <tr id="subtotal">
                            <td><span class="bold">REMEDIATION</span>
                            </td>
                            <td class="subtotal">
                                <?= (isset($mestimate) && isset($mestimate->sub_total)) ? $mestimate->sub_total : 0.00 ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="bold">DISCOUNT <?= (isset($mestimate) && isset($mestimate->discount)) ? $mestimate->discount : 0.00 ?></span>
                            </td>
                            <td class="total">
                                <?= (isset($mestimate) && isset($mestimate->discount_val)) ? $mestimate->discount_val : 0.00 ?>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="bold">TOTAL</span>
                            </td>
                            <td class="total">
                                <?= (isset($mestimate) && isset($mestimate->total)) ? $mestimate->total : 0.00 ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="bold">PERCENTAGE PAID <?= (isset($mestimate) && isset($mestimate->paid_by_customer_percent)) ? $mestimate->paid_by_customer_percent : 0.00 ?></span>
                            </td>
                            <td class="total">
                                <?= (isset($mestimate) && isset($mestimate->paid_by_customer_text)) ? $mestimate->paid_by_customer_text : "" ?>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="bold">PAID IN FULL -</span>
                            </td>
                            <td class="total">
                                <?= (isset($mestimate) && isset($mestimate->balance_due_val)) ? $mestimate->balance_due_val : "" ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="clearfix"></div>
                <hr>
                <div class="col-md-12">
                    <p class="bold text-muted">Estimate Files</p>
                </div>
                <div class="mbot15 row col-md-12" data-attachment-id="3">

                    <table class="table table-bordered table-striped mb-0 table-mestimate-files" data-order-col="7"
                           data-order-type="desc">
                        <thead>
                        <tr>
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
        </div>
    </div>
</div>