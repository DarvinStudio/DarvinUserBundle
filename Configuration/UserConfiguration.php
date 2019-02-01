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
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

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
    public function getModel(): iterable
    {
        yield new ParameterModel('notification_emails', ParameterModel::TYPE_ARRAY, [], [
            'form' => [
                'options' => [
                    'allow_add'     => true,
                    'allow_delete'  => true,
                    'delete_empty'  => true,
                    'entry_type'    => EmailType::class,
                    'entry_options' => [
                        'constraints' => [
                            new NotBlank(),
                            new Email(),
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'darvin_user';
    }
}
