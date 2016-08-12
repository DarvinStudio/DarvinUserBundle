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
use Symfony\Component\Translation\TranslatorInterface;

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
     * @var \Symfony\Component\Translation\TranslatorInterface
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
     * @param \Symfony\Component\Translation\TranslatorInterface $translator Translator
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param \Darvin\UserBundle\Entity\BaseUser $user     User
     * @param array                              $options  Options
     * @param string                             $property Property name
     *
     * @return string
     */
    protected function createContent($user, array $options, $property)
    {
        $roles = [];

        foreach ($user->getRoles() as $role) {
            if (BaseUser::ROLE_USER !== $role) {
                $roles[] = $this->roleConfig->hasRole($role)
                    ? $this->translator->trans($this->roleConfig->getRole($role)->getTitle(), [], 'admin')
                    : $role;
            }
        }

        return implode(', ', $roles);
    }

    /**
     * {@inheritdoc}
     */
    protected function getAllowedEntityClasses()
    {
        return [
            BaseUser::BASE_USER_CLASS,
        ];
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
