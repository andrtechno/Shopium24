<?php

class CManagerTheme extends CThemeManager {

    private $_config;
    protected $data = array();
    public $cache_key = 'cached_theme_settings';

    private function eventTheme($theme) {
        $c = Yii::app()->settings->get('app');
        if (!empty($c['etheme'])) {
            $now = CMS::time();
            $timeStart = strtotime($c['etheme_start']);
            $timeEnd = strtotime($c['etheme_end']);
            if ($timeStart < $now) {
                if ($timeEnd < $now) {
                    $t = $theme;
                } else {
                    $t = $c['etheme'];
                }
                return $t;
            }
        } else {
            return $theme;
        }
    }

    public function getTheme($name) {
        $name =  $this->eventTheme($name);
        $themePath = $this->getBasePath() . DIRECTORY_SEPARATOR . $name;
        if (is_dir($themePath)) {
            $class = Yii::import($this->themeClass, true);
            return new $class($name, $themePath, $this->getBaseUrl() . '/' .$name);
        } else
            return null;
    }

    public function init() {
        if (!Yii::app()->getDb()->schema->getTable('{{settings_theme}}')) {
            Yii::app()->getDb()->createCommand()->createTable('{{settings_theme}}', array(
                'id' => 'pk',
                'type' => 'string NOT NULL',
                'cur' => 'string NOT NULL',
                'rate' => 'string NOT NULL',
                'date' => 'date',
            ));
        }
        $this->data = Yii::app()->cache->get($this->cache_key);

        if (!$this->data) {
            // Load settings
            $settings = Yii::app()->db->createCommand()
                    ->from('{{settings_theme}}')
                    ->order('category')
                    ->queryAll();

            if (!empty($settings)) {
                foreach ($settings as $row) {
                    if (!isset($this->data[$row['category']]))
                        $this->data[$row['category']] = array();
                    $this->data[$row['category']][$row['key']] = $row['value'];
                }
            }
            Yii::app()->cache->set($this->cache_key, $this->data);
        }
    }

    public function getConfig() {
        $this->_config = $this->get(Yii::app()->theme->name);
        return $this->_config;
    }

    public function set($category, array $data) {
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if ($this->get($category, $key) !== null) {
                    Yii::app()->db->createCommand()->update('{{settings_theme}}', array(
                        'value' => $value), '{{settings_theme}}.category=:category AND {{settings_theme}}.key=:key', array(':category' => $category, ':key' => $key));
                } else {
                    Yii::app()->db->createCommand()->insert('{{settings_theme}}', array(
                        'category' => $category,
                        'key' => $key,
                        'value' => $value
                    ));
                }
            }

            if (!isset($this->data[$category]))
                $this->data[$category] = array();
            $this->data[$category] = CMap::mergeArray($this->data[$category], $data);

            // Update cache
            Yii::app()->cache->set($this->cache_key, $this->data);
        }
    }

    /**
     * @param $category string component unique id.
     * @param null $key option key. If not provided all category settings will be returned as array.
     * @param null|string $default default value if original does not exists
     * @return mixed
     */
    public function get($category, $key = null, $default = null) {
        if (!isset($this->data[$category]))
            return $default;

        if ($key === null)
            return $this->data[$category];
        if (isset($this->data[$category][$key]))
            return $this->data[$category][$key];
        else
            return $default;
    }

    /**
     * Remove category from DB
     * @param $category
     */
    public function clear($category) {
        Yii::app()->db->createCommand()->delete('{{settings_theme}}', 'category=:category', array(':category' => $category));
        if (isset($this->data[$category]))
            unset($this->data[$category]);

        Yii::app()->cache->delete($this->cache_key);
    }

}
