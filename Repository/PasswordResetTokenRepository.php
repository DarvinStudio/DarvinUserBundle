<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Password reset token entity repository
 */
class PasswordResetTokenRepository extends EntityRepository
{
    /**
     * @param string $base64EncodedId Base64-encoded password reset token ID
     *
     * @return \Darvin\UserBundle\Entity\PasswordResetToken
     */
    public function getOneNonExpiredByBase64EncodedId($base64EncodedId)
    {
        $qb = $this->createDefaultQueryBuilder()
            ->setMaxResults(1);
        $this
            ->addIdFilter($qb, base64_decode($base64EncodedId))
            ->addNonExpiredFilter($qb);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb Query builder
     * @param int                        $id Password reset token ID
     *
     * @return PasswordResetTokenRepository
     */
    private function addIdFilter(QueryBuilder $qb, $id)
    {
        $qb->andWhere('o.id = :id')->setParameter('id', $id);

        return $this;
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb Query builder
     *
     * @return PasswordResetTokenRepository
     */
    private function addNonExpiredFilter(QueryBuilder $qb)
    {
        $qb->andWhere('o.expireAt >= :now')->setParameter('now', new \DateTime());

        return $this;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function createDefaultQueryBuilder()
    {
        return $this->createQueryBuilder('o');
    }
}
