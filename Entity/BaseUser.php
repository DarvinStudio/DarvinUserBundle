<?php
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
use Symfony\Bridge\Doctrine\Validator\Constraints as Doctrine;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Base user
 *
 * @ORM\Entity(repositoryClass="Darvin\UserBundle\Repository\UserRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\Table(name="user")
 *
 * @Doctrine\UniqueEntity(fields={"username"})
 * @Doctrine\UniqueEntity(fields={"email"})
 */
class BaseUser implements \Serializable, AdvancedUserInterface, EquatableInterface
{
    public const ROLE_USER = 'ROLE_USER';

    /**
     * @var int
     *
     * @ORM\Column(type="integer", unique=true)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Id
     */
    protected $id;

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
     * @ORM\Column
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column
     */
    protected $salt;

    /**
     * @var string
     *
     * @ORM\Column(unique=true)
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(unique=true)
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
     * @var RegistrationConfirmToken
     *
     * @ORM\Embedded(class="Darvin\UserBundle\Entity\RegistrationConfirmToken")
     */
    protected $registrationConfirmToken;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->locked = false;
        $this->enabled = true;
        $this->roles = [];
        $this->updatedAt = new \DateTime();
        $this->registrationConfirmToken = new RegistrationConfirmToken();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function isEqualTo(UserInterface $user)
    {
        return $this->username === $user->getUsername();
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
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
            $this->username,
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
     * @return bool
     */
    public function isActive()
    {
        return $this->enabled && !$this->locked;
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
        return array_unique(array_merge([self::ROLE_USER], $this->roles));
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
     * @param string $username username
     *
     * @return BaseUser
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
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
        $this->plainPassword = $plainPassword;

        $this->updatedAt = new \DateTime();

        return $this;
    }

    /**
     * @return RegistrationConfirmToken
     */
    public function getRegistrationConfirmToken()
    {
        return $this->registrationConfirmToken;
    }
}
