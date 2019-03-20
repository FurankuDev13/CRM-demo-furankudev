<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BoardController extends AbstractController
{
    /**
     * @Route("/", name="board_index")
     */
    public function index()
    {
        return $this->render('tests/index.html.twig', [
            'page_title' => 'Tableau de bord',
        ]);
    }

    /**
     * @Route("/list-test", name="list-test")
     */
    public function listTest()
    {
        return $this->render('tests/list.html.twig', [
            'page_title' => 'Produit',
        ]);
    }

    /**
     * @Route("/show-test", name="show-test")
     */
    public function showTest()
    {
        return $this->render('tests/show.html.twig', [
            'page_title' => 'Produit',
        ]);
    }
}
