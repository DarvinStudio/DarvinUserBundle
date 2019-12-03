<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Controller\Security;

use Darvin\UserBundle\Form\Factory\SecurityFormFactoryInterface;
use Darvin\UserBundle\Form\Handler\SecurityFormHandlerInterface;
use Darvin\UserBundle\Form\Renderer\SecurityFormRendererInterface;
use Darvin\Utils\Flash\FlashNotifierInterface;
use Darvin\Utils\HttpFoundation\AjaxResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;

/**
 * Register controller
 */
class RegisterController
{
    /**
     * @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var \Darvin\UserBundle\Form\Factory\SecurityFormFactoryInterface
     */
    private $formFactory;

    /**
     * @var \Darvin\UserBundle\Form\Handler\SecurityFormHandlerInterface
     */
    private $formHandler;

    /**
     * @var \Darvin\UserBundle\Form\Renderer\SecurityFormRendererInterface
     */
    private $formRenderer;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @var bool
     */
    private $confirmationRequired;

    /**
     * @var string
     */
    private $loggedInRoute;

    /**
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker Authorization checker
     * @param \Darvin\UserBundle\Form\Factory\SecurityFormFactoryInterface                 $formFactory          Security form factory
     * @param \Darvin\UserBundle\Form\Handler\SecurityFormHandlerInterface                 $formHandler          Security form handler
     * @param \Darvin\UserBundle\Form\Renderer\SecurityFormRendererInterface               $formRenderer         Security form renderer
     * @param \Symfony\Component\Routing\RouterInterface                                   $router               Router
     * @param bool                                                                         $confirmationRequired Is registration confirmation required
     * @param string                                                                       $loggedInRoute        Already logged in redirect route
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        SecurityFormFactoryInterface $formFactory,
        SecurityFormHandlerInterface $formHandler,
        SecurityFormRendererInterface $formRenderer,
        RouterInterface $router,
        bool $confirmationRequired,
        string $loggedInRoute
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->formFactory = $formFactory;
        $this->formHandler = $formHandler;
        $this->formRenderer = $formRenderer;
        $this->router = $router;
        $this->confirmationRequired = $confirmationRequired;
        $this->loggedInRoute = $loggedInRoute;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request): Response
    {
        if ($this->authorizationChecker->isGranted(AuthenticatedVoter::IS_AUTHENTICATED_REMEMBERED)) {
            return new RedirectResponse($this->router->generate($this->loggedInRoute));
        }

        $widget = $request->isXmlHttpRequest();

        $form = $this->formFactory->createRegistrationForm()->handleRequest($request);

        if (!$form->isSubmitted()) {
            return new Response($this->formRenderer->renderRegistrationForm($form, $widget));
        }

        $successMessage = 'security.register.success';

        $event = $this->formHandler->handleRegistrationForm($form, $request, $successMessage, $this->confirmationRequired);

        if (null === $event) {
            $html = $this->formRenderer->renderRegistrationForm($form, $widget);

            return $widget
                ? new AjaxResponse($html, false, FlashNotifierInterface::MESSAGE_FORM_ERROR)
                : new Response($html);
        }
        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $url = $this->confirmationRequired
            ? $this->router->generate('darvin_user_security_confirm_registration')
            : $this->router->generate($this->loggedInRoute);

        return $widget
            ? new AjaxResponse(null, true, $successMessage, [], $url)
            : new RedirectResponse($url);
    }
}
