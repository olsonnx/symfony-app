<?php

namespace App\Security\Voter;

use App\Entity\Tag;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class TagVoter.
 */
class TagVoter extends Voter
{
    private const EDIT = 'EDIT';
    private const VIEW = 'VIEW';
    private const DELETE = 'DELETE';

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof Tag;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if (!$subject instanceof Tag) {
            return false;
        }

        return match ($attribute) {
            self::EDIT => $this->canEdit($subject, $user),
            self::VIEW => $this->canView($subject, $user),
            self::DELETE => $this->canDelete($subject, $user),
            default => false,
        };
    }

    /**
     * @param Tag $tag
     * @param UserInterface $user
     * @return bool
     */
    private function canEdit(Tag $tag, UserInterface $user): bool
    {
        return $user->getRoles() === ['ROLE_ADMIN'];
    }

    /**
     * @param Tag $tag
     * @param UserInterface $user
     * @return bool
     */
    private function canView(Tag $tag, UserInterface $user): bool
    {
        return true; // ZakĹ‚adamy, ĹĽe tagi mogÄ… byÄ‡ oglÄ…dane przez wszystkich
    }

    /**
     * @param Tag $tag
     * @param UserInterface $user
     * @return bool
     */
    private function canDelete(Tag $tag, UserInterface $user): bool
    {
        return $user->getRoles() === ['ROLE_ADMIN'];
    }
}
