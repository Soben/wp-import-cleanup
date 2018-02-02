<?php

namespace BigSea\XML;

class Processor
{
    private $input;
    private $output;

    private $exclusionList;
    private $inclusionList;

    public function __construct(FileReader $input, FileWriter $output)
    {
        $this->input = $input;
        $this->output = $output;

        $this->output->insertBase($this->input);
    }

    public function setExclusionListFromCSV($csvFile)
    {
        if (!file_exists($csvFile)) {
            throw new \Exception('Exclusion File not Found');
        }

        $handle = fopen($csvFile, 'r');

        if (!$handle) {
            throw new \Exception('Could not open exclusion file');
        }

        $headers = fgetcsv($handle);
        $this->exclusionList = [];
        foreach ($headers as $header) {
            $this->exclusionList[$header] = [];
        }

        while (!feof($handle)) {
            $line = fgetcsv($handle);

            for ($i = 0; $i < count($line); $i++) {
                $this->exclusionList[$headers[$i]][] = $line[$i];
            }
        }
    }

    public function setInclusionListFromCSV($csvFile)
    {
        if (!file_exists($csvFile)) {
            throw new \Exception('Inclusion File not Found');
        }

        $handle = fopen($csvFile, 'r');

        if (!$handle) {
            throw new \Exception('Could not open inclusion file');
        }

        $headers = fgetcsv($handle);
        $this->inclusionList = [];
        foreach ($headers as $header) {
            $this->inclusionList[$header] = [];
        }

        while (!feof($handle)) {
            $line = fgetcsv($handle);

            for ($i = 0; $i < count($line); $i++) {
                $this->inclusionList[$headers[$i]][] = $line[$i];
            }
        }
    }

    public function processItems()
    {
        $items = $this->input->getItems();

        echo count($items) . " - Total Items" . PHP_EOL;
        $count = 0;
        foreach ($items as $item) {
            if (!$this->includeItem($item) || $this->excludeItem($item)) {
                continue;
            }

            $count++;
            $this->output->addItem($item);
        }

        echo $count . " - Matches" . PHP_EOL;
    }

    private function includeItem($item)
    {
        if (!$this->inclusionList) {
            return true;
        }

        foreach ($this->inclusionList as $field => $values) {
            $itemField = $this->getFieldFromItem($item, $field);
            if (!$itemField) {
                continue;
            }

            if ($this->inInclusionList($itemField, $values)) {
                return true;
            }
        }

        return false;
    }

    private function excludeItem($item)
    {
        if (!$this->exclusionList) {
            return false;
        }

        foreach ($this->exclusionList as $field => $values) {
            $itemExclusionField = $this->getFieldFromItem($item, $field);
            if (!$itemExclusionField) {
                continue;
            }

            if ($this->inExclusionList($itemExclusionField, $values)) {
                return true;
            }
        }

        return false;
    }

    private function inExclusionList($field, $values)
    {
        foreach ($values as $value) {
            if(preg_match("/\/{$value}(\/*)$/", $field['value']) > 0) {
                return true;
            }
        }

        return false;
    }

    private function inInclusionList($field, $values)
    {
        foreach ($values as $value) {
            if(preg_match("/\/{$value}(\/*)$/", $field['value']) > 0) {
                return true;
            }
        }

        return false;
    }

    private function getFieldFromItem($item, $field)
    {
        foreach ($item['value'] as $key => $details) {
            if (strpos($details['name'], $field) === false) {
                continue;
            }

            return $details;
        }

        return false;
    }
}