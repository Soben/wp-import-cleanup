<?php

echo "XML Parser Starting Up" . PHP_EOL . PHP_EOL;

try {
    require_once('vendor/autoload.php');

    $originalFile   = __DIR__ . "/data/import.xml";
    $finalFile      = __DIR__ . "/data/import-processed.xml";

    // Actually Parse.
    $originalXML = new BigSea\XML\FileReader($originalFile);
    $responseXML = new BigSea\XML\FileWriter($finalFile);

    // Include list of "ignore"
    $include_posts = __DIR__ . "/data/include.csv";

    $processor = new BigSea\XML\Processor($originalXML, $responseXML);
    $processor->setInclusionListFromCSV($include_posts);
    $processor->processItems();
    
    $responseXML->save();

} catch (Exception $e)
{
    echo $e->getMessage() . PHP_EOL;
    exit;
}

echo "Parser Complete." . PHP_EOL;