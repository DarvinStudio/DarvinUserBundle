<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Validator;

use Darvin\UserBundle\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * User exists and active constraint validator
 */
class UserExistsAndActiveValidator extends ConstraintValidator
{
    /**
     * @var \Darvin\UserBundle\Repository\UserRepository
     */
    private $userRepository;

    /**
     * @param \Darvin\UserBundle\Repository\UserRepository $userRepository User entity repository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param string                                                                                               $email      Email
     * @param \Darvin\UserBundle\Validator\Constraints\UserExistsAndActive|\Symfony\Component\Validator\Constraint $constraint Constraint
     */
    public function validate($email, Constraint $constraint): void
    {
        if (!empty($email) && !$this->userRepository->userExistsAndActive($email)) {
            $this->context->addViolation($constraint->message);
        }
    }
}
