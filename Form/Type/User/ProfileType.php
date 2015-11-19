<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Form\Type\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * User profile form type
 */
class ProfileType extends AbstractType
{
    const NAME = 'darvin_user_user_profile';

    const PROFILE_TYPE_CLASS = __CLASS__;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', null, array(
                'label' => 'user.entity.email',
            ))
            ->add('fullName', null, array(
                'label' => 'user.entity.full_name',
            ))
            ->add('address', null, array(
                'label' => 'user.entity.address',
            ))
            ->add('phone', null, array(
                'label' => 'user.entity.phone',
            ))
            ->add('plainPassword', 'Symfony\Component\Form\Extension\Core\Type\PasswordType', array(
                'label'    => 'user.entity.plain_password',
                'required' => false,
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
                'intention'         => md5(__FILE__.$this->getBlockPrefix()),
                'validation_groups' => array(
                    'Default',
                    'Profile',
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
