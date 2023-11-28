<?php

namespace Message\Formatter;

interface FormatterInterface
{
    public function format(string $message): string;
}
