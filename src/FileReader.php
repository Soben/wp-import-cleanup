<?php

namespace BigSea\XML;

class FileReader extends FileBase
{
    public function __construct($xmlFile) {
        if (!file_exists($xmlFile)) {
            throw new \Exception('Missing XML Export File');
        }

        parent::__construct($xmlFile);

        $xmlNodes = $this->xmlService->parse(file_get_contents($xmlFile));

        $this->xmlNodes = $xmlNodes;
    }

    public function getBase()
    {
        $response = $this->xmlNodes[0];
        $nonItems = [];

        foreach ($this->xmlNodes[0]['value'] as $child) {
            if (strpos($child['name'], '}item') === FALSE) {
                $nonItems[] = $child;
            }
        }

        $response['value'] = $nonItems;

        return $response;
    }

    public function getItems()
    {
        $items = [];

        foreach ($this->xmlNodes[0]['value'] as $child) {
            if (strpos($child['name'], '}item') !== FALSE) {
                $items[] = $child;
            }
        }

        return $items;
    }
}