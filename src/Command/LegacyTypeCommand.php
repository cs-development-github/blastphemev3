<?php

namespace App\Command;

use App\Entity\Tag;
use App\Entity\Type;
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

class LegacyTypeCommand extends AbstractLegacyCommand
{
    protected static $defaultName = 'legacy:type';
    protected static $defaultDescription = 'Charger les types';


    private $oldDatabase;
    private $em;

    /**
     * @var Connection
     */
    private $connection;

    public function __construct(string $name = null, Connection $connection, ManagerRegistry $managerRegistry, EntityManagerInterface $entityManager, LoggerInterface $logger, string $oldDatabase)
    {
      
        $this->oldDatabase = $oldDatabase;

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

        $this->truncateTable(Type::class, $io);

        $io->note('Loading types ...');
        $sql = "SELECT * FROM $database.Type ";

        $results = $this->getOldData($sql);
 
        $progress = new ProgressBar($output);

        foreach ($progress->iterate($results) as $items) {
            $type = new Type();
            $type->setLibelle($items['libelle_Type']);
            $this->getManager()->persist($type);
        }

        $type = new Type();
        $type->setLibelle('Others');
        $this->getManager()->persist($type);
        
        $progress->finish();
        $io->newLine();
        $this->getManager()->flush();

        $io->success('Tout les types on était importer !');

        return Command::SUCCESS;
    }

    
}
