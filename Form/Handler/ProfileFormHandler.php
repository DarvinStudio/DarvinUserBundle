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

use Darvin\UserBundle\Form\Type\Profile\ProfileType;
use Darvin\Utils\Flash\FlashNotifierInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormInterface;

/**
 * Profile form handler
 */
class ProfileFormHandler implements ProfileFormHandlerInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Darvin\Utils\Flash\FlashNotifierInterface
     */
    private $flashNotifier;

    /**
     * @param \Doctrine\ORM\EntityManager                $em            Entity manager
     * @param \Darvin\Utils\Flash\FlashNotifierInterface $flashNotifier Flash notifier
     */
    public function __construct(EntityManager $em, FlashNotifierInterface $flashNotifier)
    {
        $this->em = $em;
        $this->flashNotifier = $flashNotifier;
    }

    /**
     * {@inheritDoc}
     */
    public function handleEditForm(FormInterface $form, bool $addFlashes = false, ?string $successMessage = null): bool
    {
        if (!$form->getConfig()->getType()->getInnerType() instanceof ProfileType) {
            throw new \InvalidArgumentException('Unable to handle form: provided form is not profile form.');
        }
        if (!$form->isSubmitted()) {
            throw new \LogicException('Unable to handle profile edit form: it is not submitted.');
        }
        if (!$form->isValid()) {
            if ($addFlashes) {
                $this->flashNotifier->formError();
            }

            return false;
        }

        $this->em->flush();

        if ($addFlashes && !empty($successMessage)) {
            $this->flashNotifier->success($successMessage);
        }

        return true;
    }
}
