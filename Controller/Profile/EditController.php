<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Controller\Profile;

use Darvin\UserBundle\Form\Factory\ProfileFormFactoryInterface;
use Darvin\UserBundle\Form\Handler\ProfileFormHandlerInterface;
use Darvin\UserBundle\Form\Renderer\ProfileFormRendererInterface;
use Darvin\Utils\Flash\FlashNotifierInterface;
use Darvin\Utils\HttpFoundation\AjaxResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

/**
 * Edit profile controller
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class EditController
{
    /**
     * @var \Darvin\UserBundle\Form\Factory\ProfileFormFactoryInterface
     */
    private $formFactory;

    /**
     * @var \Darvin\UserBundle\Form\Handler\ProfileFormHandlerInterface
     */
    private $formHandler;

    /**
     * @var \Darvin\UserBundle\Form\Renderer\ProfileFormRendererInterface
     */
    private $formRenderer;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @param \Darvin\UserBundle\Form\Factory\ProfileFormFactoryInterface   $formFactory  Profile form factory
     * @param \Darvin\UserBundle\Form\Handler\ProfileFormHandlerInterface   $formHandler  Profile form handler
     * @param \Darvin\UserBundle\Form\Renderer\ProfileFormRendererInterface $formRenderer Profile form renderer
     * @param \Symfony\Component\Routing\RouterInterface                    $router       Router
     */
    public function __construct(
        ProfileFormFactoryInterface $formFactory,
        ProfileFormHandlerInterface $formHandler,
        ProfileFormRendererInterface $formRenderer,
        RouterInterface $router
    ) {
        $this->formFactory = $formFactory;
        $this->formHandler = $formHandler;
        $this->formRenderer = $formRenderer;
        $this->router = $router;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request): Response
    {
        $form    = $this->formFactory->createEditForm()->handleRequest($request);
        $partial = $request->isXmlHttpRequest();

        if (!$form->isSubmitted()) {
            return new Response($this->formRenderer->renderEditForm($form, $partial));
        }

        $successMessage = 'profile.edit.success';

        if (!$this->formHandler->handleEditForm($form, !$partial, $successMessage)) {
            $html = $this->formRenderer->renderEditForm($form, $partial);

            if ($partial) {
                return new AjaxResponse($html, false, FlashNotifierInterface::MESSAGE_FORM_ERROR);
            }

            return new Response($html);
        }
        if ($partial) {
            return new AjaxResponse($this->formRenderer->renderEditForm($form), true, $successMessage);
        }

        return new RedirectResponse($this->router->generate('darvin_user_profile_edit'));
    }
}
