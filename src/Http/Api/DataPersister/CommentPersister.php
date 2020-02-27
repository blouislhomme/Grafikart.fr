<?php

namespace App\Http\Api\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Domain\Application\Entity\Content;
use App\Domain\Auth\User;
use App\Domain\Comment\Comment;
use App\Domain\Comment\CommentRepository;
use App\Http\Api\Resource\CommentResource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class CommentPersister implements ContextAwareDataPersisterInterface
{
    private ValidatorInterface $validator;
    private Security $security;
    private EntityManagerInterface $em;

    public function __construct(
        ValidatorInterface $validator,
        Security $security,
        EntityManagerInterface $em
    ) {
        $this->validator = $validator;
        $this->security = $security;
        $this->em = $em;
    }

    /**
     * @param object $data
     * @param array $context
     * @return bool
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof CommentResource;
    }

    /**
     * @param CommentResource $data
     * @param array $context
     * @throws \Exception
     */
    public function persist($data, array $context = []): CommentResource
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $groups = [];
        if (!$user instanceof User) {
            $groups = ['anonymous'];
        }
        $this->validator->validate($data, ['groups' => $groups]);

        if ($data->id) {
            // On met à jour un commentaire
            /** @var CommentRepository $repository */
            $repository = $this->em->getRepository(Comment::class);
            $comment = $repository->findPartial($data->id);
            $comment->setContent($data->content);
        } else {
            // On crée un nouveau commentaire
            /** @var Content $target */
            $target = $this->em->getRepository(Content::class)->find($data->target);
            $comment = (new Comment())
                ->setAuthor($user)
                ->setUsername($data->username)
                ->setEmail($data->email)
                ->setCreatedAt(new \DateTime())
                ->setContent($data->content)
                ->setTarget($target);
            $this->em->persist($comment);
        }
        $this->em->flush();
        return CommentResource::fromComment($comment);
    }

    /**
     * @param CommentResource $data
     * @param array $context
     */
    public function remove($data, array $context = []): CommentResource
    {
        /** @var Comment $comment */
        $comment = $this->em->getReference(Comment::class, $data->id);
        $this->em->remove($comment);
        $this->em->flush();
        return $data;
    }
}
