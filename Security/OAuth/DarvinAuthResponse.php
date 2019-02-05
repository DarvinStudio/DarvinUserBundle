<?php declare(strict_types=1);
/**
 * @author    Lev Semin <lev@darvin-studio.ru>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Security\OAuth;

use HWI\Bundle\OAuthBundle\OAuth\Response\PathUserResponse;

/**
 * Darvin auth response
 */
class DarvinAuthResponse extends PathUserResponse
{
    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return null !== $this->getError();
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->getValueForPath('error');
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->getValueForPath('gender');
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->getValueForPath('phone');
    }

    /**
     * @return string|null
     */
    public function getPosition(): ?string
    {
        return $this->getValueForPath('position');
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = $this->getValueForPath('roles');

        if (empty($roles)) {
            return [];
        }
        if (is_array($roles)) {
            return $roles;
        }

        return [$roles];
    }

    /**
     * @return string|null
     */
    public function getSkype(): ?string
    {
        return $this->getValueForPath('skype');
    }
}
