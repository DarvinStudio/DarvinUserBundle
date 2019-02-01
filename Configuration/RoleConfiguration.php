<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2016-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Configuration;

/**
 * Role configuration
 */
class RoleConfiguration implements RoleConfigurationInterface
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
     * {@inheritDoc}
     */
    public function getRole(string $role): Role
    {
        if (!$this->hasRole($role)) {
            throw new \InvalidArgumentException(sprintf('Role "%s" does not exist.', $role));
        }

        return $this->roles[$role];
    }

    /**
     * {@inheritDoc}
     */
    public function hasRole(string $role): bool
    {
        return isset($this->roles[$role]);
    }

    /**
     * {@inheritDoc}
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
}
