<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Form\Factory;

use Darvin\UserBundle\Entity\BaseUser;
use Darvin\UserBundle\Form\Type\Profile\PasswordChangeType;
use Darvin\UserBundle\Form\Type\Profile\ProfileType;
use Darvin\UserBundle\User\UserManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Profile form factory
 */
class ProfileFormFactory implements ProfileFormFactoryInterface
{
    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    private $genericFormFactory;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @var \Darvin\UserBundle\User\UserManagerInterface
     */
    private $userManager;

    /**
     * @var string
     */
    private $userClass;

    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $genericFormFactory Generic form factory
     * @param \Symfony\Component\Routing\RouterInterface   $router             Router
     * @param \Darvin\UserBundle\User\UserManagerInterface $userManager        User manager
     * @param string                                       $userClass          User entity class
     */
    public function __construct(
        FormFactoryInterface $genericFormFactory,
        RouterInterface $router,
        UserManagerInterface $userManager,
        string $userClass
    ) {
        $this->genericFormFactory = $genericFormFactory;
        $this->router = $router;
        $this->userManager = $userManager;
        $this->userClass = $userClass;
    }

    /**
     * {@inheritDoc}
     */
    public function createEditForm(?BaseUser $user = null, array $options = [], string $type = ProfileType::class, ?string $name = null): FormInterface
    {
        if (null === $user) {
            $user = $this->userManager->getCurrentUser();
        }

        $options = array_merge([
            'data_class' => $this->userClass,
        ], $options);

        if (!isset($options['action'])) {
            $options['action'] = $this->router->generate('darvin_user_profile_edit');
        }
        if (null !== $name) {
            return $this->genericFormFactory->createNamed($name, $type, $user, $options);
        }

        return $this->genericFormFactory->create($type, $user, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function createPasswordChangeForm(?BaseUser $user = null, array $options = [], string $type = PasswordChangeType::class, ?string $name = null): FormInterface
    {
        if (null === $user) {
            $user = $this->userManager->getCurrentUser();
        }

        $options = array_merge([
            'data_class' => $this->userClass,
        ], $options);

        if (!isset($options['action'])) {
            $options['action'] = $this->router->generate('darvin_user_profile_change_password');
        }
        if (null !== $name) {
            return $this->genericFormFactory->createNamed($name, $type, $user, $options);
        }

        return $this->genericFormFactory->create($type, $user, $options);
    }
}
