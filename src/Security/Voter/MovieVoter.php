<?php

namespace App\Security\Voter;

use App\Entity\Movie;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MovieVoter extends Voter
{
    public const RATED = 'movie.rated';
    public const EDIT = 'movie.edit';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof Movie
            && \in_array($attribute, [self::RATED, self::EDIT]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::RATED => $this->checkRated($subject, $user),
            self::EDIT => $this->checkEdit($subject, $user),
            default => false,
        };
    }

    protected function checkRated(Movie $movie, User $user): bool
    {
        if ('G' === $movie->getRated()) {
            return true;
        }

        $age = $user->getAge();

        return match ($movie->getRated()) {
            'PG', 'PG-13' => $age && $age >= 13,
            'R', 'NC-17' => $age && $age >= 17
        };
    }

    protected function checkEdit(Movie $movie, User $user): bool
    {
        return $this->checkRated($movie, $user) && $user === $movie->getCreatedBy();
    }
}
