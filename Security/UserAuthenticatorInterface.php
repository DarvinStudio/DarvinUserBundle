<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Security;

use Darvin\UserBundle\Entity\BaseUser;

/**
 * User authenticator
 */
interface UserAuthenticatorInterface
{
    /**
     * @param \Darvin\UserBundle\Entity\BaseUser $user         User
     * @param string                             $firewallName Firewall name
     */
    public function authenticateUser(BaseUser $user, string $firewallName): void;
}
