<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Form\Factory;

use Darvin\UserBundle\Entity\User;
use Darvin\UserBundle\Form\Type\User\ProfileType;
use Darvin\UserBundle\User\UserManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * User form factory
 */
class UserFormFactory
{
    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @var \Darvin\UserBundle\User\UserManagerInterface
     */
    private $userManager;

    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $formFactory Form factory
     * @param \Symfony\Component\Routing\RouterInterface   $router      Router
     * @param \Darvin\UserBundle\User\UserManagerInterface $userManager User manager
     */
    public function __construct(FormFactoryInterface $formFactory, RouterInterface $router, UserManagerInterface $userManager)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->userManager = $userManager;
    }

    /**
     * @param \Darvin\UserBundle\Entity\User $user User
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProfileForm(User $user = null)
    {
        if (empty($user)) {
            $user = $this->userManager->getCurrentUser();
        }

        return $this->formFactory->create(new ProfileType(), $user, array(
            'action' => $this->router->generate('darvin_user_user_profile'),
        ));
    }
}
