<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * User create command
 */
class UserCreateCommand extends ContainerAwareCommand
{
    const NO_ROLE = '-';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('darvin:user:create')
            ->setDescription('Creates user.')
            ->setDefinition([
                new InputArgument('email', InputArgument::REQUIRED, 'User email'),
                new InputArgument('password', InputArgument::REQUIRED, 'User plain password'),
            ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        list(, $email, $plainPassword) = array_values($input->getArguments());

        $role = $io->choice('Please select role', $this->buildRoleChoices(), self::NO_ROLE);

        $user = $this->createUser($email, $plainPassword, self::NO_ROLE !== $role ? [$role] : []);

        $violations = $this->getValidator()->validate($user);

        if ($violations->count() > 0) {
            /** @var \Symfony\Component\Validator\ConstraintViolation $violation */
            foreach ($violations as $violation) {
                $io->error(
                    sprintf('%s "%s": %s', $violation->getPropertyPath(), $violation->getInvalidValue(), $violation->getMessage())
                );
            }

            return;
        }

        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        $io->success(
            sprintf(
                'User with e-mail "%s", password "%s", and %s successfully created.',
                $email,
                $plainPassword,
                self::NO_ROLE !== $role ? sprintf('role "%s"', $role) : 'without any role'
            )
        );
    }

    /**
     * @return array
     */
    private function buildRoleChoices()
    {
        $choices = [
            self::NO_ROLE,
        ];

        foreach ($this->getRoleConfiguration()->getRoles() as $role) {
            $choices[] = $role->getRole();
        }

        return $choices;
    }

    /**
     * @param string $email         Email
     * @param string $plainPassword Plain password
     * @param array  $roles         Roles
     *
     * @return \Darvin\UserBundle\Entity\BaseUser
     */
    private function createUser($email, $plainPassword, array $roles)
    {
        return $this->getUserFactory()->createUser()
            ->setEmail($email)
            ->setPlainPassword($plainPassword)
            ->setRoles($roles);
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    private function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @return \Darvin\UserBundle\Configuration\RoleConfiguration
     */
    private function getRoleConfiguration()
    {
        return $this->getContainer()->get('darvin_user.configuration.roles');
    }

    /**
     * @return \Darvin\UserBundle\User\UserFactory
     */
    private function getUserFactory()
    {
        return $this->getContainer()->get('darvin_user.user.factory');
    }

    /**
     * @return \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    private function getValidator()
    {
        return $this->getContainer()->get('validator');
    }
}
