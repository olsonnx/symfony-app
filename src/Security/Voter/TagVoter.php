<?php
/**
 * Notice management app.
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

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
     * Determine if the voter supports the given attribute and subject.
     *
     * @param string $attribute The action being checked (e.g., EDIT, VIEW, DELETE)
     * @param mixed  $subject   The object being secured (should be an instance of Tag)
     *
     * @return bool True if the voter supports the attribute and subject
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof Tag;
    }

    /**
     * Perform a vote on the given attribute and subject.
     *
     * @param string         $attribute The action being checked (e.g., EDIT, VIEW, DELETE)
     * @param mixed          $subject   The object being secured (should be an instance of Tag)
     * @param TokenInterface $token     The authentication token
     *
     * @return bool True if the user is authorized to perform the action
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
     * Check if the user can edit the tag.
     *
     * @param Tag           $tag  The tag being edited
     * @param UserInterface $user The authenticated user
     *
     * @return bool True if the user can edit the tag
     */
    private function canEdit(Tag $tag, UserInterface $user): bool
    {
        return $user->getRoles() === ['ROLE_ADMIN'];
    }

    /**
     * Check if the user can view the tag.
     *
     * @param Tag           $tag  The tag being viewed
     * @param UserInterface $user The authenticated user
     *
     * @return bool True if the user can view the tag
     */
    private function canView(Tag $tag, UserInterface $user): bool
    {
        return true; // Zakładamy, że tagi mogą być oglądane przez wszystkich
    }

    /**
     * Check if the user can delete the tag.
     *
     * @param Tag           $tag  The tag being deleted
     * @param UserInterface $user The authenticated user
     *
     * @return bool True if the user can delete the tag
     */
    private function canDelete(Tag $tag, UserInterface $user): bool
    {
        return $user->getRoles() === ['ROLE_ADMIN'];
    }
}
