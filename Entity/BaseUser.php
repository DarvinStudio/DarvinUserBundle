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
use Symfony\Bridge\Doctrine\Validator\Constraints as Doctrine;
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
class BaseUser implements \Serializable, UserInterface, EquatableInterface
{
    public const ROLE_USER = 'ROLE_USER';

    /**
     * @var int
     *
     * @ORM\Column(type="integer", unique=true)
     * @ORM\GeneratedValue
     * @ORM\Id
     */
    protected $id;

    /**
     * @var \Darvin\UserBundle\Entity\RegistrationConfirmToken
     *
     * @ORM\Embedded(class="Darvin\UserBundle\Entity\RegistrationConfirmToken")
     */
    protected $registrationConfirmToken;

    /**
     * @var bool
     *
     * @ORM\Column(type="bool")
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
     * @Assert\NotBlank(groups={"AdminNew", "PasswordChange", "PasswordReset", "Register"})
     */
    protected $plainPassword;

    /**
     * Base user constructor.
     */
    public function __construct()
    {
        $this->registrationConfirmToken = new RegistrationConfirmToken();
        $this->enabled                  = true;
        $this->roles                    = [];
        $this->updatedAt                = new \DateTime();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    /**
     * {@inheritdoc}
     */
    public function isEqualTo(UserInterface $user): bool
    {
        return $this->username === $user->getUsername();
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
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
    public function unserialize($serialized): void
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
     * @return array
     */
    public function getData(): array
    {
        return [
            'email' => $this->email,
        ];
    }

    /**
     * @return BaseUser
     */
    public function generateRandomPlainPassword(): BaseUser
    {
        $this->plainPassword = hash('sha512', uniqid((string)mt_rand(), true));

        return $this;
    }

    /**
     * @return BaseUser
     */
    public function updateSalt(): BaseUser
    {
        $this->salt = hash('sha512', uniqid((string)mt_rand(), true));

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \Darvin\UserBundle\Entity\RegistrationConfirmToken
     */
    public function getRegistrationConfirmToken(): RegistrationConfirmToken
    {
        return $this->registrationConfirmToken;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled enabled
     *
     * @return BaseUser
     */
    public function setEnabled(bool $enabled): BaseUser
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        return array_unique(array_merge([self::ROLE_USER], $this->roles));
    }

    /**
     * @param array $roles roles
     *
     * @return BaseUser
     */
    public function setRoles(array $roles): BaseUser
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password password
     *
     * @return BaseUser
     */
    public function setPassword(?string $password): BaseUser
    {
        $this->password = $password;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        return $this->salt;
    }

    /**
     * @param string $salt salt
     *
     * @return BaseUser
     */
    public function setSalt(?string $salt): BaseUser
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username username
     *
     * @return BaseUser
     */
    public function setUsername(?string $username): BaseUser
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email email
     *
     * @return BaseUser
     */
    public function setEmail(?string $email): BaseUser
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt updatedAt
     *
     * @return BaseUser
     */
    public function setUpdatedAt(\DateTime $updatedAt): BaseUser
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword plainPassword
     *
     * @return BaseUser
     */
    public function setPlainPassword(?string $plainPassword): BaseUser
    {
        $this->plainPassword = $plainPassword;

        $this->updatedAt = new \DateTime();

        return $this;
    }
}
