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

use Darvin\UserBundle\Form\Type\PasswordResetToken\RequestType;
use Symfony\Component\Form\FormInterface;

/**
 * Password reset token form factory
 */
interface PasswordResetTokenFormFactoryInterface
{
    /**
     * @param array       $options Options
     * @param string      $type    Type
     * @param string|null $name    Name
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createRequestForm(array $options = [], string $type = RequestType::class, ?string $name = null): FormInterface;
}
