<div class="row mtop10">
    <div class="col-md-3">
        <?php /*echo format_mestimate_status($mestimate->status, 'mtop5'); */ ?>
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
            <?php if (!empty($mestimate->clientid)) { ?>
                <a href="#" class="mestimate-send-to-client btn btn-default btn-with-tooltip" data-toggle="tooltip"
                  data-placement="bottom"><span data-toggle="tooltip"><i class="fa fa-envelope"></i></span></a>
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
            <?php if ($mestimate->invoiceid == NULL) { ?>
                <?php if (has_permission('invoices', '', 'create') && !empty($mestimate->clientid)) { ?>
                    <div class="btn-group pull-right mleft5">
                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                            <?php echo _l('mestimate_convert_to_invoice'); ?> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?php echo admin_url('mestimates/convert_to_invoice/' . $mestimate->id . '?save_as_draft=true'); ?>"><?php echo _l('convert_and_save_as_draft'); ?></a>
                            </li>
                            <li class="divider">
                            <li>
                                <a href="<?php echo admin_url('mestimates/convert_to_invoice/' . $mestimate->id); ?>"><?php echo _l('convert'); ?></a>
                            </li>
                            </li>
                        </ul>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <a href="<?php echo admin_url('invoices/list_invoices/' . $mestimate->invoice->id); ?>"
                   data-placement="bottom" data-toggle="tooltip"
                   title="<?php echo _l('mestimate_invoiced_date', _dt($mestimate->invoiced_date)); ?>"
                   class="btn mleft10 btn-info"><?php echo format_invoice_number($mestimate->invoice->id); ?></a>
            <?php } ?>
            <!--<div class="btn-group">
                <button type="button" class="btn btn-default pull-left dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    <?php /*echo _l('more'); */?> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li>
                        <a href="<?php /*echo site_url('mestimate/' . $mestimate->id) */?>"
                           target="_blank">
                            <?php /*echo _l('view_mestimate_as_client'); */?>
                        </a>
                    </li>
                    <?php /*hooks()->do_action('after_mestimate_view_as_client_link', $mestimate); */?>
                    <?php /*if ((!empty($mestimate->due_date) && date('Y-m-d') < $mestimate->due_date && ($mestimate->status == 2 || $mestimate->status == 5)) && is_mestimates_expiry_reminders_enabled()) { */?>
                        <li>
                            <a href="<?php /*echo admin_url('mestimates/send_expiry_reminder/' . $mestimate->id); */?>">
                                <?php /*echo _l('send_expiry_reminder'); */?>
                            </a>
                        </li>
                    <?php /*} */?>
                    <li>
                        <a href="#" data-toggle="modal"
                           data-target="#sales_attach_file"><?php /*echo _l('invoice_attach_file'); */?></a>
                    </li>
                    <?php /*if (has_permission('mestimates', '', 'create')) { */?>
                        <li>
                            <a href="<?php /*echo admin_url('mestimates/copy/' . $mestimate->id); */?>">
                                <?php /*echo _l('copy_mestimate'); */?>
                            </a>
                        </li>
                    <?php /*} */?>
                    <?php /*if (!empty($mestimate->signature) && has_permission('mestimates', '', 'delete')) { */?>
                        <li>
                            <a href="<?php /*echo admin_url('mestimates/clear_signature/' . $mestimate->id); */?>"
                               class="_delete">
                                <?php /*echo _l('clear_signature'); */?>
                            </a>
                        </li>
                    <?php /*} */?>
                    <?php /*if (has_permission('mestimates', '', 'delete')) { */?>
                        <?php
/*                        if ((get_option('delete_only_on_last_mestimate') == 1 && is_last_mestimate($mestimate->id)) || (get_option('delete_only_on_last_mestimate') == 0)) { */?>
                            <li>
                                <a href="<?php /*echo admin_url('mestimates/delete/' . $mestimate->id); */?>"
                                   class="text-danger delete-text _delete"><?php /*echo _l('delete_mestimate_tooltip'); */?></a>
                            </li>
                            <?php
/*                        }
                    }
                    */?>
                </ul>
            </div>-->
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
                <?php if ($mestimate->status == 4 && !empty($mestimate->acceptance_firstname) && !empty($mestimate->acceptance_lastname) && !empty($mestimate->acceptance_email)) { ?>
                    <div class="col-md-12">
                        <div class="alert alert-info mbot15">
                            <?php echo _l('accepted_identity_info', array(
                                _l('mestimate_lowercase'),
                                '<b>' . $mestimate->acceptance_firstname . ' ' . $mestimate->acceptance_lastname . '</b> (<a href="mailto:' . $mestimate->acceptance_email . '">' . $mestimate->acceptance_email . '</a>)',
                                '<b>' . _dt($mestimate->acceptance_date) . '</b>',
                                '<b>' . $mestimate->acceptance_ip . '</b>' . (is_admin() ? '&nbsp;<a href="' . admin_url('mestimates/clear_acceptance_info/' . $mestimate->id) . '" class="_delete text-muted" data-toggle="tooltip" data-title="' . _l('clear_this_information') . '"><i class="fa fa-remove"></i></a>' : '')
                            )); ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($mestimate->project_id != 0) { ?>
                    <div class="col-md-12">
                        <h4 class="font-medium mbot15"><?php echo _l('related_to_project', array(
                                _l('mestimate_lowercase'),
                                _l('project_lowercase'),
                                '<a href="' . admin_url('projects/view/' . $mestimate->project_id) . '" target="_blank">' . $mestimate->project_data->name . '</a>',
                            )); ?></h4>
                    </div>
                <?php } ?>
                <div class="col-md-6 col-sm-6">
                    <h4 class="bold">
                        <?php
                        $tags = get_tags_in($mestimate->id, 'mestimate');
                        if (count($tags) > 0) {
                            echo '<i class="fa fa-tag" aria-hidden="true" data-toggle="tooltip" data-title="' . html_escape(implode(', ', $tags)) . '"></i>';
                        }
                        ?>
                        <a href="<?php echo admin_url('mestimates/mestimate/' . $mestimate->id); ?>">
                           <span id="mestimate-number">
                           <?php echo format_mestimate_number($mestimate->id); ?>
                           </span>
                        </a>
                    </h4>
                    <address>
                        <?php echo format_organization_info(); ?>
                    </address>
                </div>
                <div class="col-sm-6 text-right">
                    <span class="bold"><?php echo _l('mestimate_to'); ?>:</span>
                    <address>
                        <?php echo format_customer_info($mestimate, 'mestimate', 'billing', true); ?>
                    </address>
                    <?php if ($mestimate->include_shipping == 1 && $mestimate->show_shipping_on_mestimate == 1) { ?>
                        <span class="bold"><?php echo _l('ship_to'); ?>:</span>
                        <address>
                            <?php echo format_customer_info($mestimate, 'mestimate', 'shipping'); ?>
                        </address>
                    <?php } ?>
                    <p class="no-mbot">
                           <span class="bold">
                           <?php echo _l('mestimate_data_date'); ?>:
                           </span>
                        <?php echo $mestimate->date; ?>
                    </p>
                    <?php if (!empty($mestimate->expirydate)) { ?>
                        <p class="no-mbot">
                            <span class="bold"><?php echo _l('mestimate_data_expiry_date'); ?>:</span>
                            <?php echo $mestimate->expirydate; ?>
                        </p>
                    <?php } ?>
                    <?php if (!empty($mestimate->reference_no)) { ?>
                        <p class="no-mbot">
                            <span class="bold"><?php echo _l('reference_no'); ?>:</span>
                            <?php echo $mestimate->reference_no; ?>
                        </p>
                    <?php } ?>
                    <?php if ($mestimate->sale_agent != 0 && get_option('show_sale_agent_on_mestimates') == 1) { ?>
                        <p class="no-mbot">
                            <span class="bold"><?php echo _l('sale_agent_string'); ?>:</span>
                            <?php echo get_staff_full_name($mestimate->sale_agent); ?>
                        </p>
                    <?php } ?>
                    <?php if ($mestimate->project_id != 0 && get_option('show_project_on_mestimate') == 1) { ?>
                        <p class="no-mbot">
                            <span class="bold"><?php echo _l('project'); ?>:</span>
                            <?php echo get_project_name_by_id($mestimate->project_id); ?>
                        </p>
                    <?php } ?>
                    <?php $pdf_custom_fields = get_custom_fields('mestimate', array('show_on_pdf' => 1));
                    foreach ($pdf_custom_fields as $field) {
                        $value = get_custom_field_value($mestimate->id, $field['id'], 'mestimate');
                        if ($value == '') {
                            continue;
                        } ?>
                        <p class="no-mbot">
                            <span class="bold"><?php echo $field['name']; ?>: </span>
                            <?php echo $value; ?>
                        </p>
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <?php
                        $items = get_items_table_data($mestimate, 'mestimate', 'html', true);
                        echo $items->table();
                        ?>
                    </div>
                </div>
                <div class="col-md-5 col-md-offset-7">
                    <table class="table text-right">
                        <tbody>
                        <tr id="subtotal">
                            <td><span class="bold"><?php echo _l('mestimate_subtotal'); ?></span>
                            </td>
                            <td class="subtotal">
                                <?php echo app_format_money($mestimate->subtotal, $mestimate->currency_name); ?>
                            </td>
                        </tr>
                        <?php if (is_sale_discount_applied($mestimate)) { ?>
                            <tr>
                                <td>
                                    <span class="bold"><?php echo _l('mestimate_discount'); ?>
                                        <?php if (is_sale_discount($mestimate, 'percent')) { ?>
                                            (<?php echo app_format_number($mestimate->discount_percent, true); ?>%)
                                        <?php } ?></span>
                                </td>
                                <td class="discount">
                                    <?php echo '-' . app_format_money($mestimate->discount_total, $mestimate->currency_name); ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php
                        foreach ($items->taxes() as $tax) {
                            echo '<tr class="tax-area"><td class="bold">' . $tax['taxname'] . ' (' . app_format_number($tax['taxrate']) . '%)</td><td>' . app_format_money($tax['total_tax'], $mestimate->currency_name) . '</td></tr>';
                        }
                        ?>
                        <?php if ((int)$mestimate->adjustment != 0) { ?>
                            <tr>
                                <td>
                                    <span class="bold"><?php echo _l('mestimate_adjustment'); ?></span>
                                </td>
                                <td class="adjustment">
                                    <?php echo app_format_money($mestimate->adjustment, $mestimate->currency_name); ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td><span class="bold"><?php echo _l('mestimate_total'); ?></span>
                            </td>
                            <td class="total">
                                <?php echo app_format_money($mestimate->total, $mestimate->currency_name); ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <?php if (count($mestimate->attachments) > 0) { ?>
                    <div class="clearfix"></div>
                    <hr/>
                    <div class="col-md-12">
                        <p class="bold text-muted"><?php echo _l('mestimate_files'); ?></p>
                    </div>
                    <?php foreach ($mestimate->attachments as $attachment) {
                        $attachment_url = site_url('download/file/sales_attachment/' . $attachment['attachment_key']);
                        if (!empty($attachment['external'])) {
                            $attachment_url = $attachment['external_link'];
                        }
                        ?>
                        <div class="mbot15 row col-md-12" data-attachment-id="<?php echo $attachment['id']; ?>">
                            <div class="col-md-8">
                                <div class="pull-left"><i
                                            class="<?php echo get_mime_class($attachment['filetype']); ?>"></i></div>
                                <a href="<?php echo $attachment_url; ?>"
                                   target="_blank"><?php echo $attachment['file_name']; ?></a>
                                <br/>
                                <small class="text-muted"> <?php echo $attachment['filetype']; ?></small>
                            </div>
                            <div class="col-md-4 text-right">
                                <?php if ($attachment['visible_to_customer'] == 0) {
                                    $icon = 'fa fa-toggle-off';
                                    $tooltip = _l('show_to_customer');
                                } else {
                                    $icon = 'fa fa-toggle-on';
                                    $tooltip = _l('hide_from_customer');
                                }
                                ?>
                                <a href="#" data-toggle="tooltip"
                                   onclick="toggle_file_visibility(<?php echo $attachment['id']; ?>,<?php echo $mestimate->id; ?>,this); return false;"
                                   data-title="<?php echo $tooltip; ?>"><i class="<?php echo $icon; ?>"
                                                                           aria-hidden="true"></i></a>
                                <?php if ($attachment['staffid'] == get_staff_user_id() || is_admin()) { ?>
                                    <a href="#" class="text-danger"
                                       onclick="delete_mestimate_attachment(<?php echo $attachment['id']; ?>); return false;"><i
                                                class="fa fa-times"></i></a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
                <?php if ($mestimate->clientnote != '') { ?>
                    <div class="col-md-12 mtop15">
                        <p class="bold text-muted"><?php echo _l('mestimate_note'); ?></p>
                        <p><?php echo $mestimate->clientnote; ?></p>
                    </div>
                <?php } ?>
                <?php if ($mestimate->terms != '') { ?>
                    <div class="col-md-12 mtop15">
                        <p class="bold text-muted"><?php echo _l('terms_and_conditions'); ?></p>
                        <p><?php echo $mestimate->terms; ?></p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="tab_tasks">
        <?php init_relation_tasks_table(array('data-new-rel-id' => $mestimate->id, 'data-new-rel-type' => 'mestimate')); ?>
    </div>
    <div role="tabpanel" class="tab-pane" id="tab_reminders">
        <a href="#" data-toggle="modal" class="btn btn-info"
           data-target=".reminder-modal-mestimate-<?php echo $mestimate->id; ?>"><i
                    class="fa fa-bell-o"></i> <?php echo _l('mestimate_set_reminder_title'); ?></a>
        <hr/>
        <?php render_datatable(array(_l('reminder_description'), _l('reminder_date'), _l('reminder_staff'), _l('reminder_is_notified')), 'reminders'); ?>
        <?php $this->load->view('admin/includes/modals/reminder', array('id' => $mestimate->id, 'name' => 'mestimate', 'members' => $members, 'reminder_title' => _l('mestimate_set_reminder_title'))); ?>
    </div>
    <div role="tabpanel" class="tab-pane" id="tab_emails_tracking">
        <?php
        $this->load->view('admin/includes/emails_tracking', array(
                'tracked_emails' =>
                    get_tracked_emails($mestimate->id, 'mestimate'))
        );
        ?>
    </div>
    <div role="tabpanel" class="tab-pane" id="tab_notes">
        <?php echo form_open(admin_url('mestimates/add_note/' . $mestimate->id), array('id' => 'sales-notes', 'class' => 'mestimate-notes-form')); ?>
        <?php echo render_textarea('description'); ?>
        <div class="text-right">
            <button type="submit" class="btn btn-info mtop15 mbot15"><?php echo _l('mestimate_add_note'); ?></button>
        </div>
        <?php echo form_close(); ?>
        <hr/>
        <div class="panel_s mtop20 no-shadow" id="sales_notes_area">
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="tab_activity">
        <div class="row">
            <div class="col-md-12">
                <?php
                if ($activity) {
                    ?>
                    <div class="activity-feed">
                        <?php foreach ($activity as $activity) {
                            $_custom_data = false;
                            ?>
                            <div class="feed-item" data-sale-activity-id="<?php echo $activity['id']; ?>">
                                <div class="date">
                              <span class="text-has-action" data-toggle="tooltip"
                                    data-title="<?php echo _dt($activity['date']); ?>">
                              <?php echo time_ago($activity['date']); ?>
                              </span>
                                </div>
                                <div class="text">
                                    <?php if (is_numeric($activity['staffid']) && $activity['staffid'] != 0) { ?>
                                        <a href="<?php echo admin_url('profile/' . $activity["staffid"]); ?>">
                                            <?php echo staff_profile_image($activity['staffid'], array('staff-profile-xs-image pull-left mright5'));
                                            ?>
                                        </a>
                                    <?php } ?>
                                    <?php
                                    $additional_data = '';
                                    if (!empty($activity['additional_data'])) {
                                        $additional_data = unserialize($activity['additional_data']);
                                        $i = 0;
                                        foreach ($additional_data as $data) {
                                            if (strpos($data, '<original_status>') !== false) {
                                                $original_status = get_string_between($data, '<original_status>', '</original_status>');
                                                $additional_data[$i] = format_mestimate_status($original_status, '', false);
                                            } else if (strpos($data, '<new_status>') !== false) {
                                                $new_status = get_string_between($data, '<new_status>', '</new_status>');
                                                $additional_data[$i] = format_mestimate_status($new_status, '', false);
                                            } else if (strpos($data, '<status>') !== false) {
                                                $status = get_string_between($data, '<status>', '</status>');
                                                $additional_data[$i] = format_mestimate_status($status, '', false);
                                            } else if (strpos($data, '<custom_data>') !== false) {
                                                $_custom_data = get_string_between($data, '<custom_data>', '</custom_data>');
                                                unset($additional_data[$i]);
                                            }
                                            $i++;
                                        }
                                    }
                                    $_formatted_activity = _l($activity['description'], $additional_data);
                                    if ($_custom_data !== false) {
                                        $_formatted_activity .= ' - ' . $_custom_data;
                                    }
                                    if (!empty($activity['full_name'])) {
                                        $_formatted_activity = $activity['full_name'] . ' - ' . $_formatted_activity;
                                    }
                                    echo $_formatted_activity;
                                    if (is_admin()) {
                                        echo '<a href="#" class="pull-right text-danger" onclick="delete_sale_activity(' . $activity['id'] . '); return false;"><i class="fa fa-remove"></i></a>';
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="tab_views">
        <?php
        $views_activity = get_views_tracking('mestimate', $mestimate->id);
        if (count($views_activity) === 0) {
            echo '<h4 class="no-mbot">' . _l('not_viewed_yet', _l('mestimate_lowercase')) . '</h4>';
        }
        foreach ($views_activity as $activity) { ?>
            <p class="text-success no-margin">
                <?php echo _l('view_date') . ': ' . _dt($activity['date']); ?>
            </p>
            <p class="text-muted">
                <?php echo _l('view_ip') . ': ' . $activity['view_ip']; ?>
            </p>
            <hr/>
        <?php } ?>
    </div>
</div>