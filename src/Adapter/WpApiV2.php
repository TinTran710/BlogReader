<?php

namespace Dok123\BlogReader\Adapter;
use GuzzleHttp\Client;

class WpApiV2 extends ReaderAbstract {

    const BASE_URL = '/wp-json/wp/v2/';
    protected $url;
    protected $page = 1;

    public function __construct($url) {
        $this->url = $url;
        $request = $this->url.'/wp-json';
        $this->blog_info = $this->makeHttpRequest($request);
    }

    public function getInfo() {
        return $this->blog_info;
    }

    public function posts(array $fields = null, $page = null, $per_page = 20) {
        $request = $this->url.self::BASE_URL.'posts/?per_page='.$per_page;

        if($page) {
            $this->page = $page;
            $request .= '&page=' .$page;
        }

        $this->posts_info = $this->makeHttpRequest($request);

        if($fields) {
            $responseArray = [];
            $index = 0;
            foreach($this->posts_info as $post) {
                foreach($fields as $value) {
                    $responseArray[$index][$value] = $post->$value;
                }
                $index++;
            }
            $this->posts_info = $responseArray;
        }

        return $this->posts_info;
    }

    public function next() {
        $this->page++;
        $page = $this->page;
        $request = $this->url.self::BASE_URL.'posts/?page='.$page;
        $client = new \GuzzleHttp\Client(array('curl' => array( CURLOPT_SSL_VERIFYPEER => false,),));
        try {
            $response = $client->request('GET', $request);
            $objectResponse = json_decode($response->getBody());
            $arrayResponse = (array) $objectResponse;
            $this->posts_info =  $arrayResponse;
            return true;
        } catch(\Exception $e) {
            return false;
        }
    }

    public function current_page() {
        return $this->page;
    }

    public function setKeyword($keyword) {
        $this->keyword = $keyword;
        $request = $this->url.self::BASE_URL.'posts/?search='.$keyword;
        $this->posts_info = $this->makeHttpRequest($request);
        return $this->posts_info;
    }

    public function resetKeyword() {
        $this->keyword = '';
        $request = $this->url.self::BASE_URL.'posts/';
        $this->posts_info = $this->makeHttpRequest($request);
        return $this->posts_info;
    }

    public function labels($limit = 100) {
        $request = $this->url.self::BASE_URL.'categories/';
        $arrayReponse = $this->makeHttpRequest($request);
        $labels = [];
        $count = 1;

        foreach($arrayReponse as $item) {
            if($count <= $limit) {
                $labels[] = $item->name;
                $count++;
            }
        }

        if($count < $limit) {
            $request = $this->url.self::BASE_URL.'tags/';
            $arrayReponse = $this->makeHttpRequest($request);
            foreach($arrayReponse as $item) {
                if($count <= $limit) {
                    $labels[] = $item->name;
                    $count++;
                }
            }
        }

        return $labels;
    }


}