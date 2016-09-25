<?php

/*
 * The CORNER CMS License
 *
 * Copyright (c) 2014-2016 CORNER CMS development team <dev@corner-cms.com>.
 *
 * Website: http://corner-cms.com
 * E-mail: support@corner-cms.com
 */

/**
 * Дополнительные функции системы.
 * 
 * @author CORNER CMS development team <dev@corner-cms.com>
 * @version 1.0
 */
class CMS {

    const MEMORY_LIMIT = 64; // Minimal memory_limit

    public static function isModile() {
        return (preg_match('!(tablet|pad|mobile|phone|symbian|android|ipod|ios|blackberry|webos)!i', Yii::app()->request->getUserAgent())) ? true : false;
    }

    public static function resizeImage($size, $filename, $uploadAlias, $assetsAlias, $resize = 'resize') {
        $thumbPath = Yii::getPathOfAlias("webroot.assets.{$assetsAlias}.{$size}");
        if (!file_exists($thumbPath)) {
            mkdir($thumbPath, 0777, true);
        }
        // Path to source image
        $fullPath = Yii::getPathOfAlias("webroot.uploads.{$uploadAlias}") . DS . $filename;

        // Path to thumb
        $thumbPath = $thumbPath . DS . $filename;
        if (!file_exists($thumbPath)) {
            // Resize if needed
            Yii::import('ext.phpthumb.PhpThumbFactory');
            $sizes = explode('x', $size);
            $thumb = PhpThumbFactory::create($fullPath);
            $thumb->$resize($sizes[0], $sizes[1])->save($thumbPath);
        }
        return "/assets/{$assetsAlias}/{$size}/" . $filename;
    }

    public static function tableName() {
        // return table name with DB name to get relations from different DBs to work
        $name = preg_match("/dbname=([^;]*)/", Yii::app()->db->connectionString, $matches);
        return $matches[1];
    }
    
    public static function textReplace($text, $array) {
        $tmpArray = array();
        $tmpArray['{site_name}'] = Yii::app()->settings->get('app', 'site_name');
        $tmpArray['{host}'] = Yii::app()->request->serverName;
        $resultArray = CMap::mergeArray($tmpArray, $array);
        foreach ($resultArray as $from => $to) {
            $text = str_replace($from, $to, $text);
        }
        return $text;
    }
    
    public static function getCityNameByIp($ip) {
        $ip = (string) $ip;
        if (Yii::app()->hasComponent('geoip') && self::getMemoryLimit() > self::MEMORY_LIMIT) {
            $geoip = Yii::app()->geoip;
            $code = $geoip->lookupLocation($ip);
            if (isset($code)) {
                if (!empty($code)) {
                    $content = Yii::t('geoip_regions', $code->region);
                } else {
                    return null;
                }
            }
            return $content;
        }
    }

    public static function getCountryNameByIp($ip) {
        $ip = (string) $ip;
        if (Yii::app()->hasComponent('geoip') && self::getMemoryLimit() > self::MEMORY_LIMIT) {
            $geoip = Yii::app()->geoip;
            $code = $geoip->lookupCountryCode($ip);
            $name = $geoip->lookupCountryName($ip);
            if (isset($code)) {
                if (!empty($code)) {
                    $content = $name . ', ' . $code;
                } else {
                    return null;
                }
            }
            return $content;
        }
    }

    public static function getMemoryLimit() {
        $memory_limit = ini_get('memory_limit');
        if (preg_match('/^(\d+)(.)$/', $memory_limit, $matches)) {
            if ($matches[2] == 'M') {
                $memory_limit = $matches[1] * 1024 * 1024; // nnnM -> nnn MB
            } else if ($matches[2] == 'K') {
                $memory_limit = $matches[1] * 1024; // nnnK -> nnn KB
            }
        }
        return $matches[1];
    }

    public static function truncate($text, $strip, $type = 1) {
        if ($type == 1) {
            $text = (mb_strlen($text, Yii::app()->charset) > $strip) ? mb_substr($text, 0, $strip, Yii::app()->charset) . "..." : $text;
        } else {
            $text = mb_substr($text, 0, $strip, Yii::app()->charset);
        }
        return $text;
    }

    /**
     * 
     * @param type $birth_date
     * @return type
     */
    public static function age($birth_date) {
        $birth_time = strtotime($birth_date);
        $birth = getdate($birth_time);
        $now = getdate();
        $age = $now['year'] - $birth['year'];
        if ($now['mon'] < $birth['mon'])
            $age--;

        if ($now['mon'] === $birth['mon'])
            if ($now['mday'] < $birth['mday'])
                $age--;

        return $age;
    }

    /**
     * Определение возраста
     * 
     * @param int $age
     * @return string
     */
    public static function years($age) {
        $age = abs($age);
        $t1 = $age % 10;
        $t2 = $age % 100;
        $a_str = "";
        if ($t1 == 1)
            $a_str = Yii::t('app', 'YEARS', 1);
        else if (($t1 >= 2) && ($t1 <= 4))
            $a_str = Yii::t('app', 'YEARS', 2);
        if (($t1 >= 5) && ($t1 <= 9) || ($t1 == 0) || ($t2 >= 11) && ($t2 <= 19))
            $a_str = Yii::t('app', 'YEARS', 0);
        return $a_str;
    }

    /**
     * Показываем список категорий текущего модуля
     * @return type
     */
    public static function getSelectCategories() {
        return Html::listData(CategoriesModel::model()->module()->findAll(), 'id', 'name');
    }

    /**
     * Display Time filter
     * @param type $sec
     * @return type
     */
    public static function display_time($sec) {
        $min = floor($sec / 60);
        $hours = floor($min / 60);
        $seconds = $sec % 60;
        $minutes = $min % 60;
        $content = ($hours == 0) ? (($min == 0) ? $seconds . " " . Yii::t('app', 'SEC') . "." : $min . " " . Yii::t('app', 'MIN') . ". " . $seconds . " " . Yii::t('app', 'SEC') . ".") : $hours . " " . Yii::t('app', 'HOUR') . ". " . $minutes . " " . Yii::t('app', 'MIN') . ". " . $seconds . " " . Yii::t('app', 'SEC') . ".";
        return $content;
    }

    /**
     * Осталось
     * @param int $time
     * @return string
     */
    public static function timeLeft($time) {
        $t = intval($time - CMS::time());
        return Yii::t('app', 'PURCHASED') . ": " . self::display_time($t);
    }

    /**
     * 
     * @param type $gender
     * @return type
     */
    public static function gender($gender) {
        return Yii::t('app', 'GENDER', $gender);
    }

    /**
     * 
     * @param type $votes
     * @param type $total
     * @return string
     */
    public static function vote_graphic($votes, $total) {

        // Yii::app()->clientScript->registerCssFile($this->getAssetsUrl() . '/css/rating.css');
        $votes = (intval($votes)) ? $votes : 1;
        $width = number_format($total / $votes, 2) * 17;
        $result = substr($total / $votes, 0, 4);
        $title = (intval($votes) && intval($total)) ? Yii::t('app', 'RATING_HIT', array('{votes}' => $votes, '{result}' => $result)) : Yii::t('app', 'RATING_HIT', array('{votes}' => 0, '{result}' => 0));
        $content = "<ul class=\"urating\" title=\"" . $title . "\"><li class=\"crating\" style=\"width: " . $width . "px;\"></li></ul>";
        return $content;
    }

    /**
     * 
     * @return type
     * @todo ыводит ссылку без языка - удаляя /en, /ru etc
     */
    public static function currentUrl() {
        $request = Yii::app()->request;
        $parts = explode('/', $request->requestUri);
        $lang = Yii::app()->languageManager;
        if ($lang->default->code == $lang->active->code) {
            $pathInfo = $request->requestUri;
        } else {
            if (in_array($parts[1], $lang->getCodes())) {
                unset($parts[1]);
                $pathInfo = implode($parts, '/');

                if (empty($pathInfo)) {
                    $pathInfo = '/';
                }
            }
        }
        return $pathInfo;
    }

    /**
     * 
     * @param type $str
     * @return type
     */
    public static function decodeHtmlEnt($str) {
        $ret = html_entity_decode($str, ENT_COMPAT, 'UTF-8');
        $p2 = -1;
        for (;;) {
            $p = strpos($ret, '&#', $p2 + 1);
            if ($p === FALSE)
                break;
            $p2 = strpos($ret, ';', $p);
            if ($p2 === FALSE)
                break;

            if (substr($ret, $p + 2, 1) == 'x')
                $char = hexdec(substr($ret, $p + 3, $p2 - $p - 3));
            else
                $char = intval(substr($ret, $p + 2, $p2 - $p - 2));

            $newchar = iconv(
                    'UCS-4', 'UTF-8', chr(($char >> 24) & 0xFF) . chr(($char >> 16) & 0xFF) . chr(($char >> 8) & 0xFF) . chr($char & 0xFF)
            );
            $ret = substr_replace($ret, $newchar, $p, 1 + $p2 - $p);
            $p2 = $p + strlen($newchar);
        }
        return $ret;
    }

    /**
     * 
     * @param string $dir Absolute path
     * 
     * @return array
     * size - Размер
     * howmany - Количество файлов
     */
    public static function dir_size($dir) {
        if (is_file($dir))
            return array('size' => filesize($dir), 'howmany' => 0);
        if ($dh = opendir($dir)) {
            $size = 0;
            $n = 0;
            while (($file = readdir($dh)) !== false) {
                if ($file == '.' || $file == '..')
                    continue;
                $n++;
                $data = self::dir_size($dir . '/' . $file);
                $size += $data['size'];
                $n += $data['howmany'];
            }
            closedir($dh);
            return array('size' => $size, 'howmany' => $n);
        }
        return array('size' => 0, 'howmany' => 0);
    }

    /**
     * 
     * @param int $size
     * @return type
     */
    public static function files_size($size) {
        $name = array("Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");
        $mysize = $size ? round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . " " . $name[$i] : $size . " Bytes";
        return $mysize;
    }

    /**
     * Get user agent
     * @return string
     */
    public static function getagent() {
        if (getenv("HTTP_USER_AGENT") && strcasecmp(getenv("HTTP_USER_AGENT"), "unknown")) {
            $agent = getenv("HTTP_USER_AGENT");
        } elseif (!empty($_SERVER['HTTP_USER_AGENT']) && strcasecmp($_SERVER['HTTP_USER_AGENT'], "unknown")) {
            $agent = $_SERVER['HTTP_USER_AGENT'];
        } else {
            $agent = "unknown";
        }
        return $agent;
    }

    public static function isBot() {
        $bots = array(
            'rambler' => 'Rambler',
            'googlebot' => 'Google Bot',
            'aport' => 'aport',
            'yahoo' => 'Yahoo',
            'msnbot' => 'MSN Bot',
            'turtle' => 'Turtle',
            'mail.ru' => 'Mail.ru',
            'omsktele' => 'omsktele',
            'yetibot' => 'yetibot',
            'picsearch' => 'picsearch',
            'sape.bot' => 'sape',
            'sape_context' => 'sape_context',
            'gigabot' => 'gigabot',
            'snapbot' => 'snapbot',
            'alexa.com' => 'alexa.com',
            'megadownload.net' => 'megadownload.net',
            'askpeter.info' => 'askpeter.info',
            'igde.ru' => 'igde.ru',
            'ask.com' => 'ask.com',
            'qwartabot' => 'qwartabot',
            'yanga.co.uk' => 'yanga.co.uk',
            'scoutjet' => 'scoutjet',
            'similarpages' => 'similarpages',
            'oozbot' => 'oozbot',
            'shrinktheweb.com' => 'shrinktheweb.com',
            'aboutusbot' => 'aboutusbot',
            'followsite.com' => 'followsite.com',
            'dataparksearch' => 'dataparksearch',
            'google-sitemaps' => 'google-sitemaps',
            'appEngine-google' => 'appEngine-google',
            'feedfetcher-google' => 'feedfetcher-google',
            'liveinternet.ru' => 'Live Internet',
            'xml-sitemaps.com' => 'xml-sitemaps.com',
            'agama' => 'agama',
            'metadatalabs.com' => 'metadatalabs.com',
            'h1.hrn.ru' => 'h1.hrn.ru',
            'googlealert.com' => 'googlealert.com',
            'seo-rus.com' => 'seo-rus.com',
            'yaDirectBot' => 'yaDirectBot',
            'yandeG' => 'yandeG',
            'yandex' => 'Yandex',
            'yandexSomething' => 'yandexSomething',
            'Copyscape.com' => 'Copyscape.com',
            'AdsBot-Google' => 'AdsBot-Google',
            'domaintools.com' => 'domaintools.com',
            'Nigma.ru' => 'Nigma.ru',
            'bing.com' => 'bing.com',
            'dotnetdotcom' => 'dotnetdotcom'
        );
        $result = array();
        foreach ($bots as $key => $bot) {
            if (stripos(Yii::app()->request->userAgent, $key) !== false) {
                $result['success'] = true;
                $result['name'] = $bot;
                break;
            } else {
                $result['success'] = false;
            }
        }
        return $result;
    }

    public static function domain($url, $str = "") {
        $massiv = explode(",", $url);
        $str = intval($str);
        foreach ($massiv as $val)
            $dom[] = "<a href=\"$val\" target=\"_blank\">" . (($str) ? mb_substr(preg_replace("/http\:\/\/|www./", "", $val), 0, $str, 'UTF-8') : preg_replace("/http\:\/\/|www./", "", $val)) . "</a>";
        return preg_replace("/http\:\/\/|https\:\/\/|www./", "", $url);
    }

    /**
     * Get IP
     * @return string
     */
    public static function getip() {
        $strRemoteIP = $_SERVER['REMOTE_ADDR'];
        if (!$strRemoteIP) {
            $strRemoteIP = urldecode(getenv('HTTP_CLIENTIP'));
        }
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $strIP = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $strIP = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $strIP = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $strIP = getenv('HTTP_FORWARDED');
        } else {
            $strIP = $_SERVER['REMOTE_ADDR'];
        }

        if ($strRemoteIP != $strIP) {
            $strIP = $strRemoteIP . ", " . $strIP;
        }
        return $strIP;
    }

    /**
     * 
     * @return string
     * @todo Get referer
     */
    public static function get_referer() {
        $referer = getenv("HTTP_REFERER");
        if (!empty($referer) && $referer != "" && !preg_match("/^unknown/i", $referer) && !preg_match("/^bookmark/i", $referer) && !strpos($referer, $_SERVER["HTTP_HOST"])) {
            $refer = $referer;
        } else {
            $refer = "";
        }
        return $refer;
    }

    /**
     * 
     * @param User $user
     * @return type
     */
    public static function userLink(User $user) {
        $html = Html::link($user->login . ' <b class="caret"></b>', '#', array('class' => 'btn btn-link dropdown-toggle', 'data-toggle' => "dropdown", 'aria-haspopup' => "true", 'aria-expanded' => "false"));
        return '<div style="position:relative;" class="btn-group">' . $html . '
            <ul class="dropdown-menu">
            <li><a href="' . $user->getUpdateUrl() . '"><i class="flaticon-edit-2"></i> ' . Yii::t('app', 'UPDATE', 1) . '</a></li>
            </ul>
            </div>';
    }

    /**
     * 
     * @param string $ip
     * @param int $type
     * @param type $user
     * @return string or null
     */
    public static function ip($ip, $type = 1, $user = null) {
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            if (Yii::app()->hasComponent('geoip') && self::getMemoryLimit() > self::MEMORY_LIMIT) {
                $geoip = Yii::app()->geoip;
                $code = $geoip->lookupCountryCode($ip);
                $name = $geoip->lookupCountryName($ip);
                if (isset($code)) {
                    if (!empty($code)) {
                        $image = Html::image('/uploads/language/' . strtolower($code) . '.png', $ip, array('title' => Yii::t('app', 'COUNTRY') . ': ' . Yii::t('geoip_country', $name)));
                        if ($type == 1) {
                            $content = Html::link($image . ' ' . $ip, 'javascript:void(0)', array('onClick' => 'common.geoip("' . $ip . '")', 'title' => Yii::t('app', 'COUNTRY') . ': ' . Yii::t('geoip_country', $name)));
                        } elseif ($type == 2 && $user) {
                            $content = Html::link($image . ' ' . $user, 'javascript:void(0)', array('onClick' => 'common.geoip("' . $ip . '")', 'title' => Yii::t('app', 'COUNTRY') . ': ' . Yii::t('geoip_country', $name)));
                        } elseif ($type == 3) {
                            $content = $image . ' ' . $ip;
                        } else {
                            $content = $image;
                        }
                    } else {
                        //return null;
                        $content = $ip;
                    }
                } else {
                    $content = $ip;
                }
                return $content;
            }
        } else {
            return $ip . ' (IPv6)';
        }
    }

    /**
     * 
     * @param type $mail
     * @return type
     */
    static function emailLink($mail) {
        if (Yii::app()->hasModule('delivery')) {
            return Html::link($mail, Yii::app()->createAbsoluteUrl('/admin/delivery/send', array('mail' => $mail)), array('onClick' => 'sendEmail("' . $mail . '")'));
        } else {
            return $mail;
        }
    }

    /**
     * 
     * @param type $msg_path файл переводов
     * @param type $msg_param параметр перевода
     * @param int $number число
     * @example CMS::GetFormatWord('PageModule.default','_ELEMENTS',$num);
     * @example '_ELEMENTS'=>'0#елемент|1#елемента|2#елементов';
     * @return string message
     */
    public static function GetFormatWord($msg_path, $msg_param, $number) {
        $num = $number % 10;
        if ($num == 1)
            return Yii::t($msg_path, $msg_param, 0);
        elseif ($num > 1 && $num < 5)
            return Yii::t($msg_path, $msg_param, 1);
        else
            return Yii::t($msg_path, $msg_param, 2);
    }

    public static function GetFormatWordNew($t, $number) {
        $num = $number % 10;
        if ($num == 1)
            $t = Yii::t($msg_path, $msg_param, 0);
        elseif ($num > 1 && $num < 5)
            $t = Yii::t($msg_path, $msg_param, 1);
        else
            $t = Yii::t($msg_path, $msg_param, 2);
        return $t;
    }

    /**
     * 
     * @param datetime $date Y-m-d H:i:s
     * @param boolean $time Показывать время true|false
     * @param boolean $static Статичная дата. true|false
     * @return string
     */
    public static function date($date, $time = false, $static = false) {
        $df = Yii::app()->dateFormatter;
        $conf = Yii::app()->settings->get('app', 'format_date');
        $formatted = strtotime($date);
        $q_data_hour = date("H:i", $formatted);
        $oneDay = 86400;
        $resDate = $df->format($conf, $date);
        if (!$static) {
            if ($formatted > mktime(0, 0, 0)) {
                $t = $formatted - ($oneDay * 1);
                if ($t >= time()) {
                    $result = $resDate;
                } else {
                    $result = Yii::t('app', 'TODAY_IN', array('{time}' => $q_data_hour));
                }
            } elseif ($formatted > mktime(0, 0, 0) - $oneDay) {
                $result = Yii::t('app', 'YESTERDAY_IN', array('{time}' => $q_data_hour));
            } else {
                if ($time) {
                    $result = $resDate . ' ' . Yii::t('app', 'IN') . ' ' . $q_data_hour;
                } else {
                    $result = $resDate;
                }
            }
        } else {
            $dateByZone = new DateTime($date);
            $dateByZone->setTimezone(new DateTimeZone(Yii::app()->controller->getTimezone()));
            $q_data_hour = $dateByZone->format('H:i');
            $resDate = $df->format($conf, $dateByZone->format('Y-m-d H:i:s'));
            if ($time) {
                $result = $resDate . ' ' . Yii::t('app', 'IN') . ' ' . $q_data_hour;
            } else {
                $result = $resDate;
            }
        }
        return $result;
    }

    /**
     * Текушая дата с часовым поясом.
     * 
     * @param string $format Default is "Y-m-d H:i:s"
     * @return string
     */
    public static function getDate($format = 'Y-m-d H:i:s') {
        try {
            $date = new DateTime('now');
            $date->setTimezone(new DateTimeZone(Yii::app()->controller->timezone));
            return $date->format($format);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function time($format = 'Y-m-d H:i:s') {
        try {
            $date = new DateTime('now');
            $date->setTimezone(new DateTimeZone(Yii::app()->controller->timezone));
            return strtotime($date->format($format));
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Youtube parse url
     * 
     * @param string $url
     * @return string
     */
    public static function parse_yturl($url) {
        $pattern = '#^(?:https?://)?';    # Optional URL scheme. Either http or https.
        $pattern .= '(?:www\.)?';         #  Optional www subdomain.
        $pattern .= '(?:';                #  Group host alternatives:
        $pattern .= 'youtu\.be/';       #    Either youtu.be,
        $pattern .= '|youtube\.com';    #    or youtube.com
        $pattern .= '(?:';              #    Group path alternatives:
        $pattern .= '/embed/';        #      Either /embed/,
        $pattern .= '|/v/';           #      or /v/,
        $pattern .= '|/watch\?v=';    #      or /watch?v=,    
        $pattern .= '|/watch\?.+&v='; #      or /watch?other_param&v=
        $pattern .= ')';                #    End path alternatives.
        $pattern .= ')';                  #  End host alternatives.
        $pattern .= '([\w-]{11})';        # 11 characters (Length of Youtube video ids).
        $pattern .= '(?:.+)?$#x';         # Optional other ending URL parameters.
        preg_match($pattern, $url, $matches);
        return (isset($matches[1])) ? $matches[1] : false;
    }

    /**
     * Преобразование массива данных
     * 
     * @param type $arrayofValues
     * @param type $type
     * @return type
     */
    public static function recursiveValuesToType($arrayofValues, $type = 'integer') {
        if (is_array($arrayofValues))
            foreach ($arrayofValues as &$value)
                self::recursiveValuesToType($value, $type);
        else
            settype($arrayofValues, $type);
        return $arrayofValues;
    }

    /**
     * Замена перевода строк на <br />
     * 
     * @param type $subject
     * @return type
     */
    public static function slashNtoBR($subject) {
        $replaced = preg_replace("/\r\n|\r|\n/", '<br />', $subject);
        return $replaced;
    }

    /**
     * Если редактируете поправте translit.js
     * 
     * @param type $message
     * @return string
     */
    static function translit($message) {
        $array = array(
            '/' => '_',
            ' ' => '_',
            '(' => '_',
            ')' => '_',
            '−' => '-',
            ':' => '_',
            ';' => '_',
            '"' => '_',
            '\'' => '_',
            '&' => 'and',
            '!' => '',
            '?' => '',
            '—' => '',
            '.' => '',
            ',' => '',
            '»' => '',
            '«' => '',
            '-' => '_',
            '+' => '',
            'ї' => 'i',
            'є' => 'e',
            'і' => 'i',
            'І' => 'i',
            'Я' => 'ja',
            'Ї' => 'ii',
            'Є' => 'e',
            'А' => 'a',
            'Б' => 'b',
            'В' => 'v',
            'Г' => 'g',
            'Д' => 'd',
            'Е' => 'e',
            'Ё' => 'jo',
            'Ж' => 'zh',
            'З' => 'z',
            'И' => 'i',
            'Й' => 'j',
            'К' => 'k',
            'Л' => 'l',
            'М' => 'm',
            'Н' => 'n',
            'О' => 'o',
            'П' => 'p',
            'Р' => 'r',
            'С' => 's',
            'Т' => 't',
            'У' => 'u',
            'Ф' => 'f',
            'Х' => 'kh',
            'Ц' => 'c',
            'Ч' => 'ch',
            'Ш' => 'sh',
            'Щ' => 'shh',
            'Ъ' => '',
            'Ы' => 'y',
            'Ь' => '',
            'Э' => 'eh',
            'Ю' => 'ju',
            'Я' => 'ja',
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'jo',
            'ж' => 'zh',
            'з' => 'z',
            'и' => 'i',
            'й' => 'j',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'kh',
            'ц' => 'c',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'shh',
            'ъ' => '',
            'ы' => 'y',
            'ь' => '',
            'э' => 'eh',
            'ю' => 'ju',
            'я' => 'ja',
        );

        if (isset($message)) {
            $text = implode(array_slice(explode('<br>', wordwrap(trim(strip_tags(html_entity_decode($message))), 255, '<br>', false)), 0, 1));
            foreach ($array as $from => $to) {
                $text = str_replace($from, $to, $text);
            }
            return strtolower($text);
        } else {
            return $array;
        }
    }

    /**
     * Генератор случайного кода
     * 
     * @param type $var
     * @return type
     */
    static function gen($var) {
        $var = intval($var);
        $gen = "";
        for ($i = 0; $i < $var; $i++) {
            $te = mt_rand(48, 122);
            if (($te > 57 && $te < 65) || ($te > 90 && $te < 97))
                $te = $te - 9;
            $gen .= chr($te);
        }
        return $gen;
    }

    /**
     * 
     * @param type $sourse
     * @return type
     */
    public static function bb_decode($sourse) {

        $bb = array();
        $html = array();
        $bb[] = "#\[url\](ed2k://\|file\|(.*?)\|\d+\|\w+\|(h=\w+\|)?/?)\[/url\]#is";
        $html[] = "eMule/eDonkey: <a href=\"\\1\" target=\"_blank\" title=\"\\2\">\\2</a>";
        $bb[] = "#\[url=(ed2k://\|file\|(.*?)\|\d+\|\w+\|(h=\w+\|)?/?)\](.*?)\[/url\]#si";
        $html[] = "<a href=\"\\1\" target=\"_blank\" title=\"\\2\">\\4</a>";
        $bb[] = "#\[url\](ed2k://\|server\|([\d\.]+?)\|(\d+?)\|/?)\[/url\]#si";
        $html[] = "ed2k Server: <a href=\"\\1\" target=\"_blank\" title=\"\\2\">\\2</a> - Port: \\3";
        $bb[] = "#\[url=(ed2k://\|server\|[\d\.]+\|\d+\|/?)\](.*?)\[/url\]#si";
        $html[] = "<a href=\"\\1\" target=\"_blank\" title=\"\\2\">\\2</a>";
        $bb[] = "#\[url\](ed2k://\|friend\|(.*?)\|[\d\.]+\|\d+\|/?)\[/url\]#si";
        $html[] = "Friend: <a href=\"\\1\" target=\"_blank\" title=\"\\2\">\\2</a>";
        $bb[] = "#\[url=(ed2k://\|friend\|(.*?)\|[\d\.]+\|\d+\|/?)\](.*?)\[/url\]#si";
        $html[] = "<a href=\"\\1\" target=\"_blank\" title=\"\\3\">\\3</a>";
        $bb[] = "#\[url\]([\w]+?://([\w\#$%&~/.\-;:=,?@\]+]+|\[(?!url=))*?)\[/url\]#is";
        $html[] = "<a href=\"\\1\" target=\"_blank\" title=\"\\1\">\\1</a>";
        $bb[] = "#\[url\]((www|ftp)\.([\w\#$%&~/.\-;:=,?@\]+]+|\[(?!url=))*?)\[/url\]#is";
        $html[] = "<a href=\"http://\\1\" target=\"_blank\" title=\"\\1\">\\1</a>";
        $bb[] = "#\[url=([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*?)\]([^?\n\r\t].*?)\[/url\]#is";
        $html[] = "<a href=\"\\1\" target=\"_blank\" title=\"\\1\">\\2</a>";
        $bb[] = "#\[url=((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*?)\]([^?\n\r\t].*?)\[/url\]#is";
        $html[] = "<a href=\"http://\\1\" target=\"_blank\" title=\"\\1\">\\3</a>";
        $bb[] = "#\[mail\](\S+?)\[/mail\]#i";
        $html[] = "<a href=\"mailto:\\1\">\\1</a>";
        $bb[] = "#\[mail\s*=\s*([\.\w\-]+\@[\.\w\-]+\.[\w\-]+)\s*\](.*?)\[\/mail\]#i";
        $html[] = "<a href=\"mailto:\\1\">\\2</a>";

        $bb[] = "#\[img\]([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#i";
        $html[] = "<img src=\"\\1\" border=\"0\" alt=\"\\1\" title=\"\\1\">";
        $bb[] = "#\[img=([a-zA-Z]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#is";
        $html[] = "<img src=\"\\2\" align=\"\\1\" border=\"0\" alt=\"\\2\" title=\"\\2\">";
        $bb[] = "#\[img\ alt=([a-zA-Zа-яА-Я0-9\_\-\. ]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#is";
        $html[] = "<img src=\"\\2\" align=\"\\1\" border=\"0\" alt=\"\\1\" title=\"\\1\">";
        $bb[] = "#\[img=([a-zA-Z]+) alt=([a-zA-Zа-яА-Я0-9\_\-\. ]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#is";
        $html[] = "<img src=\"\\3\" align=\"\\1\" border=\"0\" alt=\"\\2\" title=\"\\2\">";
        $bb[] = "#\[b\](.*?)\[/b\]#si";
        $html[] = "<b>\\1</b>";
        $bb[] = "#\[i\](.*?)\[/i\]#si";
        $html[] = "<i>\\1</i>";
        $bb[] = "#\[u\](.*?)\[/u\]#si";
        $html[] = "<u>\\1</u>";

        $bb[] = "#\[sub\](.*?)\[/sub\]#si";
        $html[] = "<sub>\\1</sub>";
        $bb[] = "#\[sup\](.*?)\[/sup\]#si";
        $html[] = "<sup>\\1</sup>";
        $bb[] = "#\[s\](.*?)\[/s\]#si";
        $html[] = "<s>\\1</s>";

        $bb[] = "#\[size=([0-9]{1,2}+)\](.*?)\[/size\]#si";
        $html[] = "<span style=\"font-size: \\1px\">\\2</span>";
        $bb[] = "#\[font=([A-Za-z ]+)\](.*?)\[/font\]#si";
        $html[] = "<span style=\"font-family: \\1\">\\2</span>";

        $bb[] = "#\[color=(\#[0-9A-F]{6}|[a-z]+)\](.*?)\[/color\]#si";
        $html[] = "<span style=\"color: \\1\">\\2</span>";

        $bb[] = "#\[(left|right|center)\](.*?)\[/\\1\]#is";
        $html[] = "<div align=\"\\1\">\\2</div>";

        $bb[] = "#\[quote\](.*?)\[/quote\]#is";
        $html[] = "<blockquote>\\1</blockquote>";
        $bb[] = "#\[code\](.*?)\[/code\]#is";
        $html[] = "<code>\\1</code>";
        $bb[] = "#;\)#si";
        $html[] = "<img src='" . Yii::app()->theme->baseUrl . "/assets/images/smiles/wink.png' border='0' alt=''>";
        $bb[] = "#:\)#si";
        $html[] = "<img src='" . Yii::app()->theme->baseUrl . "/assets/images/smiles/smiley.png' border='0' alt=''>";
        $bb[] = "#:_\(#si";
        $html[] = "<img src='" . Yii::app()->theme->baseUrl . "/assets/images/smiles/cry.png' border='0' alt=''>";
        $bb[] = "#B-\)#si";
        $html[] = "<img src='" . Yii::app()->theme->baseUrl . "/assets/images/smiles/cool.png' border='0' alt=''>";
        $bb[] = "#>\(\(#si";
        $html[] = "<img src='" . Yii::app()->theme->baseUrl . "/assets/images/smiles/evil.png' border='0' alt=''>";
        $bb[] = "#>\(#si";
        $html[] = "<img src='" . Yii::app()->theme->baseUrl . "/assets/images/smiles/mad.png' border='0' alt=''>";



        $bb[] = "#\[list\](.*?)\[/list\]#si";
        $html[] = "<ul>\\1</ul>";
        $bb[] = "#\[list=1\](.*?)\[/list\]#si";
        $html[] = "<ol>\\1</ol>";

        $bb[] = "#\[\*\](.*?)\[/\*\]#si";
        $html[] = "<li>\\1</li>";


        $bb[] = "#javascript:#si";
        $html[] = "Java Script";
        $bb[] = "#about:#si";
        $html[] = "About";
        $bb[] = "#vbscript:#si";
        $html[] = "VB Script";
//if (preg_match("#(.*)\[list\](.*)\[/list\](.*)#si", $sourse, $matches)){
//	$sourse = CMS::bb_decode($matches[1]).preg_replace("#\[\*\](.*?)\[/\*\]#si", "<li>\\1</li>", $matches[2]).CMS::bb_decode($matches[3]);
//}

        $sourse = preg_replace($bb, $html, $sourse);
        return $sourse;
    }

}
