<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Controller;

use Darvin\UserBundle\Form\Factory\PasswordResetTokenFormFactoryInterface;
use Darvin\UserBundle\Form\Handler\PasswordResetTokenFormHandlerInterface;
use Darvin\UserBundle\Form\Renderer\PasswordResetTokenFormRendererInterface;
use Darvin\Utils\Flash\FlashNotifierInterface;
use Darvin\Utils\HttpFoundation\AjaxResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;

/**
 * Password reset token controller
 */
class PasswordResetTokenController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function requestAction(Request $request): Response
    {
        if ($this->isGranted(AuthenticatedVoter::IS_AUTHENTICATED_REMEMBERED)) {
            return $this->redirectToRoute($this->container->getParameter('darvin_user.already_logged_in_redirect_route'));
        }

        $widget = $request->isXmlHttpRequest();

        $form = $this->getPasswordResetTokenFormFactory()->createRequestForm()->handleRequest($request);

        if (!$form->isSubmitted()) {
            return new Response($this->getPasswordResetTokenFormRenderer()->renderRequestForm($form, $widget));
        }

        $successMessage = 'password_reset_token.request.success.message';

        $passwordResetToken = $this->getPasswordResetTokenFormHandler()->handleRequestForm($form, !$widget, $successMessage);

        if (empty($passwordResetToken)) {
            $html = $this->getPasswordResetTokenFormRenderer()->renderRequestForm($form, $widget);

            return $widget
                ? new AjaxResponse($html, false, FlashNotifierInterface::MESSAGE_FORM_ERROR)
                : new Response($html);
        }
        if ($widget) {
            $webmailLink   = null;
            $webmailLinker = $this->getWebmailLinker();

            if (!empty($webmailLinker)) {
                $webmailLink = $webmailLinker->getProviderByEmailAddress($passwordResetToken->getUser()->getEmail());
            }

            return new AjaxResponse(
                $this->renderView('@DarvinUser/password_reset_token/request/success.html.twig', [
                    'password_reset_token' => $passwordResetToken,
                    'webmail_link'         => $webmailLink,
                ]),
                true,
                $successMessage
            );
        }

        return $this->redirectToRoute('darvin_user_security_login');
    }

    /**
     * @return \Darvin\UserBundle\Form\Factory\PasswordResetTokenFormFactoryInterface
     */
    private function getPasswordResetTokenFormFactory(): PasswordResetTokenFormFactoryInterface
    {
        return $this->get('darvin_user.password_reset_token.form.factory');
    }

    /**
     * @return \Darvin\UserBundle\Form\Handler\PasswordResetTokenFormHandlerInterface
     */
    private function getPasswordResetTokenFormHandler(): PasswordResetTokenFormHandlerInterface
    {
        return $this->get('darvin_user.password_reset_token.form.handler');
    }

    /**
     * @return \Darvin\UserBundle\Form\Renderer\PasswordResetTokenFormRendererInterface
     */
    private function getPasswordResetTokenFormRenderer(): PasswordResetTokenFormRendererInterface
    {
        return $this->get('darvin_user.password_reset_token.form.renderer');
    }

    /**
     * @return \WebmailLinker|null
     */
    private function getWebmailLinker(): ?\WebmailLinker
    {
        if ($this->has('darvin_webmail_linker.linker')) {
            return $this->get('darvin_webmail_linker.linker');
        }

        return null;
    }
}
