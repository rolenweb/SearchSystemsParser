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
use yii\helpers\Json;

use app\models\Bing;
use app\models\Yahoo;
use app\models\Yandex;
use app\models\Aol;
use app\models\Ecosia;
use app\models\Ixquick;
use app\models\Ask;
use app\models\Google2 as Google;
use app\models\ScheduleParseSearch as Schedule;
use app\models\SearchData;
use app\models\SearchDataApi;

use Exception;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class PostSSDataController extends BaseCommand
{
    public $system_name;
    public $system;
    public $result;
    protected $sites = 
    [
        [
            'url' => 'http://womanclothing',
            'token' => '7Kb7IuoeA86BXnTFixzauJGdEnthJrDi',
        ],
        [
            'url' => 'http://menclothing',
            'token' => '7Kb7IuoeA86BXnTFixzauJGdEnthJrDi',
        ]
    ];
    
    public function actionIndex()
    {
        $site = $this->sites[1];
        /*$schedule = $api
                ->setUrl(
                    [
                        'endpoint' => $site['url'].'/search-data-shedule-apis/',
                        'token' => $site['token'],
                        'id' => 1,

                    ]
                )
                ->view();

        var_dump(Json::decode($schedule->result));
        
        $update = $api
                ->setUrl(
                    [
                        'endpoint' => $site['url'].'/search-data-shedule-apis/',
                        'token' => $site['token'],
                        'id' => 1,

                    ]
                )
                ->update(
                    [
                        'product_id' => 3,
                    ]
                );
        
        $productSearchData = $api
                ->setUrl(
                    [
                        'endpoint' => $site['url'].'/product-apis/search-data/',
                        'token' => $site['token'],
                        'id' => 1,

                    ]
                )
                ->listAll();

        $productSearchData = $api
                ->setUrl(
                    [
                        'endpoint' => $site['url'].'/product-apis/',
                        'token' => $site['token'],
                        'id' => 1,

                    ]
                )
                ->view();

        $productSearchData = $api
                ->setUrl(
                    [
                        'endpoint' => $site['url'].'/product-apis/next/',
                        'token' => $site['token'],
                        'id' => 11515,

                    ]
                )
                ->view();

        var_dump($productSearchData);
        die;
              */  

        for (;;) {
            $site = $this->sites[1];

            $this->whisper('Post to '.$site['url']);

            $this->system_name = $this->ss()[rand(0,count($this->ss())-1)];

            $this->whisper('Parse SS: '.$this->system_name);

            $api = new SearchDataApi();

            $schedule = Json::decode(
                $api
                    ->setUrl(
                        [
                            'endpoint' => $site['url'].'/search-data-shedule-apis/',
                            'token' => $site['token'],
                            'id' => 1,

                        ]
                    )
                    ->view()->result
            ); 

            if (empty($schedule['product_id'])) {
                $this->error('product_id is null');
                continue;
            }
            unset($api);

            $api = new SearchDataApi();

            $product = Json::decode(
                $api
                    ->setUrl(
                        [
                            'endpoint' => $site['url'].'/product-apis/',
                            'token' => $site['token'],
                            'id' => $schedule['product_id'],

                        ]
                    )
                    ->view()->result
            );
            if (empty($product['title'])) {
                $this->error('Product is not found');
                continue;
            }
            unset($api);

            $api = new SearchDataApi();

            $nextProduct = Json::decode(
                $api
                    ->setUrl(
                        [
                            'endpoint' => $site['url'].'/product-apis/next/',
                            'token' => $site['token'],
                            'id' => $product['id'],

                        ]
                    )
                    ->view()->result
            );

            if (empty($nextProduct['title'])) {
                $this->error('Next product is not found');
                continue;
            }
            unset($api);
            $this->whisper('Parse Title: '.$product['title']);

            switch ($this->system_name) {
                case 'bing':
                    $this->system = new Bing();
                    break;

                case 'yahoo':
                    $this->system = new Yahoo();
                    break;

                case 'yandex':
                    $this->system = new Yandex();
                    break;

                case 'aol':
                    $this->system = new Aol();
                    break;

                case 'ecosia':
                    $this->system = new Ecosia();
                    break;

                case 'ixquick':
                    $this->system = new Ixquick();
                    break;

                case 'ask':
                    $this->system = new Ask();
                    break;

                case 'google':
                    $this->system = new Google();
                    break;
                
                default:
                    # code...
                    break;
            }
            try {
                $this->result = $this->system->parse(strtolower($product['title']));    
            } catch (Exception $e){
                $this->error($e->getMessage());
            }

            
            if (empty($this->result) === false) {
                $api = new SearchDataApi();

                $oldProductSearchData = Json::decode(
                    $api
                        ->setUrl(
                            [
                                'endpoint' => $site['url'].'/product-apis/search-data/',
                                'token' => $site['token'],
                                'id' => $product['id'],
                            ]
                        )
                        ->view()->result
                ); 
                unset($api);

                if (empty($oldProductSearchData) === false) {
                    $api = new SearchDataApi();

                    foreach ($oldProductSearchData as $singleProductSearchData) {
                        
                        $deleteSingleProductSearchData = $api
                            ->setUrl(
                                [
                                    'endpoint' => $site['url'].'/search-data-apis/',
                                    'token' => $site['token'],
                                    'id' => $singleProductSearchData['id'],

                                ]
                            )
                            ->delete();
                    }
                    
                    unset($api);
                }
                foreach ($this->result as $item) {
                    if (empty($item['title']) === false && empty($item['url']) === false && empty($item['snippet']) === false) {
                        
                        $api = new SearchDataApi();

                        $newSearchData = Json::decode(
                            $api
                                ->setUrl(
                                    [
                                        'endpoint' => $site['url'].'/search-data-apis',
                                        'token' => $site['token'],
                                    ]
                                )
                                ->create(
                                    [
                                        'product_id' => $schedule['product_id'],
                                        'name_ss' => $this->system_name,
                                        'title' => $item['title'],
                                        'url' => $item['url'],
                                        'snippet' => $item['snippet']
                                    ]
                                )->result
                        ); 
                        unset($api);
                        if (empty($newSearchData['product_id'])) {
                            foreach ($newSearchData as $error) {
                                $this->error($error['message']);
                                continue;
                            }
                        }
                        
                    }
                }

                $api = new SearchDataApi();

                $updateShedule = Json::decode(
                    $api
                        ->setUrl(
                            [
                                'endpoint' => $site['url'].'/search-data-shedule-apis/',
                                'token' => $site['token'],
                                'id' => 1,

                            ]
                        )
                        ->update(
                            [
                                'product_id' => $nextProduct['id'],
                            ]
                        )->result
                );
                unset($api);
                
            }else{
                $this->error('Result is null');
            }
            $sleep = rand(50,70);
            $this->whisper($sleep.' secs');
            sleep($sleep);
        }
    	
        

    }

    public function ss()
    {
        return ['bing','yahoo','yandex','aol','ecosia','ixquick','ask','google'];
    }

    
    
}
