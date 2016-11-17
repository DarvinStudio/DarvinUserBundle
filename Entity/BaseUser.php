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
use Symfony\Bridge\Doctrine\Validator\Constraints as Doctrine;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Base user
 *
 * @ORM\Entity(repositoryClass="Darvin\UserBundle\Repository\UserRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\Table(name="user")
 * @Doctrine\UniqueEntity(fields={"email"})
 */
class BaseUser implements \Serializable, AdvancedUserInterface
{
    const BASE_USER_CLASS = __CLASS__;

    const ROLE_USER = 'ROLE_USER';

    /**
     * @var int
     *
     * @ORM\Column(type="integer", unique=true)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Id
     */
    protected $id;

    /**
     * @var \Darvin\UserBundle\Entity\PasswordResetToken
     *
     * @ORM\OneToOne(targetEntity="Darvin\UserBundle\Entity\PasswordResetToken", mappedBy="user", cascade={"remove"})
     */
    protected $passwordResetToken;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $locked;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $enabled;

    /**
     * @var array
     *
     * @ORM\Column(type="simple_array", nullable=true)
     */
    protected $roles;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $salt;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     *
     * @Assert\Email
     * @Assert\NotBlank
     */
    protected $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"AdminNew", "PasswordReset", "Register"})
     */
    protected $plainPassword;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->locked = false;
        $this->enabled = true;
        $this->roles = [];
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->email,
            $this->password,
            $this->salt,
            $this->roles,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password,
            $this->salt,
            $this->roles
        ) = unserialize($serialized);
    }

    /**
     * @return BaseUser
     */
    public function generateRandomPlainPassword()
    {
        $this->plainPassword = hash('sha512', uniqid(mt_rand(), true));

        return $this;
    }

    /**
     * @return BaseUser
     */
    public function updateSalt()
    {
        $this->salt = hash('sha512', uniqid(mt_rand(), true));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked()
    {
        return !$this->locked;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * @param boolean $enabled enabled
     *
     * @return BaseUser
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param array $roles roles
     *
     * @return BaseUser
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        $roles = $this->roles;
        $roles[] = self::ROLE_USER;

        return $roles;
    }

    /**
     * @param string $password password
     *
     * @return BaseUser
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $salt salt
     *
     * @return BaseUser
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \Darvin\UserBundle\Entity\PasswordResetToken
     */
    public function getPasswordResetToken()
    {
        return $this->passwordResetToken;
    }

    /**
     * @param \Darvin\UserBundle\Entity\PasswordResetToken $passwordResetToken passwordResetToken
     *
     * @return BaseUser
     */
    public function setPasswordResetToken(PasswordResetToken $passwordResetToken = null)
    {
        $this->passwordResetToken = $passwordResetToken;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isLocked()
    {
        return $this->locked;
    }

    /**
     * @param boolean $locked locked
     *
     * @return BaseUser
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email email
     *
     * @return BaseUser
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt updatedAt
     *
     * @return BaseUser
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword plainPassword
     *
     * @return BaseUser
     */
    public function setPlainPassword($plainPassword)
    {
        $this->refreshUpdatedAt();

        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return BaseUser
     */
    protected function refreshUpdatedAt()
    {
        $this->updatedAt = new \DateTime();

        return $this;
    }
}
