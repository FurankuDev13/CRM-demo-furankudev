<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\CompanyRepository;
use App\Repository\UserRoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/** 
 *  @Route("/user", name="user_") 
*/
class UserController extends AbstractController
{
    /**
     * @Route("/index", name="index", methods={"GET"})
     */
    public function index(UserRepository $userRepo, UserRoleRepository $userRoleRepo)
    {
        $users = $userRepo->findWherePersonIsActive();
        $userRoles = $userRoleRepo->findByIsActive(true);

        return $this->render('user/index.html.twig', [
            'page_title' => 'Liste des utilisateurs (collaborateurs)',
            'users' => $users,
            'userRoles' => $userRoles,
        ]);
    }

    /**
     * @Route("/{id}/show", name="show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(User $user)
    {
        if (!$user) {
            throw $this->createNotFoundException("L'utilisateur indiqué n'existe pas"); 
        }

        return $this->render('user/show.html.twig', [
            'page_title' => 'Utilisateur: ' . $user->getPerson()->getFirstname() . ' ' . $user->getPerson()->getLastname(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        return $this->render('user/new.html.twig', [
            'page_title' => 'Ajouter un nouvel utilisateur',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function edit(User $user, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$user) {
            throw $this->createNotFoundException("L'utilisateur indiqué n'existe pas"); 
        }

        return $this->render('user/edit.html.twig', [
            'page_title' => "Mettre à jour l'utilisateur: " . $user->getPerson()->getFirstname() . ' ' . $user->getPerson()->getLastname(),
        ]);
    }

    /**
     * @Route("/{id}/archive", name="archive", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function archive(User $user, Request $request, EntityManagerInterface $entityManager, CompanyRepository $companyRepo)
    {
        if (!$user) {
            throw $this->createNotFoundException("L'utilisateur indiqué n'existe pas"); 
        }

        //On retire l'utilisateur des Sociétés auxquelles il a été assigné
        $userNull = new User();
        $userNull = NULL;
        $companies = $companyRepo->findByUser($user->getId());
        foreach ($companies as $company) {
            $company->setUser($userNull);
        }

        $user->getPerson()->setIsActive(!$user->getPerson()->getIsActive());
        $this->addFlash(
            'success',
            'L\'Utilisateur ' . $user->getPerson()->getFirstname() . " " . $user->getPerson()->getLastname() . ' a été archivé !'
        );

        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }
}
