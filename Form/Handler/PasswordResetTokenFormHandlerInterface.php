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

use Darvin\UserBundle\Entity\PasswordResetToken;
use Symfony\Component\Form\FormInterface;

/**
 * Password reset token form handler
 */
interface PasswordResetTokenFormHandlerInterface
{
    /**
     * @param \Symfony\Component\Form\FormInterface $form           Form
     * @param bool                                  $addFlashes     Whether to add flash messages
     * @param string|null                           $successMessage Success message
     *
     * @return \Darvin\UserBundle\Entity\PasswordResetToken|null
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function handleRequestForm(FormInterface $form, bool $addFlashes = false, ?string $successMessage = null): ?PasswordResetToken;
}
