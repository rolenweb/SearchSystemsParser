<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Console;


/**
 * ContactForm is the model behind the contact form.
 */
class SearchDataApi extends Model
{
    public $curl;
    public $result;
    public $url;

    public function __construct($config = [])
    {
        $this->scenario = (empty($config['scenario']) === false) ? $config['scenario'] : 'default';
        $this->curl = curl_init();
    }

    public function setUrl($data)
    {
        if (empty($data['id'])) {
            $this->url = $data['endpoint'].'?access-token='.$data['token'];
        }else{
            $this->url = $data['endpoint'].$data['id'].'?access-token='.$data['token'];
        }

        return $this;
    }
    
    public function rules()
    {
        return [
            
        ];
    }

    public function attributeLabels()
    {
        return [
            
            
        ];
    }

    public function listAll()
    {
        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        $this->result = curl_exec($this->curl);
        return $this;
    }
    
    public function create($data)
    {
        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($data)); 
        curl_setopt($this->curl, CURLOPT_POST, 1);
        $this->result = curl_exec($this->curl);
        return $this;
    }

    public function delete()
    {
        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($this->curl, CURLOPT_POST, 1);
        curl_exec($this->curl);
        return;
    }

    public function view()
    {
        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        $this->result = curl_exec($this->curl);
        return $this;
    }

    public function update($data)
    {

        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($data)); 
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: PUT'));
        $this->result = curl_exec($this->curl);
        return $this;
    }

    public function __destruct()
    {
        curl_close($this->curl);
    }
}
