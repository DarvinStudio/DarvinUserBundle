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

use Darvin\UserBundle\Form\Factory\PasswordResetTokenFormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * Password reset token form renderer
 */
class PasswordResetTokenFormRenderer implements PasswordResetTokenFormRendererInterface
{
    /**
     * @var \Darvin\UserBundle\Form\Factory\PasswordResetTokenFormFactoryInterface
     */
    private $passwordResetTokenFormFactory;

    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    private $templating;

    /**
     * @param \Darvin\UserBundle\Form\Factory\PasswordResetTokenFormFactoryInterface $passwordResetTokenFormFactory Password reset token form factory
     * @param \Symfony\Component\Templating\EngineInterface                          $templating                    Templating
     */
    public function __construct(PasswordResetTokenFormFactoryInterface $passwordResetTokenFormFactory, EngineInterface $templating)
    {
        $this->passwordResetTokenFormFactory = $passwordResetTokenFormFactory;
        $this->templating = $templating;
    }

    /**
     * {@inheritDoc}
     */
    public function renderRequestForm(?FormInterface $form = null, bool $partial = true, ?string $template = null): string
    {
        if (empty($form)) {
            $form = $this->passwordResetTokenFormFactory->createRequestForm();
        }
        if (empty($template)) {
            $template = sprintf('@DarvinUser/password_reset_token/request/%srequest.html.twig', $partial ? '_' : '');
        }

        return $this->templating->render($template, [
            'form' => $form->createView(),
        ]);
    }
}
