/*
var theme = new Array(
{
    "default":['gray','green','black'],
    "classic":['blue','red']
    }
);
console.log(theme);*/
function open_design_dialog(){
    $.ajax({
        type:'POST',
        url:'/ajax/design.action',
        beforeSend:function(){
            $('body').append('<div id=\"select_design\"></div>');
        //$.jGrowl('Загрузка...');
        },
        success:function(html){
            // $.jGrowl('close');
            $('#select_design').dialog({
                model:true,
                // autoOpen: true,
                draggable: false,
                resizable: false,
                height: 'auto',
                minHeight: 95,
                title:'Выбор дизайна',
                width: 550,
                modal: true,
                open:function(){},
                close:function(){
                    $('#select_design').remove();
                //  $('#jGrowl').jGrowl('shutdown').remove();
                },
                buttons: false
        
            });
            $('#select_design').html(html); 
        }
    });
}

function get_theme_color(t){

    var themeName = $(t,"option:selected").val();
    $.ajax({
        type:'POST',
        url:'/ajax/design.action',
        data:{
            theme:themeName
        },
        beforeSend:function(){
            //$.jGrowl('Загрузка...');
        },
        success:function(html){
            //$.jGrowl('close');
            $('#select_design').html(html); 
        }
    });
}


function get_theme_preview(t){
    var themeName = $('#theme option:selected').val();
    var color = $("#theme_color option:selected").text();
    var src  = assetsDesignPath+'/images/'+themeName+'-'+color+'.jpg';
    $('#preview_image').empty().append($("<img>",{'src':src,'class':'img-responsive img-thumbnail'}));
    //$('#preview_image').attr('src',src);
}