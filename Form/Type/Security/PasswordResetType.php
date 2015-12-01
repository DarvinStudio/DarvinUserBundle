<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Form\Type\Security;

use Darvin\Utils\Form\Type\AntiSpamType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Password reset form type
 */
class PasswordResetType extends AbstractType
{
    const NAME = 'darvin_user_security_password_reset';

    const PASSWORD_RESET_TYPE_CLASS = __CLASS__;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', 'Symfony\Component\Form\Extension\Core\Type\PasswordType', array(
                'label' => 'security.action.reset_password.plain_password',
            ))
            ->add('title', AntiSpamType::ANTI_SPAM_TYPE_CLASS);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
                'csrf_token_id'     => md5(__FILE__.$this->getBlockPrefix()),
                'validation_groups' => array(
                    'PasswordReset',
                ),
            ))
            ->remove('data_class')
            ->setRequired('data_class')
            ->setAllowedTypes('data_class', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return self::NAME;
    }
}
