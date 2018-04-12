<?php

namespace Dok123\BlogReader;
use Dok123\BlogReader\Exceptions\BlogNotFoundException;
use Dok123\BlogReader\Adapter\BlogSpot;
use Dok123\BlogReader\Adapter\WpApiV1;
use Dok123\BlogReader\Adapter\WpApiV2;

class BlogReader {

    public static function fromUrl($url, $api_key = null) {
        try {
            if($api_key) {
                $instance = new BlogSpot($url, $api_key);
            }
            $instance = new BlogSpot($url);
        } catch (\Exception $e) {
            try {
                $instance = new WpApiV1($url);
            } catch (\Exception $e) {
                try {
                    $instance = new WpApiV2($url);
                } catch (\Exception $e) {
                    throw new BlogNotFoundException('Not found !');
                }
            }
        }
        return $instance;
    }

}