<div class="modal-header">
    <button type="button" class="close" onclick="close_modal_manually('._mestimate_file'); return false;">
        <span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title"><?php echo $file->subject; ?></h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-6 border-right project_file_area">
            <?php
            if ($file->staffid == get_staff_user_id() || has_permission('mestimates', '', 'create')) {
                ?>
                <?php echo render_input('file_subject', 'mestimate_discussion_subject', $file->subject, 'text', ['onblur' => 'update_file_data(' . $file->id . ')']); ?>
                <?php echo render_textarea('file_description', 'mestimate_discussion_description', $file->description, ['onblur' => 'update_file_data(' . $file->id . ')']); ?>
                <hr/>
                <?php
            } else {
                ?>
                <?php if (!empty($file->description)) {
                    ?>
                    <p class="bold"><?php echo _l('mestimate_discussion_description'); ?></p>
                    <p class="text-muted"><?php echo $file->description; ?></p>
                    <hr/>
                    <?php
                } ?>
                <?php
            } ?>
            <?php if (!empty($file->external) && $file->external == 'dropbox') {
                ?>
                <a href="<?php echo $file->external_link; ?>" target="_blank" class="btn btn-info mbot20">
                    <i class="fa fa-dropbox" aria-hidden="true"></i>
                    <?php echo _l('open_in_dropbox'); ?>
                </a>
                <br/>
                <?php
            } elseif (!empty($file->external) && $file->external == 'gdrive') {
                ?>
                <a href="<?php echo $file->external_link; ?>" target="_blank" class="btn btn-info mbot20">
                    <i class="fa fa-google" aria-hidden="true"></i>
                    <?php echo _l('open_in_google'); ?>
                </a>
                <br/>
                <?php
            } ?>
            <?php
            $path = FCPATH . 'uploads/mestimates' . '/' . $file->mestimate_id . '/' . $file->file_name;
            if (is_image($path)) {
                ?>
                <img src="<?php echo base_url('uploads/mestimates/' . $file->mestimate_id . '/' . $file->file_name); ?>"
                     class="img img-responsive">
                <?php
            } elseif (!empty($file->external) && !empty($file->thumbnail_link) && $file->external == 'dropbox') {
                ?>
                <img src="<?php echo optimize_dropbox_thumbnail($file->thumbnail_link); ?>"
                     class="img img-responsive">
                <?php
            } elseif (strpos($file->filetype, 'pdf') !== false && empty($file->external)) {
                ?>
                <iframe src="<?php echo base_url('uploads/mestimates/' . $file->mestimate_id . '/' . $file->file_name); ?>"
                        height="100%" width="100%" frameborder="0"></iframe>
                <?php
            } elseif (is_html5_video($path)) {
                ?>
                <video width="100%" height="100%"
                       src="<?php echo site_url('download/preview_video?path=' . protected_file_url_by_path($path) . '&type=' . $file->filetype); ?>"
                       controls>
                    Your browser does not support the video tag.
                </video>
                <?php
            } elseif (is_markdown_file($path) && $previewMarkdown = markdown_parse_preview($path)) {
                echo $previewMarkdown;
            } else {
                if (empty($file->external)) {
                    echo '<a href="' . site_url('uploads/mestimates/' . $file->mestimate_id . '/' . $file->file_name) . '" download>' . $file->file_name . '</a>';
                } else {
                    echo '<a href="' . $file->external_link . '" target="_blank">' . $file->file_name . '</a>';
                }
                echo '<p class="text-muted">' . _l('no_preview_available_for_file') . '</p>';
            } ?>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="modal-footer">
    <div style="float: left">
        <?php if (isset($file_next)) { ?>
            <button type="button" class="modal-title" style="margin-right: 15px;"
                    onclick="view_mestimate_file('<?= $file_next->id ?>', '<?= $file_next->mestimate_id ?>'); return false;">
                <i class="fa fa-arrow-left"></i></button>
        <?php } ?>
        <!--bootstrap card with 3 horizontal images-->

        <?php if (isset($file_next) && is_image(FCPATH . 'uploads/mestimates' . '/' . $file_next->mestimate_id . '/' . $file_next->file_name) || (!empty($file_next->external) && !empty($file_next->thumbnail_link))) {
            echo '<img onclick="view_mestimate_file(\'' . $file_next->id . '\', \'' . $file_next->mestimate_id . '\'); return false;" class="modal-title img-width-50 mestimate-file-image" src="' . mestimate_file_url(get_object_vars($file_next), true) . '" width="80">';
        } ?>

        <?php if (isset($file) && is_image(FCPATH . 'uploads/mestimates' . '/' . $file->mestimate_id . '/' . $file->file_name) || (!empty($file->external) && !empty($file->thumbnail_link))) {
            echo '<img class="modal-title mestimate-file-image" src="' . mestimate_file_url(get_object_vars($file), true) . '" width="50" height="50" style="border:1px solid black">';
        } ?>

        <?php if (isset($file_previous) && is_image(FCPATH . 'uploads/mestimates' . '/' . $file_previous->mestimate_id . '/' . $file_previous->file_name) || (!empty($file_previous->external) && !empty($file_previous->thumbnail_link))) {
            echo '<img onclick="view_mestimate_file(\'' . $file_previous->id . '\', \'' . $file_previous->mestimate_id . '\'); return false;" class="modal-title img-width-50 mestimate-file-image" src="' . mestimate_file_url(get_object_vars($file_previous), true) . '" width="80">';
        } ?>

        <?php if (isset($file_previous)) { ?>
            <button type="button" class="modal-title" style="margin-right: 15px;"
                    onclick="view_mestimate_file('<?= $file_previous->id ?>', '<?= $file_previous->mestimate_id ?>'); return false;">
                <i
                        class="fa fa-arrow-right"></i></button>
        <?php } ?>
    </div>
    <button type="button" class="btn btn-default"
            onclick="close_modal_manually('._mestimate_file'); return false;"><?php echo _l('close'); ?></button>
</div>