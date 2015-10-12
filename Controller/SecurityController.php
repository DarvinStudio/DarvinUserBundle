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
        $form = $this->getSecurityFormFactory()->createLoginForm();

        $error = $this->getAuthenticationUtils()->getLastAuthenticationError();

        return $this->render('DarvinUserBundle:Security:login.html.twig', array(
            'error' => !empty($error) ? $error->getMessage() : null,
            'form'  => $form->createView(),
        ));
    }

    /**
     * @return \Symfony\Component\Security\Http\Authentication\AuthenticationUtils
     */
    private function getAuthenticationUtils()
    {
        return $this->get('security.authentication_utils');
    }

    /**
     * @return \Darvin\UserBundle\Form\Factory\Security\SecurityFormFactoryInterface
     */
    private function getSecurityFormFactory()
    {
        return $this->get('darvin_user.security.form_factory');
    }
}
