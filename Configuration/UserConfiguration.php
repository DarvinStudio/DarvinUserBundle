<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Configuration;

use Darvin\ConfigBundle\Configuration\AbstractConfiguration;
use Darvin\ConfigBundle\Parameter\ParameterModel;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

/**
 * Configuration
 *
 * @method string[] getNotificationEmails()
 */
class UserConfiguration extends AbstractConfiguration
{
    /**
     * {@inheritdoc}
     */
    public function getModel(): array
    {
        return [
            new ParameterModel('notification_emails', ParameterModel::TYPE_ARRAY, [], [
                'form' => [
                    'options' => [
                        'entry_type'   => EmailType::class,
                        'allow_add'    => true,
                        'allow_delete' => true,
                    ],
                ],
            ]),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'darvin_user';
    }
}
