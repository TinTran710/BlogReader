<?php

namespace Dok123\BlogReader\Adapter;

abstract class ReaderAbstract implements ReaderInterface {

    protected $blog_info;
    protected $posts_info;
    protected $keyword;

    public function makeHttpRequest($request) {
        $client = new \GuzzleHttp\Client(array('curl' => array( CURLOPT_SSL_VERIFYPEER => false,),));
        $response = $client->request('GET', $request);
        $objectResponse = json_decode($response->getBody());
        $arrayResponse = (array) $objectResponse;
        return $arrayResponse;
    }

    public function getInfo() {
        return $this->blog_info;
    }

    public function get_posts_info() {
        return $this->posts_info;
    }

    public function get_keyword() {
        return $this->keyword;
    }

    public function setInfo($arrayResponse) {
        $this->blog_info = $arrayResponse;
    }

    public function set_posts_info($arrayResponse) {
        $this->posts_info = $arrayResponse;
    }

}