<?php
/**
 * Notice management app.
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\Security\Voter;

use App\Entity\Notice;
use App\Entity\NoticeStatus;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class NoticeVoter.
 */
class NoticeVoter extends Voter
{
    private const EDIT = 'EDIT';
    private const VIEW = 'VIEW';
    private const DELETE = 'DELETE';

    /**
     * Determine if the voter supports the given attribute and subject.
     *
     * @param string $attribute The action being checked (e.g., EDIT, VIEW, DELETE)
     * @param mixed  $subject   The object being secured (should be an instance of Notice)
     *
     * @return bool True if the voter supports the attribute and subject
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE]) && $subject instanceof Notice;
    }

    /**
     * Perform a vote on the given attribute and subject.
     *
     * @param string         $attribute The action being checked (e.g., EDIT, VIEW, DELETE)
     * @param mixed          $subject   The object being secured (should be an instance of Notice)
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

        if (!$subject instanceof Notice) {
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
     * Check if the user can edit the notice.
     *
     * @param Notice        $notice The notice being edited
     * @param UserInterface $user   The authenticated user
     *
     * @return bool True if the user can edit the notice
     */
    private function canEdit(Notice $notice, UserInterface $user): bool
    {
        return $notice->getAuthor() === $user || in_array('ROLE_ADMIN', $user->getRoles());
    }

    /**
     * Check if the user can view the notice.
     *
     * @param Notice        $notice The notice being viewed
     * @param UserInterface $user   The authenticated user
     *
     * @return bool True if the user can view the notice
     */
    private function canView(Notice $notice, UserInterface $user): bool
    {
        // Admins can view all notices
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        // Everyone can view active notices
        return NoticeStatus::STATUS_ACTIVE === $notice->getStatus();
    }

    /**
     * Check if the user can delete the notice.
     *
     * @param Notice        $notice The notice being deleted
     * @param UserInterface $user   The authenticated user
     *
     * @return bool True if the user can delete the notice
     */
    private function canDelete(Notice $notice, UserInterface $user): bool
    {
        return $notice->getAuthor() === $user || in_array('ROLE_ADMIN', $user->getRoles());
    }
}
