
var common = window.CMS_common || {};
common = {
    debug: true,
    language: 'en',
    flashMessage: false,
    token: null,
    isDashboard: false,
    getMsg: function (code) {
        return this.lang[this.language][code];
    },
    notify: function (text, type) {
                    var t = (type == 'error') ? 'danger' : type;
        if (common.isDashboard) {

            $.notify({message: text}, {
                type: t,
                allow_dismiss: false,
                placement: {
                    from: "bottom",
                    align: "left"
                }
            });
        } else {
            $.notify({message: text}, {
                type: t,
                allow_dismiss: false
            });
        }

    },
    report: function (string, type) {
        if (this.debug) {
            console.log(string);
        }
        if (this.flashMessage) {
            if (typeof jQuery.jGrowl === 'undefined') {
                console.log('error jGrowl plugin not included');
            } else {
                $.jGrowl(string);
                /*if (type === 'error') {
                 $.growl.error({message: string, title: ''});
                 } else if (type === 'success') {
                 $.growl.notice({message: string, title: ''});
                 } else if (type === 'warning') {
                 $.growl.warning({message: string, title: ''});
                 } else {
                 $.growl({message: string, title: ''});
                 }*/

            }
        }
    },
    geoip: function (ip) {
        // common.flashMessage = true;


        $.ajax({
            url: '/admin/core/ajax/geo/ip/' + ip,
            type: 'GET',
            dataType: 'html',
            beforeSend: function () {
                $('body').append('<div id=\"geo-dialog\"></div>');
            },
            success: function (result) {
                $('#geo-dialog').dialog({
                    model: true,
                    responsive: true,
                    resizable: false,
                    height: 'auto',
                    minHeight: 95,
                    title: 'Информация о ' + ip,
                    width: 700,
                    draggable: false,
                    modal: true,
                    open: function () {
                        $('.ui-widget-overlay').bind('click', function () {
                            $('#geo-dialog').dialog('close');
                        });
                    },
                    close: function () {
                        $('#geo-dialog').remove();
                        $('#jGrowl').jGrowl('shutdown').remove();
                    },
                });

                $('#geo-dialog').html(result);

                $('.ui-dialog').position({
                    my: 'center',
                    at: 'center',
                    of: window,
                    collision: 'fit'
                });
            }
        });
    },
    close_alert: function (aid) {
        $('#alert' + aid).fadeOut(1000);
        $.cookie('alert' + aid, true, {
            expires: 1, // one day
            path: '/'
        });
    },
    hasChecked: function (has, classes) {
        if ($(has).is(':checked')) {
            $(classes).removeClass('hidden');
        } else {
            $(classes).addClass('hidden');
        }
    },
    addLoader: function (text) {
        if (text !== undefined) {
            var t = text;
        } else {
            var t = 'Loading...';
        }
        $('body').append('<div class="ajax-loading">' + t + '</div>');

    },
    removeLoader: function () {
        $('.ajax-loading').remove();
    },
    closeReport: function () {
        $.jGrowl('close');
    },
    init: function () {
        this.report('common init()');
    },
    ajax: function (url, data, success, dataType, type) {
        var t = this;
        $.ajax({
            url: url,
            type: (type == undefined) ? 'POST' : type,
            data: data,
            dataType: (dataType == undefined) ? 'html' : dataType,
            beforeSend: function (xhr) {
                // if(t.ajax.beforeSend.message){
                //t.report(t.ajax.beforeSend.message);
                //}else{
                // t.report(t.getText('loadingText'));
                //}

            },
            error: function (xhr, textStatus, errorThrown) {
                t.report(textStatus + ' ajax() ' + xhr.status + ' ' + xhr.statusText);
                //t.report(textStatus+' ajax() '+xhr.responseText);
            },
            success: success

        });
    },
    setText: function (param, text) {
        this.lang[this.language][param] = text;
    },
    getText: function (param) {
        return common.lang[this.language][param];
    },
    lang: {
        en: {
            error: 'Error',
            loadingText: 'Loading...'
        },
        ru: {
            error: 'Ошибка',
            loadingText: 'Загрузка...'
        }
    },
    enterSubmit: function (formid) {
        $(formid).keydown(function (event) {
            if (event.which == 13) {
                // event.preventDefault();
                $(formid).submit();
            }
        });
    }
};
common.init();

