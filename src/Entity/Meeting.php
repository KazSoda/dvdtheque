<?php

namespace App\Entity;

use App\Repository\MeetingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeetingRepository::class)]
class Meeting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'meetings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'boughtItems')]
    #[ORM\JoinTable(name: 'meeting_bought_items')]
    private Collection $boughtItems;

    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'returnedItems')]
    #[ORM\JoinTable(name: 'meeting_returned_items')]
    private Collection $returnedItems;


    public function __construct()
    {
        $this->boughtItems = new ArrayCollection();
        $this->returnedItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getBoughtItems(): Collection
    {
        return $this->boughtItems;
    }

    public function addBoughtItem(Product $boughtItem): static
    {
        if (!$this->boughtItems->contains($boughtItem)) {
            $this->boughtItems->add($boughtItem);
            $boughtItem->addBoughtItem($this);
        }

        return $this;
    }

    public function removeBoughtItem(Product $boughtItem): static
    {
        $this->boughtItems->removeElement($boughtItem);

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getReturnedItems(): Collection
    {
        return $this->returnedItems;
    }

    public function addReturnedItem(Product $returnedItem): static
    {
        if (!$this->returnedItems->contains($returnedItem)) {
            $this->returnedItems->add($returnedItem);
            $returnedItem->addReturnedItem($this);
        }

        return $this;
    }

    public function removeReturnedItem(Product $returnedItem): static
    {
        $this->returnedItems->removeElement($returnedItem);

        return $this;
    }
}
