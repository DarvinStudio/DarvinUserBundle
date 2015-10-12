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

/**
 * Security form factory
 */
interface SecurityFormFactoryInterface
{
    /**
     * @param string $actionRoute Action route
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createLoginForm($actionRoute = 'darvin_user_security_login_check');
}
