<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RequestRepository")
 */
class Request
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
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"user_group"})
     */
    private $body;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\HandlingStatus", inversedBy="requests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $handlingStatus;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RequestType", inversedBy="requests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $requestType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contact", inversedBy="requests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contact;

    public function __toString()
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

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

    public function getHandlingStatus(): ?HandlingStatus
    {
        return $this->handlingStatus;
    }

    public function setHandlingStatus(?HandlingStatus $handlingStatus): self
    {
        $this->handlingStatus = $handlingStatus;

        return $this;
    }

    public function getRequestType(): ?RequestType
    {
        return $this->requestType;
    }

    public function setRequestType(?RequestType $requestType): self
    {
        $this->requestType = $requestType;

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }
}
