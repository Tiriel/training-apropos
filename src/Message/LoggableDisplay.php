<?php

namespace Message;

use Psr\Log\LoggerInterface;
use User\Member;

class LoggableDisplay extends Display
{
    public function __construct(
        private readonly Display $inner,
        private readonly LoggerInterface $logger,
    ) {}

    public function displayMemberDetails(Member $member, string $format): string
    {
        $this->logger->info(sprintf('Displaying details for member %s in format %s', $member->getLogin(), $format));

        return $this->inner->displayMemberDetails($member, $format);
    }

}
