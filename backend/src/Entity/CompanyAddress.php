<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyAddressRepository")
 */
class CompanyAddress
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $firstAddressField;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $secondAddressField;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $country;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\CompanyAddressType", inversedBy="companyAddresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $companyAddressType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="companyAddresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    public function __toString()
    {
        return $this->firstAddressField . ' ' . $this->secondAddressField . ' ' . $this->postalCode . ' ' . $this->city;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstAddressField(): ?string
    {
        return $this->firstAddressField;
    }

    public function setFirstAddressField(string $firstAddressField): self
    {
        $this->firstAddressField = $firstAddressField;

        return $this;
    }

    public function getSecondAddressField(): ?string
    {
        return $this->secondAddressField;
    }

    public function setSecondAddressField(?string $secondAddressField): self
    {
        $this->secondAddressField = $secondAddressField;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

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

    public function getCompanyAddressType(): ?CompanyAddressType
    {
        return $this->companyAddressType;
    }

    public function setCompanyAddressType(?CompanyAddressType $companyAddressType): self
    {
        $this->companyAddressType = $companyAddressType;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }
}
