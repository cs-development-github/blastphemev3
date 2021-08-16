<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Service\AlertServiceInterface;
use App\Service\UploadServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MyAccountController
 * @package App\Controller
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */

class MyAccountController extends AbstractController
{

     /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var AlertServiceInterface
     */
    private $alertService;
    /**
     * @var string
     */
    private $uploadImageUser;

    /**
     * MyAccountController constructor.
     * @param EntityManagerInterface $entityManager
     * @param AlertServiceInterface $alertService
     * @param string $uploadImageUser
     */
    public function __construct(EntityManagerInterface $entityManager, AlertServiceInterface $alertService, string $uploadImageUser)
    {
        $this->entityManager = $entityManager;
        $this->alertService = $alertService;
        $this->uploadImageUser = $uploadImageUser;
    }

    /**
     * @Route("/my-account", name="my_account_index")
     */
    public function index(): Response
    {
        return $this->render('my_account/index.html.twig', [
            'controller_name' => 'MyAccountController',
        ]);
    }

    /**
     * @Route("/my-account/edit", name="my_account_edit")
     * @param Request $request
     * @param UploadServiceInterface $uploadService
     * @return Response
     */
    public function edit(Request $request, UploadServiceInterface $uploadService): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($file = $form->get('photo')->getData())) {
                $user->setPhoto($uploadService->upload($file, $this->uploadImageUser));
            }

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->alertService->success('Compte mis à jour');
            return $this->redirectToRoute('my_account_index');
        }

        return $this->render('my_account/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
