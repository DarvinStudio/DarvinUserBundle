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
 * Registration form type
 */
class RegistrationType extends AbstractType
{
    const REGISTRATION_TYPE_CLASS = __CLASS__;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', null, [
                'label' => 'user.entity.email',
            ])
            ->add('fullName', null, [
                'label' => 'user.entity.full_name',
            ])
            ->add('address', null, [
                'label' => 'user.entity.address',
            ])
            ->add('phone', null, [
                'label' => 'user.entity.phone',
            ])
            ->add('plainPassword', 'Symfony\Component\Form\Extension\Core\Type\PasswordType', [
                'label' => 'user.entity.plain_password',
            ])
            ->add('title', AntiSpamType::ANTI_SPAM_TYPE_CLASS);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'csrf_token_id'     => md5(__FILE__.$this->getBlockPrefix()),
                'validation_groups' => [
                    'Default',
                    'Register',
                ],
            ])
            ->remove('data_class')
            ->setRequired('data_class')
            ->setAllowedTypes('data_class', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'darvin_user_security_registration';
    }
}
