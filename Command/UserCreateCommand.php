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

use Darvin\UserBundle\Entity\BaseUser;
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

        $roles = [];

        if ($io->confirm('Create superadmin?')) {
            $roles[] = BaseUser::ROLE_SUPERADMIN;
        }

        $user = $this->createUser($email, $plainPassword, $roles);

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

        $io->success(sprintf('User with e-mail "%s" and password "%s" successfully created.', $email, $plainPassword));
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
