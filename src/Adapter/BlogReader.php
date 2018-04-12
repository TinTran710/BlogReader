<?php

namespace Dok123\BlogReader\Adapter;
use GuzzleHttp\Client;

class BlogReader extends ReaderAbstract {

    const BASE_URL = 'https://www.googleapis.com/blogger/v3/blogs/';
    protected $api_key = 'AIzaSyBKjXhnrkKS-WT4wjtFPkYq2bn52gUUt0o';
    protected $page_token;

    public function __construct($url) {
        $request = self::BASE_URL.'byurl?url='.$url.'/&key='.$this->api_key;
        $this->blog_info = $this->makeHttpRequest($request);
    }

    public function posts(array $fields = null, $page = null, $per_page = 20) {
        $request = self::BASE_URL.$this->blog_info['id'].'/posts/?key='.$this->api_key.'&maxResults='.$per_page;

        $strFields = 'items(';
        if($fields) {
            foreach($fields as $key => $value) {
                if($key == sizeof($fields) - 1) {
                    $strFields .= $value;
                } else {
                    $strFields .= $value.', ';
                }
            }
            $strFields .= ')';
            $request .= '&fields='.$strFields;
        }

        if($page) {
            $request .= '&pageToken=' .$page;
        }
        $this->posts_info = $this->makeHttpRequest($request);
        return $this->posts_info;
    }

    public function next() {
        if(!array_key_exists('nextPageToken', $this->posts_info) || $this->posts_info['nextPageToken'] == null) {
            return false;
        } else {
            $request = self::BASE_URL.$this->blog_info['id'].'/posts?key='.$this->api_key.'&pageToken='.$this->posts_info['nextPageToken'];
            $this->page_token = $this->posts_info['nextPageToken'];
            $this->posts_info = $this->makeHttpRequest($request);
            return true;
        }
    }

    public function current_page() {
        return $this->page_token;
    }

    public function setKeyword($keyword) {
        $this->keyword = $keyword;
        $request = self::BASE_URL.$this->blog_info['id'].'/posts/search?q='.$this->keyword.'&key='.$this->api_key;
        $this->posts_info = $this->makeHttpRequest($request);
        return $this->posts_info;
    }

    public function resetKeyword() {
        $this->keyword = '';
        $request = self::BASE_URL.$this->blog_info['id'].'/posts/search?q='.$this->keyword.'&key='.$this->api_key;
        $this->posts_info = $this->makeHttpRequest($request);
        return $this->posts_info;
    }

    public function labels($limit = 100) {
        $labels = [];
        foreach($this->posts_info['items'] as $item) {
            if(isset($item->labels)) {
                foreach ($item->labels as $label) {
                    if (sizeof($labels) == $limit) {
                        break;
                    }
                    $labels[] = $label;
                }
            }
        }
        return $labels;
    }

    public function get_page_token() {
        return $this->page_token;
    }

}