<?php

/**
 * Контроллер статичных страниц
 * 
 * @author CORNER CMS development team <dev@corner-cms.com>
 * @package modules.pages.controllers
 * @uses Controller
 */
class DefaultController extends Controller {


    public function actionIndex($category = null) {

        
        


            $this->pageName = Yii::t('PlansModule.default', 'MODULE_NAME');
            $this->breadcrumbs = array($this->pageName);
  

        
       $this->render('index', array(
            
        ));
    }
  

}
