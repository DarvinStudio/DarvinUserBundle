<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Form\Handler;

use Darvin\UserBundle\Event\UserEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Security form handler
 */
interface SecurityFormHandlerInterface
{
    /**
     * @param \Symfony\Component\Form\FormInterface $form           Form
     * @param bool                                  $addFlashes     Whether to add flash messages
     * @param string|null                           $successMessage Success message
     *
     * @return bool
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function handlePasswordResetForm(FormInterface $form, bool $addFlashes = false, ?string $successMessage = null): bool;

    /**
     * @param \Symfony\Component\Form\FormInterface     $form                 Form
     * @param \Symfony\Component\HttpFoundation\Request $request              Request
     * @param string|null                               $successMessage       Success message
     * @param bool                                      $confirmationRequired Is registration confirmation required
     *
     * @return \Darvin\UserBundle\Event\UserEvent|null
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function handleRegistrationForm(FormInterface $form, Request $request, ?string $successMessage = null, bool $confirmationRequired = false): ?UserEvent;
}
