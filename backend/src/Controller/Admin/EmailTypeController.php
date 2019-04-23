<?php

namespace App\Controller\Admin;

use App\Entity\EmailType;
use App\Repository\EmailTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EmailTemplateRepository;
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
    public function index(Request $request, EmailTypeRepository $emailTypeRepo, EmailTemplateRepository $emailTemplateRepo)
    {
        $isActive = [true,false];
        $field = $request->query->get('field', 'title');
        $order = $request->query->get('order', 'ASC');

        $emailTypes = $emailTypeRepo->findByIsActiveOrderedByField($isActive, $field , $order);
        $emailTemplates = $emailTemplateRepo->findByIsActive(true);

        return $this->render('admin/email_type/index.html.twig', [
            'page_title' => 'Liste des notifications',
            'emailTypes' => $emailTypes,
            'emailTemplates' => $emailTemplates
        ]);
    }

    /**
     * @Route("/{id}/edit-template", name="editTemplate", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function editTemplate(EmailType $emailType, Request $request, EntityManagerInterface $entityManager, EmailTemplateRepository $emailTemplateRepo)
    {
        if (!$emailType) {
            throw $this->createNotFoundException("La notification indiquée n'existe pas"); 
        }
        
        $emailTemplateId = $request->request->get('emailTemplateId');
        $emailTemplate = $emailTemplateRepo->find($emailTemplateId);
        $emailType->setEmailTemplate($emailTemplate);
        $entityManager->flush();

        $this->addFlash(
            'warning',
            'Le template' . $emailType->getTitle() . ' a bien été attribué à la notification ' . $emailType->getTitle() . ' !'
        );

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    /**
     * @Route("/{id}/archive", name="archive", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function archive(EmailType $emailType, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$emailType) {
            throw $this->createNotFoundException("La notification indiquée n'existe pas"); 
        }

        $emailType->setIsActive(!$emailType->getIsActive());
        $notification = ($emailType->getIsActive() ? ' a été activé' : ' a été désactivé !');
        $this->addFlash(
            'warning',
            'Le type ' . $emailType->getTitle() . $notification
        );
        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }
}
