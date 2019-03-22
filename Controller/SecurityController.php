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

use Darvin\UserBundle\Entity\PasswordResetToken;
use Darvin\UserBundle\Event\SecurityEvents;
use Darvin\UserBundle\Event\UserEvent;
use Darvin\UserBundle\Form\Factory\SecurityFormFactoryInterface;
use Darvin\UserBundle\Form\Handler\SecurityFormHandlerInterface;
use Darvin\UserBundle\Form\Renderer\SecurityFormRendererInterface;
use Darvin\UserBundle\Repository\PasswordResetTokenRepository;
use Darvin\Utils\Flash\FlashNotifierInterface;
use Darvin\Utils\HttpFoundation\AjaxResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;

/**
 * Security controller
 */
class SecurityController extends AbstractController
{
    /**
     * @param string|null $code Code
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function confirmRegistrationAction(?string $code): Response
    {
        if (null === $code) {
            return $this->render('@DarvinUser/security/confirm_registration/code_sent.html.twig');
        }

        $user = $this->get('darvin_user.user.repository')->getOneByRegistrationToken($code);

        if (empty($user)) {
            throw $this->createNotFoundException('security.confirm_registration.invalid_code_error');
        }

        $user->getRegistrationConfirmToken()->setId(null);
        $user->setEnabled(true);
        $this->getDoctrine()->getManager()->flush();

        $this->get('event_dispatcher')->dispatch(SecurityEvents::REGISTRATION_CONFIRMED, new UserEvent($user));

        return $this->render('@DarvinUser/security/confirm_registration/success.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request): Response
    {
        if ($this->isGranted(AuthenticatedVoter::IS_AUTHENTICATED_REMEMBERED)) {
            return $this->redirectToRoute($this->container->getParameter('darvin_user.already_logged_in_redirect_route'));
        }

        return new Response($this->getSecurityFormRenderer()->renderLoginForm(null, $request->isXmlHttpRequest()));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request): Response
    {
        if ($this->isGranted(AuthenticatedVoter::IS_AUTHENTICATED_REMEMBERED)) {
            return $this->redirectToRoute($this->container->getParameter('darvin_user.already_logged_in_redirect_route'));
        }

        $widget = $request->isXmlHttpRequest();

        $form = $this->getSecurityFormFactory()->createRegistrationForm()->handleRequest($request);

        if (!$form->isSubmitted()) {
            return new Response($this->getSecurityFormRenderer()->renderRegistrationForm($form, $widget));
        }

        $successMessage = 'security.register.success';

        $needConfirm = $this->container->getParameter('darvin_user.confirm_registration');
        if (!$this->getSecurityFormHandler()->handleRegistrationForm($form, !$widget, $successMessage, $needConfirm)) {
            $html = $this->getSecurityFormRenderer()->renderRegistrationForm($form, $widget);

            return $widget
                ? new AjaxResponse($html, false, FlashNotifierInterface::MESSAGE_FORM_ERROR)
                : new Response($html);
        }

        $url = $needConfirm ?
            $this->generateUrl('darvin_user_security_confirm_registration') :
            $this->generateUrl($this->container->getParameter('darvin_user.already_logged_in_redirect_route'))
        ;

        return $widget
            ? new AjaxResponse('', true, $successMessage, [], $url)
            : $this->redirect($url);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function resetPasswordAction(Request $request): Response
    {
        $widget = $request->isXmlHttpRequest();

        $passwordResetToken = $this->getPasswordResetToken($request->query->get('token', ''));

        $form = $this->getSecurityFormFactory()->createPasswordResetForm($passwordResetToken)->handleRequest($request);

        if (!$form->isSubmitted()) {
            return new Response($this->getSecurityFormRenderer()->renderPasswordResetForm($form, $widget));
        }

        $successMessage = 'security.reset_password.success';

        if (!$this->getSecurityFormHandler()->handlePasswordResetForm($form, !$widget, $successMessage)) {
            $html = $this->getSecurityFormRenderer()->renderPasswordResetForm($form, $widget);

            return $widget
                ? new AjaxResponse($html, false, FlashNotifierInterface::MESSAGE_FORM_ERROR)
                : new Response($html);
        }

        $url = $this->generateUrl($this->container->getParameter('darvin_user.already_logged_in_redirect_route'));

        return $widget
            ? new AjaxResponse('', true, $successMessage, [], $url)
            : $this->redirect($url);
    }

    /**
     * @param string $base64EncodedId Base64-encoded password reset token ID
     *
     * @return \Darvin\UserBundle\Entity\PasswordResetToken
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function getPasswordResetToken(string $base64EncodedId): PasswordResetToken
    {
        $passwordResetToken = $this->getPasswordResetTokenRepository()->getOneNonExpiredByBase64EncodedId($base64EncodedId);

        if (empty($passwordResetToken)) {
            throw $this->createNotFoundException(
                sprintf('Unable to find non-expired password reset token by ID "%s".', base64_decode($base64EncodedId))
            );
        }

        return $passwordResetToken;
    }

    /**
     * @return \Darvin\UserBundle\Repository\PasswordResetTokenRepository
     */
    private function getPasswordResetTokenRepository(): PasswordResetTokenRepository
    {
        return $this->getDoctrine()->getRepository(PasswordResetToken::class);
    }

    /**
     * @return \Darvin\UserBundle\Form\Factory\SecurityFormFactoryInterface
     */
    private function getSecurityFormFactory(): SecurityFormFactoryInterface
    {
        return $this->get('darvin_user.security.form.factory');
    }

    /**
     * @return \Darvin\UserBundle\Form\Handler\SecurityFormHandlerInterface
     */
    private function getSecurityFormHandler(): SecurityFormHandlerInterface
    {
        return $this->get('darvin_user.security.form.handler');
    }

    /**
     * @return \Darvin\UserBundle\Form\Renderer\SecurityFormRendererInterface
     */
    private function getSecurityFormRenderer(): SecurityFormRendererInterface
    {
        return $this->get('darvin_user.security.form.renderer');
    }
}
