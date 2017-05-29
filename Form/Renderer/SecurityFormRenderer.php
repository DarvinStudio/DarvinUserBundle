<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Form\Renderer;

use Darvin\UserBundle\Form\Factory\Security\LoginFormFactoryInterface;
use Darvin\UserBundle\Form\Factory\Security\SecurityFormFactory;
use Darvin\UserBundle\Form\Type\Security\LoginType;
use Darvin\Utils\Service\ServiceProviderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Security form renderer
 */
class SecurityFormRenderer
{
    /**
     * @var \Symfony\Component\Security\Http\Authentication\AuthenticationUtils
     */
    private $authenticationUtils;

    /**
     * @var \Darvin\UserBundle\Form\Factory\Security\LoginFormFactoryInterface
     */
    private $loginFormFactory;

    /**
     * @var \Darvin\UserBundle\Form\Factory\Security\SecurityFormFactory
     */
    private $securityFormFactory;

    /**
     * @var \Darvin\Utils\Service\ServiceProviderInterface
     */
    private $templatingProvider;

    /**
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $authenticationUtils Authentication utilities
     * @param \Darvin\UserBundle\Form\Factory\Security\LoginFormFactoryInterface  $loginFormFactory    Login form factory
     * @param \Darvin\UserBundle\Form\Factory\Security\SecurityFormFactory        $securityFormFactory Security form factory
     * @param \Darvin\Utils\Service\ServiceProviderInterface                      $templatingProvider  Templating service provider
     */
    public function __construct(
        AuthenticationUtils $authenticationUtils,
        LoginFormFactoryInterface $loginFormFactory,
        SecurityFormFactory $securityFormFactory,
        ServiceProviderInterface $templatingProvider
    ) {
        $this->authenticationUtils = $authenticationUtils;
        $this->loginFormFactory = $loginFormFactory;
        $this->securityFormFactory = $securityFormFactory;
        $this->templatingProvider = $templatingProvider;
    }

    /**
     * @param bool   $widget      Whether to render widget
     * @param string $actionRoute Action route
     * @param string $type        Form type
     * @param string $name        Form name
     * @param string $template    Template
     *
     * @return string
     */
    public function renderLoginForm(
        $widget = true,
        $actionRoute = 'darvin_user_security_login_check',
        $type = LoginType::class,
        $name = null,
        $template = null
    ) {
        if (null === $template) {
            $template = $widget ? 'DarvinUserBundle:Security/widget:login.html.twig' : 'DarvinUserBundle:Security:login.html.twig';
        }

        $exception = $this->authenticationUtils->getLastAuthenticationError();

        return $this->getTemplating()->render($template, [
            'error' => !empty($exception) ? $exception->getMessage() : null,
            'form'  => $this->loginFormFactory->createLoginForm($actionRoute, $type, $name)->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form   Form
     * @param bool                                  $widget Whether to render widget
     *
     * @return string
     */
    public function renderPasswordResetForm(FormInterface $form, $widget = true)
    {
        $template = $widget
            ? 'DarvinUserBundle:Security/widget:reset_password.html.twig'
            : 'DarvinUserBundle:Security:reset_password.html.twig';

        return $this->getTemplating()->render($template, [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param bool                                  $widget   Whether to render widget
     * @param \Symfony\Component\Form\FormInterface $form     Form
     * @param string                                $template Template
     *
     * @return string
     */
    public function renderRegistrationForm($widget = true, FormInterface $form = null, $template = null)
    {
        if (empty($form)) {
            $form = $this->securityFormFactory->createRegistrationForm();
        }
        if (null === $template) {
            $template = $widget
                ? 'DarvinUserBundle:Security/widget:register.html.twig'
                : 'DarvinUserBundle:Security:register.html.twig';
        }

        return $this->getTemplating()->render($template, [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return \Symfony\Component\Templating\EngineInterface
     */
    private function getTemplating()
    {
        return $this->templatingProvider->getService();
    }
}
