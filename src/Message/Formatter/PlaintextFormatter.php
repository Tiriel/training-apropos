<?php

namespace Message\Formatter;

class PlaintextFormatter implements FormatterInterface
{

    public function format(string $message): string
    {
        return $message;
    }
}
