<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class Help extends Model
{
    
    public static function cutUrl($url)
    {
        $pos = stripos($url,'/url?q=');
        if ($pos !== false) {
            return substr($url,$pos+7);
        }else{
        	return $url;	
        }
        
    }
}
