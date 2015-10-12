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
use Darvin\AdminBundle\Security\Permissions\ObjectPermissions;
use Darvin\ConfigBundle\Parameter\ParameterModel;
use Darvin\UserBundle\Entity\User;

/**
 * Security configuration
 */
class SecurityConfiguration extends AbstractSecurityConfiguration
{
    /**
     * {@inheritdoc}
     */
    public function getModel()
    {
        return array(
            new ParameterModel(
                'permissions',
                ParameterModel::TYPE_ARRAY,
                array(
                    'user' => new ObjectPermissions(User::USER_CLASS),
                ),
                array(
                    'form' => array(
                        'options' => array(
                            'type' => 'darvin_admin_security_object_permissions',
                        ),
                    ),
                )
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'darvin_user_security';
    }
}
