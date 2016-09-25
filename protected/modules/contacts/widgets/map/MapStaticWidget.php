<?php

Yii::import('mod.contacts.widgets.map.CMapWidget');

class MapStaticWidget extends CMapWidget {

    public $pk;

    const MAP_VERION = '2.1';

    public function run() {
        $cs = Yii::app()->clientScript;
        $map = ContactsMaps::model()->findByPk($this->pk);
        if ($map) {


            $this->setId(__CLASS__ . $map->id);

            $options = CMap::mergeArray($this->getOptions($map), $this->options);
            $this->renderMap($this->id, $options);

            $cs->registerScript($this->id, "

    var markers = " . CJSON::encode($this->getMapMarkers($map)) . ";
    var mapID = '" . $this->id . "';
    var mapOptions = " . CJavaScript::encode($options) . ";
    api.addMap(mapID,mapOptions);


   $.each(markers,function(i,marker){
        api.setMark(marker,'#'+mapID);
        if(min_x > marker.coordx) min_x = marker.coordx;
        if(min_y > marker.coordy) min_y = marker.coordy;
        if(max_x < marker.coordx) max_x = marker.coordx;
        if(max_y < marker.coordy) max_y = marker.coordy;
    });
    



    if(markers.length > 1){
        api.setBounds([[min_y,min_x],[max_y,max_x]],mapID);
        api.setZoomMap(mapOptions.zoom,mapID);
    }
    ", CClientScript::POS_READY);


    
    
    
            $cs->registerScript($this->id, "
                

function ajaxAddress(hash){

        var str;
        if(hash){
            str = {'data[index]':hash,'data[type]':[1,2]};
        }else{
            str = $('#list-form').serialize();
        }

        $.ajax({
            url:'".Yii::app()->createUrl("/contacts/getAddressList")."',
            type:'POST',
            data:str,
            dataType:'json',
            success:function(data){
             if(hash){
                  $('.city-list ul li.ct'+hash+' a').addClass('active');
                }
                var addli='';
               var min_x=999;
                var max_x=0;
                var min_y=999;
                var max_y=0;
                if(data.success){

                $.each(data.address,function(i,v){

addli += '<li class=\"address-name\" onClick=\"addClassToAddress(this); api.setCenterMap(['+v.coordx+','+v.coordy+'],\'" . $this->id . "\',15);\" data-coords=\"'+v.coordy+', '+v.coordx+'\">';
addli += '<div class=\"name\">'+v.name+'</div>';
addli += '<div class=\"time\">будние дни: '+v.weekdays_time+',  выходные дни: '+v.weekend_time+'</div>';
addli += '</li>';



                    api.setMark({
                        coords:[v.coordx,v.coordy],
                        properties:{
                         //   iconCaption:v.name,
                         balloonContent:v.name
                        },
                        options:{
                       // preset: 'islands#blueCircleDotIconWithCaption',
                            iconLayout: 'default#image',
                            iconImageHref: '".Yii::app()->controller->assetsUrl."/images/marker.png',
                            iconImageSize: [39, 42],
                            iconImageOffset: [-3, -42],
                         //   iconCaptionMaxWidth: '150'
                        }
                    },'#map');

                    if(min_x > v.coordx) min_x = v.coordx;
                    if(min_y > v.coordy) min_y = v.coordy;
                    if(max_x < v.coordx) max_x = v.coordx;
                    if(max_y < v.coordy) max_y = v.coordy;
                });
          api.setBounds([[min_x,min_y],[max_x,max_y]],{
                    precizeZoom: true,
                    checkZoomRange: false,
                });

 }else{
 addli += data.message;
 
}


                $('.mCSB_container').html('<ul class=\"address-list\">'+addli+'</ul>');
                //$('.mCSB_container').removeClass('loading-address');
              //  common.removeLoader();

              $('.scroller').mCustomScrollbar('update');
            },
            beforeSend:function(){

           // $('.mCSB_container').html('').addClass('loading-address');
               // common.addLoader();
            },
            complete:function(){
            console.log('complete');
            

             //$('.scroller').mCustomScrollbar('update');
            }
        });
}




    ", CClientScript::POS_BEGIN);

        }
    }
}
