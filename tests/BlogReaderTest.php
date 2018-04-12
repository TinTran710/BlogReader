<?php

use Dok123\BlogReader\BlogReader;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class BlogReaderTest extends TestCase {

    public function testFromUrl() {
        $blog = BlogReader::fromUrl('http://tintran710.blogspot.com/');
        $this->assertEquals('3225494220594274525', $blog->getInfo()['id']);
    }

    public function testPostsFields() {
        $blog = BlogReader::fromUrl('http://tintran710.blogspot.com/');
        $fields = ['id', 'title', 'content'];
        $posts_info = $blog->posts($fields);
        $temp = $posts_info['items'];
        $object = $temp[0];
        $totalFields = count((array)$object);
        $this->assertEquals(3, $totalFields);
    }

    public function testPostsPerPage() {
        $blog = BlogReader::fromUrl('http://blogger.googleblog.com/');
        $per_page = 8;
        $posts_info = $blog->posts(null, null, $per_page);
        $temp = $posts_info['items'];
        $this->assertEquals($per_page, count($temp));
    }

    public function testNext() {
        $blog = BlogReader::fromUrl('http://blogger.googleblog.com/');
        $blog->posts(null, null, 13);
        $practical = $blog->next();
        $this->assertEquals(true, $practical);
    }

    public function testCurrentPage() {
        $blog = BlogReader::fromUrl('http://blogger.googleblog.com/');
        $blog->posts();
        $blog->next();
        $this->assertEquals('CgkIFBiAuMyN3ycQ0b2SAQ', $blog->get_page_token());
    }

    public function testSetKeyword() {
        $blog = BlogReader::fromUrl('http://blogger.googleblog.com/');
        $posts_info = $blog->setKeyword('FTP');
        $temp = $posts_info['items'];
        $this->assertEquals(10, count($temp));
    }

    public function testResetKeyword() {
        $blog = BlogReader::fromUrl('http://blogger.googleblog.com/');
        $blog->setKeyword('technology');
        $blog->resetKeyword();
        $this->assertEquals(null, $blog->get_keyword());
    }

    public function testLabels() {
        $blog = BlogReader::fromUrl('http://blogger.googleblog.com/');
        $blog->posts();
        $labels = $blog->labels(100);
        $this->assertEquals(7, count($labels));
    }


}