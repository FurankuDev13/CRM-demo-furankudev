<?php

namespace App\Controller\Admin;

use App\Entity\EmailTemplate;
use App\Form\EmailTemplateFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EmailTemplateRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/** 
 *  @Route("/admin/emailTemplate", name="admin_emailTemplate_") 
*/
class EmailTemplateController extends AbstractController
{
    /**
     * @Route("/index", name="index", methods={"GET"})
     */
    public function index(Request $request, EmailTemplateRepository $emailTemplateRepo)
    {
        $isActive = [true,false];
        $field = $request->query->get('field', 'title');
        $order = $request->query->get('order', 'ASC');

        $emailTemplates = $emailTemplateRepo->findByIsActiveOrderedByField($isActive, $field , $order);

        return $this->render('admin/email_template/index.html.twig', [
            'page_title' => 'Liste des templates',
            'emailTemplates' => $emailTemplates,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $emailTemplate = new EmailTemplate();

        $form = $this->createForm(EmailTemplateFormType::class, $emailTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($emailTemplate);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Le template ' . $emailTemplate->getTitle() . ' a bien été ajouté !'
            );
            return $this->redirectToRoute('admin_emailTemplate_index');
        }


        return $this->render('admin/email_template/new.html.twig', [
            'page_title' => 'Ajouter un nouveau template',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(emailTemplate $emailTemplate, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$emailTemplate) {
            throw $this->createNotFoundException("Le template indiqué n'existe pas"); 
        }

        $form = $this->createForm(EmailTemplateFormType::class, $emailTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash(
                'warning',
                'Le template ' . $emailTemplate->getTitle() . ' a bien été mis à jour !'
            );
            return $this->redirectToRoute('admin_emailTemplate_index');
        }

        return $this->render('admin/email_template/edit.html.twig', [
            'page_title' => 'Mettre à jour le template ' . $emailTemplate->getTitle(),
            'form' => $form->createView(),
            'emailTemplate' => $emailTemplate
        ]);
    }

    /**
     * @Route("/{id}/archive", name="archive", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function archive(emailTemplate $emailTemplate, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$emailTemplate) {
            throw $this->createNotFoundException("Le template indiqué n'existe pas"); 
        }

        $emailTemplate->setIsActive(!$emailTemplate->getIsActive());
        $notification = ($emailTemplate->getIsActive() ? ' a été désarchivé' : ' a été archivé !');
        $this->addFlash(
            'warning',
            'Le template ' . $emailTemplate->getTitle() . $notification
        );
        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }

        /**
     * @Route("/{id}/delete", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(emailTemplate $emailTemplate, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$emailTemplate) {
            throw $this->createNotFoundException("Le template indiqué n'existe pas"); 
        }

        if (!$emailTemplate->getEmailType()) {
            $entityManager->remove($emailTemplate);;
            $this->addFlash(
                'danger',
                'Le template ' . $emailTemplate->getTitle() . ' a été supprimé ! '
            );
            $entityManager->flush();
        } else {
            $this->addFlash(
                'danger',
                'Le template ' . $emailTemplate->getTitle() . " ne peut être supprimé tant qu'une notification y est associée ! "
            );
        }
        

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }

}
