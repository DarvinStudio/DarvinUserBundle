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

use Darvin\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * User repository
 */
class UserRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getNotSuperadminsBuilder()
    {
        $qb = $this->createDefaultQueryBuilder();

        return $qb
            ->andWhere($qb->expr()->like('o.roles', ':role_admin'))
            ->setParameter('role_admin', '%'.User::ROLE_ADMIN.'%')
            ->andWhere($qb->expr()->notLike('o.roles', ':role_superadmin'))
            ->setParameter('role_superadmin', '%'.User::ROLE_SUPERADMIN.'%');
    }

    /**
     * @param string $email Email
     *
     * @return bool
     */
    public function userExists($email)
    {
        $qb = $this->createDefaultQueryBuilder()
            ->select('o.id')
            ->setMaxResults(1);
        $this->addEmailFilter($qb, $email);

        return null !== $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb    Query builder
     * @param string                     $email Email
     *
     * @return UserRepository
     */
    private function addEmailFilter(QueryBuilder $qb, $email)
    {
        $qb->andWhere('o.email = :email')->setParameter('email', $email);

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
