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

use Darvin\UserBundle\Entity\BaseUser;
use Darvin\UserBundle\Entity\PasswordResetToken;
use Darvin\UserBundle\Event\PasswordResetTokenEvent;
use Darvin\UserBundle\Event\PasswordResetTokenEvents;
use Darvin\UserBundle\Form\Type\PasswordResetToken\RequestType;
use Darvin\UserBundle\PasswordResetToken\PasswordResetTokenFactoryInterface;
use Darvin\UserBundle\Repository\PasswordResetTokenRepository;
use Darvin\UserBundle\Repository\UserRepository;
use Darvin\Utils\Flash\FlashNotifierInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Password reset token form handler
 */
class PasswordResetTokenFormHandler implements PasswordResetTokenFormHandlerInterface
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
     * @var \Darvin\UserBundle\PasswordResetToken\PasswordResetTokenFactoryInterface
     */
    private $passwordResetTokenFactory;

    /**
     * @var \Darvin\UserBundle\Repository\UserRepository
     */
    private $userRepository;

    /**
     * @param \Doctrine\ORM\EntityManager                                              $em                        Entity manager
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface              $eventDispatcher           Event dispatcher
     * @param \Darvin\Utils\Flash\FlashNotifierInterface                               $flashNotifier             Flash notifier
     * @param \Darvin\UserBundle\PasswordResetToken\PasswordResetTokenFactoryInterface $passwordResetTokenFactory Password reset token factory
     * @param \Darvin\UserBundle\Repository\UserRepository                             $userRepository            User entity repository
     */
    public function __construct(
        EntityManager $em,
        EventDispatcherInterface $eventDispatcher,
        FlashNotifierInterface $flashNotifier,
        PasswordResetTokenFactoryInterface $passwordResetTokenFactory,
        UserRepository $userRepository
    ) {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->flashNotifier = $flashNotifier;
        $this->passwordResetTokenFactory = $passwordResetTokenFactory;
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function handleRequestForm(FormInterface $form, bool $addFlashes = false, ?string $successMessage = null): ?PasswordResetToken
    {
        if (!$form->getConfig()->getType()->getInnerType() instanceof RequestType) {
            throw new \InvalidArgumentException('Unable to handle form: provided form is not password reset token request form.');
        }
        if (!$form->isSubmitted()) {
            throw new \LogicException('Unable to handle password reset token request form: it is not submitted.');
        }
        if (!$form->isValid()) {
            if ($addFlashes) {
                $this->flashNotifier->formError();
            }

            return null;
        }

        $data = $form->getData();

        $user = $this->getUser($data['user_email']);

        $existingPasswordResetToken = $this->getPasswordResetTokenRepository()->findOneBy([
            'user' => $user->getId(),
        ]);

        if (!empty($existingPasswordResetToken)) {
            $this->em->remove($existingPasswordResetToken);
            $this->em->flush();
        }

        $passwordResetToken = $this->passwordResetTokenFactory->createPasswordResetToken($user);

        $this->em->persist($passwordResetToken);
        $this->em->flush();

        $this->eventDispatcher->dispatch(new PasswordResetTokenEvent($passwordResetToken), PasswordResetTokenEvents::REQUESTED);

        if ($addFlashes && !empty($successMessage)) {
            $this->flashNotifier->success($successMessage);
        }

        return $passwordResetToken;
    }

    /**
     * @param string|null $email User email
     *
     * @return \Darvin\UserBundle\Entity\BaseUser
     * @throws \InvalidArgumentException
     */
    private function getUser(?string $email): BaseUser
    {
        $user = $this->userRepository->findOneBy([
            'email' => $email,
        ]);

        if (empty($user)) {
            throw new \InvalidArgumentException(sprintf('Unable to find user by email "%s".', $email));
        }

        return $user;
    }

    /**
     * @return \Darvin\UserBundle\Repository\PasswordResetTokenRepository
     */
    private function getPasswordResetTokenRepository(): PasswordResetTokenRepository
    {
        return $this->em->getRepository(PasswordResetToken::class);
    }
}
