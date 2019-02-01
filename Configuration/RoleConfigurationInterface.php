<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Configuration;

/**
 * Role configuration
 */
interface RoleConfigurationInterface
{
    /**
     * @param string $role Role
     *
     * @return \Darvin\UserBundle\Configuration\Role
     * @throws \InvalidArgumentException
     */
    public function getRole(string $role): Role;

    /**
     * @param string $role Role
     *
     * @return bool
     */
    public function hasRole(string $role): bool;

    /**
     * @return \Darvin\UserBundle\Configuration\Role[]
     */
    public function getRoles(): array;
}
