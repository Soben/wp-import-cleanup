<?php

namespace BigSea\XML;

use Sabre\Xml\Service;

class FileWriter extends FileBase
{
    public function __construct($xmlFile) {
        parent::__construct($xmlFile);
    }

    public function insertBase (FileReader $original)
    {
        $this->xmlNodes = $original->getCleanSlate();
    }

    public function insertItem()
    {
        throw new \Exception('Missing Handler');
    }

    public function save()
    {
        return file_put_contents($this->file, $this->xmlService->write('{}channel', $this->xmlNodes));
    }
}