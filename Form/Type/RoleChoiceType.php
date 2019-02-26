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
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Role choice form type
 */
class RoleChoiceType extends AbstractType
{
    /**
     * @var \Darvin\UserBundle\Config\RoleConfigInterface
     */
    private $roleConfig;

    /**
     * @param \Darvin\UserBundle\Config\RoleConfigInterface $roleConfig Role configuration
     */
    public function __construct(RoleConfigInterface $roleConfig)
    {
        $this->roleConfig = $roleConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('choices', $this->buildChoices());
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
     * @return array
     */
    private function buildChoices(): array
    {
        $choices = [];

        foreach ($this->roleConfig->getRoles() as $role) {
            $choices[$role->getTitle()] = $role->getRole();
        }

        return $choices;
    }
}
