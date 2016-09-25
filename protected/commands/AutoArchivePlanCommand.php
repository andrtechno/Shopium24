<?php

class AutoArchivePlanCommand extends CConsoleCommand {

    const FILENAME = 'extract';
    const PATH_CREATE = 'webroot.archive.plans';

    public function run($args) {

        $directory = realpath(Yii::getPathOfAlias('application') . DS . '..' . DS . '..' . DS . 'demo'); // папка какую заархивировать.
        $filename = self::FILENAME . '.zip';
        $df = realpath(Yii::getPathOfAlias('application') . '/../archive/plans/') . '/' . $filename; //путь куда будет сохранен ZIP файл.

        $zip = new ZipArchive();
        if (file_exists($df)) {
            unlink($df);
        }

        $res = $zip->open($df, ZipArchive::CREATE);
        if ($res === true) {
            $files = CFileHelper::findFiles($directory, array(
                        'level' => -1,
                        'absolutePaths' => false
            ));

            foreach ($files as $file) {
                if (!preg_match("/^assets\/[a-zA-Z0-9]+/si", $file) && !preg_match("/^protected\/runtime\/[a-zA-Z0-9]+/si", $file)) {
                    $zip->addFile("{$directory}/{$file}", "{$file}");
                }
            }

            $zip->close();
        } else {
            Yii::log('ERROR CRON AUTO ZIP', 'info', 'cron');
        }
    }

}
