<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Form\Factory;

use Darvin\UserBundle\Entity\BaseUser;
use Darvin\UserBundle\Form\Type\User\ProfileType;
use Symfony\Component\Form\FormInterface;

/**
 * User form factory
 */
interface UserFormFactoryInterface
{
    /**
     * @param \Darvin\UserBundle\Entity\BaseUser|null $user    User
     * @param array                                   $options Options
     * @param string                                  $type    Type
     * @param string|null                             $name    Name
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProfileForm(?BaseUser $user = null, array $options = [], string $type = ProfileType::class, ?string $name = null): FormInterface;
}
