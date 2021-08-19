<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     */
    private $accroche;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="boolean")
     */
    private $parution;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="articles",cascade={"persist"})
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $auteur;

    /**
     * @ORM\OneToMany(targetEntity=ContenuArticle::class, mappedBy="article", orphanRemoval=true)
     */
    private $contenuArticles;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->contenuArticles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }


    public function getAccroche(): ?string
    {
        return $this->accroche;
    }

    public function setAccroche(string $accroche): self
    {
        $this->accroche = $accroche;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getParution(): ?bool
    {
        return $this->parution;
    }

    public function setParution(bool $parution): self
    {
        $this->parution = $parution;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * @return Collection|ContenuArticle[]
     */
    public function getContenuArticles(): Collection
    {
        return $this->contenuArticles;
    }

    public function addContenuArticle(ContenuArticle $contenuArticle): self
    {
        if (!$this->contenuArticles->contains($contenuArticle)) {
            $this->contenuArticles[] = $contenuArticle;
            $contenuArticle->setArticle($this);
        }

        return $this;
    }

    public function removeContenuArticle(ContenuArticle $contenuArticle): self
    {
        if ($this->contenuArticles->removeElement($contenuArticle)) {
            // set the owning side to null (unless already changed)
            if ($contenuArticle->getArticle() === $this) {
                $contenuArticle->setArticle(null);
            }
        }

        return $this;
    }

}
