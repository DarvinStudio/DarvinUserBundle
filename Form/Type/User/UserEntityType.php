<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2016, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Form\Type\User;

use Darvin\UserBundle\Entity\BaseUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * User entity form type
 */
class UserEntityType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'class' => BaseUser::BASE_USER_CLASS,
                'roles' => [],
            ])
            ->setAllowedTypes('roles', 'array')
            ->setNormalizer('query_builder', function (Options $options) {
                /** @var \Doctrine\ORM\EntityManager $em */
                $em = $options['em'];
                /** @var \Darvin\UserBundle\Repository\UserRepository $repository */
                $repository = $em->getRepository($options['class']);
                $roles = $options['roles'];

                $qb = !empty($roles) ? $repository->getByRolesBuilder($roles) : $repository->getAllBuilder();
                $qb
                    ->addSelect('password_reset_token')
                    ->leftJoin('o.passwordResetToken', 'password_reset_token');

                return $qb;
            });
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'Symfony\Bridge\Doctrine\Form\Type\EntityType';
    }
}
