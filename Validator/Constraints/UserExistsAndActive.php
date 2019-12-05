<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * User exists and active validation constraint
 *
 * @Annotation
 */
class UserExistsAndActive extends Constraint
{
    /**
     * @var string
     */
    public $message = 'user.exists_and_active';

    /**
     * {@inheritDoc}
     */
    public function validatedBy(): string
    {
        return 'darvin_user_user_exists_and_active';
    }
}
