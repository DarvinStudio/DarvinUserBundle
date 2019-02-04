<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\User;

use Darvin\UserBundle\Entity\BaseUser;

/**
 * User manager
 */
interface UserManagerInterface
{
    /**
     * @return \Darvin\UserBundle\Entity\BaseUser|null
     */
    public function getCurrentUser(): ?BaseUser;

    /**
     * @param \Darvin\UserBundle\Entity\BaseUser $user User
     *
     * @return bool Was password updated
     */
    public function updatePassword(BaseUser $user): bool;
}
