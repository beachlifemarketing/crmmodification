<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$client_id = (isset($mestimate->client_id) ? $mestimate->client_id : (isset($client_id)) ? $client_id : null);
if (isset($client_id) && $client_id != '') {
    $mestimate_id = (isset($mestimate) ? $mestimate->id : (isset($mestimate_id) ? $mestimate_id : 0));

    ?>
    <div id="mestimate-files-upload">
        <input type="file" name="file" id="file_estimate" multiple/>
        <button class="btn btn-info only-save customer-form-submiter" type="button" onclick="saveFile();return false;">
            Save File
        </button>
        <input type="hidden" name="client_id" id="hid_client_id" value="<?php echo $client_id ?>"/>
        <input type="hidden" name="mestimate_id" id="hid_mestimate_id" value="<?php echo $mestimate_id ?>"/>
        <?php echo form_close(); ?>
        <div class="clearfix"></div>
        <table class="table dt-table scroll-responsive table-mestimate-files" data-order-col="7" data-order-type="desc">
            <thead>
            <tr>
                <th data-orderable="false"><span class="hide"> - </span>
                    <div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all"
                                                                      data-to-table="mestimate-files"><label></label>
                    </div>
                </th>
                <th><?php echo _l('mestimate_file_filename'); ?></th>
                <th><?php echo _l('mestimate_file__filetype'); ?></th>
                <th><?php echo _l('mestimate_file_uploaded_by'); ?></th>
                <th><?php echo _l('mestimate_file_dateadded'); ?></th>
                <th><?php echo _l('options'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $files = (isset($files) ? $files : []);
            ?>
            <?php foreach ($files as $file) {
                $path = FCPATH . 'uploads/mestimates' . '/' . $mestimate_id . '/' . $file['file_name'];
                ?>
                <tr>
                    <td>
                        <div class="checkbox"><input
                                    type="checkbox" <?= ($mestimate_id != 0 && $file['mestimate_id'] == $mestimate_id) ?>
                                    name="image_ids[]" value="<?php echo $file['id']; ?>"><label></label>
                        </div>
                    </td>
                    <td data-order="<?php echo $file['file_name']; ?>">
                        <a href="#"
                           onclick="view_mestimate_file(<?php echo $file['id']; ?>,<?php echo $file['mestimate_id']; ?>); return false;">
                            <?php if (is_image($path) || (!empty($file['external']) && !empty($file['thumbnail_link']))) {
                                echo '<img class="mestimate-file-image img-table-loading" src="' . mestimate_file_url($file, true) . '" data-orig="' . mestimate_file_url($file, true) . '" width="100">';
                            }
                            echo $file['subject']; ?></a>
                    </td>
                    <td data-order="<?php echo $file['filetype']; ?>"><?php echo $file['filetype']; ?></td>
                    <td>
                        <?php if ($file['staffid'] != 0) {
                            $_data = '<a href="' . admin_url('staff/profile/' . $file['staffid']) . '">' . staff_profile_image($file['staffid'], array(
                                    'staff-profile-image-small'
                                )) . '</a>';
                            $_data .= ' <a href="' . admin_url('staff/member/' . $file['staffid']) . '">' . get_staff_full_name($file['staffid']) . '</a>';
                            echo $_data;
                        } else {
                            echo ' <img src="' . contact_profile_image_url($file['contact_id'], 'thumb') . '" class="client-profile-image-small mrigh5">
             <a href="' . admin_url('clients/client/' . get_user_id_by_contact_id($file['contact_id']) . '?contactid=' . $file['contact_id']) . '">' . get_contact_full_name($file['contact_id']) . '</a>';
                        }
                        ?>
                    </td>
                    <td data-order="<?php echo $file['dateadded']; ?>"><?php echo _dt($file['dateadded']); ?></td>
                    <td>
                        <?php if ($file['staffid'] == get_staff_user_id() || has_permission('mestimates', '', 'delete')) { ?>
                            <a href="#" onclick="doRemoveFile(<?= $file['id'] ?>); return false;"
                               class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <div id="mestimate_file_data"></div>
    </div>
    <?php
}
?>

