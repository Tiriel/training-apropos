<?php

namespace User;

use User\Interface\AuthInterface;

class Member implements AuthInterface
{
    protected static int $counter = 0;

    public function __construct(
        protected string $login,
        protected string $password,
        protected int $age,
    ) {
        static::$counter++;
    }

    public function __destruct()
    {
        static::$counter--;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function auth(string $login, #[SensitiveParameter] string $password): bool
    {
        if ($login !== $this->login || $password !== $this->password) {
            throw new \InvalidArgumentException();
        }

        return true;
    }

    public static function count(): int
    {
        return static::$counter;
    }
}
