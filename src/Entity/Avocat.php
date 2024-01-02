<?php

namespace App\Entity;

use App\Repository\AvocatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvocatRepository::class)]
class Avocat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userAvocat')]
    private ?Users $avocatUser = null;

    #[ORM\ManyToOne(inversedBy: 'specialite')]
    private ?Specialite $specialite = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'avocat', targetEntity: Commentaire::class, cascade: ['persist', 'remove'])]
    private Collection $commentaires;

    #[ORM\Column(type:"string", length: 255, nullable: true)]
    private $photo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAvocatUser(): ?Users
    {
        return $this->avocatUser;
    }

    public function setAvocatUser(?Users $avocatUser): static
    {
        $this->avocatUser = $avocatUser;

        return $this;
    }

    public function getspecialite(): ?Specialite
    {
        return $this->specialite;
    }

    public function setspecialite(?Specialite $specialite): static
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setAvocat($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getAvocat() === $this) {
                $commentaire->setAvocat(null);
            }
        }

        return $this;
    }

}
