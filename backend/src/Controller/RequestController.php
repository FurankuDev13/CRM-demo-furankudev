<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Entity\Attachment;
use App\Form\AttachmentType;
use App\Entity\RequestDetail;
use App\Form\RequestFormType;
use App\Service\FileUploader;
use App\Form\RequestDetailType;
use App\Repository\CommentRepository;
use App\Repository\CompanyRepository;
use App\Repository\ContactRepository;
use App\Repository\RequestRepository;
use App\Entity\Request as DemandRequest;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RequestTypeRepository;
use App\Repository\RequestDetailRepository;
use App\Repository\HandlingStatusRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;

/** 
 *  @Route("/request", name="request_") 
*/
class RequestController extends AbstractController
{
    /**
     * @Route("/index", name="index", methods={"GET"})
     */
    public function index(Request $request, RequestRepository $requestRepo, CompanyRepository $companyRepo, HandlingStatusRepository $handlingStatusRepo, RequestTypeRepository $requestTypeRepo)
    {
        $filter = $request->query->get('filter', null);
        $filterId = $request->query->get('filterId', null);
        $table = $request->query->get('table', 'r');
        $field = $request->query->get('field', 'createdAt');
        $order = $request->query->get('order', 'DESC');

        $handlingStatuses = $handlingStatusRepo->findByIsActiveOrderedByField(true);
        $requestTypes = $requestTypeRepo->findByIsActiveOrderedByField(true);

        if ($filter == 'handlingStatus') {
            $handlingStatus = $handlingStatusRepo->findOneByTitle($filterId);
            $demandRequests = $requestRepo->findIsActiveByHandlingStatus($handlingStatus, $table, $field, $order);

        } elseif ($filter == 'requestType') {
            $requestType = $requestTypeRepo->findOneByTitle($filterId);
            $demandRequests = $requestRepo->findIsActiveByRequestType($requestType, $table, $field, $order);
        } else {
            $demandRequests = $requestRepo->findIsActiveOrderedByField($table, $field, $order);
        }

        return $this->render('request/index.html.twig', [
            'page_title' => 'Demandes',
            'requests' => $demandRequests,
            'requestTypes' => $requestTypes,
            'handlingStatuses' => $handlingStatuses,
            'filter' => $filter,
            'filterId' => $filterId,
        ]);
    }

    /**
     * @Route("/{id}/show", name="show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(DemandRequest $demandRequest, Request $request, CommentRepository $commentRepo, RequestDetailRepository $requestDetailRepo)
    {
        if (!$demandRequest) {
            throw $this->createNotFoundException("La demande indiquée n'existe pas");
        }

        $index = $request->query->get('index', 1);

        $comments = $commentRepo->findCommentIsActiveByRequest($demandRequest);
        $details = $requestDetailRepo->findIsActiveByRequestOrderedByField($demandRequest);

        $companyDiscount = $demandRequest->getContact()->getCompany()->getDiscount()->getRate();
        $amount = 0;

        foreach($details as $detail) {
            $productMaxDiscount = $detail->getProduct()->getMaxDiscountRate();
            $productListPrice = $detail->getProduct()->getListPrice();
            $productDiscount = min($companyDiscount, $productMaxDiscount);
            $productSellingPrice = $productListPrice * (100 - $productDiscount)/100;
            $amount += ($detail->getQuantity() * $productSellingPrice);
        }

        return $this->render('request/show.html.twig', [
            'page_title' => 'Demande: ' . $demandRequest->getTitle(),
            'request' => $demandRequest,
            'comments' => $comments,
            'details' => $details,
            'amount' => $amount,
            'index' => $index

        ]);
    }

    /**
     * @Route("/{id}/pdf", name="pdf", methods={"GET"}, requirements={"id"="\d+"})
     */
/*     public function pdf(DemandRequest $demandRequest, Request $request, CommentRepository $commentRepo, RequestDetailRepository $requestDetailRepo, \Knp\Snappy\Pdf $knpSnappy)
    {
        if (!$demandRequest) {
            throw $this->createNotFoundException("La demande indiquée n'existe pas"); 
        }

        $comments = $commentRepo->findCommentIsActiveByRequest($demandRequest);
        $details = $requestDetailRepo->findisActiveOrderedByField();

        $amount = 0;

        foreach($details as $detail) {
            $amount += ($detail->getQuantity() * $detail->getProduct()->getListPrice());
        }

        $html = $this->renderView('request/PDF/_export_details.html.twig', array(
            'page_title' => 'Demande: ' . $demandRequest->getTitle(),
            'request' => $demandRequest,
            'comments' => $comments,
            'details' => $details,
            'amount' => $amount,
            'index' => $index
        ));

        return new PdfResponse(
            $knpSnappy->getOutputFromHtml($html),
            'file.pdf'
        );
    } */

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, ContactRepository $contactRepo)
    {
        $demandRequest = new DemandRequest();
        $requestDetail = new RequestDetail();
        $demandRequest->addRequestDetail($requestDetail);

        $contactId = $request->query->get('contact_id');
        if ($contactId) {
            $contact = $contactRepo->find($contactId);
            $demandRequest->setContact($contact);
        }

        $form = $this->createForm(RequestFormType::class, $demandRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($demandRequest);

            foreach($form->getData()->getRequestDetails() as $requestDetail) {
                $requestDetail->setRequest($demandRequest);
                $entityManager->persist($requestDetail);
            }

            $entityManager->flush();

            $this->addFlash(
                'success',
                'La demande ' . $demandRequest->getTitle() . ' a bien été ajoutée !'
            );
            return $this->redirectToRoute('request_show', ['id' => $demandRequest->getId()]);
        }

        return $this->render('request/new.html.twig', [
            'page_title' => 'Ajouter une nouvelle demande',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function edit(DemandRequest $demandRequest, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$demandRequest) {
            throw $this->createNotFoundException("La société indiquée n'existe pas"); 
        }

        $form = $this->createForm(RequestFormType::class, $demandRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach($form->getData()->getRequestDetails() as $requestDetail) {
                $requestDetail->setRequest($demandRequest);
                $entityManager->persist($requestDetail);
            }

            $entityManager->flush();

            $this->addFlash(
                'success',
                'La demande ' . $demandRequest->getTitle() . ' a bien été mise à jour !'
            );
            return $this->redirectToRoute('request_show', ['id' => $demandRequest->getId()]);
        }

        return $this->render('request/edit.html.twig', [
            'page_title' => 'Mettre à jour la demande: ' . $demandRequest->getTitle(),
            'request' => $demandRequest,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/{id}/edit-handling-status", name="editHandlingStatus", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function editHandlingStatus(DemandRequest $demandRequest, Request $request, EntityManagerInterface $entityManager, HandlingStatusRepository $handlingStatusRepo)
    {
        if (!$demandRequest) {
            throw $this->createNotFoundException("La demande indiquée n'existe pas"); 
        }
        
        $handlingStatusId = $request->request->get('handlingStatusId');
        $handlingStatus = $handlingStatusRepo->find($handlingStatusId);
        $demandRequest->setHandlingStatus($handlingStatus);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Le status ' . $handlingStatus->getTitle() . ' a bien été attribué à la demande ' . $demandRequest->getTitle() . ' !'
        );

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    /**
     * @Route("/{id}/comment/new", name="comment_new", methods={"GET", "POST"})
     */
    public function newComment(DemandRequest $demandRequest, Request $request, EntityManagerInterface $entityManager)
    {
        $comment = new Comment();
     
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        $user = $this->getUser();

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setRequest($demandRequest);
            $comment->setUser($user);
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Le nouveau commentaire a bien été ajoutée et associée à " . $demandRequest->getTitle()
            );
            return $this->redirectToRoute('request_show', ['id' => $demandRequest->getId(), 'index' => 2]);
        }

        return $this->render('request/new_comment.html.twig', [
            'page_title' => 'Ajouter un nouveau commentaire',
            'commentForm' => $commentForm->createView(),
            'request' => $demandRequest
        ]);
    }

    /**
     * @Route("/{id}/comment/{comment_id}/edit", name="comment_edit", methods={"GET", "POST"}, requirements={"id"="\d+", "id"="\d+"})
     * @ParamConverter("comment", options={"id" = "comment_id"})
     */
    public function editComment(DemandRequest $demandRequest, Request $request, EntityManagerInterface $entityManager, Comment $comment)
    {
        if (!$demandRequest) {
            throw $this->createNotFoundException("La demande indiquée n'existe pas"); 
        }

        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        $user = $this->getUser();

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setRequest($demandRequest);
            $comment->setUser($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Le commentaire a bien été mise à jour pour la demande " . $demandRequest->getTitle()
            );
            return $this->redirectToRoute('request_show', ['id' => $demandRequest->getId(), 'index' => 2]);
        }

        return $this->render('request/edit_comment.html.twig', [
            'page_title' => "Mettre à jour le commentaire",
            'commentForm' => $commentForm->createView(),
            'request' => $demandRequest
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
        return $this->redirect($referer);
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
            'success',
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
    public function newAttachment(Request $request, EntityManagerInterface $entityManager, DemandRequest $demandRequest, Comment $comment, FileUploader $fileUploader)
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
            }

            $entityManager->persist($attachment);

            $comment->setAttachment($attachment);

            $entityManager->flush();

            $this->addFlash(
                'success',
                "La pièce jointe a bien été associée au commentaire !"
            );
            return $this->redirectToRoute('request_show', ['id' => $demandRequest->getId(), 'index' => 2]);
        }

        return $this->render('request/new_attachment.html.twig', [
            'page_title' => 'Ajouter une pièce jointe',
            'attachmentForm' => $attachmentForm->createView(),
            'request' => $demandRequest
        ]);
    }

     /**
     * @Route("/{id}/comment/{comment_id}/edit-attachment", name="comment_attachment_edit", methods={"GET", "POST"}, requirements={"id"="\d+", "id"="\d+"})
     * @ParamConverter("comment", options={"id" = "comment_id"})
     */
    public function editAttachment(Request $request, EntityManagerInterface $entityManager, DemandRequest $demandRequest, Comment $comment, FileUploader $fileUploader)
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
                'success',
                "La pièce jointe a bien été associée au commentaire !"
            );
            return $this->redirectToRoute('request_show', ['id' => $demandRequest->getId(), 'index' => 2]);
        }

        return $this->render('request/edit_attachment.html.twig', [
            'page_title' => 'Ajouter une pièce jointe',
            'attachmentForm' => $attachmentForm->createView(),
            'request' => $demandRequest
        ]);
    }


    /**
     * @Route("/{id}/comment/{comment_id}/archive-attachment", name="comment_attachment_archive", methods={"PATCH"}, requirements={"id"="\d+", "id"="\d+"})
     * @ParamConverter("comment", options={"id" = "comment_id"})
     */
    public function archiveAttachment(Request $request, EntityManagerInterface $entityManager, DemandRequest $demandRequest, Comment $comment)
    {
        if (!$comment) {
            throw $this->createNotFoundException("Le commentaire indiqué n'existe pas"); 
        }

        $attachment = $comment->getAttachment();

        if ($attachment) {
            $attachment->setIsActive(!$attachment->getIsActive());
            $this->addFlash(
                'success',
                'La pièce jointe ' . $attachment->getTitle() . ' a été archivée !'
            );

            $entityManager->flush();
        }
        
        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    /**
     * @Route("/{id}/detail/new", name="detail_new", methods={"GET", "POST"})
     */
    public function newDetail(DemandRequest $demandRequest, Request $request, EntityManagerInterface $entityManager)
    {
        $requestDetail = new RequestDetail();
     
        $form = $this->createForm(RequestDetailType::class, $requestDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $requestDetail->setRequest($demandRequest);
            $entityManager->persist($requestDetail);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Le nouvel élément a bien été ajoutée et associée à " . $demandRequest->getTitle()
            );
            return $this->redirectToRoute('request_show', ['id' => $demandRequest->getId()]);
        }

        return $this->render('request/new_detail.html.twig', [
            'page_title' => 'Ajouter un nouvel élément à la demande',
            'form' => $form->createView(),
            'request' => $demandRequest
        ]);
    }

    /**
     * @Route("{id}/detail/{detail_id}/edit", name="detail_edit", methods={"GET", "POST"}, requirements={"id"="\d+", "id"="\d+"})
     * @ParamConverter("requestDetail", options={"id" = "detail_id"})
     */
    public function editDetail(DemandRequest $demandRequest, Request $request, EntityManagerInterface $entityManager, RequestDetail $requestDetail)
    {
        if (!$requestDetail) {
            throw $this->createNotFoundException("L'élément de la demande indiqué n'existe pas"); 
        }

        $form = $this->createForm(RequestDetailType::class, $requestDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $requestDetail->setRequest($demandRequest);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "L'élément a bien été mise à jour pour la demande " . $demandRequest->getTitle()
            );
            return $this->redirectToRoute('request_show', ['id' => $demandRequest->getId()]);
        }

        return $this->render('request/edit_detail.html.twig', [
            'page_title' => "Mettre à jour l'élément",
            'form' => $form->createView(),
            'request' => $demandRequest
        ]);
    }

    /**
     * @Route("/detail/{id}/archive", name="detail_archive", methods={"PATCH"}, requirements={"id"="\d+", "id"="\d+"})
     */
    public function archiveDetail(Request $request, EntityManagerInterface $entityManager, RequestDetail $requestDetail)
    {
        if (!$requestDetail) {
            throw $this->createNotFoundException("L'élément indiqué n'existe pas"); 
        }

        $requestDetail->setIsActive(!$requestDetail->getIsActive());
        $this->addFlash(
            'success',
            "L'élément concernant le produit "  . $requestDetail->getProduct()->getName() . ' a été archivé !'
        );

        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    /**
     * @Route("/{id}/archive", name="archive", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function archive(DemandRequest $demandRequest, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$demandRequest) {
            throw $this->createNotFoundException("La demande indiquée n'existe pas"); 
        }

        // avant d'archiver une demande, il faut vérifier qu'elle est Terminée
        if ($demandRequest->getHandlingStatus()->getTitle() != "Terminée") {
            $this->addFlash(
                'warning',
                'La demande de la société n\'est pas Terminées, elle ne peut pas être archivée !'
            );
            $referer = $request->headers->get('referer');
            return $this->redirect($referer);;
        }

        $demandRequest->setIsActive(!$demandRequest->getIsActive());
        $notification = ($demandRequest->getIsActive() ? ' a été désarchivée' : ' a été archivée !');
        $this->addFlash(
            'success',
            'La Demande ' . $demandRequest->getTitle() . $notification
        );
        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);;
    }

}
