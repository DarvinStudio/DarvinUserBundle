<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Repository;

use Darvin\UserBundle\Entity\PasswordResetToken;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Password reset token entity repository
 */
class PasswordResetTokenRepository extends EntityRepository
{
    /**
     * @param string|null $base64EncodedId Base64-encoded password reset token ID
     *
     * @return \Darvin\UserBundle\Entity\PasswordResetToken|null
     */
    public function getOneNonExpiredByBase64EncodedId(?string $base64EncodedId): ?PasswordResetToken
    {
        if (null === $base64EncodedId) {
            return null;
        }

        $qb = $this->createDefaultBuilder()
            ->setMaxResults(1);
        $this
            ->addIdFilter($qb, base64_decode($base64EncodedId))
            ->addNonExpiredFilter($qb);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb Query builder
     * @param mixed                      $id Password reset token ID
     *
     * @return PasswordResetTokenRepository
     */
    private function addIdFilter(QueryBuilder $qb, $id): PasswordResetTokenRepository
    {
        $qb->andWhere('o.id = :id')->setParameter('id', $id);

        return $this;
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb Query builder
     *
     * @return PasswordResetTokenRepository
     */
    private function addNonExpiredFilter(QueryBuilder $qb): PasswordResetTokenRepository
    {
        $qb->andWhere('o.expireAt >= :now')->setParameter('now', new \DateTime());

        return $this;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function createDefaultBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('o');
    }
}
