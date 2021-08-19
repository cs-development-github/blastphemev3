<?php

namespace App\Controller;

use App\Datatables\TagDatatables;
use App\Entity\Tag;
use App\Form\TagType;
use App\Service\AlertService;
use Doctrine\ORM\EntityManagerInterface;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TagController
 * @package App\Controller
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */

class TagController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var $alertService
     */
    private $alertService;

    /**
     * tagController constructor.
     * @param EntityManagerInterface $entityManager
     * @param AlertServiceInterface $alertService
     * @param string $uploadCoverPath
     */
    public function __construct(EntityManagerInterface $entityManager, AlertService $alertService)
    {
        $this->entityManager = $entityManager;
        $this->alertService = $alertService;
        
    }

    /**
     * @Route("/tag", name="tag_index", methods={"GET"})
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     * @return Response
     */
    public function index(DataTableFactory $dataTableFactory, Request $request): Response
    {
        $dataTableTag = $dataTableFactory->createFromType(
            TagDatatables::class,
            [
                'data_class' => Tag::class,
            ],
            ['pageLength' => 10]
        );
        $dataTableTag->handleRequest($request);

        if ($dataTableTag->isCallback()) {
            return $dataTableTag->getResponse();
        }

        return $this->render('tag/index.html.twig', [
            'dataTableTag' => $dataTableTag,
        ]);
    }
    
    /**
     * @Route("/tag/new", name="tag_new", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag, [
            'action' => $this->generateUrl('tag_new')
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($tag);
            $this->entityManager->flush();

            $this->alertService->success("Le tag été crée !");
            return $this->json(true);
        }

        return $this->render('tag/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/tag/edit/{id}", name="tag_edit", methods={"GET", "POST"})
     * @param tag $tag
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(Tag $tag, Request $request)
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();

            $this->addFlash('success', "Tag modifié avec succès");

            return $this->redirectToRoute('tag_index');
        }

        return $this->render('tag/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tag/delete/{id}", name="tag_delete", methods={"POST"})
     * @param Tag $tag
     * @param Request $request
     * @return Response
     */
    public function delete(Tag $tag, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete-tag'.$tag->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tag);
            $em->flush();

            $this->addFlash('success', "Tag supprimée avec succès");
        }
        return $this->redirectToRoute('tag_index');
    }
}
