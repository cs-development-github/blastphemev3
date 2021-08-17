<?php

namespace App\Command;

use App\Entity\Tag;
use App\Entity\Type;
use App\Entity\User;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LegacyUserCommand extends AbstractLegacyCommand
{
    protected static $defaultName = 'legacy:user';
    protected static $defaultDescription = 'Add a short description for your command';

    private $oldDatabase;
    private $passwordHasher;

    public function __construct(string $name = null, Connection $connection, ManagerRegistry $managerRegistry, EntityManagerInterface $entityManager, LoggerInterface $logger, string $oldDatabase, UserPasswordHasherInterface $passwordHasher )
    {
      
        $this->oldDatabase = $oldDatabase;
        $this->passwordHasher = $passwordHasher;

        parent::__construct($name, $connection, $managerRegistry, $entityManager, $logger);
    
    }
    
    protected function configure(): void
    {
        $this
            ->addArgument('database', InputArgument::OPTIONAL, 'Name of database', $this->oldDatabase)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $database = $input->getArgument('database');

        $this->truncateTable(User::class, $io);

        $user = new User();
        $user->setEmail('Bruce@bruce.fr');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordHasher->hashPassword($user,"azerty"));
        $user->setDescription('Description a definir');
        $user->setName('THE BRUCE 666');
        $user->setPhoto('Image a definir ');
        $this->getManager()->persist($user);

        $user = new User();
        $user->setEmail('Hokuto@Hokuto.fr');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordHasher->hashPassword($user,"azerty"));
        $user->setDescription('Description a definir');
        $user->setName('Hokuto 2 Kuizine');
        $user->setPhoto('Image a definir ');
        $this->getManager()->persist($user);

        $user = new User();
        $user->setEmail('BloodEagle@hotmail.fr');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordHasher->hashPassword($user,"azerty"));
        $user->setDescription('Description a definir');
        $user->setName('Blood Eagle');
        $user->setPhoto('Image a definir ');
        $this->getManager()->persist($user);

        $io->note('Loading User ...');
        $sql = "SELECT * FROM $database.Administration ";

        $results = $this->getOldData($sql);
 
        $progress = new ProgressBar($output);
        foreach ($progress->iterate($results) as $items) {
            $user = new User();
            $user->setEmail($items['email']);
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($this->passwordHasher->hashPassword($user,"azerty"));
            $user->setDescription('Description a definir');
            $user->setName($this->getNameByEmail($items['email']));
            $user->setPhoto('Image a definir ');
            $this->getManager()->persist($user);
        }
        $progress->finish();
        $io->newLine();
        $this->getManager()->flush();

        $io->success('Tout les users on était importer !');

        return Command::SUCCESS;
    }

    private function getNameByEmail(?string $email): ?string
    {
        switch($email){
            case'blondin.julie@live.fr':
                return 'Alek_y_ann';
            case'acabiran@gmail.com':
                return 'Verveneyel';
            case'juliengregory8013@gmail.com':
                return 'Evil Ted';
            case'sebastien.denis.sin@gmail.com':
                return 'sebastien';
            case'chris.vermersch@hotmail.com':
                return 'admin';
            default: 
                return null;
        } 


    }
}
