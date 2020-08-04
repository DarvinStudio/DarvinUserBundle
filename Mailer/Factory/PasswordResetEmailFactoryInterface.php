<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Mailer\Factory;

use Darvin\MailerBundle\Model\Email;
use Darvin\UserBundle\Entity\PasswordResetToken;

/**
 * Password reset email factory
 */
interface PasswordResetEmailFactoryInterface
{
    /**
     * @param \Darvin\UserBundle\Entity\PasswordResetToken $token Password reset token
     *
     * @return \Darvin\MailerBundle\Model\Email
     * @throws \Darvin\MailerBundle\Factory\Exception\CantCreateEmailException
     */
    public function createRequestedEmail(PasswordResetToken $token): Email;
}
