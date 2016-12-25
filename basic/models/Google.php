<?php

namespace app\models;

use Yii;
use yii\base\Model;

use app\models\Help;
use app\commands\tools\CurlClient;
/**
 * ContactForm is the model behind the contact form.
 */
class Google extends Model
{
    
    const URL = 'http://www.google.com/search?hl=en&output=search&q=';

    public static function parse($search)
    {
        $result = [];
        if (empty($search)) {
            return;           
        }
        $url = self::URL.urlencode($search);
            $client = new CurlClient();
            $content = $client->parsePage($url);

            $titles = $client->parseProperty($content,'string','h3.r > a',$url,null);
            $descriptions = $client->parseProperty($content,'html','span.st',$url,null);
            $links =  $client->parseProperty($content,'link','h3.r > a',$url,null);

            if (empty($titles) === false) {
                foreach ($titles as $n => $title) {
                    $result[$n]['title'] = $title;
                    $result[$n]['description'] = (empty($descriptions[$n]) === false) ? $descriptions[$n] : null;
                    $result[$n]['url'] = (empty($links[$n]) === false) ? Help::cutUrl($links[$n]) : null;
                }
            }
        return $result;
    }
}
