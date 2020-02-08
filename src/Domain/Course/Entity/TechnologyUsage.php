<?php

namespace App\Domain\Course\Entity;

use App\Domain\Application\Entity\Content;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Domain\Course\Entity\TechnologyUsageRepository")
 */
class TechnologyUsage
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Domain\Course\Entity\Technology", inversedBy="usages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $technology;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Domain\Application\Entity\Content", inversedBy="technologyUsages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $version;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $secondary;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTechnology(): ?Technology
    {
        return $this->technology;
    }

    public function setTechnology(?Technology $technology): self
    {
        $this->technology = $technology;

        return $this;
    }

    public function getContent(): ?Content
    {
        return $this->content;
    }

    public function setContent(?Content $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(?string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getSecondary(): ?bool
    {
        return $this->secondary;
    }

    public function setSecondary(bool $secondary): self
    {
        $this->secondary = $secondary;

        return $this;
    }
}
