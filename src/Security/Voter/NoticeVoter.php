<?php
/**
 * Notice voter.
 */

namespace App\Security\Voter;

use App\Entity\Notice;
use App\Entity\Enum\NoticeStatus;
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

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE]) && $subject instanceof Notice;
    }

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

    private function canEdit(Notice $notice, UserInterface $user): bool
    {
        return $notice->getAuthor() === $user || in_array('ROLE_ADMIN', $user->getRoles());
    }

    private function canView(Notice $notice, UserInterface $user): bool
    {
        // Admins can view all notices
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        // Everyone can view active notices
        return $notice->getStatus() === NoticeStatus::STATUS_ACTIVE;
    }

    private function canDelete(Notice $notice, UserInterface $user): bool
    {
        return $notice->getAuthor() === $user || in_array('ROLE_ADMIN', $user->getRoles());
    }
}
