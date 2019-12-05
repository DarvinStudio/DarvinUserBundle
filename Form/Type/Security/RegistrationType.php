<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Form\Type\Security;

use Darvin\Utils\Form\Type\AntiSpamType;
use Darvin\Utils\Strings\StringsUtil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Registration form type
 */
class RegistrationType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('plainPassword', PasswordType::class)
            ->add('title', AntiSpamType::class);
    }

    /**
     * {@inheritDoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        foreach ($view->children as $name => $field) {
            if (null === $field->vars['label']) {
                $field->vars['label'] = sprintf('user.entity.%s', StringsUtil::toUnderscore($name));
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
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
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return 'darvin_user_security_registration';
    }
}
