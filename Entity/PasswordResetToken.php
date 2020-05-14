<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Password reset token
 *
 * @ORM\Entity(repositoryClass="Darvin\UserBundle\Repository\PasswordResetTokenRepository")
 * @ORM\Table(name="user_password_reset_token")
 */
class PasswordResetToken
{
    /**
     * @var string
     *
     * @ORM\Column(length=36, unique=true)
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Id
     */
    private $id;

    /**
     * @var \Darvin\UserBundle\Entity\BaseUser
     *
     * @ORM\OneToOne(targetEntity="Darvin\UserBundle\Entity\BaseUser")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $expireAt;

    /**
     * @param \Darvin\UserBundle\Entity\BaseUser $user     User
     * @param \DateTime                          $expireAt Expire at
     */
    public function __construct(BaseUser $user, \DateTime $expireAt)
    {
        $this->user = $user;
        $this->expireAt = $expireAt;
    }

    /**
     * @return string
     */
    public function getBase64EncodedId(): string
    {
        return base64_encode($this->id);
    }

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return \Darvin\UserBundle\Entity\BaseUser
     */
    public function getUser(): BaseUser
    {
        return $this->user;
    }

    /**
     * @param \Darvin\UserBundle\Entity\BaseUser $user user
     *
     * @return PasswordResetToken
     */
    public function setUser(BaseUser $user): PasswordResetToken
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpireAt(): \DateTime
    {
        return $this->expireAt;
    }

    /**
     * @param \DateTime $expireAt expireAt
     *
     * @return PasswordResetToken
     */
    public function setExpireAt(\DateTime $expireAt): PasswordResetToken
    {
        $this->expireAt = $expireAt;

        return $this;
    }
}
