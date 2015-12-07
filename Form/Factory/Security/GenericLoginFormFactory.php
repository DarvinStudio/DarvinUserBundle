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

use Darvin\UserBundle\Form\Type\Security\LoginType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Generic login form factory
 */
class GenericLoginFormFactory implements LoginFormFactoryInterface
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
     * @var string
     */
    private $csrfTokenId;

    /**
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $authenticationUtils Authentication utils
     * @param \Symfony\Component\Form\FormFactoryInterface                        $formFactory         Form factory
     * @param \Symfony\Component\Routing\RouterInterface                          $router              Router
     * @param string                                                              $csrfTokenId         CSRF token ID
     */
    public function __construct(
        AuthenticationUtils $authenticationUtils,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        $csrfTokenId
    ) {
        $this->authenticationUtils = $authenticationUtils;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->csrfTokenId = $csrfTokenId;
    }

    /**
     * {@inheritdoc}
     */
    public function createLoginForm($actionRoute = 'darvin_user_security_login_check')
    {
        return $this->formFactory->create(
            LoginType::LOGIN_TYPE_CLASS,
            array(
                '_remember_me' => true,
                '_username'    => $this->authenticationUtils->getLastUsername(),
            ),
            array(
                'action'        => $this->router->generate($actionRoute),
                'csrf_token_id' => $this->csrfTokenId,
            )
        );
    }
}
