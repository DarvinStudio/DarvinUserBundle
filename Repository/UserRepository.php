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

use Darvin\UserBundle\Entity\BaseUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * User repository
 */
class UserRepository extends ServiceEntityRepository
{
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
     * @param string|string[]      $roles     Roles
     * @param string|string[]|null $blacklist Role blacklist
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @throws \InvalidArgumentException
     */
    public function createBuilderByRoles($roles, $blacklist = null): QueryBuilder
    {
        if (empty($roles)) {
            throw new \InvalidArgumentException('No roles provided.');
        }
        if (!is_array($roles)) {
            $roles = [$roles];
        }

        $qb = $this->createDefaultBuilder();

        $rolesExpr = $qb->expr()->orX();

        foreach ($roles as $role) {
            $rolesExpr->add($qb->expr()->like('o.roles', sprintf(':%s', $role)));

            $qb->setParameter($role, '%'.$role.'%');
        }

        $qb->andWhere($rolesExpr);

        if (empty($blacklist)) {
            return $qb;
        }
        if (!is_array($blacklist)) {
            $blacklist = [$blacklist];
        }

        $blacklistExpr = $qb->expr()->orX();

        foreach ($blacklist as $role) {
            $blacklistExpr->add($qb->expr()->notLike('o.roles', sprintf(':%s', $role)));

            $qb->setParameter($role, '%'.$role.'%');
        }

        $qb->andWhere($blacklistExpr);

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
        $qb = $this->createDefaultBuilder();
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
     * @param string|null $credential Credential
     *
     * @return \Darvin\UserBundle\Entity\BaseUser|null
     */
    public function provideUser(?string $credential): ?BaseUser
    {
        if (empty($credential)) {
            return null;
        }

        $qb = $this->createDefaultBuilder();
        $qb
            ->andWhere($qb->expr()->orX(
                'o.username = :credential',
                'o.email    = :credential'
            ))
            ->setParameter('credential', $credential);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param string $email Email
     *
     * @return bool
     */
    public function userExistsAndActive($email)
    {
        $qb = $this->createDefaultBuilder()
            ->select('o.id')
            ->setMaxResults(1);
        $this
            ->addActiveFilter($qb)
            ->addEmailFilter($qb, $email);

        return null !== $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createDefaultBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('o')->addOrderBy('o.email');
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
}
