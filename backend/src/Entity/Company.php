<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user_group"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     * @Groups({"user_group"})
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"user_group"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user_group"})
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     * @Groups({"user_group"})
     */
    private $sirenNumber;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCustomer;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Discount", inversedBy="companies")
     */
    private $discount;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CompanyAddress", mappedBy="company")
     */
    private $companyAddresses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contact", mappedBy="company")
     */
    private $contacts;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="companies")
     */
    private $user;

    public function __construct()
    {
        $this->companyAddresses = new ArrayCollection();
        $this->contacts = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSirenNumber(): ?string
    {
        return $this->sirenNumber;
    }

    public function setSirenNumber(?string $sirenNumber): self
    {
        $this->sirenNumber = $sirenNumber;

        return $this;
    }

    public function getIsCustomer(): ?bool
    {
        return $this->isCustomer;
    }

    public function setIsCustomer(bool $isCustomer): self
    {
        $this->isCustomer = $isCustomer;

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

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    public function setDiscount(?Discount $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @return Collection|CompanyAddress[]
     */
    public function getCompanyAddresses(): Collection
    {
        return $this->companyAddresses;
    }

    public function addCompanyAddress(CompanyAddress $companyAddress): self
    {
        if (!$this->companyAddresses->contains($companyAddress)) {
            $this->companyAddresses[] = $companyAddress;
            $companyAddress->setCompany($this);
        }

        return $this;
    }

    public function removeCompanyAddress(CompanyAddress $companyAddress): self
    {
        if ($this->companyAddresses->contains($companyAddress)) {
            $this->companyAddresses->removeElement($companyAddress);
            // set the owning side to null (unless already changed)
            if ($companyAddress->getCompany() === $this) {
                $companyAddress->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Contact[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setCompany($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->contains($contact)) {
            $this->contacts->removeElement($contact);
            // set the owning side to null (unless already changed)
            if ($contact->getCompany() === $this) {
                $contact->setCompany(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $result = false;

        //un $user peut être NULL dans le cas où on désassigne un Commercial d'une société (phil)
        if ($user != NULL) {
            foreach($user->getUserRoles() as $userRole) {
                if ($userRole->getCode() == 'ROLE_SALES') {
                    $result = true;
                }
            }
            if ($result) {
                $this->user = $user;
            }
        } else {
            $this->user = $user;
        }
        
        return $this;
    }
}
