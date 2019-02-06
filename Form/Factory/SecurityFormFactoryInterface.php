<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Form\Factory;

use Darvin\UserBundle\Entity\BaseUser;
use Darvin\UserBundle\Entity\PasswordResetToken;
use Darvin\UserBundle\Form\Type\Security\LoginType;
use Darvin\UserBundle\Form\Type\Security\PasswordResetType;
use Darvin\UserBundle\Form\Type\Security\RegistrationType;
use Symfony\Component\Form\FormInterface;

/**
 * Security form factory
 */
interface SecurityFormFactoryInterface
{
    /**
     * @param array       $options Options
     * @param string      $type    Type
     * @param string|null $name    Name
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createLoginForm(array $options = [], string $type = LoginType::class, ?string $name = null): FormInterface;

    /**
     * @param \Darvin\UserBundle\Entity\PasswordResetToken $passwordResetToken Password reset token
     * @param array                                        $options            Options
     * @param string                                       $type               Type
     * @param string|null                                  $name               Name
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createPasswordResetForm(PasswordResetToken $passwordResetToken, array $options = [], string $type = PasswordResetType::class, ?string $name = null): FormInterface;

    /**
     * @param \Darvin\UserBundle\Entity\BaseUser|null $user    User
     * @param array                                   $options Options
     * @param string                                  $type    Type
     * @param string|null                             $name    Name
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createRegistrationForm(?BaseUser $user = null, array $options = [], string $type = RegistrationType::class, ?string $name = null): FormInterface;
}
