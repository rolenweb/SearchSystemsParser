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
class Iana extends Model
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



        $crawler = $this->client->request('GET', 'https://www.nic.ru/whois/');
        
        $form = $crawler->filter('form[name = "universal_form"]')->form();
        $form['query'] = $this->phrase;
        
        $crawler = $this->client->submit($form);



        $data = $crawler->filter('div.b-whois-info__info')->each(function ($node) {
            return $node->html();          
        });
        
        return (empty($data[0]) === false) ? $data[0] : null;   
    }

    
}
