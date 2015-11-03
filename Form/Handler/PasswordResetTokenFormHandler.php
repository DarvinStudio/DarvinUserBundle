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

use Darvin\UserBundle\Entity\User;
use Darvin\UserBundle\Event\Events;
use Darvin\UserBundle\Event\PasswordResetTokenEvent;
use Darvin\UserBundle\Form\FormException;
use Darvin\UserBundle\Form\Type\PasswordResetToken\RequestType;
use Darvin\UserBundle\PasswordResetToken\PasswordResetTokenFactory;
use Darvin\Utils\Flash\FlashNotifierInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Password reset token form handler
 */
class PasswordResetTokenFormHandler
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
     * @var \Darvin\UserBundle\PasswordResetToken\PasswordResetTokenFactory
     */
    private $passwordResetTokenFactory;

    /**
     * @param \Doctrine\ORM\EntityManager                                     $em                        Entity manager
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface     $eventDispatcher           Event dispatcher
     * @param \Darvin\Utils\Flash\FlashNotifierInterface                      $flashNotifier             Flash notifier
     * @param \Darvin\UserBundle\PasswordResetToken\PasswordResetTokenFactory $passwordResetTokenFactory Password reset token factory
     */
    public function __construct(
        EntityManager $em,
        EventDispatcherInterface $eventDispatcher,
        FlashNotifierInterface $flashNotifier,
        PasswordResetTokenFactory $passwordResetTokenFactory
    ) {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->flashNotifier = $flashNotifier;
        $this->passwordResetTokenFactory = $passwordResetTokenFactory;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form             Form
     * @param bool                                  $addFlashMessages Whether to add flash messages
     * @param string                                $successMessage   Success message
     *
     * @return \Darvin\UserBundle\Entity\PasswordResetToken
     * @throws \Darvin\UserBundle\Form\FormException
     */
    public function handleRequestForm(FormInterface $form, $addFlashMessages = false, $successMessage = null)
    {
        if (!$form->getConfig()->getType()->getInnerType() instanceof RequestType) {
            throw new FormException('Unable to handle form: provided form is not password reset token request form.');
        }
        if (!$form->isSubmitted()) {
            throw new FormException('Unable to handle password reset token request form: it is not submitted.');
        }
        if (!$form->isValid()) {
            if ($addFlashMessages) {
                $this->flashNotifier->formError();
            }

            return null;
        }

        $data = $form->getData();

        $user = $this->getUser($data['user_email']);

        $existingPasswordResetToken = $user->getPasswordResetToken();

        if (!empty($existingPasswordResetToken)) {
            $this->em->remove($existingPasswordResetToken);
            $user->setPasswordResetToken(null);
            $this->em->flush();
        }

        $passwordResetToken = $this->passwordResetTokenFactory->createPasswordResetToken($user);
        $this->em->persist($passwordResetToken);
        $this->em->flush();

        $this->eventDispatcher->dispatch(
            Events::POST_PASSWORD_RESET_TOKEN_REQUEST,
            new PasswordResetTokenEvent($passwordResetToken)
        );

        if ($addFlashMessages && !empty($successMessage)) {
            $this->flashNotifier->success($successMessage);
        }

        return $passwordResetToken;
    }

    /**
     * @param string $email User email
     *
     * @return \Darvin\UserBundle\Entity\User
     * @throws \Darvin\UserBundle\Form\FormException
     */
    private function getUser($email)
    {
        $user = $this->getUserRepository()->findOneBy(array(
            'email' => $email,
        ));

        if (empty($user)) {
            throw new FormException(sprintf('Unable to find user by email "%s".', $email));
        }

        return $user;
    }

    /**
     * @return \Darvin\UserBundle\Repository\UserRepository
     */
    private function getUserRepository()
    {
        return $this->em->getRepository(User::USER_CLASS);
    }
}
