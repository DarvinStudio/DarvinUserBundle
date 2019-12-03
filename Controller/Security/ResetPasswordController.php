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

use Darvin\UserBundle\Entity\PasswordResetToken;
use Darvin\UserBundle\Form\Factory\SecurityFormFactoryInterface;
use Darvin\UserBundle\Form\Handler\SecurityFormHandlerInterface;
use Darvin\UserBundle\Form\Renderer\SecurityFormRendererInterface;
use Darvin\UserBundle\Repository\PasswordResetTokenRepository;
use Darvin\Utils\Flash\FlashNotifierInterface;
use Darvin\Utils\HttpFoundation\AjaxResponse;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

/**
 * Reset password controller
 */
class ResetPasswordController
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

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
     * @var string
     */
    private $loggedInRoute;

    /**
     * @param \Doctrine\ORM\EntityManager                                    $em            Entity manager
     * @param \Darvin\UserBundle\Form\Factory\SecurityFormFactoryInterface   $formFactory   Security form factory
     * @param \Darvin\UserBundle\Form\Handler\SecurityFormHandlerInterface   $formHandler   Security form handler
     * @param \Darvin\UserBundle\Form\Renderer\SecurityFormRendererInterface $formRenderer  Security form renderer
     * @param \Symfony\Component\Routing\RouterInterface                     $router        Router
     * @param string                                                         $loggedInRoute Already logged in redirect route
     */
    public function __construct(
        EntityManager $em,
        SecurityFormFactoryInterface $formFactory,
        SecurityFormHandlerInterface $formHandler,
        SecurityFormRendererInterface $formRenderer,
        RouterInterface $router,
        string $loggedInRoute
    ) {
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->formHandler = $formHandler;
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
        $widget = $request->isXmlHttpRequest();

        $passwordResetToken = $this->getPasswordResetToken($request->query->get('token', ''));

        $form = $this->formFactory->createPasswordResetForm($passwordResetToken)->handleRequest($request);

        if (!$form->isSubmitted()) {
            return new Response($this->formRenderer->renderPasswordResetForm($form, $widget));
        }

        $successMessage = 'security.reset_password.success';

        if (!$this->formHandler->handlePasswordResetForm($form, !$widget, $successMessage)) {
            $html = $this->formRenderer->renderPasswordResetForm($form, $widget);

            return $widget
                ? new AjaxResponse($html, false, FlashNotifierInterface::MESSAGE_FORM_ERROR)
                : new Response($html);
        }

        $url = $this->router->generate($this->loggedInRoute);

        return $widget
            ? new AjaxResponse(null, true, $successMessage, [], $url)
            : new RedirectResponse($url);
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

        if (null === $passwordResetToken) {
            throw new NotFoundHttpException(
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
        return $this->em->getRepository(PasswordResetToken::class);
    }
}
