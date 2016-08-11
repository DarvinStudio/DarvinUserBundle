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
 * User repository
 */
class UserRepository extends EntityRepository
{
    /**
     * @param string[] $roles      Roles
     * @param string   $exceptRole Except role
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @throws \Darvin\UserBundle\Repository\RepositoryException
     */
    public function getByRolesBuilder(array $roles, $exceptRole = null)
    {
        if (empty($roles)) {
            throw new RepositoryException('Roles array must not be empty.');
        }

        $qb = $this->createDefaultQueryBuilder();

        $rolesExpr = implode(' OR ', array_map(function ($role) {
            return 'o.roles LIKE :role_'.$role;
        }, $roles));
        $qb->andWhere($rolesExpr);

        foreach ($roles as $role) {
            $qb->setParameter('role_'.$role, '%'.$role.'%');
        }
        if (!empty($exceptRole)) {
            $qb->andWhere($qb->expr()->notLike('o.roles', ':except_role'))->setParameter('except_role', '%'.$exceptRole.'%');
        }

        return $qb;
    }

    /**
     * @param string $email Email
     *
     * @return bool
     */
    public function userExistsAndActive($email)
    {
        $qb = $this->createDefaultQueryBuilder()
            ->select('o.id')
            ->setMaxResults(1);
        $this
            ->addActiveFilter($qb)
            ->addEmailFilter($qb, $email);

        return null !== $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb Query builder
     *
     * @return UserRepository
     */
    private function addActiveFilter(QueryBuilder $qb)
    {
        return $this
            ->addEnabledFilter($qb)
            ->addNonLockedFilter($qb);
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb Query builder
     *
     * @return UserRepository
     */
    private function addEnabledFilter(QueryBuilder $qb)
    {
        $qb->andWhere('o.enabled = :enabled')->setParameter('enabled', true);

        return $this;
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb Query builder
     *
     * @return UserRepository
     */
    private function addNonLockedFilter(QueryBuilder $qb)
    {
        $qb->andWhere('o.locked = :locked')->setParameter('locked', false);

        return $this;
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
