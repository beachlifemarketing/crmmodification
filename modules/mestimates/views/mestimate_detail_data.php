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
            <?php if (!empty($mestimate->clientid)) { ?>
                <a href="#" class="mestimate-send-to-client btn btn-default btn-with-tooltip" data-toggle="tooltip"
                   title="<?php echo $_tooltip; ?>" data-placement="bottom"><span data-toggle="tooltip"
                                                                                  data-title="<?php echo $_tooltip_already_send; ?>"><i
                                class="fa fa-envelope"></i></span></a>
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
                    <h4 class="bold">
                        <a href="https://localhost/blm/admin/estimates/estimate/1">
                           <span id="estimate-number">
                           <?= $mestimate->id ?></span>
                        </a>
                    </h4>
                    <address>
                    </address>
                </div>
                <div class="col-sm-6 text-right">
                    <span class="bold">To:</span>
                    <address>
                        <a href="https://localhost/blm/admin/clients/client/8"
                           target="_blank"><b><?= (isset($contact) ? $contact->firstname : '') . " " . (isset($contact) ? $contact->lastname : '') ?>
                                <br><?=(isset($client) ? $client->company : '')?></b></a><br> <?=(isset($client) ? $client->address : '')?><br> 1212 121<br><br> VAT Number: 123123
                    </address>
                    <span class="bold">Ship to:</span>
                    <address>
                        212<br> 1 12
                    </address>
                    <p class="no-mbot">
                           <span class="bold">
                           Estimate Date:
                           </span>
                        2021-05-14 </p>
                    <p class="no-mbot">
                        <span class="bold">Expiry Date:</span>
                        2021-05-21 </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table items items-preview estimate-items-preview" data-type="estimate">
                            <thead>
                            <tr>
                                <th align="center">#</th>
                                <th class="description" width="38%" align="left">Item</th>
                                <th align="right">Qty</th>
                                <th align="right">Rate</th>
                                <th align="right">Tax</th>
                                <th align="right">Amount</th>
                            </tr>
                            </thead>
                            <tbody class="ui-sortable">
                            <tr class="sortable" data-item-id="1">
                                <td class="dragger item_no ui-sortable-handle" align="center" width="5%">1</td>
                                <td class="description" align="left;" width="33%"><span style="font-size:px;"><strong>Item 3 Group 1</strong></span>
                                </td>
                                <td align="right" width="9%">1</td>
                                <td align="right" width="9%">122.00</td>
                                <td align="right" width="9%">0%</td>
                                <td class="amount" align="right" width="9%">122.00</td>
                            </tr>
                            <tr class="sortable" data-item-id="2">
                                <td class="dragger item_no ui-sortable-handle" align="center" width="5%">2</td>
                                <td class="description" align="left;" width="33%"><span style="font-size:px;"><strong>Item 3 Group 1</strong></span>
                                </td>
                                <td align="right" width="9%">1</td>
                                <td align="right" width="9%">122.00</td>
                                <td align="right" width="9%">0%</td>
                                <td class="amount" align="right" width="9%">122.00</td>
                            </tr>
                            <tr class="sortable" data-item-id="3">
                                <td class="dragger item_no ui-sortable-handle" align="center" width="5%">3</td>
                                <td class="description" align="left;" width="33%"><span style="font-size:px;"><strong>Item 3 Group 1</strong></span>
                                </td>
                                <td align="right" width="9%">1</td>
                                <td align="right" width="9%">122.00</td>
                                <td align="right" width="9%">0%</td>
                                <td class="amount" align="right" width="9%">122.00</td>
                            </tr>
                            <tr class="sortable" data-item-id="4">
                                <td class="dragger item_no ui-sortable-handle" align="center" width="5%">4</td>
                                <td class="description" align="left;" width="33%"><span style="font-size:px;"><strong>Item 2 Group 1</strong></span>
                                </td>
                                <td align="right" width="9%">1</td>
                                <td align="right" width="9%">12.00</td>
                                <td align="right" width="9%">0%</td>
                                <td class="amount" align="right" width="9%">12.00</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-5 col-md-offset-7">
                    <table class="table text-right">
                        <tbody>
                        <tr id="subtotal">
                            <td><span class="bold">Sub Total</span>
                            </td>
                            <td class="subtotal">
                                $378.00
                            </td>
                        </tr>
                        <tr>
                            <td><span class="bold">Total</span>
                            </td>
                            <td class="total">
                                $378.00
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
                    <div class="col-md-8">
                        <div class="pull-left"><i class="mime mime-image"></i>
                        </div>
                        <a href="https://localhost/blm/download/file/sales_attachment/c1ec5603c758f680a620f7f8fb5eb05c"
                           target="_blank">399468-20.jpg</a>
                        <br>
                        <small class="text-muted"> image/jpeg</small>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="#" data-toggle="tooltip" onclick="toggle_file_visibility(3,1,this); return false;"
                           data-title="Show to customer"><i class="fa fa-toggle-off" aria-hidden="true"></i></a>
                        <a href="#" class="text-danger" onclick="delete_estimate_attachment(3); return false;"><i
                                    class="fa fa-times"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>