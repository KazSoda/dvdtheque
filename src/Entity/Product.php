<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $content = null;

    #[ORM\ManyToMany(targetEntity: Meeting::class, mappedBy: 'boughtItems')]
    private Collection $boughtItems;

    #[ORM\ManyToMany(targetEntity: Meeting::class, mappedBy: 'returnedItems')]
    private Collection $returnedItems;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $price = null;

    #[ORM\Column]
    private ?int $quantity = null;

    public function __construct()
    {
        $this->boughtItems = new ArrayCollection();
        $this->returnedItems = new ArrayCollection();
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

    public function getContent(): ?Article
    {
        return $this->content;
    }

    public function setContent(?Article $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return Collection<int, Meeting>
     */
    public function getBoughtItems(): Collection
    {
        return $this->boughtItems;
    }

    public function addBoughtItem(Meeting $boughtItem): static
    {
        if (!$this->boughtItems->contains($boughtItem)) {
            $this->boughtItems->add($boughtItem);
            $boughtItem->addBoughtItem($this);
        }

        return $this;
    }

    public function removeBoughtItem(Meeting $boughtItem): static
    {
        if ($this->boughtItems->removeElement($boughtItem)) {
            $boughtItem->removeBoughtItem($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Meeting>
     */
    public function getReturnedItems(): Collection
    {
        return $this->returnedItems;
    }

    public function addReturnedItem(Meeting $returnedItem): static
    {
        if (!$this->returnedItems->contains($returnedItem)) {
            $this->returnedItems->add($returnedItem);
            $returnedItem->addReturnedItem($this);
        }

        return $this;
    }

    public function removeReturnedItem(Meeting $returnedItem): static
    {
        if ($this->returnedItems->removeElement($returnedItem)) {
            $returnedItem->removeReturnedItem($this);
        }

        return $this;
    }
}
