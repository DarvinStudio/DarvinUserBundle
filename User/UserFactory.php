<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\User;

use Darvin\UserBundle\Entity\BaseUser;

/**
 * User factory
 */
class UserFactory implements UserFactoryInterface
{
    /**
     * @var string
     */
    private $userClass;

    /**
     * @param string $userClass User entity class
     */
    public function __construct(string $userClass)
    {
        $this->userClass = $userClass;
    }

    /**
     * {@inheritDoc}
     */
    public function createUser(): BaseUser
    {
        $class = $this->userClass;

        return new $class();
    }
}
