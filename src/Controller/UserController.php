<?php

namespace App\Controller;

use App\Datatables\UserDatatables;
use App\Entity\User;
use App\Form\UserAdminModifType;
use App\Form\UserAdminType;
use App\Form\UserType;
use App\Service\AlertService;
use App\Service\UploadServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 * @package App\Controller
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */

class UserController extends AbstractController
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
    private $uploadImageUser;

    /**
     * UserController constructor.
     * @param EntityManagerInterface $entityManager
     * @param AlertServiceInterface $alertService
     * @param string $uploadCoverPath
     */
    public function __construct(EntityManagerInterface $entityManager, AlertService $alertService, string $uploadImageUser)
    {
        $this->entityManager = $entityManager;
        $this->alertService = $alertService;
        $this->uploadImageUser = $uploadImageUser;
    }

    /**
     * @Route("/user", name="user_index", methods={"GET"})
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     * @return Response
     */
    public function index(DataTableFactory $dataTableFactory, Request $request): Response
    {

        $dataTableClient = $dataTableFactory->createFromType(
            UserDatatables::class,
            [
                'data_class' => User::class,
            ],
            ['pageLength' => 10]
        );
        $dataTableClient->handleRequest($request);

        if ($dataTableClient->isCallback()) {
            return $dataTableClient->getResponse();
        }

        return $this->render('user/index.html.twig', [
            'dataTableClient' => $dataTableClient,
        ]);
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete", methods={"POST"})
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function delete(User $user, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete-user'.$user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            $this->addFlash('success', "Utilisateur supprimée avec succès");
        }
        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/user/edit/{id}", name="user_edit", methods={"GET", "POST"})
     * @param User $user
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(User $user, Request $request, UploadServiceInterface $uploadService)
    {
        $form = $this->createForm(UserAdminModifType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($file = $form->get('photo')->getData())) {
                $user->setPhoto($uploadService->upload($file, $this->uploadImageUser));
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "Utilisateur modifié avec succès");

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/new", name="user_new", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder, UploadServiceInterface $uploadService): Response
    {
        $user = new User();
        $form = $this->createForm(UserAdminType::class, $user, [
            'action' => $this->generateUrl('user_new')
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('photo')->getData();
            $user->setPhoto($uploadService->upload($photo, $this->uploadImageUser));

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->alertService->success("L'utilisateur a été crée !");
            return $this->json(true);
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
