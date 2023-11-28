<?php

use Message\Display;
use Message\Formatter\HtmlFormatter;
use Message\Formatter\PlaintextFormatter;
use User\Admin;
use User\Member;

require_once __DIR__.'/../vendor/autoload.php';

$member = new Admin('Benjamin', 'admin1234', 36);

$display = new Display([
    'plaintext' => new PlaintextFormatter(),
    'html' => new HtmlFormatter()
]);

if ('dev' === getenv('APP_ENV')) {
    $display = new \Message\LoggableDisplay($display, new \Psr\Log\NullLogger());
}

if (true === getenv(('SEND_MAIL'))) {
    $display = new \Message\SendMailDisplay($display);
}

function run(Display $display, Member $member): void
{
    try {
        echo $display->displayMemberDetails($member, 'html');
    } catch (\InvalidArgumentException) {
        echo 'Something went wrong.';
    }
}

run($display, $member);
