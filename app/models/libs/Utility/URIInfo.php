<?php

class URIInfo 
{
    public $info;
    public $header;
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
        $this->setData();
    }

    public function setData() 
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_FILETIME, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $this->header = curl_exec($curl);
        $this->info = curl_getinfo($curl);
        curl_close($curl);
    }

    public function getContentType() 
    {
        return explode(';', $this->info['content_type'])[0];
    }

    public function getHttpCode()
    {
        return $this->info['http_code'];
    }
}

