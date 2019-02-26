<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Config;

use Darvin\ConfigBundle\Configuration\AbstractConfiguration;
use Darvin\ConfigBundle\Parameter\ParameterModel;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * User configuration
 */
class UserConfig extends AbstractConfiguration implements UserConfigInterface
{
    /**
     * @var bool
     */
    private $mailerEnabled;

    /**
     * @param bool $mailerEnabled Is mailer enabled
     */
    public function __construct(bool $mailerEnabled)
    {
        $this->mailerEnabled = $mailerEnabled;
    }

    /**
     * {@inheritdoc}
     */
    public function getModel(): iterable
    {
        if ($this->mailerEnabled) {
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
    }

    /**
     * {@inheritDoc}
     */
    public function getNotificationEmails(): array
    {
        return $this->__call(__FUNCTION__);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'darvin_user';
    }
}
