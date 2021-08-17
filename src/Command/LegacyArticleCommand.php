<?php

namespace App\Command;

use App\Entity\Article;
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

class LegacyArticleCommand extends AbstractLegacyCommand
{
    private const AUTHOR_NAME_JULIEN = "Julien (Evil Ted)";
    private const AUTHOR_NAME_ADRIEN = "Verveneyel";
    private const AUTHOR_NAME_JULIE  = "Alek_y_ann";
    private const AUTHOR_NAME_BRUCE  = "THE BRUCE 666";
    private const AUTHOR_NAME_HOKUTO  = "Hokuto 2 Kuizine";
    private const AUTHOR_NAME_BLOOD  = "Blood Eagle";

    protected static $defaultName = 'legacy:article';
    protected static $defaultDescription = 'Charger les Articles';


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

        $this->truncateTable(Article::class, $io);

        $sql = "UPDATE $database.Intro_Article SET auteur = REPLACE(auteur, 'Verveneyel', 'acabiran@gmail.com') ";
        $this->executeSql($sql);
        $sql = "UPDATE $database.Intro_Article SET auteur = REPLACE(auteur, 'Alek_y_ann', 'blondin.julie@live.fr') ";
        $this->executeSql($sql);
        $sql = "UPDATE $database.Intro_Article SET auteur = REPLACE(auteur, 'THE BRUCE 666', 'Bruce@bruce.fr') ";
        $this->executeSql($sql);
        $sql = "UPDATE $database.Intro_Article SET auteur = REPLACE(auteur, 'The Bruce 666', 'Bruce@bruce.fr') ";
        $this->executeSql($sql);
        $sql = "UPDATE $database.Intro_Article SET auteur = REPLACE(auteur, 'Hokuto 2 Kuizine', 'Hokuto@Hokuto.fr') ";
        $this->executeSql($sql);
        $sql = "UPDATE $database.Intro_Article SET auteur = REPLACE(auteur, 'HOKUTO 2 KUIZINE', 'Hokuto@Hokuto.fr') ";
        $this->executeSql($sql);
        $sql = "UPDATE $database.Intro_Article SET auteur = REPLACE(auteur, 'Blood Eagle', 'BloodEagle@hotmail.fr') ";
        $this->executeSql($sql);
    
        $sql = "SELECT * FROM $database.Intro_Article WHERE auteur LIKE '%Evil%' ";
        $results = $this->getOldData($sql);
        
        foreach($results as $result){
            $sql = "UPDATE $database.Intro_Article SET auteur = 'juliengregory8013@gmail.com' WHERE id_article =".$result['id_article'] ;
            $this->executeSql($sql);
        }

        $io->note('Loading types ...');
        $sql = "SELECT * FROM $database.Intro_Article ";
        
        $results = $this->getOldData($sql);
 
        $progress = new ProgressBar($output);
        foreach ($progress->iterate($results) as $items) {
            $article = new Article();
            $type = $this->getManager()->getRepository(Type::class)->find($items['id_type']);
            if(!$type){
                $type = $this->getManager()->getRepository(Type::class)->find(7);
        }
            $article->setType($type);
            $article->setTitre($items['titre']);
            $article->setAccroche($items['accroche']);
            $article->setImage($items['photo']);
            $article->setParution(true);
            $user = $this->getManager()->getRepository(User::class)->findOneBy(['email' => $items['auteur']]);
            $article->setAuteur($user);

            //Gestion tag
            $tags = explode(' ', $items['id_tag']);
            
            foreach($tags as $tag){
                
                if(!empty($tag)){
                    //Recup de l'id du tag
                    $sql = "SELECT * FROM $database.Tag WHERE id_tag = ".$tag;
                    $results = $this->getOldData($sql)[0];
                    
                    $tag = $this->getManager()->getRepository(Tag::class)->findOneBy(['libelle' => $results['libelle_tag']]);
                    $article->addTag($tag);
                }
            }
            $this->getManager()->persist($article);
        }
        $progress->finish();
        $io->newLine();
        $this->getManager()->flush();

        $io->success('Tout les articles on était importer !');

        return Command::SUCCESS;
    }


    
}
