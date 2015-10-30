<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Form\Factory\Security;

use Darvin\UserBundle\Entity\User;
use Darvin\UserBundle\Form\Type\Security\RegistrationType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Security form factory
 */
class SecurityFormFactory
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
     * @param \Symfony\Component\Form\FormFactoryInterface $formFactory Form factory
     * @param \Symfony\Component\Routing\RouterInterface   $router      Router
     */
    public function __construct(FormFactoryInterface $formFactory, RouterInterface $router)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    /**
     * @param \Darvin\UserBundle\Entity\User $user User
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createRegistrationForm(User $user = null)
    {
        if (empty($user)) {
            $user = new User();
        }

        return $this->formFactory->create(new RegistrationType(), $user, array(
            'action' => $this->router->generate('darvin_user_security_register'),
        ));
    }
}
