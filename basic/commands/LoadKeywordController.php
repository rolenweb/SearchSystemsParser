<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\FileHelper;

use app\models\ThemeKeyword;
use app\models\Keyword;


/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class LoadKeywordController extends BaseCommand
{
    
    const FOLDER_UPLOAD = 'upload';

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {
    	if (empty($this->themeid)) {
            $this->listTheme();
            return;
        }
        
        $keys = $this->fileToArray();
        if (empty($keys)) {
            $this->error('The array of keys is null');
            return;
        }
        $total = count($keys);
        $i = 0;
        Console::startProgress($i, $total);
        foreach ($keys as $key) {
            Console::updateProgress(++$i, $total);
            $new_key = new Keyword();
            $new_key->key = $key;
            $new_key->theme_keyword_id = (int)$this->themeid;
            $new_key->save();
        }
        Console::endProgress();
        $this->deleteFiles();
    }

    public function listTheme()
    {
        $list = ThemeKeyword::dd();
        if (empty($list)) {
            $this->error('The list of themes is null');
            return;
        }
        $this->success('Please choose the theme and run: load-keyword -tid=ID');
        foreach ($list as $id => $theme) {
            $this->success($theme.' : '.$id);
        }
    }

    public function fileToArray()
    {
        if (!file_exists(Yii::$app->basePath.DIRECTORY_SEPARATOR.self::FOLDER_UPLOAD)) {
            $this->error(Yii::$app->basePath.DIRECTORY_SEPARATOR.self::FOLDER_UPLOAD.' is not exists');
        }
        $files = FileHelper::findFiles(Yii::$app->basePath.DIRECTORY_SEPARATOR.self::FOLDER_UPLOAD);
        if (empty($files)) {
            $this->error('There are not files for load');
        }

        foreach ($files as $file) {
            return file($file);
        }
    }

    public function deleteFiles()
    {
        if (!file_exists(Yii::$app->basePath.DIRECTORY_SEPARATOR.self::FOLDER_UPLOAD)) {
            $this->error(Yii::$app->basePath.DIRECTORY_SEPARATOR.self::FOLDER_UPLOAD.' is not exists');
        }
        $files = FileHelper::findFiles(Yii::$app->basePath.DIRECTORY_SEPARATOR.self::FOLDER_UPLOAD);
        if (empty($files)) {
            $this->error('There are not files for load');
        }

        foreach ($files as $file) {
            unlink($file);
        }
    }
}
