<?php

namespace Message\Formatter;

class HtmlFormatter implements FormatterInterface
{

    public function format(string $message): string
    {
        return sprintf("<b>%s</b>", $message);
    }
}
