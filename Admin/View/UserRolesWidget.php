<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2016-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Admin\View;

use Darvin\AdminBundle\Security\Permissions\Permission;
use Darvin\AdminBundle\View\Widget\Widget\AbstractWidget;
use Darvin\UserBundle\Configuration\RoleConfigurationInterface;
use Darvin\UserBundle\Entity\BaseUser;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * User roles admin view widget
 */
class UserRolesWidget extends AbstractWidget
{
    /**
     * @var \Darvin\UserBundle\Configuration\RoleConfigurationInterface
     */
    private $roleConfig;

    /**
     * @var \Symfony\Contracts\Translation\TranslatorInterface
     */
    private $translator;

    /**
     * @param \Darvin\UserBundle\Configuration\RoleConfigurationInterface $roleConfig Role configuration
     * @param \Symfony\Contracts\Translation\TranslatorInterface          $translator Translator
     */
    public function __construct(RoleConfigurationInterface $roleConfig, TranslatorInterface $translator)
    {
        $this->roleConfig = $roleConfig;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    protected function createContent($entity, array $options): ?string
    {
        $parts = [];
        $roles = $this->getPropertyValue($entity, $options['property']);

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
    protected function getRequiredPermissions(): iterable
    {
        yield Permission::VIEW;
    }
}
