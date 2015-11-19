<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Configuration;

use Darvin\ConfigBundle\Configuration\AbstractConfiguration;
use Darvin\ConfigBundle\Parameter\ParameterModel;

/**
 * Configuration
 *
 * @method array getNotificationEmails()
 */
class Configuration extends AbstractConfiguration
{
    /**
     * {@inheritdoc}
     */
    public function getModel()
    {
        return array(
            new ParameterModel('notification_emails', ParameterModel::TYPE_ARRAY, array(), array(
                'form' => array(
                    'options' => array(
                        'entry_type'   => 'Symfony\Component\Form\Extension\Core\Type\EmailType',
                        'allow_add'    => true,
                        'allow_delete' => true,
                    ),
                ),
            )),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'darvin_user';
    }
}
