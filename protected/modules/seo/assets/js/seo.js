$(document).ready(function(){

    $(".meta-name").on("click",function(){
        var name = $("#name").val();
        if(name == "")
            alert("Change Meta Name");
        else{
            $.ajax( {
                url: '/admin/seo/default/addmetaname',
                type: 'POST',
                data: {name : name},
                success: function (msg) {
                    $("#load-meta-name").append(msg);
                }
            });
        }
    });

    $(".deleteblock").on("click",function(){
        var ptr = confirm('Вы действительно хотите удалить элемент?');
        if(ptr){
            var id = $(this).data("id");
            console.log(id);
            var $this = $(this);
            if(id == null)
            {
                $(this).parent().fadeOut();
            }
            else{
                $.ajax( {
                    url: '/admin/seo/default/deletemetaname',
                    type: 'POST',
                    data: {id : id},
                    success: function (msg) {
                        $this.parent().fadeOut(500,function(){
                            $this.parent().remove();
                        });
                    }
                });
            }

        }
        return false;
    });

});
