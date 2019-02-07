<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Form\Handler;

use Darvin\UserBundle\Configuration\RoleConfigurationInterface;
use Darvin\UserBundle\Entity\PasswordResetToken;
use Darvin\UserBundle\Event\SecurityEvents;
use Darvin\UserBundle\Event\UserEvent;
use Darvin\UserBundle\Form\Type\Security\PasswordResetType;
use Darvin\UserBundle\Form\Type\Security\RegistrationType;
use Darvin\UserBundle\Repository\PasswordResetTokenRepository;
use Darvin\UserBundle\Security\UserAuthenticatorInterface;
use Darvin\Utils\Flash\FlashNotifierInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Security form handler
 */
class SecurityFormHandler implements SecurityFormHandlerInterface
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
     * @var \Darvin\UserBundle\Configuration\RoleConfigurationInterface
     */
    private $roleConfig;

    /**
     * @var \Darvin\UserBundle\Security\UserAuthenticatorInterface
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
     * @param \Darvin\UserBundle\Configuration\RoleConfigurationInterface $roleConfig         Role configuration
     * @param \Darvin\UserBundle\Security\UserAuthenticatorInterface      $userAuthenticator  User authenticator
     * @param string                                                      $publicFirewallName Public firewall name
     */
    public function __construct(
        EntityManager $em,
        EventDispatcherInterface $eventDispatcher,
        FlashNotifierInterface $flashNotifier,
        RoleConfigurationInterface $roleConfig,
        UserAuthenticatorInterface $userAuthenticator,
        string $publicFirewallName
    ) {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->flashNotifier = $flashNotifier;
        $this->roleConfig = $roleConfig;
        $this->userAuthenticator = $userAuthenticator;
        $this->publicFirewallName = $publicFirewallName;
    }

    /**
     * {@inheritDoc}
     */
    public function handlePasswordResetForm(FormInterface $form, bool $addFlashes = false, ?string $successMessage = null): bool
    {
        if (!$form->getConfig()->getType()->getInnerType() instanceof PasswordResetType) {
            throw new \InvalidArgumentException('Unable to handle form: provided form is not password reset form.');
        }
        if (!$form->isSubmitted()) {
            throw new \LogicException('Unable to handle password reset form: it is not submitted.');
        }
        if (!$form->isValid()) {
            if ($addFlashes) {
                $this->flashNotifier->formError();
            }

            return false;
        }

        /** @var \Darvin\UserBundle\Entity\BaseUser $user */
        $user = $form->getData();

        $passwordResetToken = $this->getPasswordResetTokenRepository()->findOneBy([
            'user' => $user->getId(),
        ]);

        if (!empty($passwordResetToken)) {
            $this->em->remove($passwordResetToken);
            $this->em->flush();
        }

        $this->userAuthenticator->authenticateUser($user, $this->publicFirewallName);

        if ($addFlashes && !empty($successMessage)) {
            $this->flashNotifier->success($successMessage);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function handleRegistrationForm(FormInterface $form, bool $addFlashes = false, ?string $successMessage = null, bool $confirmationRequired = false): bool
    {
        if (!$form->getConfig()->getType()->getInnerType() instanceof RegistrationType) {
            throw new \InvalidArgumentException('Unable to handle form: provided form is not registration form.');
        }
        if (!$form->isSubmitted()) {
            throw new \LogicException('Unable to handle registration form: it is not submitted.');
        }
        if (!$form->isValid()) {
            if ($addFlashes) {
                $this->flashNotifier->formError();
            }

            return false;
        }

        /** @var \Darvin\UserBundle\Entity\BaseUser $user */
        $user = $form->getData();

        foreach ($user->getRoles() as $role) {
            if ($this->roleConfig->hasRole($role) && $this->roleConfig->getRole($role)->isModerated()) {
                $user->setEnabled(false);

                break;
            }
        }
        if ($confirmationRequired) {
            $user->setEnabled(false);
            $user->getRegistrationConfirmToken()->setId(md5(uniqid()));
        }

        $this->em->persist($user);
        $this->em->flush($user);

        $this->eventDispatcher->dispatch(SecurityEvents::REGISTERED, new UserEvent($user));

        if ($addFlashes && !empty($successMessage)) {
            $this->flashNotifier->success($successMessage);
        }

        return true;
    }

    /**
     * @return \Darvin\UserBundle\Repository\PasswordResetTokenRepository
     */
    private function getPasswordResetTokenRepository(): PasswordResetTokenRepository
    {
        return $this->em->getRepository(PasswordResetToken::class);
    }
}
