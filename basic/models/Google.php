<?php

namespace app\models;

use Yii;
use yii\base\Model;

use app\models\Help;
use app\commands\tools\CurlClient;
use app\commands\tools\Recognize;

use NekoWeb\AntigateClient;
use iMarc\GoogleCustomSearch;

/**
 * ContactForm is the model behind the contact form.
 */
class Google extends Model
{
    
    
    public static function parse($search)
    {
        
        $result = [];
        if (empty($search)) {
            return;           
        }
        
        if (empty(Yii::$app->params['gcs_api'])) {
            return [
                'error' => [
                    'Google Custom Search API KEY is not set',
                ]
            ]; 
        }
        if (empty(Yii::$app->params['search_engine_id'])) {
            return [
                'error' => [
                    'Google Search Engine ID is not set',
                ]
            ]; 
        }
        $GCS = new GoogleCustomSearch(Yii::$app->params['search_engine_id'], Yii::$app->params['gcs_api']);


        $params = [
            'gl' => 'us',
            'hl' => 'en',
            'googlehost' => 'google.com',
        ];
        
        $result = $GCS->search(urlencode($search),1,10,$params);

        return (empty($result->results) === false) ? $result->results : null;
    }

    public static function recaptcha($client, $content)
    {

        $src_captcha = $client->parseProperty($content,'attribute','img','','src');
        if (empty($src_captcha[0])) {
            return;
        }
        $query = (empty(parse_url($src_captcha[0])['query']) === false) ? parse_url($src_captcha[0])['query'] : null;
        if (empty($query)) {
            return;
        }
        parse_str($query);
        if (empty($id)) {
            return;
        }
        if (empty($continue)) {
            return;
        }

        $q_captcha = $client->parseProperty($content,'attribute','input[name = "q"]','','value');
        var_dump($q_captcha[0]);

        $continue_captcha = $client->parseProperty($content,'attribute','input[name = "continue"]','','value');
        var_dump($continue_captcha[0]);
        
        $contentImg = $client->parsePage2('https://www.google.com'.$src_captcha[0]);
        file_put_contents(Yii::$app->basePath.DIRECTORY_SEPARATOR."recognize/captcha.jpg", $contentImg);
        
        /*$clientAntigate = new AntigateClient();
        $clientAntigate->setApiKey('7f737bcdc78ddcd7f204e40f532643ff');
        $captcha = $clientAntigate->recognizeByFilename(Yii::$app->basePath.DIRECTORY_SEPARATOR."recognize/captcha.jpg");
        var_dump($captcha);
        if (empty($captcha)) {
            return;
        }*/

        $captcha = (new Recognize())->recognize(Yii::$app->basePath.DIRECTORY_SEPARATOR."recognize/captcha.jpg", '');
            
        
        $captchaForm = array(
            'q'       => $q_captcha[0],
            
            'continue' => $continue_captcha[0],
            'captcha'  => $captcha,
            'submit'   => 'Submit',
          );
        var_dump($captchaForm);
        $url = 'http://ipv4.google.com/sorry/index' . '?' . http_build_query($captchaForm, '', '&');


        $content2 = $client->parsePage2($url);

        var_dump($content2);
        die;
    }
}
