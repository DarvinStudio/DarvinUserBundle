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

use Darvin\UserBundle\Form\FormException;
use Darvin\UserBundle\Form\Type\Security\RegistrationType;
use Darvin\Utils\Flash\FlashNotifierInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Security form handler
 */
class SecurityFormHandler
{
    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    private $authTokenStorage;

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
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $authTokenStorage Authentication token storage
     * @param \Doctrine\ORM\EntityManager                                                         $em               Entity manager
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface                         $eventDispatcher  Event dispatcher
     * @param \Darvin\Utils\Flash\FlashNotifierInterface                                          $flashNotifier    Flash notifier
     */
    public function __construct(
        TokenStorageInterface $authTokenStorage,
        EntityManager $em,
        EventDispatcherInterface $eventDispatcher,
        FlashNotifierInterface $flashNotifier
    ) {
        $this->authTokenStorage = $authTokenStorage;
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->flashNotifier = $flashNotifier;
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
        if ($addFlashMessages && !empty($successMessage)) {
            $this->flashNotifier->success($successMessage);
        }

        return true;
    }
}
