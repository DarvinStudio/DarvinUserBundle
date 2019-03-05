<?php declare(strict_types=1);
/**
 * @author    Lev Semin <lev@darvin-studio.ru>
 * @copyright Copyright (c) 2017-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Class RegistrationConfirmToken
 * @package Darvin\UserBundle\Entity
 *
 * @ORM\Embeddable()
 */
class RegistrationConfirmToken
{
    /**
     * @ORM\Column(unique=true, nullable=true)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $expireAt = null;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return \DateTime
     */
    public function getExpireAt(): ?\DateTime
    {
        return $this->expireAt;
    }

    /**
     * @param \DateTime $expireAt
     */
    public function setExpireAt(?\DateTime $expireAt): void
    {
        $this->expireAt = $expireAt;
    }
}