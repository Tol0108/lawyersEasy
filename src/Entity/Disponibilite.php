<?php

namespace App\Entity;

use App\Repository\DisponibiliteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DisponibiliteRepository::class)]
class Disponibilite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startDateTime = null;

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: 'disponibilites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $legalAdvisor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDateTime(): ?\DateTimeInterface
    {
        return $this->startDateTime;
    }

    public function setStartDateTime(?\DateTimeInterface $startDateTime): self
    {
        $this->startDateTime = $startDateTime;

        return $this;
    }

    public function getLegalAdvisor(): ?Users
    {
        return $this->legalAdvisor;
    }

    public function setLegalAdvisor(?Users $legalAdvisor): self
    {
        $this->legalAdvisor = $legalAdvisor;

        return $this;
    }
}
