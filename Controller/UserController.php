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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * User controller
 */
class UserController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function profileAction(Request $request)
    {
        $widget = $request->isXmlHttpRequest();

        $form = $this->getUserFormFactory()->createProfileForm()->handleRequest($request);

        if (!$form->isSubmitted()) {
            return new Response($this->getUserFormRenderer()->renderProfileForm($form, $widget));
        }

        $successMessage = 'user.action.profile.success';

        if (!$this->getUserFormHandler()->handleProfileForm($form, !$widget, $successMessage)) {
            $html = $this->getUserFormRenderer()->renderProfileForm($form, $widget);

            return $widget
                ? new AjaxResponse($html, false, FlashNotifierInterface::MESSAGE_FORM_ERROR)
                : new Response($html);
        }

        return $widget
            ? new AjaxResponse($this->getUserFormRenderer()->renderProfileForm($form), true, $successMessage)
            : $this->redirectToRoute('darvin_user_user_profile');
    }

    /**
     * @return \Darvin\UserBundle\Form\Factory\UserFormFactory
     */
    private function getUserFormFactory()
    {
        return $this->get('darvin_user.user.form.factory');
    }

    /**
     * @return \Darvin\UserBundle\Form\Handler\UserFormHandler
     */
    private function getUserFormHandler()
    {
        return $this->get('darvin_user.user.form.handler');
    }

    /**
     * @return \Darvin\UserBundle\Form\Renderer\UserFormRenderer
     */
    private function getUserFormRenderer()
    {
        return $this->get('darvin_user.user.form.renderer');
    }
}
