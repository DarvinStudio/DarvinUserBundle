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

use Darvin\UserBundle\Form\Factory\UserFormFactoryInterface;
use Darvin\UserBundle\Form\Handler\UserFormHandlerInterface;
use Darvin\UserBundle\Form\Renderer\UserFormRendererInterface;
use Darvin\Utils\Flash\FlashNotifierInterface;
use Darvin\Utils\HttpFoundation\AjaxResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Profile controller
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class ProfileController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request): Response
    {
        $widget = $request->isXmlHttpRequest();

        $form = $this->getUserFormFactory()->createProfileForm()->handleRequest($request);

        if (!$form->isSubmitted()) {
            return new Response($this->getUserFormRenderer()->renderProfileForm($form, $widget));
        }

        $successMessage = 'profile.edit.success';

        if (!$this->getUserFormHandler()->handleProfileForm($form, !$widget, $successMessage)) {
            $html = $this->getUserFormRenderer()->renderProfileForm($form, $widget);

            return $widget
                ? new AjaxResponse($html, false, FlashNotifierInterface::MESSAGE_FORM_ERROR)
                : new Response($html);
        }

        return $widget
            ? new AjaxResponse($this->getUserFormRenderer()->renderProfileForm($form), true, $successMessage)
            : $this->redirectToRoute('darvin_user_profile_edit');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(): Response
    {
        /** @var \Darvin\UserBundle\Entity\BaseUser $user */
        $user = $this->getUser();

        return $this->render('@DarvinUser/profile/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @return \Darvin\UserBundle\Form\Factory\UserFormFactoryInterface
     */
    private function getUserFormFactory(): UserFormFactoryInterface
    {
        return $this->get('darvin_user.user.form.factory');
    }

    /**
     * @return \Darvin\UserBundle\Form\Handler\UserFormHandlerInterface
     */
    private function getUserFormHandler(): UserFormHandlerInterface
    {
        return $this->get('darvin_user.user.form.handler');
    }

    /**
     * @return \Darvin\UserBundle\Form\Renderer\UserFormRendererInterface
     */
    private function getUserFormRenderer(): UserFormRendererInterface
    {
        return $this->get('darvin_user.user.form.renderer');
    }
}
