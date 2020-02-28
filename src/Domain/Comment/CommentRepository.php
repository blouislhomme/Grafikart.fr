<?php

namespace App\Domain\Comment;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * Récupère les commentaires pour le listing de l'API en évitant la liaison content
     *
     * @param int $content
     * @return array<Comment>
     */
    public function findForApi(int $content): array
    {
        // Force l'enregistrement de l'entité dans l'entity manager pour éviter les requêtes supplémentaires
        $post = $this->_em->getReference(\App\Domain\Blog\Post::class, $content);
        return $this->createQueryBuilder('c')
            ->select('c, u')
            ->orderBy('c.createdAt', 'ASC')
            ->where('c.target = :content')
            ->leftJoin('c.author', 'u')
            ->setParameter('content', $content)
            ->getQuery()
            ->getResult();
    }

    /**
     * Renvoie un commentaire en évitant la liaison content
     *
     * @param int $id
     */
    public function findPartial(int $id): Comment
    {
        $result = $this->createQueryBuilder('c')
            ->select('partial c.{id, username, email, content, createdAt}, partial u.{id, username, email}')
            ->where('c.id = :id')
            ->leftJoin('c.author', 'u')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->getOneOrNullResult();
        if ($result === null) {
            throw new EntityNotFoundException();
        }
        return $result;
    }

}
