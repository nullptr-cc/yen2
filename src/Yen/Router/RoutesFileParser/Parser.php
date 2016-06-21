<?php

namespace Yen\Router\RoutesFileParser;

use Yen\Router\Exception\RouteSyntaxError;

class Parser
{
    private $file_path;

    public function __construct($file_path)
    {
        if (!is_readable($file_path)) {
            throw new \RuntimeException('Cannot open stream: ' . $file_path);
        };

        $this->file_path = $file_path;
    }

    public function parse()
    {
        $lines = file($this->file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $index => $line) {
            yield $this->processLine($index, $line);
        };
    }

    private function processLine($index, $line)
    {
        $lr = array_filter(array_map('trim', explode('=>', $line)));
        if (count($lr) != 2) {
            throw new RouteSyntaxError($index, $line);
        };

        list($location, $result) = $lr;

        if ($location[0] == '@') {
            $nl = explode(' ', $location, 2);
            $name = substr($nl[0], 1);
            $location = trim($nl[1]);
        } else {
            $name = sprintf('route%02d', $index);
        };

        return new LineParseResult($name, $location, $result);
    }
}
