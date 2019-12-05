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

use Darvin\UserBundle\Form\Factory\SecurityFormFactoryInterface;
use Darvin\UserBundle\Form\Renderer\SecurityFormRendererInterface;
use Darvin\UserBundle\Form\Type\Security\LoginType;
use Darvin\UserBundle\Form\Type\Security\RegistrationType;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Security Twig extension
 */
class SecurityExtension extends AbstractExtension
{
    /**
     * @var \Darvin\UserBundle\Form\Factory\SecurityFormFactoryInterface
     */
    private $securityFormFactory;

    /**
     * @var \Darvin\UserBundle\Form\Renderer\SecurityFormRendererInterface
     */
    private $securityFormRenderer;

    /**
     * @param \Darvin\UserBundle\Form\Factory\SecurityFormFactoryInterface   $securityFormFactory  Security form factory
     * @param \Darvin\UserBundle\Form\Renderer\SecurityFormRendererInterface $securityFormRenderer Security form renderer
     */
    public function __construct(SecurityFormFactoryInterface $securityFormFactory, SecurityFormRendererInterface $securityFormRenderer)
    {
        $this->securityFormFactory = $securityFormFactory;
        $this->securityFormRenderer = $securityFormRenderer;
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('security_login_form', [$this, 'renderLoginForm'], [
                'is_safe' => ['html'],
            ]),
            new TwigFunction('security_registration_form', [$this, 'renderRegistrationForm'], [
                'is_safe' => ['html'],
            ]),
        ];
    }

    /**
     * @param string|null $template Template
     * @param array       $options  Options
     * @param string      $type     Type
     * @param string|null $name     Name
     *
     * @return string
     */
    public function renderLoginForm(?string $template = null, array $options = [], string $type = LoginType::class, ?string $name = null): string
    {
        return $this->securityFormRenderer->renderLoginForm($this->securityFormFactory->createLoginForm($options, $type, $name), true, $template);
    }

    /**
     * @param string|null $template Template
     * @param array       $options  Options
     * @param string      $type     Type
     * @param string|null $name     Name
     *
     * @return string
     */
    public function renderRegistrationForm(?string $template = null, array $options = [], string $type = RegistrationType::class, ?string $name = null): string
    {
        return $this->securityFormRenderer->renderRegistrationForm($this->securityFormFactory->createRegistrationForm(null, $options, $type, $name), true, $template);
    }
}
