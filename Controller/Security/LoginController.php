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

use Darvin\UserBundle\Form\Renderer\SecurityFormRendererInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;

/**
 * Login controller
 */
class LoginController
{
    /**
     * @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var \Darvin\UserBundle\Form\Renderer\SecurityFormRendererInterface
     */
    private $formRenderer;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @var string
     */
    private $loggedInRoute;

    /**
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker Authorization checker
     * @param \Darvin\UserBundle\Form\Renderer\SecurityFormRendererInterface               $formRenderer         Security form renderer
     * @param \Symfony\Component\Routing\RouterInterface                                   $router               Router
     * @param string                                                                       $loggedInRoute        Already logged in redirect route
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        SecurityFormRendererInterface $formRenderer,
        RouterInterface $router,
        string $loggedInRoute
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->formRenderer = $formRenderer;
        $this->router = $router;
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

        return new Response($this->formRenderer->renderLoginForm(null, $request->isXmlHttpRequest()));
    }
}
