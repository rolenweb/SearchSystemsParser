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
use app\models\Webspider;
use app\models\SearchSystem;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;

use app\commands\tools\CurlClient as CurlClient;



/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SpiderController extends BaseCommand
{

    public $system_name;
    public $system;
    public $result;
    public $client;
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {
        
    	for (;;) { 
    		$webspider = Webspider::find()->where(['status' => Webspider::STATUS_WATING])->limit(1)->one();
            if (empty($webspider)) {
                $this->client = new Client();
                $jar = new \GuzzleHttp\Cookie\CookieJar();
                $this->client->setClient(
                    new GuzzleClient(
                        [
                            'timeout' => 5,  
                            'cookies' => $jar,
                            
                        ]
                    )
                );
                $this->parserSS();
            }else{
                $this->client = new CurlClient();
                $this->whisper('Parse url: '.$webspider->url);
                
                $content = $this->client->parsePage2($webspider->url);

                $links = $this->client->parseProperty($content,'attribute','a',null,'href');
                    
                if (empty($links) === false) {
                    foreach ($links as $link) {
                        $parseUrl = parse_url($link);
                        if (empty($parseUrl['host']) || empty($parseUrl['scheme'])) {
                            continue;
                        }
                        $newLink = new Webspider();
                        $newLink->domain = $parseUrl['host'];
                        $newLink->status = Webspider::STATUS_WATING;
                        $newLink->url = (strlen($link) > 255) ? $parseUrl['scheme'].'://'.$parseUrl['host'] : $link;
                        if ($newLink->save()) {
                            $this->success($newLink->url);
                        }else{
                            foreach ($newLink->errors as $error) {
                                //$this->error($error[0]);
                            }
                        }
                    }
                }

                
            }
            $webspider->parsed();
    	}
    }

    public function parserSS()
    {
        $this->system_name = $this->ss()[rand(0,count($this->ss())-1)];

        $this->whisper('Parse SS: '.$this->system_name);

        $ss = SearchSystem::searchByTitle('Multi');
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

        switch ($this->system_name) {
            case 'bing':
                $this->system = new Bing();
                $this->result = $this->system->parse(strtolower($keyword->key));
                break;

            case 'yahoo':
                $this->system = new Yahoo();
                $this->result = $this->system->parse(strtolower($keyword->key));
                break;

            case 'yandex':
                $this->system = new Yandex();
                $this->result = $this->system->parse(strtolower($keyword->key));
                break;

            case 'aol':
                $this->system = new Aol();
                $this->result = $this->system->parse(strtolower($keyword->key));
                 break;

            case 'ecosia':
                $this->system = new Ecosia();
                $this->result = $this->system->parse(strtolower($keyword->key));
                break;

            case 'ixquick':
                $this->system = new Ixquick();
                $this->result = $this->system->parse(strtolower($keyword->key));
                break;

            case 'ask':
                $this->system = new Ask();
                $this->result = $this->system->parse(strtolower($keyword->key));
                break;

            case 'google':
                $this->system = new Google2();
                $this->result = $this->system->parse(strtolower($keyword->key));
                break;
                
            default:
                # code...
                break;
        }
        if (empty($this->result)) {
            $this->error('Search data is null');
            return;
        }
        foreach ($this->result as $result) {
            if (empty($result['url'])) {
                continue;
            }
            $parseUrl = parse_url($result['url']);
            if ($parseUrl === false) {
                continue;
            }
            if (empty($parseUrl['host']) || empty($parseUrl['scheme'])) {
                continue;
            }
            $newLink = new Webspider();
            $newLink->domain = $parseUrl['host'];
            $newLink->status = Webspider::STATUS_WATING;
            $newLink->url = (strlen($result['url']) > 255) ? $parseUrl['scheme'].'://'.$parseUrl['host'] : $result['url'];
            if ($newLink->save()) {
                # code...
            }else{
                foreach ($newLink->errors as $error) {
                    $this->error($error[0]);
                }
            }
        }

        $nextKey = $position[0]->nextKey();

        if (empty($nextKey['error']) === false) {
            foreach ($nextKey['error'] as $message) {
                $this->error($message);
                die;
            }
        }

        return;
    }

    public function ss()
    {
        return ['bing','yahoo','yandex','aol','ecosia','ixquick','ask','google'];
    }

}
