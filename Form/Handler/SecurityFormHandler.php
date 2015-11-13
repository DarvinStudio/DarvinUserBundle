<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Form\Handler;

use Darvin\UserBundle\Event\Events;
use Darvin\UserBundle\Event\UserEvent;
use Darvin\UserBundle\Form\FormException;
use Darvin\UserBundle\Form\Type\Security\PasswordResetType;
use Darvin\UserBundle\Form\Type\Security\RegistrationType;
use Darvin\UserBundle\Security\UserAuthenticator;
use Darvin\Utils\Flash\FlashNotifierInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Security form handler
 */
class SecurityFormHandler
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
     * @var \Darvin\Utils\Flash\FlashNotifierInterface
     */
    private $flashNotifier;

    /**
     * @var \Darvin\UserBundle\Security\UserAuthenticator
     */
    private $userAuthenticator;

    /**
     * @var string
     */
    private $publicFirewallName;

    /**
     * @param \Doctrine\ORM\EntityManager                                 $em                 Entity manager
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher    Event dispatcher
     * @param \Darvin\Utils\Flash\FlashNotifierInterface                  $flashNotifier      Flash notifier
     * @param \Darvin\UserBundle\Security\UserAuthenticator               $userAuthenticator  User authenticator
     * @param string                                                      $publicFirewallName Public firewall name
     */
    public function __construct(
        EntityManager $em,
        EventDispatcherInterface $eventDispatcher,
        FlashNotifierInterface $flashNotifier,
        UserAuthenticator $userAuthenticator,
        $publicFirewallName
    ) {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->flashNotifier = $flashNotifier;
        $this->userAuthenticator = $userAuthenticator;
        $this->publicFirewallName = $publicFirewallName;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form             Form
     * @param bool                                  $addFlashMessages Whether to add flash messages
     * @param string                                $successMessage   Success message
     *
     * @return bool
     * @throws \Darvin\UserBundle\Form\FormException
     */
    public function handlePasswordResetForm(FormInterface $form, $addFlashMessages = false, $successMessage = null)
    {
        if (!$form->getConfig()->getType()->getInnerType() instanceof PasswordResetType) {
            throw new FormException('Unable to handle form: provided form is not password reset form.');
        }
        if (!$form->isSubmitted()) {
            throw new FormException('Unable to handle password reset form: it is not submitted.');
        }
        if (!$form->isValid()) {
            if ($addFlashMessages) {
                $this->flashNotifier->formError();
            }

            return false;
        }

        /** @var \Darvin\UserBundle\Entity\BaseUser $user */
        $user = $form->getData();

        $this->em->remove($user->getPasswordResetToken());
        $user->setPasswordResetToken(null);
        $this->em->flush();

        $this->userAuthenticator->authenticateUser($user, $this->publicFirewallName);

        if ($addFlashMessages && !empty($successMessage)) {
            $this->flashNotifier->success($successMessage);
        }

        return true;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form             Form
     * @param bool                                  $addFlashMessages Whether to add flash messages
     * @param string                                $successMessage   Success message
     *
     * @return bool
     * @throws \Darvin\UserBundle\Form\FormException
     */
    public function handleRegistrationForm(FormInterface $form, $addFlashMessages = false, $successMessage = null)
    {
        if (!$form->getConfig()->getType()->getInnerType() instanceof RegistrationType) {
            throw new FormException('Unable to handle form: provided form is not registration form.');
        }
        if (!$form->isSubmitted()) {
            throw new FormException('Unable to handle registration form: it is not submitted.');
        }
        if (!$form->isValid()) {
            if ($addFlashMessages) {
                $this->flashNotifier->formError();
            }

            return false;
        }

        /** @var \Darvin\UserBundle\Entity\BaseUser $user */
        $user = $form->getData();

        $this->em->persist($user);
        $this->em->flush($user);

        $this->eventDispatcher->dispatch(Events::POST_REGISTER, new UserEvent($user));

        if ($addFlashMessages && !empty($successMessage)) {
            $this->flashNotifier->success($successMessage);
        }

        return true;
    }
}
