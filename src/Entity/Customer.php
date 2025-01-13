<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Product>
     */
    #[ORM\ManyToMany(targetEntity: Product::class)]
    private Collection $products_currently_owned;

    /**
     * @var Collection<int, Meeting>
     */
    #[ORM\OneToMany(targetEntity: Meeting::class, mappedBy: 'customer', orphanRemoval: true)]
    private Collection $meetings;

    public function __construct()
    {
        $this->products_currently_owned = new ArrayCollection();
        $this->meetings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProductsCurrentlyOwned(): Collection
    {
        return $this->products_currently_owned;
    }

    public function addProductsCurrentlyOwned(Product $productsCurrentlyOwned): static
    {
        if (!$this->products_currently_owned->contains($productsCurrentlyOwned)) {
            $this->products_currently_owned->add($productsCurrentlyOwned);
        }

        return $this;
    }

    public function removeProductsCurrentlyOwned(Product $productsCurrentlyOwned): static
    {
        $this->products_currently_owned->removeElement($productsCurrentlyOwned);

        return $this;
    }

    /**
     * @return Collection<int, Meeting>
     */
    public function getMeetings(): Collection
    {
        return $this->meetings;
    }

    public function addMeeting(Meeting $meeting): static
    {
        if (!$this->meetings->contains($meeting)) {
            $this->meetings->add($meeting);
            $meeting->setCustomer($this);
        }

        return $this;
    }

    public function removeMeeting(Meeting $meeting): static
    {
        if ($this->meetings->removeElement($meeting)) {
            // set the owning side to null (unless already changed)
            if ($meeting->getCustomer() === $this) {
                $meeting->setCustomer(null);
            }
        }

        return $this;
    }
}
