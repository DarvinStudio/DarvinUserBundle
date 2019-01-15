<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2016, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Admin\View;

use Darvin\AdminBundle\Security\Permissions\Permission;
use Darvin\AdminBundle\View\Widget\Widget\AbstractWidget;
use Darvin\UserBundle\Configuration\RoleConfiguration;
use Darvin\UserBundle\Entity\BaseUser;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * User roles admin view widget
 */
class UserRolesWidget extends AbstractWidget
{
    /**
     * @var \Darvin\UserBundle\Configuration\RoleConfiguration
     */
    private $roleConfig;

    /**
     * @var \Symfony\Contracts\Translation\TranslatorInterface
     */
    private $translator;

    /**
     * @param \Darvin\UserBundle\Configuration\RoleConfiguration $roleConfig Role configuration
     */
    public function setRoleConfig(RoleConfiguration $roleConfig)
    {
        $this->roleConfig = $roleConfig;
    }

    /**
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator Translator
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    protected function createContent($entity, array $options, $property)
    {
        $parts = [];
        $roles = $this->getPropertyValue($entity, $property);

        if (!is_array($roles) && !$roles instanceof \Traversable) {
            $roles = [$roles];
        }
        foreach ($roles as $role) {
            $role = (string)$role;

            if ($entity instanceof BaseUser && BaseUser::ROLE_USER === $role) {
                continue;
            }

            $parts[] = $this->roleConfig->hasRole($role)
                ? $this->translator->trans($this->roleConfig->getRole($role)->getTitle(), [], 'admin')
                : $role;
        }

        return implode(', ', $parts);
    }

    /**
     * {@inheritdoc}
     */
    protected function getRequiredPermissions()
    {
        return [
            Permission::VIEW,
        ];
    }
}
