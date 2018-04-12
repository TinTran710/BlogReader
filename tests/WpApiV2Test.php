<?php

use Dok123\BlogReader\BlogReader;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class WpApiV2Test extends TestCase {

    public function testFromUrl() {
        $blog = BlogReader::fromUrl('http://demo.wp-api.org/');
        $this->assertEquals('WP REST API Demo', $blog->getInfo()['name']);
    }

    public function testPostsFields() {
        $blog = BlogReader::fromUrl('http://demo.wp-api.org/');
        $fields = ['id', 'title', 'content'];
        $posts_info = $blog->posts($fields);
        $this->assertEquals(3, sizeof($posts_info[0]));
    }

    public function testPostsPerPage() {
        $blog = BlogReader::fromUrl('http://demo.wp-api.org/');
        $per_page = 7;
        $posts_info = $blog->posts(null, null, $per_page);
        $this->assertEquals($per_page, sizeof($posts_info));
    }

    public function testNext() {
        $blog = BlogReader::fromUrl('http://demo.wp-api.org/');
        $blog->posts(null, null, 44);
        $practical = $blog->next();
        $this->assertEquals(true, $practical);
    }

    public function testCurrentPage() {
        $blog = BlogReader::fromUrl('http://demo.wp-api.org/');
        $blog->posts(null, 7, 1);
        $blog->next();
        $this->assertEquals(8, $blog->current_page());
    }

    public function testSetKeyword() {
        $blog = BlogReader::fromUrl('http://demo.wp-api.org/');
        $posts_info = $blog->setKeyword('Welcome');
        $this->assertEquals(1, sizeof($posts_info));
    }

    public function testResetKeyword() {
        $blog = BlogReader::fromUrl('http://demo.wp-api.org/');
        $blog->setKeyword('technology');
        $blog->resetKeyword();
        $this->assertEquals(null, $blog->get_keyword());
    }

    public function testLabels() {
        $blog = BlogReader::fromUrl('http://demo.wp-api.org/');
        $blog->posts();
        $labels = $blog->labels(10);
        $this->assertEquals(6, count($labels));
    }


}