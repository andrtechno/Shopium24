<?php

/**
 * Контроллер статичных страниц
 * 
 * @author CORNER CMS development team <dev@corner-cms.com>
 * @package modules.pages.controllers
 * @uses Controller
 */
class DefaultController extends Controller {

    public function actionSuggestTags() {
        if (isset($_GET['q']) && ($keyword = trim($_GET['q'])) !== '') {
            $tags = Tag::model()->suggestTags($keyword);
            if ($tags !== array())
                echo implode("\n", $tags);
        }
    }

    public function actionTest() {

        $this->pageName = Yii::t('NewsModule.default', 'MODULE_NAME');
        $this->breadcrumbs = array($this->pageName);



        $this->processPageRequest('page');
        $criteria = new CDbCriteria;
        $criteria->order = 't.id DESC';

        $dataProvider = new CActiveDataProvider('News', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 2,
                'pageVar' => 'page',
            ),
        ));

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('_loopAjax', array(
                'dataProvider' => $dataProvider,
            ));
            Yii::app()->end();
        } else {
            $this->render('index_ajax', array(
                'dataProvider' => $dataProvider,
            ));
        }
    }
    public function actionIndex($category = null) {
        if(Yii::app()->request->isAjaxRequest && isset($_POST['current_page'])){
            Yii::app()->settings->set('news',array('current_page'=>$_POST['current_page']));
            Yii::app()->session['news_current_page']=$_POST['current_page'];
        }
       // if(!Yii::app()->request->isAjaxRequest && isset($_GET['page'])){
       //      Yii::app()->settings->set('news',array('current_page'=>$_GET['page']-1));
       // }

        
       /*for ($x=0; $x<10; $x++){
            $add = new News;
            $add->title = CMS::gen(15);
            $add->short_text = CMS::gen(15);
            $add->full_text = CMS::gen(15);
            $add->save(false,false,false);
        }*/
        
        
        $this->dataModel = new News('search');
        if (isset($category)) {
            $this->pageName = Yii::t('NewsModule.default', 'MODULE_NAME');
            $this->breadcrumbs = array(
                $this->pageName => array('/news'),
             //   $provider->setCategory($category)->name
            );
        } else {
            $this->pageName = Yii::t('NewsModule.default', 'MODULE_NAME');
            $this->breadcrumbs = array($this->pageName);
        }

        //for new pagination
        

        $criteria = new CDbCriteria;
        $criteria->order = 't.id DESC';
        

            
      /*  $provider = new ActiveDataProvider('News', array(
            'criteria' => $criteria,
            //'pagination'=>false,
            'pagination' => array(
                'pageSize' => 15,
               'pageVar' => 'page',
           ),
        ));*/

        
       $this->render('index', array(
            'provider' => $this->dataModel,
        ));
    }
    public function actionScrollpager() {


            $this->pageName = Yii::t('NewsModule.default', 'MODULE_NAME');
            $this->breadcrumbs = array($this->pageName);

        
	           $criteria = new CDbCriteria;
	            $total = News::model()->published()->count();
	
	            $pages = new CPagination($total);
	            $pages->pageSize = 6;
	            $pages->applyLimit($criteria);
	
	            $posts = News::model()->published()->findAll($criteria);

        
       $this->render('scrollpager_index', array(
	                'posts' => $posts,
	                'pages' => $pages,
	            ));
    }

    protected function processPageRequest($param = 'page') {
        if (Yii::app()->request->isAjaxRequest && isset($_POST[$param]))
            $_GET[$param] = Yii::app()->request->getPost($param);
    }

    public function actionView($seo_alias) {
        $this->pageName = Yii::t('NewsModule.default', 'MODULE_NAME');
        $this->dataModel = News::model()
                ->published()
                // ->language(Yii::app()->languageManager->active->id)
                ->withUrl($seo_alias)
                ->find(array('limit' => 1));


        if (!$this->dataModel)
            throw new CHttpException(404);
        $this->printer($this->dataModel->title, $this->dataModel->full_text, $this->dataModel->date_create);
        $this->dataModel->saveCounters(array('views' => 1));


        $category = $this->dataModel->setCategory($this->dataModel->category_id);
        if (isset($category)) {
            $this->breadcrumbs = array(
                $this->pageName => array('/news'),
                $category->name => array('/news', 'category' => $category->seo_alias),
                $this->dataModel->title
            );
        } else {
            $this->breadcrumbs = array(
                $this->pageName => array('/news'),
                $this->dataModel->title
            );
        }

        $this->render('view', array(
            'model' => $this->dataModel,
        ));
    }

}
