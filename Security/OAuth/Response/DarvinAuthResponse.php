<?php
/**
 * @author    Lev Semin <lev@darvin-studio.ru>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Security\OAuth\Response;

use HWI\Bundle\OAuthBundle\OAuth\Response\PathUserResponse;

/**
 * Darvin auth response
 */
class DarvinAuthResponse extends PathUserResponse
{
    const DARVIN_AUTH_RESPONSE_CLASS = __CLASS__;

    /**
     * @return null|array
     */
    public function getRoles()
    {
        return $this->getValueForPath('roles');
    }

    /**
     * @return null|string
     */
    public function getPhone()
    {
        return $this->getValueForPath('phone');
    }

    /**
     * @return null|string
     */
    public function getError()
    {
        return $this->getValueForPath('error');
    }
}
