<?php

namespace App\Domain\Blog;

use App\Domain\Application\Entity\Content;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table("blog_post")
 */
class Post extends Content
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Blog\Category", inversedBy="posts")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private ?Category $category;

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

}
