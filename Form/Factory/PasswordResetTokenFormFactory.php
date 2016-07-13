<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Form\Factory;

use Darvin\UserBundle\Form\Type\PasswordResetToken\RequestType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Password reset token form factory
 */
class PasswordResetTokenFormFactory
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
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $authenticationUtils Authentication utils
     * @param \Symfony\Component\Form\FormFactoryInterface                        $formFactory         Form factory
     * @param \Symfony\Component\Routing\RouterInterface                          $router              Router
     */
    public function __construct(
        AuthenticationUtils $authenticationUtils,
        FormFactoryInterface $formFactory,
        RouterInterface $router
    ) {
        $this->authenticationUtils = $authenticationUtils;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createRequestForm()
    {
        return $this->formFactory->create(
            RequestType::REQUEST_TYPE_CLASS,
            [
                'user_email' => $this->authenticationUtils->getLastUsername(),
            ],
            [
                'action' => $this->router->generate('darvin_user_password_reset_token_request'),
            ]
        );
    }
}
