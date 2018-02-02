<?php

namespace BigSea\XML;

class FileReader extends FileBase
{
    public function __construct($xmlFile) {
        parent::__construct($xmlFile);

        $xmlNodes = $this->xmlService->parse(file_get_contents($xmlFile));

        $this->xmlNodes = $xmlNodes;
    }

    public function getCleanSlate()
    {
        $response = $this->xmlNodes[0];
        $channelChildren = [];

        foreach ($this->xmlNodes[0]['value'] as $child) {
            if (strpos($child['name'], '}item') === FALSE) {
                $channelChildren[] = $child;
            }
        }

        $response['value'] = $channelChildren;

        return $response;
    }
}