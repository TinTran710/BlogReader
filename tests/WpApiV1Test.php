<?php

use Dok123\BlogReader\BlogReader;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class WpApiV1Test extends TestCase {

    public function testFromUrl() {
        $blog = BlogReader::fromUrl('http://en.blog.wordpress.com');
        $this->assertEquals('3584907', $blog->getInfo()['ID']);
    }

    public function testPostsFields() {
        $blog = BlogReader::fromUrl('http://en.blog.wordpress.com');
        $fields = ['id', 'title', 'content'];
        $posts_info = $blog->posts($fields);
        $temp = $posts_info['posts'];
        $object = $temp[0];
        $totalFields = count((array)$object);
        $this->assertEquals(3, $totalFields);
    }

    public function testPostsPerPage() {
        $blog = BlogReader::fromUrl('http://en.blog.wordpress.com');
        $per_page = 16;
        $posts_info = $blog->posts(null, null, $per_page);
        $temp = $posts_info['posts'];
        $this->assertEquals($per_page, count($temp));
    }

    public function testNext() {
        $blog = BlogReader::fromUrl('http://en.blog.wordpress.com');
        $blog->posts(null, null, 9);
        $practical = $blog->next();
        $this->assertEquals(true, $practical);
    }

    public function testCurrentPage() {
        $blog = BlogReader::fromUrl('http://en.blog.wordpress.com');
        $blog->posts(null, 6, 1);
        $blog->next();
        $this->assertEquals(7, $blog->current_page());
    }

    public function testSetKeyword() {
        $blog = BlogReader::fromUrl('http://en.blog.wordpress.com');
        $posts_info = $blog->setKeyword('Conversations');
        $this->assertEquals(33, $posts_info['found']);
    }

    public function testResetKeyword() {
        $blog = BlogReader::fromUrl('http://en.blog.wordpress.com');
        $blog->setKeyword('technology');
        $blog->resetKeyword();
        $this->assertEquals(null, $blog->get_keyword());
    }

    public function testLabels() {
        $blog = BlogReader::fromUrl('http://en.blog.wordpress.com');
        $blog->posts();
        $labels = $blog->labels(15);
        $this->assertEquals(15, count($labels));
    }


}