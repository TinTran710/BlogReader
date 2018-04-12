<?php

namespace Dok123\BlogReader\Adapter;
use GuzzleHttp\Client;

class WpApiV2 extends ReaderAbstract {

    const BASE_URL = '/wp-json/wp/v2/';
    protected $url;
    protected $page;

    public function __construct($url) {
        $this->url = $url;
        $request = $this->url.'/wp-json';
        $this->blog_info = $this->makeHttpRequest($request);
    }

    public function getInfo() {
        return $this->blog_info;
    }

    public function posts(array $fields = null, $page = null, $per_page = 20) {
        $url = substr($this->blog_info['URL'], 7);
        $request = self::BASE_URL.$url.'/posts/?number='.$per_page;

        $strFields = '';
        if($fields) {
            foreach($fields as $key => $value) {
                if($value == 'id') {
                    $value = strtoupper($value);
                }
                if($key == sizeof($fields) - 1) {
                    $strFields .= $value;
                } else {
                    $strFields .= $value.',';
                }
            }
            $request .= '&fields='.$strFields;
        }

        if($page) {
            $this->page = $page;
            $request .= '&page=' .$page;
        }
        $this->posts_info = $this->makeHttpRequest($request);
        return $this->posts_info;
    }

    public function next() {
        $url = substr($this->blog_info['URL'], 7);
        $page = $this->page + 1;
        $request = self::BASE_URL.$url.'/posts/?page='.$page.'&number=20';
        $tempArray = $this->makeHttpRequest($request);

        if($tempArray['found'] == 0) {
            return false;
        } else {
            $this->posts_info = $tempArray;
            return true;
        }
    }

    public function current_page() {
        return $this->page;
    }

    public function setKeyword($keyword) {
        $this->keyword = $keyword;
        $url = substr($this->blog_info['URL'], 7);
        $request = self::BASE_URL.$url.'/posts/?search='.$keyword;
        $this->posts_info = $this->makeHttpRequest($request);
        return $this->posts_info;
    }

    public function resetKeyword() {
        $this->keyword = '';
        $url = substr($this->blog_info['URL'], 7);
        $request = self::BASE_URL.$url.'/posts';
        $this->posts_info = $this->makeHttpRequest($request);
        return $this->posts_info;
    }

    public function labels($limit = 100) {
        $url = substr($this->blog_info['URL'], 7);
        $request = self::BASE_URL.$url.'/categories?number='.$limit;
        $arrayReponse = $this->makeHttpRequest($request);
        foreach($arrayReponse['categories'] as $item) {
            $this->labels[] = $item->name;
        }
        return $this->labels;
    }


}