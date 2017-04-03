<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;

use app\models\Bing;
use app\models\Yahoo;
use app\models\Yandex;
use app\models\Aol;
use app\models\Ecosia;
use app\models\Ixquick;
use app\models\Ask;
use app\models\Google2;
use app\models\Iana;

use Spatie\Regex\Regex;
use Phois\Whois\Whois;

use yii\base\ErrorException;



/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class TestParserController extends BaseCommand
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {
    	$iana = new Iana();
        $data  = $iana->parse('lendup.com');
        
        $out = Regex::matchAll('/Creation Date:.(.*)</', $data)->results();
            

        try {
            $this->success(date("Y-m-d",strtotime($out[0]->group(1))));
        } catch (ErrorException $e) {
            $this->error($e->getMessage());
        }
    }

}
