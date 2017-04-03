<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Console;

use Goutte\Client;

/**
 * ContactForm is the model behind the contact form.
 */
class Duckduckgo extends Model
{
    public $phrase;
    public $url;
    public $client;

    public function __construct($config = [])
    {
        $this->scenario = (empty($config['scenario']) === false) ? $config['scenario'] : 'default';
        $this->client = new Client();
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

        $crawler = $this->client->request('GET', 'https://duckduckgo.com/');
        
        $form = $crawler->filter('form#search_form_homepage')->form();
        $form['q'] = $this->phrase;
        
        $crawler = $this->client->submit($form);

        $title = $crawler->filter('div#links h2.result__title a.result__a')->each(function ($node) {
            return $node->text();          
        });

        $url = $crawler->filter('div#links h2.result__title a.result__a')->extract(array('href'));
        
        $snippet = $crawler->filter('div#links div.result__snippet')->each(function ($node) {
            return $node->html();          
        });
        
        foreach ($title as $n => $item) {
            $response[$n]['title'] = trim($item);
            $response[$n]['url'] = (empty($url[$n]) === false) ? $url[$n] : null;
            $response[$n]['snippet'] = (empty($snippet[$n]) === false) ? $snippet[$n] : null;
        }
        
        return $response;   
    }

    
}
