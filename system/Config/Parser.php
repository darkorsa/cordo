<?php

namespace System\Config;

use Exception;
use Noodlehaus\Exception\ParseException;

class Parser extends \Noodlehaus\Parser\Php
{
    public function parseFile($filename)
    {
        // Run the fileEval the string, if it throws an exception, rethrow it
        try {
            $data[basename($filename, ".php")] = require $filename;
        } catch (Exception $exception) {
            throw new ParseException(
                [
                    'message'   => 'PHP file threw an exception',
                    'exception' => $exception,
                ]
            );
        }
        // Complete parsing
        return $this->parse($data);
    }
}
