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
 * Role configuration
 */
class RoleConfiguration
{
    /**
     * @var \Darvin\UserBundle\Configuration\Role[]
     */
    private $roles;

    /**
     * @param string[] $roles Roles
     */
    public function __construct(array $roles)
    {
        $this->roles = [];

        foreach ($roles as $role) {
            $this->roles[$role] = new Role($role);
        }
    }

    /**
     * @return \Darvin\UserBundle\Configuration\Role[]
     */
    public function getRoles()
    {
        return $this->roles;
    }
}
