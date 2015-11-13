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

/**
 * User factory
 */
class UserFactory
{
    /**
     * @var string
     */
    private $userClass;

    /**
     * @param string $userClass User entity class
     */
    public function __construct($userClass)
    {
        $this->userClass = $userClass;
    }

    /**
     * @return \Darvin\UserBundle\Entity\BaseUser
     */
    public function createUser()
    {
        $class = $this->userClass;

        return new $class();
    }
}
