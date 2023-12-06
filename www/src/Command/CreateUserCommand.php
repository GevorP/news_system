<?php

namespace App\Command;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

#[AsCommand(name: 'app:create:user', description: 'Create user')]
class CreateUserCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository,
        private PasswordHasherFactoryInterface $hasherFactory
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED);
        $this->addArgument('password', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument("email");
        $password = $input->getArgument("password");


        $user = new User();
        $user->setEmail($email);

        $passwordHasher = $this->hasherFactory->getPasswordHasher($user);
        $user->setPassword($passwordHasher->hash($password));

        $this->userRepository->save($user, true);

        $output->writeln('User created');

        return Command::SUCCESS;
    }
}