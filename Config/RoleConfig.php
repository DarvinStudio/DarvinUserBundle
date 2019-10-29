<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2016-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Config;

/**
 * Role configuration
 */
class RoleConfig implements RoleConfigInterface
{
    /**
     * @var \Darvin\UserBundle\Config\Role[]
     */
    private $roles;

    /**
     * @param array $config Role configuration
     */
    public function __construct(array $config)
    {
        /** @var \Darvin\UserBundle\Config\Role[] $roles */
        $roles = [];

        foreach ($config as $roleName => $attr) {
            $roles[$roleName] = new Role($roleName, $attr['moderated']);
        }
        foreach ($config as $roleName => $attr) {
            foreach ($roles as $grantableRole) {
                if (isset($attr['grantable_roles']['list'][$grantableRole->getName()])) {
                    if ($attr['grantable_roles']['list'][$grantableRole->getName()]) {
                        $roles[$roleName]->addGrantableRole($grantableRole);
                    }

                    continue;
                }
                if ($attr['grantable_roles']['default']) {
                    $roles[$roleName]->addGrantableRole($grantableRole);
                }
            }
        }

        $this->roles = $roles;
    }

    /**
     * {@inheritDoc}
     */
    public function getRole(string $name): Role
    {
        if (!$this->hasRole($name)) {
            throw new \InvalidArgumentException(sprintf('Role "%s" does not exist.', $name));
        }

        return $this->roles[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function hasRole(string $name): bool
    {
        return isset($this->roles[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
}
