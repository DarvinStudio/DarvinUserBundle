<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2017-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Twig\Extension;

use Darvin\UserBundle\Form\Renderer\SecurityFormRenderer;
use Darvin\UserBundle\Form\Type\Security\LoginType;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Security Twig extension
 */
class SecurityExtension extends AbstractExtension
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
    public function getFunctions(): iterable
    {
        foreach ([
            'security_login_form'        => 'renderLoginForm',
            'security_registration_form' => 'renderRegistrationForm',
        ] as $name => $method) {
            yield new TwigFunction($name, [$this, $method], [
                'is_safe' => ['html'],
            ]);
        }
    }

    /**
     * @param string      $template    Template
     * @param string      $actionRoute Action route
     * @param string      $type        Form type
     * @param string|null $name        Form name
     *
     * @return string
     */
    public function renderLoginForm(
        string $template    = '@DarvinUser/security/_login.html.twig',
        string $actionRoute = 'darvin_user_security_login_check',
        string $type        = LoginType::class,
        ?string $name       = null
    ): string {
        return $this->securityFormRenderer->renderLoginForm(true, $actionRoute, $type, $name, $template);
    }

    /**
     * @param string $template Template
     *
     * @return string
     */
    public function renderRegistrationForm(string $template = '@DarvinUser/security/_register.html.twig'): string
    {
        return $this->securityFormRenderer->renderRegistrationForm(true, null, $template);
    }
}
