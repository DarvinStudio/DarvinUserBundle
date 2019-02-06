<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\PasswordResetToken;

use Darvin\UserBundle\Entity\BaseUser;
use Darvin\UserBundle\Entity\PasswordResetToken;

/**
 * Password reset token factory
 */
interface PasswordResetTokenFactoryInterface
{
    /**
     * @param \Darvin\UserBundle\Entity\BaseUser $user User
     *
     * @return \Darvin\UserBundle\Entity\PasswordResetToken
     * @throws \InvalidArgumentException
     */
    public function createPasswordResetToken(BaseUser $user): PasswordResetToken;
}
