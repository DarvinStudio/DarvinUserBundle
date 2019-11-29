<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Form\Renderer;

use Darvin\UserBundle\Form\Factory\SecurityFormFactoryInterface;
use Darvin\Utils\Service\ServiceProviderInterface;
use Symfony\Component\Form\FormInterface;
use Twig\Environment;

/**
 * Security form renderer
 */
class SecurityFormRenderer implements SecurityFormRendererInterface
{
    /**
     * @var \Darvin\UserBundle\Form\Factory\SecurityFormFactoryInterface
     */
    private $securityFormFactory;

    /**
     * @var \Darvin\Utils\Service\ServiceProviderInterface
     */
    private $twigProvider;

    /**
     * @param \Darvin\UserBundle\Form\Factory\SecurityFormFactoryInterface $securityFormFactory Security form factory
     * @param \Darvin\Utils\Service\ServiceProviderInterface               $twigProvider        Twig service provider
     */
    public function __construct(SecurityFormFactoryInterface $securityFormFactory, ServiceProviderInterface $twigProvider)
    {
        $this->securityFormFactory = $securityFormFactory;
        $this->twigProvider = $twigProvider;
    }

    /**
     * {@inheritDoc}
     */
    public function renderLoginForm(?FormInterface $form = null, bool $partial = true, ?string $template = null): string
    {
        if (empty($form)) {
            $form = $this->securityFormFactory->createLoginForm();
        }
        if (empty($template)) {
            $template = sprintf('@DarvinUser/security/%slogin.html.twig', $partial ? '_' : '');
        }

        return $this->getTwig()->render($template, [
            'form' => $form->createView(),
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function renderPasswordResetForm(FormInterface $form, bool $partial = true, ?string $template = null): string
    {
        if (empty($template)) {
            $template = sprintf('@DarvinUser/security/%sreset_password.html.twig', $partial ? '_' : '');
        }

        return $this->getTwig()->render($template, [
            'form' => $form->createView(),
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function renderRegistrationForm(?FormInterface $form = null, bool $partial = true, ?string $template = null): string
    {
        if (empty($form)) {
            $form = $this->securityFormFactory->createRegistrationForm();
        }
        if (empty($template)) {
            $template = sprintf('@DarvinUser/security/%sregister.html.twig', $partial ? '_' : '');
        }

        return $this->getTwig()->render($template, [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return \Twig\Environment
     */
    private function getTwig(): Environment
    {
        return $this->twigProvider->getService();
    }
}
