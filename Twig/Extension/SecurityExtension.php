<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Twig\Extension;

use Darvin\UserBundle\Form\Renderer\SecurityFormRenderer;
use Darvin\UserBundle\Form\Type\Security\LoginType;

/**
 * Security Twig extension
 */
class SecurityExtension extends \Twig_Extension
{
    /**
     * @var \Darvin\UserBundle\Form\Renderer\SecurityFormRenderer
     */
    private $securityFormRenderer;

    /**
     * @param \Darvin\UserBundle\Form\Renderer\SecurityFormRenderer $securityFormRenderer Security form renderer
     */
    public function __construct(SecurityFormRenderer $securityFormRenderer)
    {
        $this->securityFormRenderer = $securityFormRenderer;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('security_login_form', [$this, 'renderLoginForm'], [
                'is_safe' => ['html'],
            ]),
            new \Twig_SimpleFunction('security_registration_form', [$this, 'renderRegistrationForm'], [
                'is_safe' => ['html'],
            ]),
        ];
    }

    /**
     * @param string $template    Template
     * @param string $actionRoute Action route
     * @param string $type        Form type
     * @param string $name        Form name
     *
     * @return string
     */
    public function renderLoginForm(
        $template = '@DarvinUser/security/_login.html.twig',
        $actionRoute = 'darvin_user_security_login_check',
        $type = LoginType::class,
        $name = null
    ) {
        return $this->securityFormRenderer->renderLoginForm(true, $actionRoute, $type, $name, $template);
    }

    /**
     * @param string $template Template
     *
     * @return string
     */
    public function renderRegistrationForm($template = '@DarvinUser/security/_register.html.twig')
    {
        return $this->securityFormRenderer->renderRegistrationForm(true, null, $template);
    }
}
