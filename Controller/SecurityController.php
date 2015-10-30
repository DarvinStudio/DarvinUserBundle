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

        $form = $this->getLoginFormFactory()->createLoginForm();

        $error = $this->getAuthenticationUtils()->getLastAuthenticationError();

        return $this->render('DarvinUserBundle:Security:login.html.twig', array(
            'error' => !empty($error) ? $error->getMessage() : null,
            'form'  => $form->createView(),
        ));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        return new Response($this->getSecurityFormRenderer()->renderRegistrationForm($request->isXmlHttpRequest()));
    }

    /**
     * @return \Symfony\Component\Security\Http\Authentication\AuthenticationUtils
     */
    private function getAuthenticationUtils()
    {
        return $this->get('security.authentication_utils');
    }

    /**
     * @return \Darvin\UserBundle\Form\Factory\Security\LoginFormFactoryInterface
     */
    private function getLoginFormFactory()
    {
        return $this->get('darvin_user.security.form.factory.login');
    }

    /**
     * @return \Darvin\UserBundle\Form\Renderer\SecurityFormRenderer
     */
    private function getSecurityFormRenderer()
    {
        return $this->get('darvin_user.security.form.renderer');
    }
}
