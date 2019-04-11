<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StampQuoteRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class StampQuote
{
    const CUSTOM_CREATE_QUOTE_KEY = '_create_stamp.quote';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StampQuoteSketch", mappedBy="stampQuote")
     */
    private $stampQuoteSketches;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $identifier;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $additionalComment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shippingCountry;

    public function __construct()
    {
        $this->stampQuoteSketches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|StampQuoteSketch[]
     */
    public function getStampQuoteSketches(): Collection
    {
        return $this->stampQuoteSketches;
    }

    public function addStampQuoteSketch(StampQuoteSketch $stampQuoteSketch): self
    {
        if (!$this->stampQuoteSketches->contains($stampQuoteSketch)) {
            $this->stampQuoteSketches[] = $stampQuoteSketch;
            $stampQuoteSketch->setStampQuote($this);
        }

        return $this;
    }

    public function removeStampQuoteSketch(StampQuoteSketch $stampQuoteSketch): self
    {
        if ($this->stampQuoteSketches->contains($stampQuoteSketch)) {
            $this->stampQuoteSketches->removeElement($stampQuoteSketch);
            // set the owning side to null (unless already changed)
            if ($stampQuoteSketch->getStampQuote() === $this) {
                $stampQuoteSketch->setStampQuote(null);
            }
        }

        return $this;
    }


    /**
     * @ORM\PrePersist()
     */
    public function updateDates()
    {
        $this->setUpdatedAt(new \DateTime());
        if(is_null($this->createdAt)){
            $this->setCreatedAt(new \DateTime());
        }
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getAdditionalComment(): ?string
    {
        return $this->additionalComment;
    }

    public function setAdditionalComment(?string $additionalComment): self
    {
        $this->additionalComment = $additionalComment;

        return $this;
    }

    public function getShippingCountry(): ?Country
    {
        return $this->shippingCountry;
    }

    public function setShippingCountry(?Country $shippingCountry): self
    {
        $this->shippingCountry = $shippingCountry;

        return $this;
    }
}
