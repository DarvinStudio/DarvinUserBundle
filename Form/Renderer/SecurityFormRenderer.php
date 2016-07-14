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

use Darvin\UserBundle\Form\Factory\Security\SecurityFormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * Security form renderer
 */
class SecurityFormRenderer
{
    /**
     * @var \Darvin\UserBundle\Form\Factory\Security\SecurityFormFactory
     */
    private $securityFormFactory;

    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    private $templating;

    /**
     * @param \Darvin\UserBundle\Form\Factory\Security\SecurityFormFactory $securityFormFactory Security form factory
     * @param \Symfony\Component\Templating\EngineInterface                $templating          Templating
     */
    public function __construct(SecurityFormFactory $securityFormFactory, EngineInterface $templating)
    {
        $this->securityFormFactory = $securityFormFactory;
        $this->templating = $templating;
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

        return $this->templating->render($template, [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param bool                                  $widget Whether to render widget
     * @param \Symfony\Component\Form\FormInterface $form   Form
     *
     * @return string
     */
    public function renderRegistrationForm($widget = true, FormInterface $form = null)
    {
        if (empty($form)) {
            $form = $this->securityFormFactory->createRegistrationForm();
        }

        $template = $widget
            ? 'DarvinUserBundle:Security/widget:register.html.twig'
            : 'DarvinUserBundle:Security:register.html.twig';

        return $this->templating->render($template, [
            'form' => $form->createView(),
        ]);
    }
}
