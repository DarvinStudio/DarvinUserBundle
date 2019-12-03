<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Security;

use Darvin\UserBundle\Entity\BaseUser;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User checker
 */
class UserChecker implements UserCheckerInterface
{
    /**
     * {@inheritDoc}
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if ($user instanceof BaseUser && !$user->isEnabled()) {
            throw new DisabledException('User account is disabled.');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function checkPostAuth(UserInterface $user): void
    {

    }
}
