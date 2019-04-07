<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Company;
use App\Form\CommentType;
use App\Entity\Attachment;
use App\Form\AttachmentType;
use App\Service\FileUploader;
use App\Entity\CompanyAddress;
use App\Repository\UserRepository;
use App\Form\CompanyAddressFormType;
use App\Repository\PersonRepository;
use App\Repository\CommentRepository;
use App\Repository\CompanyRepository;
use App\Repository\ContactRepository;
use App\Repository\RequestRepository;
use App\Entity\Request as DemandRequest;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CompanyType as CompanyFormType;
use App\Repository\CompanyAddressRepository;
use App\Repository\HandlingStatusRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;

/** 
 *  @Route("/company", name="company_") 
*/
class CompanyController extends AbstractController
{
    /**
     * @Route("/index", name="index", methods={"GET"})
     */
    public function index(Request $request, CompanyRepository $companyRepo, UserRepository $userRepo, PersonRepository $personRepo)
    {
        $filter = $request->query->get('filter', null);
        $salesUser = $request->query->get('salesUser', null);
        $field = $request->query->get('field', 'name');
        $order = $request->query->get('order', 'ASC');

        $salesUsers = $userRepo->findSalesRoles();
        $prospects = $companyRepo->findIsActiveByIsCustomerOrderedByField(false, $field, $order);
        $customers = $companyRepo->findIsActiveByIsCustomerOrderedByField(true, $field, $order);

        if ($salesUser) {
            $user = $userRepo->find($salesUser);
            $companies = $companyRepo->findIsActiveByUserOrderedByField($user, $field, $order);
            $filterValue = $user;
        } else {
            $companies = $companyRepo->findIsACtiveOrderedByField($field, $order);
            $filterValue = null;
        }

        if ($filter == "Clients") {
            $companies = $customers;
            $filterValue = $filter;
        }

        if ($filter == "Prospects") {
            $companies = $prospects;
            $filterValue = $filter;
        }

        return $this->render('company/index.html.twig', [
            'page_title' => 'Sociétés',
            'companies' => $companies,
            'salesUsers' => $salesUsers,
            'prospects' => $prospects,
            'customers' => $customers,
            'filterType' => $filter,
            'filterValue' => $filterValue,
        ]);
    }

    /**
     * @Route("/{id}/show", name="show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Company $company, Request $request, CompanyRepository $companyRepo, CompanyAddressRepository $companyAddressRepo, CommentRepository $commentRepo, RequestRepository $requestRepo, HandlingStatusRepository $handlingStatusRepo)
    {
        if (!$company) {
            throw $this->createNotFoundException("La société indiquée n'existe pas"); 
        }
        $index = $request->query->get('index', 1);

        $companyAddresses = $companyAddressRepo->findAddressIsActiveByCompany($company);
        $comments = $commentRepo->findCommentIsActiveByCompany($company);
        $demands = $requestRepo->findIsActiveByCompany($company);
        $handlingStatuses = $handlingStatusRepo->findByIsActive(true);

        return $this->render('company/show.html.twig', [
            'page_title' => 'Société: ' . $company->getName(),
            'company' => $company,
            'companyAddresses' => $companyAddresses,
            'comments' => $comments,
            'demands' => $demands,
            'handlingStatuses' => $handlingStatuses,
            'index' => $index,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $company = new Company();
        $companyAddress = new CompanyAddress();
        $company->addCompanyAddress($companyAddress);

        $form = $this->createForm(CompanyFormType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($companyAddress->getCompanyAddressType()) {
                $entityManager->persist($company);
                foreach($form->getData()->getCompanyAddresses() as $companyAddress) {
                    $companyAddress->setCompany($company);
                    $entityManager->persist($companyAddress);
                }
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    'La société ' . $company->getName() . ' a bien été ajoutée !'
                );
                return $this->redirectToRoute('company_show', ['id' => $company->getId()]);
            } else {
                $this->addFlash(
                    'danger',
                    "Il est nécessaire de préciser un type d'adresse !"
                );
            }
        }

        return $this->render('company/new.html.twig', [
            'page_title' => 'Ajouter une nouvelle société',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function edit(Company $company, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$company) {
            throw $this->createNotFoundException("La société indiquée n'existe pas"); 
        }

        $form = $this->createForm(CompanyFormType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach($form->getData()->getCompanyAddresses() as $companyAddress) {
                $companyAddress->setCompany($company);
                $entityManager->persist($companyAddress);
            }
            $entityManager->flush();

            $this->addFlash(
                'warning',
                'La société ' . $company->getName() . ' a bien été mise à jour !'
            );
            return $this->redirectToRoute('company_show', ['id' => $company->getId()]);
        }

        return $this->render('company/edit.html.twig', [
            'page_title' => 'Mettre à jour la société: ' . $company->getName(),
            'company' => $company,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/toggleIsCustomer", name="isCustomer_toggle", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function toggleIsCustomer(Company $company, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$company) {
            throw $this->createNotFoundException("La société indiquée n'existe pas"); 
        }

        $company->setIsCustomer(!$company->getIsCustomer());
        $this->addFlash(
            'success',
            'Le statut de la Société ' . $company->getName() . ' a été mis à jour !'
        );
        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    /**
     * @Route("/{id}/user/set", name="user_set", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function setUser(Company $company, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepo)
    {
        if (!$company) {
            throw $this->createNotFoundException("La société indiquée n'existe pas"); 
        }

        $userId = $request->request->get('userId');
        $user = $userRepo->find($userId);
        $company->setUser($user);
        $entityManager->flush();

        $this->addFlash(
            'success',
            $user->getPerson()->getFirstname() . ' ' . $user->getPerson()->getLastname() . ' a bien été attribué à la société ' . $company->getName() . ' !'
        );

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    /**
     * @Route("/{id}/address/new", name="address_new", methods={"GET", "POST"})
     */
    public function newAddress(Request $request, EntityManagerInterface $entityManager, Company $company)
    {
        $companyAddress = new CompanyAddress();
        $companyAddressForm = $this->createForm(CompanyAddressFormType::class, $companyAddress);
        $companyAddressForm->handleRequest($request);

        if ($companyAddressForm->isSubmitted() && $companyAddressForm->isValid()) {
            if ($companyAddress->getCompanyAddressType()) {
                $companyAddress->setCompany($company);
                $entityManager->persist($companyAddress);
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    "La nouvelle adresse a bien été ajoutée et associée à " . $company->getName()
                );
                return $this->redirectToRoute('company_show', ['id' => $company->getId()]);
            } else {
                $this->addFlash(
                    'danger',
                    "Il est nécessaire de préciser un type d'adresse !"
                );
            }
        }

        return $this->render('company/new_address.html.twig', [
            'page_title' => 'Ajouter une nouvelle adresse',
            'form' => $companyAddressForm->createView(),
            'company' => $company,
        ]);
    }

    /**
     * @Route("/{id}/address/{address_id}/edit", name="address_edit", methods={"GET", "POST"}, requirements={"id"="\d+", "id"="\d+"})
     * @ParamConverter("companyAddress", options={"id" = "address_id"})
     */
    public function editAddress(Request $request, EntityManagerInterface $entityManager, Company $company, CompanyAddress $companyAddress)
    {
        if (!$companyAddress) {
            throw $this->createNotFoundException("L'adresse' indiquée n'existe pas"); 
        }

        $companyAddressForm = $this->createForm(CompanyAddressFormType::class, $companyAddress);
        $companyAddressForm->handleRequest($request);

        if ($companyAddressForm->isSubmitted() && $companyAddressForm->isValid()) {
            $companyAddress->setCompany($company);
            $entityManager->flush();

            $this->addFlash(
                'warning',
                "L'adresse a bien été mise à jour pour la société " . $company->getName()
            );
            return $this->redirectToRoute('company_show', ['id' => $company->getId()]);
        }

        return $this->render('company/edit_address.html.twig', [
            'page_title' => "Mettre à jour l'adresse de la société: " . $company->getName(),
            'form' => $companyAddressForm->createView(),
            'company' => $company
        ]);
    }

    /**
     * @Route("/address/{id}/archive", name="address_archive", methods={"PATCH"}, requirements={"id"="\d+", "id"="\d+"})
     */
    public function archiveAddress(Request $request, EntityManagerInterface $entityManager, CompanyAddress $companyAddress)
    {
        if (!$companyAddress) {
            throw $this->createNotFoundException("L'adresse' indiquée n'existe pas"); 
        }

        $companyAddress->setIsActive(!$companyAddress->getIsActive());
        $this->addFlash(
            'warning',
            'L\'adresse de ' . $companyAddress->getCity() . ' a été archivée !'
        );
        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }

    /**
     * @Route("/{id}/comment/new", name="comment_new", methods={"GET", "POST"})
     */
    public function newComment(Request $request, EntityManagerInterface $entityManager, Company $company)
    {
        $comment = new Comment();
     
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        $user = $this->getUser();

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setCompany($company);
            $comment->setUser($user);
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Le nouveau commentaire a bien été ajoutée et associée à " . $company->getName()
            );
            return $this->redirectToRoute('company_show', ['id' => $company->getId(), 'index' => 2]);
        }

        return $this->render('company/new_comment.html.twig', [
            'page_title' => 'Ajouter un nouveau commentaire',
            'commentForm' => $commentForm->createView(),
            'company' => $company,
        ]);
    }

    /**
     * @Route("/{id}/comment/{comment_id}/edit", name="comment_edit", methods={"GET", "POST"}, requirements={"id"="\d+", "id"="\d+"})
     * @ParamConverter("comment", options={"id" = "comment_id"})
     */
    public function editComment(Request $request, EntityManagerInterface $entityManager, Company $company, Comment $comment)
    {
        if (!$company) {
            throw $this->createNotFoundException("La société indiquée n'existe pas"); 
        }

        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        $user = $this->getUser();

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setCompany($company);
            $comment->setUser($user);
            $entityManager->flush();

            $this->addFlash(
                'warning',
                "Le commentaire a bien été mise à jour pour la société " . $company->getName()
            );
            return $this->redirectToRoute('company_show', ['id' => $company->getId(), 'index' => 2]);
        }

        return $this->render('company/edit_comment.html.twig', [
            'page_title' => "Mettre à jour le commentaire",
            'commentForm' => $commentForm->createView(),
            'company' => $company
        ]);
    }

    /**
     * @Route("/comment/{id}", name="comment_toggleIsOnBoard", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function toggleIsOnBoardComment(Comment $comment, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$comment) {
            throw $this->createNotFoundException("La demande indiquée n'existe pas"); 
        }

        if ($comment->getIsOnBoard()) {
            $comment->setIsOnBoard(false);
            $this->addFlash(
                'success',
                'Le commentaire ' . $comment->getTitle() . ' a été ajouté au tableau de bord !'
            );

        } else {
            $comment->setIsOnBoard(true);
            $this->addFlash(
                'warning',
                'Le commentaire ' . $comment->getTitle() . ' a été retiré du tableau de bord !'
            );
        }

        $entityManager->flush();

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);;
    }

    /**
     * @Route("/comment/{id}/archive", name="comment_archive", methods={"PATCH"}, requirements={"id"="\d+", "id"="\d+"})
     */
    public function archiveComment(Request $request, EntityManagerInterface $entityManager, Comment $comment)
    {
        if (!$comment) {
            throw $this->createNotFoundException("Le commentaire indiqué n'existe pas"); 
        }

        $comment->setIsActive(!$comment->getIsActive());
        $this->addFlash(
            'warning',
            'Le commentaire ' . $comment->getTitle() . ' a été archivé !'
        );

        $attachment = $comment->getAttachment();

        if ($attachment) {
            $attachment->setIsActive(!$attachment->getIsActive());

            $entityManager->flush();
        }

        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    /**
     * @Route("/{id}/comment/{comment_id}/new-attachment", name="comment_attachment_new", methods={"GET", "POST"}, requirements={"id"="\d+", "id"="\d+"})
     * @ParamConverter("comment", options={"id" = "comment_id"})
     */
    public function newAttachment(Request $request, EntityManagerInterface $entityManager, Company $company, Comment $comment, FileUploader $fileUploader)
    {
        if (!$comment) {
            throw $this->createNotFoundException("Le commentaire indiqué n'existe pas"); 
        }
        
        $attachment = new Attachment();

        $attachmentForm = $this->createForm(AttachmentType::class, $attachment);
        $attachmentForm->handleRequest($request);

        if ($attachmentForm->isSubmitted() && $attachmentForm->isValid()) {
            $file = $attachment->getPath();

            if(!is_null($file)){
                $fileName = $fileUploader->upload($file);
                $attachment->setPath($fileName);
            } else {
                $this->addFlash(
                    'danger',
                    "Une pièce jointe doit être uploadée !"
                );
                return $this->render('request/new_attachment.html.twig', [
                    'page_title' => 'Ajouter une pièce jointe',
                    'attachmentForm' => $attachmentForm->createView(),
                    'request' => $demandRequest
                ]);

            }

            $entityManager->persist($attachment);

            $comment->setAttachment($attachment);

            $entityManager->flush();

            $this->addFlash(
                'success',
                "La pièce jointe a bien été associée au commentaire !"
            );
            return $this->redirectToRoute('company_show', ['id' => $company->getId(), 'index' => 2]);
        }

        return $this->render('company/new_attachment.html.twig', [
            'page_title' => 'Ajouter une pièce jointe',
            'attachmentForm' => $attachmentForm->createView(),
            'company' => $company,
        ]);
    }

     /**
     * @Route("/{id}/comment/{comment_id}/edit-attachment", name="comment_attachment_edit", methods={"GET", "POST"}, requirements={"id"="\d+", "id"="\d+"})
     * @ParamConverter("comment", options={"id" = "comment_id"})
     */
    public function editAttachment(Request $request, EntityManagerInterface $entityManager, Company $company, Comment $comment, FileUploader $fileUploader)
    {
        if (!$comment) {
            throw $this->createNotFoundException("Le commentaire indiqué n'existe pas"); 
        }

        $attachment = $comment->getAttachment();
        $savedPath = $attachment->getPath();

        if (!empty($savedPath)) {
            $attachment->setPath(
                new File($this->getParameter('attachments_directory').'/'. $savedPath)
            );
        }
        

        $attachmentForm = $this->createForm(AttachmentType::class, $attachment);
        $attachmentForm->handleRequest($request);

        if ($attachmentForm->isSubmitted() && $attachmentForm->isValid()) {
            $file = $attachment->getPath();

            if(!is_null($file)){
                $fileName = $fileUploader->upload($file);
                $attachment->setPath($fileName);

                if(!empty($savedPath)) {
                    unlink(
                        $this->getParameter('attachments_directory').'/'. $savedPath
                    );
                }

            } else {
                $attachment->setPath($savedPath);
            }

            $entityManager->flush();

            $this->addFlash(
                'warning',
                "La pièce jointe a bien été mise à jour !"
            );
            return $this->redirectToRoute('company_show', ['id' => $company->getId(), 'index' => 2]);
        }

        return $this->render('company/edit_attachment.html.twig', [
            'page_title' => 'Ajouter une pièce jointe',
            'attachmentForm' => $attachmentForm->createView(),
            'company' => $company,
        ]);
    }


    /**
     * @Route("/{id}/comment/{comment_id}/archive-attachment", name="comment_attachment_archive", methods={"PATCH"}, requirements={"id"="\d+", "id"="\d+"})
     * @ParamConverter("comment", options={"id" = "comment_id"})
     */
    public function archiveAttachment(Request $request, EntityManagerInterface $entityManager, Company $company, Comment $comment)
    {
        if (!$comment) {
            throw $this->createNotFoundException("Le commentaire indiqué n'existe pas"); 
        }

        $attachment = $comment->getAttachment();

        if ($attachment) {
            $attachment->setIsActive(!$attachment->getIsActive());
            $this->addFlash(
                'warning',
                'La pièce jointe ' . $attachment->getTitle() . ' a été archivée !'
            );

            $entityManager->flush();
        }
        
        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    /**
     * @Route("/{id}/archive", name="archive", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function archive(Company $company, Request $request, EntityManagerInterface $entityManager, RequestRepository $requestRepo, CompanyAddressRepository $companyAddressRepo, ContactRepository $contactRepo)
    {
        if (!$company) {
            throw $this->createNotFoundException("La société indiquée n'existe pas"); 
        }

        // avant d'archiver une société, il faut vérifier que tous ses demandes/devis sont Terminés
        $demandRequests = $requestRepo->findAllByOneCompany($company);
        $allCompleted = true;
        foreach ($demandRequests as $demandRequest) {
            if ($demandRequest->getHandlingStatus()->getTitle() != "Terminée") {
                $allCompleted = false;
            }
        }
        if (!$allCompleted) {
            $this->addFlash(
                'danger',
                'Toutes les demandes de la société ne sont pas Terminées, la société ne peut pas être archivée !'
            );
            $referer = $request->headers->get('referer');
            return $this->redirect($referer);;
        }
        
        // archiver/désarchiver la société
        $company->setIsActive(!$company->getIsActive());

        if (!$company->getIsActive()) {
        //cas de l'archivage
            // archiver également les adresses de la société
            $companyAddresses = $companyAddressRepo->findAllByOneCompany($company);
            foreach ($companyAddresses as $companyAddress) {
                $companyAddress->setIsActive(false);
            }
    
            // archiver également les personnes en contact de la société
            $contacts = $contactRepo->findAllByOneCompany($company);
            foreach ($contacts as $contact) {
                $contact->getPerson()->setIsActive(false);
            }
            
            // archiver également, pour toutes les personnes en contact de la société, les demandes de ces contacts
            $demandRequests = $requestRepo->findAllByOneCompany($company);
            foreach ($demandRequests as $demandRequest) {
                $demandRequest->setIsActive(false);
            }
            // flash message global
            $this->addFlash(
                'warning',
                'La Société ' . $company->getName() . ' a été archivée, '.
                'ainsi que ses ' . count($companyAddresses) . ' adresses,' . 
                'ses ' . count($contacts) . ' personnes en contact,' . 
                'ses ' . count($demandRequests) . ' demandes.'
            );
        } else {
            // flash message global
            $this->addFlash(
                'warning',
                'La Société ' . $company->getName() . ' a été désarchivée, '
            );

        }
        
        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }


}
