<?php

rrequire_once('vendor/autoload.php');

$originalFile   = "/path/to/import.xml";
$finalFile      = "/path/to/import-processed.xml";

// Actually Parse.
$originalXML = new BigSea\XML\FileReader($originalFile);
$responseXML = new BigSea\XML\FileWriter($finalFile);

$processor = new BigSea\XML\Processor($originalXML, $responseXML);

$exclusionList = "/path/to/exclude.csv";
$processor->setExclusionListFromCSV($exclusionList);

$processor->processItems();

$responseXML->save();