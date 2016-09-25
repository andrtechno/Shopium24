
var grid = {};
grid = {
    dialog_id:'dialog_edit_columns',
    editcolums: function (gid, m, mod) {
        var t = this;
        $('body').append($('<div/>', {
            'id': grid.dialog_id
        }));


        $('#'+grid.dialog_id).dialog({
            dialogClass: grid.dialog_id,
            modal: true,
            autoOpen: true,
            width: 500,
            height: 'auto',
            title: 'Доспутные ячейки',
            responsive: true,
            resizable: false,
            draggable: false,
            create: function (event, ui) {

            },
            open: function () {
                var that = this;
                //$('.ui-dialog-buttonset').addClass('btn-group');
                common.ajax('/admin/core/ajax/widget.editGridColumns', {
                    token: common.token,
                    grid_id: gid,
                    module: mod,
                    model: m
                }, function (data, textStatus, xhr) {

                    $(that).html(data);
                    $('.ui-dialog').position({
                        my: 'center',
                        at: 'center',
                        of: window,
                        collision: 'fit'
                    });

                }, 'html', 'POST');

            },
            close: function () {
                $(this).remove();
            },
            buttons: [{
                    'text': common.message.save,
                    "class": 'btn btn-success',
                    'click': function () {
                        t.save(gid);
                    }
                },
                {
                    'text': common.message.cancel,
                    "class": 'btn btn-default',
                    'click': function () {
                        $('#'+grid.dialog_id).remove();
                    }
                }]
        });
    },
    save: function (gridid) {
        var form = $('#edit_grid_columns_form').serialize();
        common.ajax('/admin/core/ajax/widget.editGridColumns', form, function () {
            $('#'+grid.dialog_id).remove();
            //    window.location.reload("true");
            // $('#'+gridid).yiiGridView.update(gridid);
            $.fn.yiiGridView.update(gridid);//,{url: window.location.href}
            //   $.fn.yiiGridView({'ajaxUpdate':['shopmanufacturer-grid']});
        }, 'html', 'POST');

    }
};