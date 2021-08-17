<?php

namespace App\Command;

use App\Entity\Tag;
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

class LegacyTagCommand extends AbstractLegacyCommand
{
    protected static $defaultName = 'legacy:tag';
    protected static $defaultDescription = 'Ma premiere commande';


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

        $io->note('Loading tags ...');
        $sql = "SELECT * FROM $database.Tag ";

        $results = $this->getOldData($sql);
 
        $progress = new ProgressBar($output);
        foreach ($progress->iterate($results) as $items) {
            $tag = new Tag();
            $tag->setLibelle($items['libelle_tag']);
            $this->getManager()->persist($tag);
        }
        $progress->finish();
        $io->newLine();
        $this->getManager()->flush();

        $io->success('Tout les tags on était importer !');

        return Command::SUCCESS;
    }

    
}
