<?php

echo "XML Parser Starting Up" . PHP_EOL . PHP_EOL;

define("FILE_PATH", __DIR__ . "/data");

if ( isset( $argv ) ) {
    parse_str(
        join( "&", array_slice( $argv, 1 )
    ), $_GET );
}

try {
    require_once('vendor/autoload.php');

    if (!isset($_GET["file"])) {
        throw new Exception("Please define a 'file.'");
    }

    $originalFile = FILE_PATH . "/{$_GET["file"]}.xml";
    $finalFile = "{$_GET["file"]}-final.xml";
    if (!file_exists($originalFile)) {
        throw new \Exception("file {$originalFile} not found.");
    }

    // Actually Parse.
    $originalXML = new BigSea\XML\FileReader($originalFile);
    $responseXML = new BigSea\XML\FileWriter(FILE_PATH . "/$finalFile");

    $responseXML->insertBase($originalXML);

    // Include list of "ignore"
    
    // Insert Items that are filtered by ignore list.

    $responseXML->save();

} catch (Exception $e)
{
    echo $e->getMessage() . PHP_EOL;
    exit;
}

echo "Parser Complete. See {$finalFile}" . PHP_EOL;