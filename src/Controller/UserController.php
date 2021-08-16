<?php

namespace App\Controller;

use App\Datatables\UserDatatables;
use App\Entity\User;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */

class UserController extends AbstractController
{
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
}
