<?php

namespace Dok123\BlogReader;
use Dok123\BlogReader\Exceptions\BlogNotFoundException;

class BlogReader {

    public static function fromUrl($url) {
        try {
            $instance = new Adapter\BlogReader($url);
        } catch (\Exception $e) {
            try {
                $instance = new Adapter\WpApiV1($url);
            } catch (\Exception $e) {
                try {
                    $instance = new Adapter\WpApiV2($url);
                } catch (\Exception $e) {
                    throw new BlogNotFoundException('Not found !');
                }
            }
        }
        return $instance;
    }

}