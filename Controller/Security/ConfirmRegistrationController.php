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

use Darvin\UserBundle\Entity\BaseUser;
use Darvin\UserBundle\Event\SecurityEvents;
use Darvin\UserBundle\Event\UserEvent;
use Darvin\UserBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

/**
 * Confirm registration controller
 */
class ConfirmRegistrationController
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var \Twig\Environment
     */
    private $twig;

    /**
     * @param \Doctrine\ORM\EntityManager                                 $em              Entity manager
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher Event dispatcher
     * @param \Twig\Environment                                           $twig            Twig
     */
    public function __construct(EntityManager $em, EventDispatcherInterface $eventDispatcher, Environment $twig)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->twig = $twig;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     * @param string|null                               $code    Code
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function __invoke(Request $request, ?string $code): Response
    {
        if (null === $code) {
            return new Response($this->twig->render('@DarvinUser/security/confirm_registration/code_sent.html.twig'));
        }

        $user = $this->getUserRepository()->getOneByRegistrationToken($code);

        if (null === $user) {
            throw new NotFoundHttpException('security.confirm_registration.invalid_code_error');
        }

        $user->getRegistrationConfirmToken()->setId(null);
        $user->setEnabled(true);
        $this->em->flush();

        $event = new UserEvent($user, $request);

        $this->eventDispatcher->dispatch($event, SecurityEvents::REGISTRATION_CONFIRMED);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        return new Response($this->twig->render('@DarvinUser/security/confirm_registration/success.html.twig'));
    }

    /**
     * @return \Darvin\UserBundle\Repository\UserRepository
     */
    private function getUserRepository(): UserRepository
    {
        return $this->em->getRepository(BaseUser::class);
    }
}
