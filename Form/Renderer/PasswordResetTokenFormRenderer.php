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
use Twig\Environment;

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
     * @var \Twig\Environment
     */
    private $twig;

    /**
     * @param \Darvin\UserBundle\Form\Factory\PasswordResetTokenFormFactoryInterface $passwordResetTokenFormFactory Password reset token form factory
     * @param \Twig\Environment                                                      $twig                          Twig
     */
    public function __construct(PasswordResetTokenFormFactoryInterface $passwordResetTokenFormFactory, Environment $twig)
    {
        $this->passwordResetTokenFormFactory = $passwordResetTokenFormFactory;
        $this->twig = $twig;
    }

    /**
     * {@inheritDoc}
     */
    public function renderRequestForm(?FormInterface $form = null, bool $partial = true, ?string $template = null): string
    {
        if (null === $form) {
            $form = $this->passwordResetTokenFormFactory->createRequestForm();
        }
        if (null === $template) {
            $template = sprintf('@DarvinUser/password_reset_token/request/%srequest.html.twig', $partial ? '_' : '');
        }

        return $this->twig->render($template, [
            'form' => $form->createView(),
        ]);
    }
}
