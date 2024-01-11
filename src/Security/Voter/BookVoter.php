<?php

namespace App\Security\Voter;

use App\Entity\Book;
use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

#[AutoconfigureTag(name: 'security.voter', attributes: ['priority' => 300])]
class BookVoter extends Voter
{
    public const EDIT = 'book.edit';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof Book
            && \in_array($attribute, [self::EDIT]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (\in_array('ROLE_ADMIN', $token->getRoleNames())) {
            return true;
        }

        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::EDIT => $this->checkEdit($subject, $user),
            default => false,
        };
    }

    protected function checkEdit(Book $book, User $user): bool
    {
        return $user === $book->getCreatedBy();
    }
}
