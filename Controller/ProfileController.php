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

use Darvin\UserBundle\Form\Factory\ProfileFormFactoryInterface;
use Darvin\UserBundle\Form\Handler\ProfileFormHandlerInterface;
use Darvin\UserBundle\Form\Renderer\ProfileFormRendererInterface;
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
    public function changePasswordAction(Request $request): Response
    {

    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request): Response
    {
        $widget = $request->isXmlHttpRequest();

        $form = $this->getProfileFormFactory()->createEditForm()->handleRequest($request);

        if (!$form->isSubmitted()) {
            return new Response($this->getProfileFormRenderer()->renderEditForm($form, $widget));
        }

        $successMessage = 'profile.edit.success';

        if (!$this->getProfileFormHandler()->handleEditForm($form, !$widget, $successMessage)) {
            $html = $this->getProfileFormRenderer()->renderEditForm($form, $widget);

            return $widget
                ? new AjaxResponse($html, false, FlashNotifierInterface::MESSAGE_FORM_ERROR)
                : new Response($html);
        }

        return $widget
            ? new AjaxResponse($this->getProfileFormRenderer()->renderEditForm($form), true, $successMessage)
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
     * @return \Darvin\UserBundle\Form\Factory\ProfileFormFactoryInterface
     */
    private function getProfileFormFactory(): ProfileFormFactoryInterface
    {
        return $this->get('darvin_user.profile.form_factory');
    }

    /**
     * @return \Darvin\UserBundle\Form\Handler\ProfileFormHandlerInterface
     */
    private function getProfileFormHandler(): ProfileFormHandlerInterface
    {
        return $this->get('darvin_user.profile.form_handler');
    }

    /**
     * @return \Darvin\UserBundle\Form\Renderer\ProfileFormRendererInterface
     */
    private function getProfileFormRenderer(): ProfileFormRendererInterface
    {
        return $this->get('darvin_user.profile.form_renderer');
    }
}
