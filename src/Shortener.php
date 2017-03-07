<?php

namespace LojasKD\Url;

class Shortener
{

    private $apiUrl = 'https://www.googleapis.com/urlshortener/v1/url';
    private $key = '';

    public function __construct($key, $apiUrl = null)
    {
        $this->key = $key;
        if (!is_null($apiUrl)) {
            $this->apiUrl = $apiUrl;
        }
    }

    public function shorten($url)
    {
        $response = $this->send($url);
        return isset($response['id']) ? $response['id'] : false;
    }

    public function send($url, $expand = false)
    {
        $curl = curl_init();
        $uri = "{$this->apiUrl}?key={$this->key}" . ($expand ? "&shortUrl={$url}" : "");
        curl_setopt($curl, CURLOPT_URL, $uri);

        $header = array("Content-Type: application/json");
        if ("DEV" == "DEV") {
            $header[] = "Referer: www.lojaskd.com.br";
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        if (!$expand) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array("longUrl" => $url)));
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return json_decode($result, true);
    }

    public function expand($url)
    {
        $response = $this->send($url, false);
        return isset($response['longUrl']) ? $response['longUrl'] : false;
    }
}
