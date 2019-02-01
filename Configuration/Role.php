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
 * Role
 */
class Role
{
    /**
     * @var string
     */
    private $role;

    /**
     * @var bool
     */
    private $moderated;

    /**
     * @var string
     */
    private $title;

    /**
     * @param string $role      Role
     * @param bool   $moderated Is moderated
     */
    public function __construct(string $role, bool $moderated)
    {
        $this->role = $role;
        $this->moderated = $moderated;

        $this->title = sprintf('role.%s', preg_replace('/^role_/', '', strtolower($role)));
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->role;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @return bool
     */
    public function isModerated(): bool
    {
        return $this->moderated;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}
