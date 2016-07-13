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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Login form type
 */
class LoginType extends AbstractType
{
    const LOGIN_TYPE_CLASS = __CLASS__;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', 'Symfony\Component\Form\Extension\Core\Type\EmailType', [
                'label' => 'security.action.login.email',
            ]
            )
            ->add('_password', 'Symfony\Component\Form\Extension\Core\Type\PasswordType', [
                'label' => 'security.action.login.password',
            ]
            )
            ->add('_remember_me', 'Symfony\Component\Form\Extension\Core\Type\CheckboxType', [
                'label'    => 'security.action.login.remember_me',
                'required' => false,
            ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
            'csrf_field_name' => '_csrf_token',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return null;
    }
}
