<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
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
 */
class PasswordResetToken
{
    const PASSWORD_RESET_TOKEN_CLASS = __CLASS__;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Id
     */
    private $id;

    /**
     * @var \Darvin\UserBundle\Entity\BaseUser
     *
     * @ORM\OneToOne(targetEntity="Darvin\UserBundle\Entity\BaseUser", inversedBy="passwordResetToken")
     * @ORM\JoinColumn(nullable=false)
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
    public function __construct(BaseUser $user = null, \DateTime $expireAt = null)
    {
        $this->user = $user;
        $this->expireAt = $expireAt;
    }

    /**
     * @return string
     */
    public function getBase64EncodedId()
    {
        return base64_encode($this->id);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \Darvin\UserBundle\Entity\BaseUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \Darvin\UserBundle\Entity\BaseUser $user user
     *
     * @return PasswordResetToken
     */
    public function setUser(BaseUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpireAt()
    {
        return $this->expireAt;
    }

    /**
     * @param \DateTime $expireAt expireAt
     *
     * @return PasswordResetToken
     */
    public function setExpireAt(\DateTime $expireAt)
    {
        $this->expireAt = $expireAt;

        return $this;
    }
}
