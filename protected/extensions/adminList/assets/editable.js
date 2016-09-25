
$.fn.editable = function (options) {
    // Options
    options = $.extend({
        "url": '/admin/core/ajax/updateGridRow',
        "paramName": "q",
        "callback": null,
        "saving": common.language['success_update'],
        "type": "text",
        "submitButton": 0,
        "delayOnBlur": 0,
        "extraParams": {},
        "editClass": null
    }, options);

    // Set up
    this.click(function (e) {
        //    if (this.editing) return;
        this.editable = function () {
            var me = this;
            options = $.extend({
                "url": '/admin/core/ajax/updateGridRow',
                "paramName": "q",
                "callback": null,
                "saving": common.language['success_update'],
                "type": "text",
                "submitButton": 0,
                "delayOnBlur": 0,
                "extraParams": {},
                "editClass": null
            }, $.parseJSON($(this).attr('data-json')));



            me.editing = true;
            me.orgHTML = $(me).find('div').html();
            $('body').append($('<div/>', {
                'id': 'dialog-update-row'
            }));
            $('#dialog-update-row').dialog({
                model: false,
                responsive: true,
                resizable: false,
                draggable:false,
                open: function () {
                    var inputPk = document.createElement("input");
                    inputPk.type = 'hidden';
                    inputPk.value = $(me).attr('data-id');
                    inputPk.name = 'pk';
                    var inputModel = document.createElement("input");
                    inputModel.type = 'hidden';
                    inputModel.value = options.modelClass;
                    inputModel.name = 'modelClass';

                    var inputField = document.createElement("input");
                    inputField.type = 'hidden';
                    inputField.value = options.field;
                    inputField.name = 'field';

                    var form = document.createElement("form");
                    form.id = 'editable-row';
                    form.method = 'POST';
                    form.action = options.url;
                    $(this).append(form);
                    $(form).append(inputPk);
                    $(form).append(inputModel);
                    $(form).append(inputField);
                    $(form).append(createInputElementMe(me.orgHTML));
                   // common.enterSubmit('#editable-row');

                },
                close: function () {
                    $(this).remove();
                },
                buttons: [{
                        text: 'Обновить',
                        'class': 'btn btn-success',
                        click: function () {
                            var d = $(this);
                            var form = d.find('form');
                            $.ajax({
                                dataType: 'json',
                                url: options.url,
                                type: form.attr('method'),
                                data: form.serialize(),
                                success: function (response) {
                                    //    

                                    common.notify(response.message,'success');
                                    //$.fn.yiiGridView.update(options.grid);
                                    d.remove();
                                    $(me).html('<div>'+response.value+'</div>');
                                    // this.editable();
                                }
                            });


                        }
                    }]
            });

        };
        this.editable();
    });
    // Don't break the chain
    return this;


    function createInputElementMe(v) {
        if (options.type == "textarea") {
            var i = document.createElement("textarea");
            i.className = 'form-control';
            options.submitButton = true;
            options.delayOnBlur = 100; // delay onBlur so we can click the button
        } else if (options.type == "dropdownlist") {
            var i = document.createElement("select");
            $.each(options.items, function (key, value) {
                $(i).append($('<option/>', {
                    'value': key
                }).text(value));
            });
        } else {
            var i = document.createElement("input");
            i.type = "text";
            i.className = 'form-control';
        }
        $(i).val(v);
        i.name = options.paramName;
        return i;
    }
};