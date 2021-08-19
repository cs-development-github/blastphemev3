<?php

namespace App\Controller;

use App\Datatables\ArticleDatatables;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Service\AlertService;
use App\Service\UploadServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController
 * @package App\Controller
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */


class ArticleController extends AbstractController
{
    
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var AlertService
     */
    private $alertService;
    /**
     * @var string
     */
    private $uploadArticleCover;
    /**
     * @var string
     */
    private $uploadArticleContent;

    /**
     * articleController constructor.
     * @param EntityManagerInterface $entityManager
     * @param AlertServiceInterface $alertService
     * @param string $uploadArticleCover
     * @param string $uploadArticleContent
     */
    public function __construct(EntityManagerInterface $entityManager, AlertService $alertService, string $uploadArticleCover, string $uploadArticleContent)
    {
        $this->entityManager = $entityManager;
        $this->alertService = $alertService;
        $this->uploadArticleCover = $uploadArticleCover;
        $this->uploadArticleContent = $uploadArticleContent;
    }
    

    /**
     * @Route("/article", name="article_index", methods={"GET"})
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     * @return Response
     */
    public function index(DataTableFactory $dataTableFactory, Request $request): Response
    {
        $dataTableArticle = $dataTableFactory->createFromType(
            ArticleDatatables::class,
            [
                'data_class' => Article::class,
            ],
            ['pageLength' => 10]
        );
        $dataTableArticle->handleRequest($request);

        if ($dataTableArticle->isCallback()) {
            return $dataTableArticle->getResponse();
        }

        return $this->render('article/index.html.twig', [
            'datatableArticle' => $dataTableArticle,
        ]);
    }

        /**
     * @Route("/article/new", name="article_new", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request, UploadServiceInterface $uploadService): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article, [
            'action' => $this->generateUrl('article_new')
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $fichier = $form->get('image')->getData();
            $article->setImage($uploadService->upload($fichier, $this->uploadArticleCover));

          
            $article->setParution(false);
            $article->setAuteur($this->getUser());
            
            $this->entityManager->persist($article);
            $this->entityManager->flush();

            $this->alertService->success("L'article a été crée !");
            return $this->json(true);
        }

        return $this->render('article/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/article/delete/{id}", name="article_delete", methods={"POST"})
     * @param Article $article
     * @param Request $request
     * @return Response
     */
    public function delete(Article $article, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete-article'.$article->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();

            $this->addFlash('success', "Article supprimée avec succès");
        }
        return $this->redirectToRoute('article_index');
    }

    /**
     * @Route("/article/edit/{id}", name="article_edit", methods={"GET", "POST"})
     * @param Article $article
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(Article $article, Request $request, UploadServiceInterface $uploadService)
    {
        
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($file = $form->get('image')->getData())) {
                $article->setImage($uploadService->upload($file, $this->uploadArticleCover));
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            $this->addFlash('success', "Article modifié avec succès");

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
