Dropzone.options.mestimateFilesUpload = false;

var expenseDropzone;
$(function () {

    init_ajax_search('customer', '#clientid_copy_mestimate.ajax-search');

    // remove the divider for mestimate actions in case there is no other li except for pin mestimate
    $('ul.mestimate-actions li:first-child').next('li.divider').remove();

    var file_id = get_url_param('file_id');
    if (file_id) {
        view_mestimate_file(file_id, mestimate_id);
    }


    $('body').on('shown.bs.modal', '._mestimate_file', function () {
        var content_height = ($('body').find('._mestimate_file .modal-content').height() - 165);
        var mestimateFilePreviewIframe = $('.mestimate_file_area iframe');

        if (mestimateFilePreviewIframe.length > 0) {
            mestimateFilePreviewIframe.css('height', content_height);
        }

        if (!is_mobile()) {
            $('.mestimate_file_area,.mestimate_file_discusssions_area').css('height', content_height);
        }
    });

    $('body').on('shown.bs.modal', '#milestone', function () {
        $('#milestone').find('input[name="name"]').focus();
    });

    $('#mestimate_top').on('change', function () {
        var val = $(this).val();
        var __mestimate_group = get_url_param('group');
        if (__mestimate_group) {
            __mestimate_group = '?group=' + __mestimate_group;
        } else {
            __mestimate_group = '';
        }
        window.location.href = admin_url + 'mestimates/view/' + val + __mestimate_group;
    });

    if ($('#mestimate-files-upload').length > 0) {
        new Dropzone('#mestimate-files-upload', appCreateDropzoneOptions({
            paramName: "file",
            uploadMultiple: true,
            parallelUploads: 20,
            maxFiles: 20,
            accept: function (file, done) {
                done();
            },
            success: function (file, response) {
                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                    window.location.href = admin_url + 'mestimates/mestimate/' + mestimate_id + '?group=mestimate_files';
                }
            },
            sending: function (file, xhr, formData) {
                formData.append("visible_to_customer", $('input[name="visible_to_customer"]').prop('checked'));
            }
        }));
    }

    if ($('#mestimate-expense-form').length > 0) {
        expenseDropzone = new Dropzone("#mestimate-expense-form", appCreateDropzoneOptions({
            autoProcessQueue: false,
            clickable: '#dropzoneDragArea',
            previewsContainer: '.dropzone-previews',
            addRemoveLinks: true,
            maxFiles: 1,
            success: function (file, response) {
                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                    window.location.reload();
                }
            }
        }));
    }


});


function view_mestimate_file(id, mestimate_id) {
    $('#mestimate_file_data').empty();
    $("#mestimate_file_data").load(admin_url + 'mestimates/file/' + id + '/' + mestimate_id, function (response, status, xhr) {
        if (status == "error") {
            alert_float('danger', xhr.statusText);
        }
    });
}

function update_file_data(id) {
    var data = {};
    data.id = id;
    data.subject = $('body input[name="file_subject"]').val();
    data.description = $('body textarea[name="file_description"]').val();
    $.post(admin_url + 'mestimates/update_file_data/', data);
}


function gantt_filter() {
    var status = $('select[name="gantt_task_status"]').selectpicker('val');
    var gantt_type = $('select[name="gantt_type"]').selectpicker('val');
    var params = [];
    params['gantt_type'] = gantt_type;
    params['group'] = 'mestimate_gantt';
    if (status) {
        params['gantt_task_status'] = status;
    }
    window.location.href = buildUrl(admin_url + 'mestimates/view/' + mestimate_id, params);
}
