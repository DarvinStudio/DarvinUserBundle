<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Controller;

use Darvin\Utils\Flash\FlashNotifierInterface;
use Darvin\Utils\HttpFoundation\AjaxResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;

/**
 * Password reset token controller
 */
class PasswordResetTokenController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function requestAction(Request $request)
    {
        if ($this->isGranted(AuthenticatedVoter::IS_AUTHENTICATED_REMEMBERED)) {
            return $this->redirectToRoute($this->getParameter('darvin_user.already_logged_in_redirect_route'));
        }

        $form = $this->getPasswordResetTokenFormFactory()->createRequestForm()->handleRequest($request);

        if (!$form->isSubmitted()) {
            return new Response(
                $this->getPasswordResetTokenFormRenderer()->renderRequestForm($request->isXmlHttpRequest(), $form)
            );
        }

        $successMessage = 'password_reset_token.action.request.success';

        if (!$this->getPasswordResetTokenFormHandler()->handleRequestForm($form, !$request->isXmlHttpRequest(), $successMessage)) {
            $html = $this->getPasswordResetTokenFormRenderer()->renderRequestForm($request->isXmlHttpRequest(), $form);

            return $request->isXmlHttpRequest()
                ? new AjaxResponse($html, false, FlashNotifierInterface::MESSAGE_FORM_ERROR)
                : new Response($html);
        }

        return $request->isXmlHttpRequest()
            ? new AjaxResponse(
                $this->renderView('DarvinUserBundle:PasswordResetToken/widget/request:submitted.html.twig'),
                true,
                $successMessage
            )
            : $this->redirectToRoute('darvin_user_security_login');
    }

    /**
     * @return \Darvin\UserBundle\Form\Factory\PasswordResetTokenFormFactory
     */
    private function getPasswordResetTokenFormFactory()
    {
        return $this->get('darvin_user.password_reset_token.form.factory');
    }

    /**
     * @return \Darvin\UserBundle\Form\Handler\PasswordResetTokenFormHandler
     */
    private function getPasswordResetTokenFormHandler()
    {
        return $this->get('darvin_user.password_reset_token.form.handler');
    }

    /**
     * @return \Darvin\UserBundle\Form\Renderer\PasswordResetTokenFormRenderer
     */
    private function getPasswordResetTokenFormRenderer()
    {
        return $this->get('darvin_user.password_reset_token.form.renderer');
    }
}
