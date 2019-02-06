<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
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
use Darvin\UserBundle\User\UserFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Security form factory
 */
class SecurityFormFactory implements SecurityFormFactoryInterface
{
    /**
     * @var \Symfony\Component\Security\Http\Authentication\AuthenticationUtils
     */
    private $authenticationUtils;

    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @var \Darvin\UserBundle\User\UserFactoryInterface
     */
    private $userFactory;

    /**
     * @var string
     */
    private $csrfTokenId;

    /**
     * @var string
     */
    private $userClass;

    /**
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $authenticationUtils Authentication utils
     * @param \Symfony\Component\Form\FormFactoryInterface                        $formFactory         Form factory
     * @param \Symfony\Component\Routing\RouterInterface                          $router              Router
     * @param \Darvin\UserBundle\User\UserFactoryInterface                        $userFactory         User factory
     * @param string                                                              $csrfTokenId         CSRF token ID
     * @param string                                                              $userClass           User entity class
     */
    public function __construct(
        AuthenticationUtils $authenticationUtils,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        UserFactoryInterface $userFactory,
        string $csrfTokenId,
        string $userClass
    ) {
        $this->authenticationUtils = $authenticationUtils;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->userFactory = $userFactory;
        $this->csrfTokenId = $csrfTokenId;
        $this->userClass = $userClass;
    }

    /**
     * {@inheritDoc}
     */
    public function createLoginForm(array $options = [], string $type = LoginType::class, string $name = ''): FormInterface
    {
        $options = array_merge([
            'csrf_token_id' => $this->csrfTokenId,
        ], $options);

        if (!isset($options['action'])) {
            $options['action'] = $this->router->generate('darvin_user_security_login_check');
        }

        return $this->formFactory->createNamed(
            $name,
            $type,
            [
                '_remember_me' => true,
                '_username'    => $this->authenticationUtils->getLastUsername(),
            ],
            $options
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createPasswordResetForm(PasswordResetToken $passwordResetToken, array $options = [], string $type = PasswordResetType::class, ?string $name = null): FormInterface
    {
        $user    = $passwordResetToken->getUser();
        $options = array_merge([
            'data_class' => $this->userClass,
        ], $options);

        if (!isset($options['action'])) {
            $options['action'] = $this->router->generate('darvin_user_security_reset_password', [
                'token' => $passwordResetToken->getBase64EncodedId(),
            ]);
        }
        if (null !== $name) {
            return $this->formFactory->createNamed($name, $type, $user, $options);
        }

        return $this->formFactory->create($type, $user, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function createRegistrationForm(?BaseUser $user = null, array $options = [], string $type = RegistrationType::class, ?string $name = null): FormInterface
    {
        if (empty($user)) {
            $user = $this->userFactory->createUser();
            $user->setEmail($this->authenticationUtils->getLastUsername());
        }

        $options = array_merge([
            'data_class' => $this->userClass,
        ], $options);

        if (!isset($options['action'])) {
            $options['action'] = $this->router->generate('darvin_user_security_register');
        }
        if (null !== $name) {
            return $this->formFactory->createNamed($name, $type, $user, $options);
        }

        return $this->formFactory->create($type, $user, $options);
    }
}
