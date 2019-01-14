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

use Darvin\UserBundle\Configuration\RoleConfiguration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * User role choice form type
 */
class RoleChoiceType extends AbstractType
{
    /**
     * @var \Darvin\UserBundle\Configuration\RoleConfiguration
     */
    private $roleConfig;

    /**
     * @param \Darvin\UserBundle\Configuration\RoleConfiguration $roleConfig Role configuration
     */
    public function __construct(RoleConfiguration $roleConfig)
    {
        $this->roleConfig = $roleConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => $this->buildChoices(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * @return array
     */
    private function buildChoices()
    {
        $choices = [];

        foreach ($this->roleConfig->getRoles() as $role) {
            $choices[$role->getTitle()] = $role->getRole();
        }

        return $choices;
    }
}
