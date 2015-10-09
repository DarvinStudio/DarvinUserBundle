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

use Darvin\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

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
            ->setDefinition(array(
                new InputArgument('email', InputArgument::REQUIRED, 'User email'),
                new InputArgument('password', InputArgument::REQUIRED, 'User plain password'),
            ));
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        list(, $email, $plainPassword) = array_values($input->getArguments());

        $roles = array();

        if ($this->getQuestionHelper()->ask($input, $output, new ConfirmationQuestion('Create admin (y/n, default y)? '))) {
            $roles[] = User::ROLE_ADMIN;
        }

        $user = $this->createUser($email, $plainPassword, $roles);

        $violations = $this->getValidator()->validate($user);

        if ($violations->count() > 0) {
            /** @var \Symfony\Component\Validator\ConstraintViolation $violation */
            foreach ($violations as $violation) {
                $message = sprintf(
                    '<error>%s "%s": %s</error>',
                    $violation->getPropertyPath(),
                    $violation->getInvalidValue(),
                    $violation->getMessage()
                );
                $output->writeln($message);
            }

            return;
        }

        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        $output->writeln(
            sprintf('<info>User with e-mail "%s" and password "%s" successfully created.</info>', $email, $plainPassword)
        );
    }

    /**
     * @param string $email         Email
     * @param string $plainPassword Plain password
     * @param array  $roles         Roles
     *
     * @return \Darvin\UserBundle\Entity\User
     */
    private function createUser($email, $plainPassword, array $roles)
    {
        $user = new User();

        return $user
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
     * @return \Symfony\Component\Console\Helper\SymfonyQuestionHelper
     */
    private function getQuestionHelper()
    {
        return $this->getHelper('question');
    }

    /**
     * @return \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    private function getValidator()
    {
        return $this->getContainer()->get('validator');
    }
}
