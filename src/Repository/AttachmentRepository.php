<?php

namespace App\Repository;

use App\Entity\Attachment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Attachment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Attachment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Attachment[]    findAll()
 * @method Attachment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttachmentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Attachment::class);
    }

    public function findAttachmentsToRemove(array $filenames, int $post_id)
    {
        $qb = $this->createQueryBuilder('a');

        $qb
            ->select()
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('a.post', $post_id),
                    $qb->expr()->notIn('a.filename', $filenames)
                )
            )
        ;

        return $qb->getQuery()->getResult();
    }

}
