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

use Darvin\UserBundle\Form\Factory\PasswordResetTokenFormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * Password reset token form renderer
 */
class PasswordResetTokenFormRenderer
{
    /**
     * @var \Darvin\UserBundle\Form\Factory\PasswordResetTokenFormFactory
     */
    private $passwordResetTokenFormFactory;

    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    private $templating;

    /**
     * @param \Darvin\UserBundle\Form\Factory\PasswordResetTokenFormFactory $passwordResetTokenFormFactory Password reset token form factory
     * @param \Symfony\Component\Templating\EngineInterface                 $templating                    Templating
     */
    public function __construct(PasswordResetTokenFormFactory $passwordResetTokenFormFactory, EngineInterface $templating)
    {
        $this->passwordResetTokenFormFactory = $passwordResetTokenFormFactory;
        $this->templating = $templating;
    }

    /**
     * @param bool                                  $widget Whether to render widget
     * @param \Symfony\Component\Form\FormInterface $form   Form
     *
     * @return string
     */
    public function renderRequestForm($widget = true, FormInterface $form = null)
    {
        if (empty($form)) {
            $form = $this->passwordResetTokenFormFactory->createRequestForm();
        }

        $template = $widget
            ? 'DarvinUserBundle:PasswordResetToken/widget/request:form.html.twig'
            : 'DarvinUserBundle:PasswordResetToken:request_form.html.twig';

        return $this->templating->render($template, array(
            'form' => $form->createView(),
        ));
    }
}
