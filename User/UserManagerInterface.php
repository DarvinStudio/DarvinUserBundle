<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\User;

use Darvin\UserBundle\Entity\User;

/**
 * User manager
 */
interface UserManagerInterface
{
    /**
     * @return \Darvin\UserBundle\Entity\User
     */
    public function getCurrentUser();

    /**
     * @param \Darvin\UserBundle\Entity\User $user User
     *
     * @return bool Was password updated
     */
    public function updatePassword(User $user);
}
