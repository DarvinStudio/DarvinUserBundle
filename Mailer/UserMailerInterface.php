<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Mailer;

use Darvin\UserBundle\Entity\BaseUser;

/**
 * User mailer
 */
interface UserMailerInterface
{
    /**
     * @param \Darvin\UserBundle\Entity\BaseUser $user     User
     * @param string                             $subject  Subject
     * @param string                             $template Template
     *
     * @return int
     */
    public function sendConfirmationCodeEmails(
        BaseUser $user,
        string $subject = 'email.confirmation.subject',
        string $template = '@DarvinUser/email/user/confirmation_code.html.twig'
    ): int;

    /**
     * @param \Darvin\UserBundle\Entity\BaseUser $user     User
     * @param string                             $subject  Subject
     * @param string                             $template Template
     *
     * @return int
     */
    public function sendRegisteredEmails(
        BaseUser $user,
        string $subject = 'email.registered.subject',
        string $template = '@DarvinUser/email/user/created.html.twig'
    ): int;
}
