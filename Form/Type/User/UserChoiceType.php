<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2016-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Form\Type\User;

use Darvin\UserBundle\Entity\BaseUser;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * User choice form type
 */
class UserChoiceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'class'         => BaseUser::class,
                'roles'         => [],
                'query_builder' => function (Options $options) {
                    /** @var \Doctrine\ORM\EntityManager $em */
                    $em    = $options['em'];
                    $roles = $options['roles'];

                    /** @var \Darvin\UserBundle\Repository\UserRepository $repository */
                    $repository = $em->getRepository($options['class']);

                    return !empty($roles) ? $repository->createBuilderByRoles($roles) : $repository->createDefaultBuilder();
                },
            ])
            ->setAllowedTypes('roles', 'array');
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return EntityType::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return 'darvin_user_user_choice';
    }
}
