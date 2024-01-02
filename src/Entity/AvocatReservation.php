<?php

namespace App\Entity;

use App\Repository\AvocatReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvocatReservationRepository::class)]
class AvocatReservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Avocat $avocatreservation = null;

    #[ORM\OneToMany(mappedBy: 'paiementreservation', targetEntity: Paiement::class)]
    private Collection $paiementreservation;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Reservations $reservationavocat = null;

    public function __construct()
    {
        $this->paiementreservation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAvocatreservation(): ?Avocat
    {
        return $this->avocatreservation;
    }

    public function setAvocatreservation(?Avocat $avocatreservation): static
    {
        $this->avocatreservation = $avocatreservation;

        return $this;
    }

    /**
     * @return Collection<int, Paiement>
     */
    public function getPaiementreservation(): Collection
    {
        return $this->paiementreservation;
    }

    public function addPaiementreservation(Paiement $paiementreservation): static
    {
        if (!$this->paiementreservation->contains($paiementreservation)) {
            $this->paiementreservation->add($paiementreservation);
            $paiementreservation->setPaiementreservation($this);
        }

        return $this;
    }

    public function removePaiementreservation(Paiement $paiementreservation): static
    {
        if ($this->paiementreservation->removeElement($paiementreservation)) {
            // set the owning side to null (unless already changed)
            if ($paiementreservation->getPaiementreservation() === $this) {
                $paiementreservation->setPaiementreservation(null);
            }
        }

        return $this;
    }

    public function getReservationavocat(): ?Reservations
    {
        return $this->reservationavocat;
    }

    public function setReservationavocat(?Reservations $reservationavocat): static
    {
        $this->reservationavocat = $reservationavocat;

        return $this;
    }
}
