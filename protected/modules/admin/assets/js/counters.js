
$(function(){
    $('.badgeNav').each(function(i, el){
        $(el).find('a').append('<span class="badge"></span>');
    });
    
    setInterval(function(){
        reloadCounters();
    }, 15000);



    function reloadCounters() {
        $.getJSON('/admin/core/ajax/counters?' + Math.random(), function(data){
            if(data.orders > 0){                        
                $('.circle-orders .badge').html(data.orders).show();
            }else{
                $('.circle-orders .badge').hide();
            }
        });
    }
    reloadCounters();
});