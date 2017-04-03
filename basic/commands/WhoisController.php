<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;

use app\models\Iana;
use app\models\Domain;

use Spatie\Regex\Regex;

use yii\base\ErrorException;



/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class WhoisController extends BaseCommand
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

    public function actionCreated()
    {
        $iana = new Iana();
        $query = Domain::find()->where(['creation_date' => null]);
        $count = clone $query;
        $this->whisper('Count: '.$count->count());
        foreach ($query->all() as $domain) {
            $this->whisper($domain->title);
            $data  = $iana->parse($domain->title);

            if (empty($data)) {
                $this->error('Parsed data is null');
                continue;
            }
        
            $out = Regex::matchAll('/Creation Date:.(.*)</', $data)->results();
                

            try {
                $this->success(date("Y-m-d",strtotime($out[0]->group(1))));
                $domain->creation_date = strtotime($out[0]->group(1));
                if ($domain->save()) {
                    # code...
                }else{
                    foreach ($domain->errors as $error) {
                        $this->error($error[0]);
                    }
                }

            } catch (ErrorException $e) {
                $this->error($e->getMessage());
            }
        }
    }

}
