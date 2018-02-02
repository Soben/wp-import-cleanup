<?php

namespace BigSea\XML;

use Sabre\Xml\Service;

class FileWriter extends FileBase
{
    private $items = [];

    public function __construct($xmlFile) {
        parent::__construct($xmlFile);
    }

    public function insertBase (FileReader $original)
    {
        $this->base = $original->getBase();
    }

    public function addItem($item)
    {
        $this->items[] = $item;
    }

    public function save()
    {
        $this->base['value'] = array_merge($this->base['value'], $this->items);
        return file_put_contents($this->file, $this->xmlService->write($this->rssWrapper(), $this->base));
    }

    public function rssWrapper()
    {
        // Todo, somehow get this to be... array doesn't work.
        /*
            <rss version="2.0"
                xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/"
                xmlns:content="http://purl.org/rss/1.0/modules/content/"
                xmlns:wfw="http://wellformedweb.org/CommentAPI/"
                xmlns:dc="http://purl.org/dc/elements/1.1/"
                xmlns:wp="http://wordpress.org/export/1.2/"
            >
        */
        return '{}rss';
    }
}