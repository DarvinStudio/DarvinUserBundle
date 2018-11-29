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
     * @param array $roles Roles
     */
    public function __construct(array $roles)
    {
        $this->roles = [];

        foreach ($roles as $role => $attr) {
            $this->roles[$role] = new Role($role, $attr['moderated']);
        }
    }

    /**
     * @param string $role Role
     *
     * @return \Darvin\UserBundle\Configuration\Role
     * @throws \Darvin\UserBundle\Configuration\ConfigurationException
     */
    public function getRole($role)
    {
        if (!$this->hasRole($role)) {
            throw new ConfigurationException(sprintf('Role "%s" does not exist.', $role));
        }

        return $this->roles[$role];
    }

    /**
     * @param string $role Role
     *
     * @return bool
     */
    public function hasRole($role)
    {
        return isset($this->roles[$role]);
    }

    /**
     * @return \Darvin\UserBundle\Configuration\Role[]
     */
    public function getRoles()
    {
        return $this->roles;
    }
}
