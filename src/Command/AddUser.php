<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use function Symfony\Component\String\u;

#[AsCommand(
    name: 'app:add-professor',
    description: 'add user to the list of users (professors)'
)]
class AddUser extends Command
{
    private SymfonyStyle $io;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $users
    ) {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setHelp($this->getCommandHelp())
            ->addArgument('email', InputArgument::OPTIONAL, 'The email of the new user')
            ->addArgument('password', InputArgument::OPTIONAL, 'The plain password of the new user')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
            // SymfonyStyle is an optional feature that Symfony provides so you can
            // apply a consistent look to the commands of your application.
            // See https://symfony.com/doc/current/console/style.html
            $this->io = new SymfonyStyle($input, $output);
    }


    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        if (null !== $input->getArgument('password') && null !== $input->getArgument('email')) {
            return;
        }

        $this->io->title('Add User Command Interactive Wizard');
        $this->io->text([
            'If you prefer to not use this interactive wizard, provide the',
            'arguments required by this command as follows:',
            '',
            ' $ php bin/console app:add-user email@example.com password',
            '',
            'Now we\'ll ask you for the value of all the missing command arguments.',
        ]);

        // Ask for the password if it's not defined
        /** @var string|null $password */
        $password = $input->getArgument('password');

        if (null !== $password) {
            $this->io->text(' > <info>Password</info>: '.u('*')->repeat(u($password)->length()));
        }

        // Ask for the email if it's not defined
        $email = $input->getArgument('email');
        if (null !== $email) {
            $this->io->text(' > <info>Email</info>: '.$email);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('add-user-command');

		/** @var string $email */
        $email = $input->getArgument('email');

        /** @var string $plainPassword */
        $plainPassword = $input->getArgument('password');

        // make sure to validate the user data is correct
        $this->validateUserData($email, $plainPassword);

        // create the user and hash its password
        $user = new User();
        $user->setEmail($email);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_PROFESSOR']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->io->success(sprintf('%s was successfully created with psw : %s ', $user->getEmail(), $plainPassword));

        $event = $stopwatch->stop('add-user-command');
        if ($output->isVerbose()) {
            $this->io->comment(sprintf('New user database id: %d / Elapsed time: %.2f ms / Consumed memory: %.2f MB', $user->getId(), $event->getDuration(), $event->getMemory() / (1024 ** 2)));
        }

        return Command::SUCCESS;
    }

    private function validateUserData(string $email, string $plainPassword): void
    {
        // check if a user with the same email already exists.
        $existingEmail = $this->users->findOneBy(['email' => $email]);

        if (null !== $existingEmail) {
            throw new RuntimeException(sprintf('There is already a user registered with the "%s" email.', $email));
        }
    }

    private function getCommandHelp(): string
    {
        return <<<'HELP'
            The <info>%command.name%</info> command creates new users and saves them in the database:
              <info>php %command.full_name%</info> <comment>email password</comment>
            By default the command creates regular users.
            If you omit any of the two required arguments, the command will ask you to
            provide the missing values:
              # command will ask you for the password
              <info>php %command.full_name%</info> <comment>email </comment>
              # command will ask you for the email and password
              <info>php %command.full_name%</info> <comment> </comment>
            HELP;
    }
}