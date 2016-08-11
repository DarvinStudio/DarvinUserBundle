<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2016, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Configuration;

/**
 * Role
 */
class Role
{
    /**
     * @var string
     */
    private $role;

    /**
     * @var string
     */
    private $title;

    /**
     * @param string $role Role
     */
    public function __construct($role)
    {
        $this->role = $role;

        $this->title = 'role.'.preg_replace('/^role_/', '', strtolower($role));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->role;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}
