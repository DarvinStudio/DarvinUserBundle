<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Form\Type\PasswordResetToken;

use Darvin\UserBundle\Validator\Constraints\UserExistsAndActive;
use Darvin\Utils\Form\Type\AntiSpamType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Password reset token request form type
 */
class RequestType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user_email', 'Symfony\Component\Form\Extension\Core\Type\EmailType', [
                'label'       => 'password_reset_token.action.request.user_email',
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                    new UserExistsAndActive(),
                ],
            ])
            ->add('title', AntiSpamType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        foreach ($view->children as $field) {
            $field->vars['attr']['autocomplete'] = 'off';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_token_id' => md5(__FILE__.$this->getBlockPrefix()),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'darvin_user_password_reset_token_request';
    }
}
