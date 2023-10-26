<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'createAdmin',
    description: 'Add a short description for your command',
)]
class CreateAdminCommand extends Command
{

    public function __construct(
        private readonly UserRepository $userRepo,
        private readonly UserPasswordHasherInterface $hasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('welcome to create admin command');

        $helper = $this->getHelper('question');
        $emailQuestion = new Question('<question>input admin email:</question> ');
        $email = $helper->ask($input, $output, $emailQuestion);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $io->error('invalid email');
            return Command::FAILURE;
        }

        $passwordQuestion = new Question('<question>input password:</question> ');
        $password = $helper->ask($input, $output, $passwordQuestion);
        if (strlen($password) < 5) {
            $io->error('min length password - 5 chars');
            return Command::FAILURE;
        }

        $this->saveAdmin($email, $password);
        $io->success('admin created');

        return Command::SUCCESS;
    }

    private function saveAdmin(string $email, string $password): void
    {
        $admin = new User();
        $hashedPassword = $this->hasher->hashPassword($admin, $password);

        $admin
            ->setEmail($email)
            ->setPassword($hashedPassword)
            ->setRoles(['ROLE_ADMIN'])
        ;

        $this->userRepo->save($admin, true);
    }
}
