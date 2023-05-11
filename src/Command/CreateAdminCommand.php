<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Add a short description for your command',
)]
class CreateAdminCommand extends Command
{
    private EntityManagerInterface $entityManager ;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct('app:create-admin');
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('full_name', InputArgument::OPTIONAL, 'Argument description')
            ->addArgument('email', InputArgument::OPTIONAL, 'Argument description')
            ->addArgument('password', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $helper = $this->getHelper('question');

        $io = new SymfonyStyle($input, $output);

        $fullName = $input->getArgument('full_name');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        if(!$fullName){
            $question = new Question("Quel est le nom de l'administrateur ? \n");
            $fullName = $helper->ask($input , $output, $question);
        }

        if(!$email){
            $question = new Question("Quel est l'adresse email de ". $fullName. " ? \n");
            $email = $helper->ask($input , $output, $question);
        }

        if(!$password){
            $question = new Question("Quel est le mdp de". $fullName. " ?\n ");
            $password = $helper->ask($input , $output, $question);
        }
        $user = new User();
        $user->setEmail($email)
              ->setFullName($fullName)
              ->setRoles(["ROLE_USER","ROLE_ADMIN"])
              ->setPlainPassword($password);

        $this->entityManager->persist($user);
//        dd($user);
        $this->entityManager->flush();


        $io->success('Votre admin a été crée avec success');

        return Command::SUCCESS;
    }
}
