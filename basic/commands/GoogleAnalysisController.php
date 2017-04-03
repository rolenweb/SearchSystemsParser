<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;

use app\models\Google2 as Google;
use app\models\SearchSystem;
use app\models\GoogleAnalysis;
use app\models\Domain;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;

use app\commands\tools\CurlClient as CurlClient;

use Spatie\Regex\Regex;
use Phois\Whois\Whois;

//Exception
use yii\base\ErrorException;
use Exception;

//Exception



/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GoogleAnalysisController extends BaseCommand
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
            $sleep = rand(60,300);
            $this->whisper('Sleep: '.$sleep);
            sleep($sleep);
    	}
    }

    public function parserSS()
    {
        $this->system_name = 'google';

        $ss = SearchSystem::searchByTitle('GoogleAnalysis');
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
                $this->system = new Google();
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
        foreach ($this->result as $nRes => $result) {
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

            $domain = Domain::findOne(['title' => $parseUrl['host']]);

            if (empty($domain)) {
                $domain = new Domain();
                $domain->title = $parseUrl['host'];
                $domain->creation_date = $this->getCreatedDate($parseUrl['host']);
                $domain->save();
            }

            if (empty($domain->id)) {
                $this->error('Domain is not found');
                continue;
            }
            $newRec = new GoogleAnalysis();
            $newRec->url = $result['url'];
            $newRec->position = $nRes;
            $newRec->domain_id = $domain->id;
            $newRec->keyword_id = $keyword->id;
            if ($newRec->save()) {
                # code...
            }else{
                foreach ($newRec->errors as $error) {
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

    public function getCreatedDate($domain)
    {
        $domain = str_replace('www.','',$domain);
        $this->whisper($domain);
        try {
            $domain = new Whois($domain);
            try {
                $whois_answer = $domain->htmlInfo();

                $out = Regex::matchAll('/Creation Date:.(.*)</', $whois_answer)->results();
            } catch (ErrorException $e) {
                $this->error($e->getMessage());
            } 
        } catch(Exception $e){
            $this->error($e->getMessage());
        }




        try {
            return strtotime($out[0]->group(1));
        } catch (ErrorException $e) {
            $this->error($e->getMessage());
        }
        return;
    }

}
