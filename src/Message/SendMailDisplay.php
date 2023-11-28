<?php

namespace Message;

use User\Member;

class SendMailDisplay extends Display
{
    public function __construct(
        private readonly Display $inner,
    ) {}

    public function displayMemberDetails(Member $member, string $format): string
    {
        //sending mail from here

        return $this->inner->displayMemberDetails($member, $format);
    }

}
