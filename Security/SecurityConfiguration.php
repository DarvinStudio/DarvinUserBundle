<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Security;

use Darvin\AdminBundle\Security\Configuration\AbstractSecurityConfiguration;
use Darvin\UserBundle\Entity\BaseUser;

/**
 * Security configuration
 */
class SecurityConfiguration extends AbstractSecurityConfiguration
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'darvin_user_security';
    }

    /**
     * {@inheritdoc}
     */
    protected function getSecurableObjectClasses()
    {
        return array(
            'user' => BaseUser::BASE_USER_CLASS,
        );
    }
}
