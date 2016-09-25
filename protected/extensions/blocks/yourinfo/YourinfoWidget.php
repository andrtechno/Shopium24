<?php

/**
 * Cart Widget
 * Display is module shop installed
 * @uses Widget 
 */
class YourinfoWidget extends BlockWidget {

   /* public function getTitle() {
        return Yii::t('YourinfoWidget.default', 'TITLE');
    }*/


 public function run() {

        Yii::import('app.addons.Browser');
        $browserClass = new Browser();
        $browser = $browserClass->getBrowser();
        $platform = $browserClass->getPlatform();

        if ($browser == Browser::BROWSER_FIREFOX) {
            $browserIcon = 'firefox';
        } elseif ($browser == Browser::BROWSER_SAFARI) {
            $browserIcon = 'safari';
        } elseif ($browser == Browser::BROWSER_OPERA) {
            $browserIcon = 'opera';
        } elseif ($browser == Browser::BROWSER_CHROME) {
            $browserIcon = 'chrome';
        } elseif ($browser == Browser::BROWSER_IE) {
            $browserIcon = 'ie';
        }

        if ($platform == Browser::PLATFORM_WINDOWS) {
            $platformIcon = 'windows7';
        } elseif ($platform == Browser::PLATFORM_WINDOWS_8) { //no tested
            $platformIcon = 'windows8';
        } elseif ($platform == Browser::PLATFORM_ANDROID) {
            $platformIcon = 'android';
        } elseif ($platform == Browser::PLATFORM_LINUX) {
            $platformIcon = 'tux';
        } elseif ($platform == Browser::PLATFORM_APPLE) {
            $platformIcon = 'apple';
        }


        $this->render($this->skin, array(
            'platformIcon' => $platformIcon,
            'browserIcon' => $browserIcon,
            'browser' => $browserClass,
        ));
    }

}
