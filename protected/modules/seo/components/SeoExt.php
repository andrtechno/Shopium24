<?php

Yii::import('mod.seo.models.SeoUrl');
Yii::import('mod.seo.models.SeoMain');
Yii::import('mod.seo.models.SeoParams');

class SeoExt extends CApplicationComponent {
    /* массив, который будет наполнятся тэгами, что бы исключать уже найденые теги в ссылках выше по иерархии */

    public $exist = array();

    public function run() {

        /*
         * получаем все возможные сслыки по Иерархии
         * Пример: исходная ссылка "site/product/type/34"
         * Результат:
          - site/product/type/34/*
          - site/product/type/34
          - site/product/type/*
          - site/product/type
          - site/product/*
          - site/product
          - site/*
          - site
          - /*
          - /
         * 
         * Изменена ****
         */

        $titleFlag = false;
        $urls = $this->getUrls();
        foreach ($urls as $url) {
            $urlF = SeoUrl::model()->findByAttributes(array('url' => $url));

            if ($urlF !== null) {
                $this->seoName($urlF);
                $titleFlag = false;
                break;
            } else {
                $titleFlag = true;
            }
        }
        $controller = Yii::app()->controller;
        if ($titleFlag)
            $this->printMeta('title', Html::encode($controller->pageTitle));
        if(isset($controller->pageKeywords))
             $this->printMeta('keywords', $controller->pageKeywords);
        if(isset($controller->pageDescription))
             $this->printMeta('description', $controller->pageDescription);
        
    }

    /*
      Данная функция находит все MetaName, по ссылке
      $url - ссылка по которой будут искаться теги
     */

    private function seoName($url) {

        $controller = Yii::app()->controller;
        if ($url->title) {
            foreach ($url->params as $paramData) {
                $param = $this->getSeoparam($paramData);

                if ($param) {
                    $url->title = str_replace($param['tpl'], $param['item'], $url->title);
                }
            }
            $this->printMeta('title', $url->title);
        } else {
            if (isset($controller->pageTitle)) {
                $this->printMeta('title', $controller->pageTitle);
            } else {
                $this->printMeta('title', Yii::app()->settings->get('app', 'site_name'));
            }
        }
        if ($url->keywords) {
            foreach ($url->params as $paramData) {
                $param = $this->getSeoparam($paramData);
                if ($param) {
                    $url->keywords = str_replace($param['tpl'], $param['item'], $url->keywords);
                }
            }
            $this->printMeta('keywords', $url->keywords);
        } else {
            if (isset($controller->pageKeywords))
                $this->printMeta('keywords', $controller->pageKeywords);
        }

        if ($url->description) {
            foreach ($url->params as $paramData) {
                $param = $this->getSeoparam($paramData);
                if ($param) {
                    $url->description = str_replace($param['tpl'], $param['item'], $url->description);
                }
            }
            $this->printMeta('description', $url->description);
        } else {
            if (isset($controller->pageDescription))
                $this->printMeta('description', $controller->pageDescription);
        }
    }

    /*
     * функция вывода Мета Тега на страницу
     * @name - название мета-тега
     * @content - значение
     */

    private function printMeta($name, $content) {

        $content = strip_tags($content);
        if ($name == "keywords")
            $content = str_replace(',', ", ", $content);
        if ($name == "title") {
            echo "<title>{$content}</title>\n";
        } else {
            echo "<meta name=\"{$name}\" content=\"{$content}\" />\n";
        }
    }

    private function getUrls() {
        $result = null;
        $urls = Yii::app()->request->url;
        if (Yii::app()->languageManager->default->code != Yii::app()->language) {
            $urls = str_replace('/' . Yii::app()->language, '', $urls);
        }

        $data = explode("/", $urls);
        $count = count($data);

        while (count($data)) {
            $_url = "";
            $i = 0;
            foreach ($data as $key => $d) {
                $_url .= $i++ ? "/" . $d : $d;
            }
            if ($count == 1) {
                $result[] = $_url;
                $result[] = $_url . "/*";
            } else {
                $result[] = $_url . "/*";
                $result[] = $_url;
            }

            unset($data[$key]);
        }
        $result[] = "/*";
        $result[] = "/";
        $result22 = array_unique($result);
        return $result22;
    }

    /*
     * функция возвращающая значение параметра если он указан
     * Существуют два типа параметров прямой (ModelName/attribyte) или по связи (ModelName/relation.attribyte)
     */

    private function getSeoparam($pdata) {

        $urls = Yii::app()->request->url;
        $data = explode("/", $urls);
        $id = $data[count($data) - 1];
        /* если есть символ ">>" значит параметр по связи */
        $param = $pdata->obj;
        $tpl = $pdata->param;
        if (strstr($param, ".")) {
            $paramType = true;
            $data = explode(".", $param);
            $param = explode("/", $data[0]);
        } else {
            $paramType = false;
            $param = explode("/", $param);
        }

        if (class_exists($param[0], false)) {
            $item = new $param[0];
            if (is_string($id)) {
                $item = $item->findByAttributes(array('seo_alias' => $id));
            } else {
                $item = $item->findByPk($id);
            }

            if (count($item)) {
                return array(
                    'tpl' => $tpl,
                    'item' => ($paramType) ? $item[$param[1]][$data[1]] : $item[$param[1]],
                );
            }
        } else {
            return false;
        }
    }

    public function googleAnalytics() {
        $config = Yii::app()->settings->get('seo');
        if (isset($config['googleanalytics_id']) && !empty($config['googleanalytics_id'])) {
            Yii::app()->clientScript->registerScript(__FUNCTION__, "
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', '" . $config['googleanalytics_id'] . "']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
        ", CClientScript::POS_END);
        }
    }

    public function yandexMetrika() {
        $config = Yii::app()->settings->get('seo');
        if (isset($config['yandexmetrika_id']) && !empty($config['yandexmetrika_id'])) {
            Yii::app()->clientScript->registerScriptFile("//mc.yandex.ru/metrika/watch.js", CClientScript::POS_END);
            Yii::app()->clientScript->registerScript(__FUNCTION__, "
            try {
                var yaCounter" . $config['yandexmetrika_id'] . " = new Ya.Metrika({
                    id:" . $config['yandexmetrika_id'] . ",
                    clickmap:" . $config['yandexmetrika_clickmap'] . ",
                    trackLinks:" . $config['yandexmetrika_trackLinks'] . ",
                    webvisor:" . $config['yandexmetrika_webvisor'] . "
                });
            } catch(e) {
            
            }
        ", CClientScript::POS_END);
        }
    }

    //		<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-PD87P5"
    //	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    public function googleTagManager($html = false) {
        $config = Yii::app()->settings->get('seo');
        if (true) {
            if ($html) {
                return '<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-PD87P5"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>';
            }
            Yii::app()->clientScript->registerScript(__FUNCTION__, "
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-PD87P5');
        ", CClientScript::POS_END);
        }
    }

}
