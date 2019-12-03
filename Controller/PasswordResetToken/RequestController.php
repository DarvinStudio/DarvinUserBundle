<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Controller\PasswordResetToken;

use Darvin\UserBundle\Form\Factory\PasswordResetTokenFormFactoryInterface;
use Darvin\UserBundle\Form\Handler\PasswordResetTokenFormHandlerInterface;
use Darvin\UserBundle\Form\Renderer\PasswordResetTokenFormRendererInterface;
use Darvin\Utils\Flash\FlashNotifierInterface;
use Darvin\Utils\HttpFoundation\AjaxResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

/**
 * Password reset token request controller
 */
class RequestController
{
    /**
     * @var \Darvin\UserBundle\Form\Factory\PasswordResetTokenFormFactoryInterface
     */
    private $formFactory;

    /**
     * @var \Darvin\UserBundle\Form\Handler\PasswordResetTokenFormHandlerInterface
     */
    private $formHandler;

    /**
     * @var \Darvin\UserBundle\Form\Renderer\PasswordResetTokenFormRendererInterface
     */
    private $formRenderer;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @var \Twig\Environment
     */
    private $twig;

    /**
     * @var \WebmailLinker|null
     */
    private $webmailLinker;

    /**
     * @param \Darvin\UserBundle\Form\Factory\PasswordResetTokenFormFactoryInterface   $formFactory  Password reset token form factory
     * @param \Darvin\UserBundle\Form\Handler\PasswordResetTokenFormHandlerInterface   $formHandler  Password reset token form handler
     * @param \Darvin\UserBundle\Form\Renderer\PasswordResetTokenFormRendererInterface $formRenderer Password reset token form renderer
     * @param \Symfony\Component\Routing\RouterInterface                               $router       Router
     * @param \Twig\Environment                                                        $twig         Twig
     */
    public function __construct(
        PasswordResetTokenFormFactoryInterface $formFactory,
        PasswordResetTokenFormHandlerInterface $formHandler,
        PasswordResetTokenFormRendererInterface $formRenderer,
        RouterInterface $router,
        Environment $twig
    ) {
        $this->formFactory = $formFactory;
        $this->formHandler = $formHandler;
        $this->formRenderer = $formRenderer;
        $this->router = $router;
        $this->twig = $twig;
    }

    /**
     * @param \WebmailLinker|null $webmailLinker Webmail linker
     */
    public function setWebmailLinker(?\WebmailLinker $webmailLinker): void
    {
        $this->webmailLinker = $webmailLinker;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request): Response
    {
        $widget = $request->isXmlHttpRequest();

        $form = $this->formFactory->createRequestForm()->handleRequest($request);

        if (!$form->isSubmitted()) {
            return new Response($this->formRenderer->renderRequestForm($form, $widget));
        }

        $successMessage = 'password_reset_token.request.success.message';

        $passwordResetToken = $this->formHandler->handleRequestForm($form, !$widget, $successMessage);

        if (null === $passwordResetToken) {
            $html = $this->formRenderer->renderRequestForm($form, $widget);

            return $widget
                ? new AjaxResponse($html, false, FlashNotifierInterface::MESSAGE_FORM_ERROR)
                : new Response($html);
        }
        if ($widget) {
            $webmailLink = null;

            if (null !== $this->webmailLinker) {
                $webmailLink = $this->webmailLinker->getProviderByEmailAddress($passwordResetToken->getUser()->getEmail());
            }

            return new AjaxResponse(
                $this->twig->render('@DarvinUser/password_reset_token/request/success.html.twig', [
                    'password_reset_token' => $passwordResetToken,
                    'webmail_link'         => $webmailLink,
                ]),
                true,
                $successMessage
            );
        }

        return new RedirectResponse($this->router->generate('darvin_user_security_login'));
    }
}
