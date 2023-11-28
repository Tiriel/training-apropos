<?php

namespace Message;

use Message\Formatter\FormatterInterface;
use User\Member;

class Display
{
    public function __construct(
        /** @var FormatterInterface[] $formatters */
        private readonly iterable $formatters
    ) {}

    public function displayMemberDetails(Member $member, string $format): string
    {
        if (!\class_exists(sprintf("Message\\Formatter\\%sFormatter", ucfirst($format)))) {
            throw new \InvalidArgumentException();
        }

        $message = sprintf("%s (age: %d)", $member->getLogin(), $member->getAge());

        return $this->formatters[$format]->format($message);
    }
}
