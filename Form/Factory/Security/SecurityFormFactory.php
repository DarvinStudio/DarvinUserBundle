<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Form\Factory\Security;

use Darvin\UserBundle\Entity\BaseUser;
use Darvin\UserBundle\Entity\PasswordResetToken;
use Darvin\UserBundle\Form\Type\Security\PasswordResetType;
use Darvin\UserBundle\Form\Type\Security\RegistrationType;
use Darvin\UserBundle\User\UserFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Security form factory
 */
class SecurityFormFactory
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
     * @var \Darvin\UserBundle\User\UserFactory
     */
    private $userFactory;

    /**
     * @var string
     */
    private $userClass;

    /**
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $authenticationUtils Authentication utils
     * @param \Symfony\Component\Form\FormFactoryInterface                        $formFactory         Form factory
     * @param \Symfony\Component\Routing\RouterInterface                          $router              Router
     * @param \Darvin\UserBundle\User\UserFactory                                 $userFactory         User factory
     * @param string                                                              $userClass           User entity class
     */
    public function __construct(
        AuthenticationUtils $authenticationUtils,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        UserFactory $userFactory,
        $userClass
    ) {
        $this->authenticationUtils = $authenticationUtils;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->userFactory = $userFactory;
        $this->userClass = $userClass;
    }

    /**
     * @param \Darvin\UserBundle\Entity\PasswordResetToken $passwordResetToken Password reset token
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createPasswordResetForm(PasswordResetToken $passwordResetToken)
    {
        return $this->formFactory->create(new PasswordResetType(), $passwordResetToken->getUser(), array(
            'action' => $this->router->generate('darvin_user_security_reset_password', array(
                'token' => $passwordResetToken->getBase64EncodedId(),
            )),
            'data_class' => $this->userClass,
        ));
    }

    /**
     * @param \Darvin\UserBundle\Entity\BaseUser $user User
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createRegistrationForm(BaseUser $user = null)
    {
        if (empty($user)) {
            $user = $this->userFactory->createUser()
                ->setEmail($this->authenticationUtils->getLastUsername());
        }

        return $this->formFactory->create(new RegistrationType(), $user, array(
            'action'     => $this->router->generate('darvin_user_security_register'),
            'data_class' => $this->userClass,
        ));
    }
}
