<?php

namespace App\Controller\Admin;

use App\Repository\EmailTypeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/** 
 *  @Route("/admin/emailType", name="admin_emailType_") 
*/
class EmailTypeController extends AbstractController
{
    /**
     * @Route("/index", name="index", methods={"GET"})
     */
    public function index(Request $request, EmailTypeRepository $emailTypeRepo)
    {
        $isActive = [true,false];
        $field = $request->query->get('field', 'title');
        $order = $request->query->get('order', 'ASC');

        $emailTypes = $emailTypeRepo->findByIsActiveOrderedByField($isActive, $field , $order);

        return $this->render('admin/email_type/index.html.twig', [
            'page_title' => 'Liste des notifications',
            'emailTypes' => $emailTypes,
        ]);
    }
}
