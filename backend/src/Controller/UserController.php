<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Person;
use App\Repository\UserRepository;
use App\Repository\CompanyRepository;
use App\Repository\UserRoleRepository;
use App\Form\UserType;
use App\Form\PersonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/** 
 *  @Route("/user", name="user_") 
*/
class UserController extends AbstractController
{
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
}
