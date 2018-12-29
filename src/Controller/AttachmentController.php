<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\AttachmentRepository;
use App\Services\AttachmentManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Flex\Response;

class AttachmentController extends AbstractController
{
    private $attachmentManager;

    public function __construct(AttachmentManager $attachmentManager)
    {
        $this->attachmentManager = $attachmentManager;
    }

    /**
     * @Route("/attachment/{id}", name="upload_attachment")
     * @param Request $request
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function uploadsImage(Request $request, Post $post)
    {
        $file = $request->files->get('file');

        $filenameAndPath = $this->attachmentManager->uploadAttachment($file, $post);

        return $this->json([
            'location' => $filenameAndPath['path']
        ]);
    }

    /**
     * @Route("/removeAll")
     * @param AttachmentRepository $attachmentRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function removeAll(AttachmentRepository $attachmentRepository, EntityManagerInterface $entityManager)
    {
        foreach ($attachmentRepository->findAll() as $attachment)
        {
            $entityManager->remove($attachment);
        }
        $entityManager->flush();

        return new Response("awdwd");
    }
}
