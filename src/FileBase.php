<?php

namespace BigSea\XML;

use Sabre\Xml\Service;

class FileBase
{
    protected $file;
    protected $xmlService;
    protected $xmlNodes = [];

    public function __construct($xmlFile) {
        $this->file = $xmlFile;
        $this->xmlService = new Service();
    }
}