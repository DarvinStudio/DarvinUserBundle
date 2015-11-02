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
 * Security controller
 */
class SecurityController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction()
    {
        if ($this->isGranted(AuthenticatedVoter::IS_AUTHENTICATED_REMEMBERED)) {
            return $this->redirectToRoute($this->getParameter('darvin_user.already_logged_in_redirect_route'));
        }

        $error = $this->getAuthenticationUtils()->getLastAuthenticationError();

        if (!empty($error)) {
            $this->getFlashNotifier()->error($error->getMessage());
        }

        return $this->render('DarvinUserBundle:Security:login.html.twig', array(
            'form' => $this->getLoginFormFactory()->createLoginForm()->createView(),
        ));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        if ($this->isGranted(AuthenticatedVoter::IS_AUTHENTICATED_REMEMBERED)) {
            return $this->redirectToRoute($this->getParameter('darvin_user.already_logged_in_redirect_route'));
        }

        $form = $this->getSecurityFormFactory()->createRegistrationForm()->handleRequest($request);

        if (!$form->isSubmitted()) {
            return new Response(
                $this->getSecurityFormRenderer()->renderRegistrationForm($request->isXmlHttpRequest(), $form)
            );
        }

        $successMessage = 'security.action.register.success';

        if (!$this->getSecurityFormHandler()->handleRegistrationForm($form, !$request->isXmlHttpRequest(), $successMessage)) {
            $html = $this->getSecurityFormRenderer()->renderRegistrationForm($request->isXmlHttpRequest(), $form);

            return $request->isXmlHttpRequest()
                ? new AjaxResponse($html, false, FlashNotifierInterface::MESSAGE_FORM_ERROR)
                : new Response($html);
        }

        $url = $this->generateUrl($this->getParameter('darvin_user.already_logged_in_redirect_route'));

        return $request->isXmlHttpRequest()
            ? new AjaxResponse('', true, $successMessage, array(), $url)
            : $this->redirect($url);
    }

    /**
     * @return \Symfony\Component\Security\Http\Authentication\AuthenticationUtils
     */
    private function getAuthenticationUtils()
    {
        return $this->get('security.authentication_utils');
    }

    /**
     * @return \Darvin\Utils\Flash\FlashNotifier
     */
    private function getFlashNotifier()
    {
        return $this->get('darvin_utils.flash.notifier');
    }

    /**
     * @return \Darvin\UserBundle\Form\Factory\Security\LoginFormFactoryInterface
     */
    private function getLoginFormFactory()
    {
        return $this->get('darvin_user.security.form.factory.login');
    }

    /**
     * @return \Darvin\UserBundle\Form\Factory\Security\SecurityFormFactory
     */
    private function getSecurityFormFactory()
    {
        return $this->get('darvin_user.security.form.factory.common');
    }

    /**
     * @return \Darvin\UserBundle\Form\Handler\SecurityFormHandler
     */
    private function getSecurityFormHandler()
    {
        return $this->get('darvin_user.security.form.handler');
    }

    /**
     * @return \Darvin\UserBundle\Form\Renderer\SecurityFormRenderer
     */
    private function getSecurityFormRenderer()
    {
        return $this->get('darvin_user.security.form.renderer');
    }
}
