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
 * Role
 */
class Role
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $moderated;

    /**
     * @var string[]
     */
    private $grantableRoles;

    /**
     * @var string
     */
    private $title;

    /**
     * @param string   $name           Name
     * @param bool     $moderated      Is moderated
     * @param string[] $grantableRoles Grantable roles
     */
    public function __construct(string $name, bool $moderated, array $grantableRoles)
    {
        $this->name = $name;
        $this->moderated = $moderated;
        $this->grantableRoles = $grantableRoles;

        $this->title = sprintf('role.%s', preg_replace('/^role_/', '', strtolower($name)));
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isModerated(): bool
    {
        return $this->moderated;
    }

    /**
     * @return string[]
     */
    public function getGrantableRoles(): array
    {
        return $this->grantableRoles;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}
