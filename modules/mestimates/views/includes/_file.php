<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade _mestimate_file" tabindex="-1" role="dialog" data-toggle="modal">
    <div class="modal-dialog full-screen-modal" role="document">
        <div class="modal-content" id="file_preview_content">
            <?php $this->load->view('mestimates/includes/_file_data'); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?php $discussion_lang = get_mestimate_discussions_language_array(); ?>
<script>
    var discussion_id = '<?php echo $file->id; ?>';
    var current_user_is_admin = '<?php echo is_admin(); ?>';
    $('body').find('._mestimate_file').modal({show: true, backdrop: 'static', keyboard: false});
</script>
