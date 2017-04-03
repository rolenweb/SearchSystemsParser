<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Console;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

/**
 * ContactForm is the model behind the contact form.
 */
class Google2 extends Model
{
    public $phrase;
    public $url;
    public $client;

    public function __construct($config = [])
    {
        $this->scenario = (empty($config['scenario']) === false) ? $config['scenario'] : 'default';
        $this->client = new Client();
        $jar = new \GuzzleHttp\Cookie\CookieJar();
        $this->client->setClient(
            new GuzzleClient(
                [
                    'timeout' => 60,  
                    'cookies' => $jar  
                ]
            )
        );
    }
    
    public function rules()
    {
        return [
            [['phrase'], 'string'],
            [['phrase'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'phrase' => 'Phrase',
            
        ];
    }

    
    
    public function parse($phrase)
    {
        $response = [];

        $this->phrase = $phrase;



        $crawler = $this->client->request('GET', 'https://www.google.com/');
        
        $form = $crawler->filter('form[name = "f"]')->form();
        $form['q'] = $this->phrase;
        
        $crawler = $this->client->submit($form);



        $title = $crawler->filter('div#search h3.r a')->each(function ($node) {
            return $node->text();          
        });
        
        $url = $crawler->filter('div#search h3.r a')->extract(array('href'));
        
        $snippet = $crawler->filter('div#search div.s span.st')->each(function ($node) {
            return $node->html();          
        });
        

        foreach ($title as $n => $item) {
            $response[$n]['title'] = trim($item);
            $response[$n]['url'] = (empty($url[$n]) === false) ? str_replace('/url?q=', '', $url[$n]) : null;
            $response[$n]['snippet'] = (empty($snippet[$n]) === false) ? $snippet[$n] : null;
        }

        return $response;   
    }

    
}
