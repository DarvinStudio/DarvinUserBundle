<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * User exists validation constraint
 *
 * @Annotation
 */
class UserExists extends Constraint
{
    /**
     * @var string
     */
    public $message = 'user.does_not_exist';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'darvin_user_user_exists';
    }
}
