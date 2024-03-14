<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\ChangePasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Factura;

class DefaultController extends AbstractController
{
    #[Route('/default', name: 'app_default')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    #[Route('/', name: 'home')]
    public function inicio(): Response
    {
        return $this->redirectToRoute('facturas');
    }

    #[Route('/facturas', name: 'facturas')]
    public function facturas(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request, ManagerRegistry $doctrine): Response
    {
        $query = $em->getRepository(Factura::class)->findAllQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            15, /*limit per page*/
            ['defaultSortFieldName' => 'f.numero', 'defaultSortDirection' => 'desc']
        );

        return $this->render('default/facturas.html.twig', [
            'pagination' => $pagination
        ]);
    }

    // #[Route('/dash', name: 'dash')]
    // public function dash(EntityManagerInterface $em): Response
    // {
    //     $resumen = $em->getRepository(Certlog::class)->getResumen();
    //     return $this->render('default/dashboard.html.twig', [
    //         'resumen' => $resumen,
    //     ]);
    // }

    // #[Route('/sites', name: 'sites')]
    // public function sites(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request, ManagerRegistry $doctrine): Response
    // {
    //     $query = $em->getRepository(Site::class)->findAllQuery();

    //     $pagination = $paginator->paginate(
    //         $query, /* query NOT result */
    //         $request->query->getInt('page', 1), /*page number*/
    //         15, /*limit per page*/
    //         ['defaultSortFieldName' => 'a.description', 'defaultSortDirection' => 'asc']
    //     );

    //     return $this->render('default/sites.html.twig', [
    //         'pagination' => $pagination
    //     ]);
    // }

    // #[Route('/checklog', name: 'checklog')]
    // public function checklog(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request, ManagerRegistry $doctrine): Response
    // {
    //     $query = $em->getRepository(Certlog::class)->findAllQuery();
    //     $pagination = $paginator->paginate(
    //         $query, /* query NOT result */
    //         $request->query->getInt('page', 1), /*page number*/
    //         15, /*limit per page*/
    //         ['defaultSortFieldName' => 'c.enddate', 'defaultSortDirection' => 'asc']
    //     );

    //     return $this->render('default/checks.html.twig', [
    //         'title' => 'Registro de monitoreo',
    //         'pagination' => $pagination
    //     ]);
    // }

    // #[Route('/lastcheck', name: 'lastcheck')]
    // public function lastcheck(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request, ManagerRegistry $doctrine): Response
    // {
    //     $query = $em->getRepository(Certlog::class)->findLastQuery();
    //     $pagination = $paginator->paginate(
    //         $query, /* query NOT result */
    //         $request->query->getInt('page', 1), /*page number*/
    //         15, /*limit per page*/
    //         ['defaultSortFieldName' => 'c.enddate', 'defaultSortDirection' => 'asc']
    //     );

    //     return $this->render('default/checks.html.twig', [
    //         'title' => 'Estado actual',
    //         'pagination' => $pagination
    //     ]);
    // }

    // #[Route('/buscar', name: 'buscar')]
    // public function buscar(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request, ManagerRegistry $doctrine): Response
    // {
    //     $cadena=$request->query->get('cadena','');

    //     if ($cadena != '') {
    //         $query = $em->getRepository(Certlog::class)->buscarQuery($cadena);

    //         $pagination = $paginator->paginate(
    //             $query, /* query NOT result */
    //             $request->query->getInt('page', 1), /*page number*/
    //             20, /*limit per page*/
    //             ['defaultSortFieldName' => 's.description', 'defaultSortDirection' => 'asc']
    //         );
    //     }
    //     else {$pagination = '';}

    //     return $this->render('default/checks.html.twig', [
    //         'title' => 'Resultados de buscar: '.$cadena,
    //         'pagination' => $pagination,
    //         'cadena' => $cadena
    //     ]);
    // }

    #[Route(path: '/change_password', name: 'change_password')]
    public function changePassword(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher):Response
    {
        $form = $this->createForm(ChangePasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pchange_data= $form->getData();
            $old_pwd = $pchange_data['old_password']; 
            $new_pwd = $pchange_data['new_password']; 

            /** @var \App\Entity\User $user */
            $user = $this->getUser();
            $checkPass = $passwordHasher->isPasswordValid($user, $old_pwd);

            if($checkPass === true) {
                $new_pwd_encoded = $passwordHasher->hashPassword($user, $new_pwd);
                $user->setPassword($new_pwd_encoded);
                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('home');
            } 
            
        }
        return $this->renderForm('security/change_password.html.twig', [
            'form' => $form,
        ]);
    }
}
