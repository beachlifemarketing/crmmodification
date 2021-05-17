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