<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Command;

use Darvin\UserBundle\Config\RoleConfigInterface;
use Darvin\UserBundle\Entity\BaseUser;
use Darvin\UserBundle\User\UserFactoryInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * User create command
 */
class UserCreateCommand extends Command
{
    private const NO_ROLE = '-';

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Darvin\UserBundle\Config\RoleConfigInterface
     */
    protected $roleConfig;

    /**
     * @var \Darvin\UserBundle\User\UserFactoryInterface
     */
    protected $userFactory;

    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    protected $validator;

    /**
     * @param string                                                    $name        Command name
     * @param \Doctrine\ORM\EntityManager                               $em          Entity manager
     * @param \Darvin\UserBundle\Config\RoleConfigInterface             $roleConfig  Role configuration
     * @param \Darvin\UserBundle\User\UserFactoryInterface              $userFactory User factory
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator   Validator
     */
    public function __construct(string $name, EntityManager $em, RoleConfigInterface $roleConfig, UserFactoryInterface $userFactory, ValidatorInterface $validator)
    {
        parent::__construct($name);

        $this->em = $em;
        $this->roleConfig = $roleConfig;
        $this->userFactory = $userFactory;
        $this->validator = $validator;
    }

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Creates user.')
            ->setDefinition([
                new InputArgument('email', InputArgument::REQUIRED, 'User email'),
                new InputArgument('password', InputArgument::REQUIRED, 'User plain password'),
                new InputArgument('role', InputArgument::OPTIONAL, 'User role'),
            ]);
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        list(, $email, $plainPassword, $role) = array_values($input->getArguments());

        $user = $this->createUser($input);

        $violations = $this->validator->validate($user);

        if ($violations->count() > 0) {
            /** @var \Symfony\Component\Validator\ConstraintViolation $violation */
            foreach ($violations as $violation) {
                $io->error(
                    sprintf('%s "%s": %s', $violation->getPropertyPath(), $violation->getInvalidValue(), $violation->getMessage())
                );
            }

            return 0;
        }

        if (!in_array($role, $this->buildRoleChoices())) {
            $role = $io->choice('Please select role', $this->buildRoleChoices(), self::NO_ROLE);
        }

        $user->setRoles(self::NO_ROLE !== $role ? [$role] : []);

        $this->em->persist($user);
        $this->em->flush();

        $io->success(
            sprintf(
                'User with email "%s", password "%s", and %s successfully created.',
                $email,
                $plainPassword,
                self::NO_ROLE !== $role ? sprintf('role "%s"', $role) : 'without any role'
            )
        );

        return 0;
    }

    /**
     * @return array
     */
    protected function buildRoleChoices(): array
    {
        $choices = [
            self::NO_ROLE,
        ];

        foreach ($this->roleConfig->getRoles() as $role) {
            $choices[] = $role->getName();
        }

        return $choices;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input Input
     *
     * @return \Darvin\UserBundle\Entity\BaseUser
     */
    protected function createUser(InputInterface $input): BaseUser
    {
        return $this->userFactory->createUser()
            ->setEmail($input->getArgument('email'))
            ->setPlainPassword($input->getArgument('password'));
    }
}
