<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2018, Darvin Studio
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
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Translation\TranslatorInterface;

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
     * @var \Symfony\Component\Translation\TranslatorInterface
     */
    private $translator;

    /**
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $authenticationUtils Authentication utilities
     * @param \Darvin\UserBundle\Form\Factory\Security\LoginFormFactoryInterface  $loginFormFactory    Login form factory
     * @param \Darvin\UserBundle\Form\Factory\Security\SecurityFormFactory        $securityFormFactory Security form factory
     * @param \Darvin\Utils\Service\ServiceProviderInterface                      $templatingProvider  Templating service provider
     * @param \Symfony\Component\Translation\TranslatorInterface                  $translator          Translator
     */
    public function __construct(
        AuthenticationUtils $authenticationUtils,
        LoginFormFactoryInterface $loginFormFactory,
        SecurityFormFactory $securityFormFactory,
        ServiceProviderInterface $templatingProvider,
        TranslatorInterface $translator
    ) {
        $this->authenticationUtils = $authenticationUtils;
        $this->loginFormFactory = $loginFormFactory;
        $this->securityFormFactory = $securityFormFactory;
        $this->templatingProvider = $templatingProvider;
        $this->translator = $translator;
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
            $template = $widget ? '@DarvinUser/security/widget/login.html.twig' : '@DarvinUser/security/login.html.twig';
        }

        $form = $this->loginFormFactory->createLoginForm($actionRoute, $type, $name);

        $exception = $this->authenticationUtils->getLastAuthenticationError();

        if (!empty($exception)) {
            $form->addError(new FormError($this->translator->trans($exception->getMessage(), [], 'security')));
        }

        return $this->getTemplating()->render($template, [
            'error' => !empty($exception) ? $exception->getMessage() : null,
            'form'  => $form->createView(),
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
            ? '@DarvinUser/security/widget/reset_password.html.twig'
            : '@DarvinUser/security/reset_password.html.twig';

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
                ? '@DarvinUser/security/widget/register.html.twig'
                : '@DarvinUser/security/register.html.twig';
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
