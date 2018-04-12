<?php

namespace Dok123\BlogReader\Adapter;

interface ReaderInterface {

    public function getInfo();
    public function posts(array $fields = null, $page = null, $per_page = 20);
    public function next();
    public function current_page();
    public function setKeyword($keyword);
    public function resetKeyword();
    public function labels($limit = 100);

}