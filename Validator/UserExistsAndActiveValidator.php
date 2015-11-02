<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Validator;

use Darvin\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * User exists and active constraint validator
 */
class UserExistsAndActiveValidator extends ConstraintValidator
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @param \Doctrine\ORM\EntityManager $em Entity manager
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param string                                                                                               $email      Email
     * @param \Darvin\UserBundle\Validator\Constraints\UserExistsAndActive|\Symfony\Component\Validator\Constraint $constraint Constraint
     */
    public function validate($email, Constraint $constraint)
    {
        if (!$this->getUserRepository()->userExistsAndActive($email)) {
            $this->context->addViolation($constraint->message);
        }
    }

    /**
     * @return \Darvin\UserBundle\Repository\UserRepository
     */
    private function getUserRepository()
    {
        return $this->em->getRepository(User::USER_CLASS);
    }
}