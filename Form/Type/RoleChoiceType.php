<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2016-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Form\Type;

use Darvin\UserBundle\Config\RoleConfigInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Role choice form type
 */
class RoleChoiceType extends AbstractType
{
    /**
     * @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var \Darvin\UserBundle\Config\RoleConfigInterface
     */
    private $roleConfig;

    /**
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker Authorization checker
     * @param \Darvin\UserBundle\Config\RoleConfigInterface                                $roleConfig           Role configuration
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker, RoleConfigInterface $roleConfig)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->roleConfig = $roleConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $buildChoices = [$this, 'buildChoices'];

        $resolver
            ->setDefaults([
                'only_grantable' => false,
                'choices'        => function (Options $options) use ($buildChoices) {
                    return $buildChoices($options['only_grantable']);
                },
            ])
            ->setAllowedTypes('only_grantable', 'boolean');
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return 'darvin_user_role_choice';
    }

    /**
     * @param bool $onlyGrantable Whether to return only grantable role choices
     *
     * @return array
     */
    protected function buildChoices(bool $onlyGrantable): array
    {
        $choices = [];

        foreach ($this->getRoles($onlyGrantable) as $role) {
            $choices[$role->getTitle()] = $role->getName();
        }

        return $choices;
    }

    /**
     * @param bool $onlyGrantable Whether to return only grantable roles
     *
     * @return \Darvin\UserBundle\Config\Role[]
     */
    private function getRoles(bool $onlyGrantable): array
    {
        if (!$onlyGrantable) {
            return $this->roleConfig->getRoles();
        }

        $roles = [];

        foreach ($this->roleConfig->getRoles() as $role) {
            if ($this->authorizationChecker->isGranted($role->getName())) {
                foreach ($role->getGrantableRoles() as $grantableRole) {
                    $roles[$grantableRole->getName()] = $grantableRole;
                }
            }
        }

        return $roles;
    }
}
