<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;

use app\models\Google;
use app\models\SearchSystem;
use app\models\SearchResult;
use app\models\PositionParser;

use app\commands\tools\CurlClient;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ParserController extends BaseCommand
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($system)
    {
    	for (;;) { 
    		
            switch ($system) {
                case 'google':
                    $this->whisper('Parse: Google');
                    $ss = SearchSystem::searchByTitle('Google');
                    if (empty($ss)) {
                        $this->error('Search System is not found');
                        return;
                    }
                    $position = $ss->positionParser;

                    if (empty($position[0])) {
                        $this->error('The position is not found');
                        return;
                    }
                    $keyword = $position[0]->keyword;
                    if (empty($keyword)) {
                        $this->error('The keyword is not found');
                        return;
                    }
                    $this->whisper('Key: '.$keyword->key);
                    $content = Google::parse($keyword->key);

                    if (empty($content)) {
                        $this->error('Content is null');
                        return;
                    }
                    $result = SearchResult::saveContent($content,$ss->id,$keyword->id);
                    if (empty($result['error']) === false) {
                        foreach ($result['error'] as $message) {
                            $this->error($message);
                        }
                    }

                    $nextPosition = PositionParser::next($position[0]->id);

                    if (empty($nextPosition['error']) === false) {
                        foreach ($nextPosition['error'] as $message) {
                            $this->error($message);
                        }
                    }
                    break;
                
                default:
                    # code...
                    break;
            }
            $sleep = rand(100,200);
            $this->whisper('Sleep '.$sleep.' sec');
            sleep($sleep);
    	}
        
    }

}
