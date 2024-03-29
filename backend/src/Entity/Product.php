<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", length=128, unique=true)
     * @Groups({"product_group"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     * @Groups({"product_group"})
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=128)
     * @Groups({"product_group"})
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"product_group"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"product_group"})
     */
    private $picture;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"product_group"})
     */
    private $listPrice;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"product_group"})
     */
    private $maxDiscountRate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isOnHomePage;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"product_group"})
     */
    private $rank;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="products")
     * @ORM\OrderBy({"name" = "ASC"})
     * @Groups({"product_group"})
     */
    private $categories;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAvailable;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RequestDetail", mappedBy="product")
     */
    private $requestDetails;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->requestDetails = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name . ' - ' . substr($this->description, 0, 20);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getListPrice(): ?int
    {
        return $this->listPrice;
    }

    public function setListPrice(int $listPrice): self
    {
        $this->listPrice = $listPrice;

        return $this;
    }

    public function getMaxDiscountRate(): ?int
    {
        return $this->maxDiscountRate;
    }

    public function setMaxDiscountRate(?int $maxDiscountRate): self
    {
        $this->maxDiscountRate = $maxDiscountRate;

        return $this;
    }

    public function getIsOnHomePage(): ?bool
    {
        return $this->isOnHomePage;
    }

    public function setIsOnHomePage(bool $isOnHomePage): self
    {
        $this->isOnHomePage = $isOnHomePage;

        return $this;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(int $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    public function getIsAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(bool $isAvailable): self
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    /**
     * @return Collection|RequestDetail[]
     */
    public function getRequestDetails(): Collection
    {
        return $this->requestDetails;
    }

    public function addRequestDetail(RequestDetail $requestDetail): self
    {
        if (!$this->requestDetails->contains($requestDetail)) {
            $this->requestDetails[] = $requestDetail;
            $requestDetail->setProduct($this);
        }

        return $this;
    }

    public function removeRequestDetail(RequestDetail $requestDetail): self
    {
        if ($this->requestDetails->contains($requestDetail)) {
            $this->requestDetails->removeElement($requestDetail);
            // set the owning side to null (unless already changed)
            if ($requestDetail->getProduct() === $this) {
                $requestDetail->setProduct(null);
            }
        }

        return $this;
    }
}
