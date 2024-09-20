<?php

namespace App\Entity;

use App\Repository\ReservationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ReservationsRepository::class)]
#[Vich\Uploadable]
class Reservations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    #[Assert\DateTime]
    #[Assert\GreaterThan("now")]
    private ?\DateTimeInterface $date_reservation = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $status = 'en attente';

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: 'reservations')]
    private ?Users $user = null;

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: 'legalreservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $legalAdvisor = null;

    // Ajout du champ pour le document téléchargé
    #[Vich\UploadableField(mapping: 'reservation_documents', fileNameProperty: 'documentName')]
    #[Assert\File(
        maxSize: '10M',
        mimeTypes: ['application/pdf', 'application/x-pdf', 'image/*'],
        mimeTypesMessage: 'Please upload a valid PDF or image file'
    )]
    private ?File $documents = null;

    // Champ pour stocker le nom du document
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $documentName = null;

    public function __construct()
    {
        $this->status = 'en attente';
    }

    // Getters et setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->date_reservation;
    }

    public function setDateReservation(\DateTimeInterface $date_reservation): self
    {
        $this->date_reservation = $date_reservation;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;
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

    // Getter et setter pour documents
    public function setDocuments(?File $documents = null): void
    {
        $this->documents = $documents;
    }

    public function getDocuments(): ?File
    {
        return $this->documents;
    }

    // Getter et setter pour documentName
    public function setDocumentName(?string $documentName): self
    {
        $this->documentName = $documentName;
        return $this;
    }

    public function getDocumentName(): ?string
    {
        return $this->documentName;
    }
}
