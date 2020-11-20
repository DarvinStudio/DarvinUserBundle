<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019-2020, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Mailer\Factory;

use Darvin\MailerBundle\Model\Email;
use Darvin\UserBundle\Entity\BaseUser;

/**
 * User email factory
 */
interface UserEmailFactoryInterface
{
    /**
     * @param \Darvin\UserBundle\Entity\BaseUser $user User
     *
     * @return \Darvin\MailerBundle\Model\Email
     * @throws \Darvin\MailerBundle\Factory\Exception\CantCreateEmailException
     */
    public function createConfirmationEmail(BaseUser $user): Email;

    /**
     * @param \Darvin\UserBundle\Entity\BaseUser $user User
     *
     * @return \Darvin\MailerBundle\Model\Email
     * @throws \Darvin\MailerBundle\Factory\Exception\CantCreateEmailException
     */
    public function createRegisteredEmail(BaseUser $user): Email;
}
