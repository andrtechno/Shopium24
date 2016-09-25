<?php

return array(
    'sourcePath' => dirname(__FILE__) . DS . '..',
    'messagePath' => dirname(__FILE__) . DS . '..' . DS . 'messages',
    //'languages' => array('zh_cn', 'zh_tw', 'de', 'el', 'es', 'sv', 'he', 'nl', 'pt', 'pt_br', 'ru', 'it', 'fr', 'ja', 'pl', 'hu', 'ro', 'id', 'vi', 'bg', 'lv', 'sk'),
    'languages' => array('en', 'ru', 'uk'),
    'fileTypes' => array('php'),
    'overwrite' => true,
    'exclude' => array(
        '/messages',
        '/vendors',
        '/assets',
    ),
);
