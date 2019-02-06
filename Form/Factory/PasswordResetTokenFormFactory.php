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

use Darvin\UserBundle\Form\Type\PasswordResetToken\RequestType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Password reset token form factory
 */
class PasswordResetTokenFormFactory implements PasswordResetTokenFormFactoryInterface
{
    /**
     * @var \Symfony\Component\Security\Http\Authentication\AuthenticationUtils
     */
    private $authenticationUtils;

    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    private $genericFormFactory;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $authenticationUtils Authentication utils
     * @param \Symfony\Component\Form\FormFactoryInterface                        $genericFormFactory  Generic form factory
     * @param \Symfony\Component\Routing\RouterInterface                          $router              Router
     */
    public function __construct(AuthenticationUtils $authenticationUtils, FormFactoryInterface $genericFormFactory, RouterInterface $router)
    {
        $this->authenticationUtils = $authenticationUtils;
        $this->genericFormFactory = $genericFormFactory;
        $this->router = $router;
    }

    /**
     * {@inheritDoc}
     */
    public function createRequestForm(array $options = [], string $type = RequestType::class, ?string $name = null): FormInterface
    {
        $data = [
            'user_email' => $this->authenticationUtils->getLastUsername(),
        ];

        if (!isset($options['action'])) {
            $options['action'] = $this->router->generate('darvin_user_password_reset_token_request');
        }
        if (null !== $name) {
            return $this->genericFormFactory->createNamed($name, $type, $data, $options);
        }

        return $this->genericFormFactory->create($type, $data, $options);
    }
}
