<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Event;

use Darvin\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * User event
 */
class UserEvent extends Event
{
    /**
     * @var \Darvin\UserBundle\Entity\User
     */
    private $user;

    /**
     * @param \Darvin\UserBundle\Entity\User $user User
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return \Darvin\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
