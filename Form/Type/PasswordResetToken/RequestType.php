<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Form\Type\PasswordResetToken;

use Darvin\UserBundle\Validator\Constraints\UserExistsAndActive;
use Darvin\Utils\Form\Type\AntiSpamType;
use Darvin\Utils\Strings\StringsUtil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Password reset token request form type
 */
class RequestType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user_email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                    new UserExistsAndActive(),
                ],
            ])
            ->add('title', AntiSpamType::class);
    }

    /**
     * {@inheritDoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        foreach ($view->children as $name => $field) {
            $field->vars['attr']['autocomplete'] = 'off';

            if (null === $field->vars['label']) {
                $field->vars['label'] = sprintf('password_reset_token.request.%s', StringsUtil::toUnderscore($name));
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return 'darvin_user_password_reset_token_request';
    }
}
