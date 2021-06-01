function startLoading() {
    $('body').append('<div style="" id="loadingDiv"><div class="loader">Please wait...</div></div>');
}

function removeLoader() {
    $("#loadingDiv").fadeOut(function () {
        $("#loadingDiv").remove(); //makes page more lightweight
    });
}

function simpleAjaxPostUpload(url, formId, successCallBack, fieldErrorCallBack, actionErrorCallBack) {
    startLoading();
    var list_params = getUrlParameter(url);
    var data = new FormData();
    var inputs = $(formId + ' input, ' + formId + ' select,' + formId + ' textarea');


    $.each(inputs, function (obj, v) {
        var name = $(v).attr("name");
        if ($(v).attr("type") == "file") {
            var id = $(v).attr("id");
            var input = document.getElementById(id);
            for (var indexElement = 0; indexElement < input.files.length; indexElement++) {
                data.append(name + "[" + indexElement + "]", input.files[indexElement]);
            }
        } else if ($(v).attr("type") == "radio") {
            if ($(v).is(':checked')) {
                data.append(name, $(v).val());
            }
        } else if ($(v).attr("type") == "checkbox") {
            if ($(v).is(':checked')) {
                data.append(name, $(v).val());
            }
        } else {
            data.append(name, $(v).val());
        }
    });
    $.each(list_params, function (index, value) {
        data.append("" + index, value);
    });
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        contentType: false,
        dataType: 'json',
        cache: false,
        processData: false,
        success: function (res) {
            if (res.errorCode == "SUCCESS") {
                if (typeof successCallBack != 'undefined' && successCallBack != null) {
                    successCallBack(res);
                } else {
                    alert(res.errorMessage);
                }
            } else if (res.errorCode == "FIELD_ERROR") {
                if (typeof fieldErrorCallBack != 'undefined' && fieldErrorCallBack != null) {
                    fieldErrorCallBack(res);
                } else {
                    $(formId).replaceWith(res.content);
                }
            } else {
                if (typeof actionErrorCallBack != 'undefined' && actionErrorCallBack != null) {
                    actionErrorCallBack(res);
                } else {
                    alert(res.errorMessage);
                }
            }
            removeLoader();
        }
    }).fail(function (jqXHR, textStatus, error) {
        alert("System error.");
        removeLoader();
    });
}

function simpleAjaxGet(url, successCallBack, fieldErrorCallBack, actionErrorCallBack) {
    $.ajax({
        type: 'GET',
        url: url,
        success: function (res) {
            if (res.errorCode == "SUCCESS") {
                if (typeof successCallBack != 'undefined' && successCallBack != null) {
                    successCallBack(res);
                }
            } else if (res.errorCode == "FIELD_ERROR") {
                if (typeof fieldErrorCallBack != 'undefined' && fieldErrorCallBack != null) {
                    fieldErrorCallBack(res);
                }
            } else {
                if (typeof actionErrorCallBack != 'undefined' && actionErrorCallBack != null) {
                    actionErrorCallBack(res);
                }
            }
        },
        async: true
    }).fail(function (jqXHR, textStatus, error) {
        alert("System error.");
    });
}

function simpleAjaxPost(url, data, successCallBack, fieldErrorCallBack, actionErrorCallBack) {
    $.ajax({
        type: 'POST',
        url: url,
        data: data,
        dataType: 'json',
        success: function (res) {
            if (res.errorCode == "SUCCESS") {
                if (typeof successCallBack != 'undefined' && successCallBack != null) {
                    successCallBack(res);
                }
            } else if (res.errorCode == "FIELD_ERROR") {
                if (typeof fieldErrorCallBack != 'undefined' && fieldErrorCallBack != null) {
                    fieldErrorCallBack(res);
                }
            } else {
                if (typeof actionErrorCallBack != 'undefined' && actionErrorCallBack != null) {
                    actionErrorCallBack(res);
                }
            }
        },
        async: true
    }).fail(function (jqXHR, textStatus, error) {
        alert("System error.");
    });
}


function simpleCUDModalUpload(dialogId, formId, actionBtnId, gUrl, pUrl, successCallBack, fieldErrorCallBack, actionErrorCallBack) {
    simpleAjaxPostUpload(gUrl, formId,
        function (res) {
            $(dialogId).html(res.data_template);
            $(dialogId).modal('toggle');
            $(actionBtnId).click(function (e) {
                e.preventDefault();
                simpleAjaxPostUpload(pUrl, dialogId, successCallBack, fieldErrorCallBack, actionErrorCallBack);
            });
        }
    );
}

function getAppendIntact(url) {
    return (!/\?/.test(url) ? '?' : '&');
}

function guid() {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
    }

    return s4() + s4() + s4() + s4() + s4() + s4() + s4() + s4();
}

function getUrlParameter(sPageURL) {
    var params = sPageURL.split('?');
    if (params.length == 0) {
        return null;
    }

    var list_param = params[1].split('&');
    var i;
    var result = {};
    for (i = 0; i < list_param.length; i++) {
        var array_param = list_param[i].split('=');
        if (array_param.length > 0) {
            result[array_param[0]] = array_param[1]
        }
    }

    return result;
}
