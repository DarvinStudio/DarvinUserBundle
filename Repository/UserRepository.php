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

use Darvin\UserBundle\Entity\BaseUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * User repository
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getAllBuilder()
    {
        return $this->createDefaultQueryBuilder();
    }

    /**
     * Find user by registration confirmation code
     *
     * @param $code
     * @return BaseUser|null
     */
    public function getByRegistrationToken($code)
    {
        $builder = $this->createQueryBuilder('u');

        return $builder
            ->where('u.registrationConfirmToken.id=:code')
            ->andWhere($builder->expr()->isNotNull('u.registrationConfirmToken.id'))
            ->setParameter('code', $code)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param string[] $roles      Roles
     * @param string   $exceptRole Except role
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @throws \InvalidArgumentException
     */
    public function getByRolesBuilder(array $roles, $exceptRole = null)
    {
        if (empty($roles)) {
            throw new \InvalidArgumentException('Array of roles is empty.');
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
     * @param string     $username Username
     * @param mixed|null $userId   User ID
     *
     * @return string[]
     */
    public function getSimilarUsernames(string $username, $userId = null): array
    {
        $qb = $this->createDefaultQueryBuilder();
        $qb
            ->select('o.username')
            ->andWhere($qb->expr()->like('o.username', ':username'))
            ->setParameter('username', $username.'%');

        if (!empty($userId)) {
            $this->addNotIdFilter($qb, $userId);
        }

        return array_column($qb->getQuery()->getScalarResult(), 'username');
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
    protected function addActiveFilter(QueryBuilder $qb)
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
    protected function addEnabledFilter(QueryBuilder $qb)
    {
        $qb->andWhere('o.enabled = :enabled')->setParameter('enabled', true);

        return $this;
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb Query builder
     *
     * @return UserRepository
     */
    protected function addNonLockedFilter(QueryBuilder $qb)
    {
        $qb->andWhere('o.locked = :locked')->setParameter('locked', false);

        return $this;
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb Query builder
     * @param mixed                      $id ID
     *
     * @return UserRepository
     */
    protected function addNotIdFilter(QueryBuilder $qb, $id): UserRepository
    {
        $qb->andWhere('o.id != :id')->setParameter('id', $id);

        return $this;
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb    Query builder
     * @param string                     $email Email
     *
     * @return UserRepository
     */
    protected function addEmailFilter(QueryBuilder $qb, $email)
    {
        $qb->andWhere('o.email = :email')->setParameter('email', $email);

        return $this;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function createDefaultQueryBuilder()
    {
        return $this->createQueryBuilder('o')->addOrderBy('o.email');
    }
}
