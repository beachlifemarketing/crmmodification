<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade _project_file" tabindex="-1" role="dialog" data-toggle="modal">
    <div class="modal-dialog full-screen-modal" role="document">
        <div class="modal-content" id="file_preview_content">
            <?php include_once '_file_data.php' ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?php $discussion_lang = get_project_discussions_language_array(); ?>
<script>
    var discussion_id = '<?php echo $file->id; ?>';
    var discussion_user_profile_image_url = '<?php echo $discussion_user_profile_image_url; ?>';
    var current_user_is_admin = '<?php echo is_admin(); ?>';
    $('body').find('._project_file').modal({show: true, backdrop: 'static', keyboard: false});
</script>
