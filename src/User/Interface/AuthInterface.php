<?php

namespace User\Interface;
interface AuthInterface
{
    public function auth(string $login, string $password): bool;
}
