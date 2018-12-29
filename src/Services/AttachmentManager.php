<?php

    namespace App\Services;

    use App\Entity\Attachment;
    use Symfony\Component\Filesystem\Filesystem;
    use Symfony\Component\HttpFoundation\File\UploadedFile;
    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Doctrine\ORM\EntityManagerInterface;
    use App\Entity\Post;


    class AttachmentManager
    {

        private $container;

        private $entityManager;

        public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager)
        {
            $this->container = $container;
            $this->entityManager = $entityManager;
        }

        public function uploadAttachment(UploadedFile $file, Post $post)
        {
            $filename = md5(uniqid()) . '.' . $file->guessClientExtension();

            $file->move(
                $this->getUploadsDirectory(),
                $filename
            );

            $attachment = new Attachment();

            $attachment->setFilename($filename);
            $attachment->setPath('/uploads/' . $filename);

            $attachment->setPost($post);
            $post->addAttachment($attachment);

            $this->entityManager->persist($attachment);
            $this->entityManager->flush();

            return [
                'path' => '/uploads/' . $filename,
                'filename' => $filename
            ];
        }

        public function removeAttachment(?string $filename)
        {
            if (!empty($filename)) {
                $filesystem = new Filesystem();

                $filesystem->remove(
                    $this->getUploadsDirectory() .  $filename
                );
            }
        }

        public function getUploadsDirectory(){
            return $this->container->getParameter('uploads');
        }
    }