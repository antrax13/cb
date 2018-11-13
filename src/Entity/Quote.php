<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuoteRepository")
 */
class Quote
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $request;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $answer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="quotes")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $customer;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BrandSketch", mappedBy="quote")
     */
    private $brandSketches;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRemoved = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $shippingTo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Shipping")
     */
    private $shipping;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $generatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $status = 'Draft';

    public function __construct()
    {
        $this->brandSketches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequest(): ?string
    {
        return $this->request;
    }

    public function setRequest(?string $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection|BrandSketch[]
     */
    public function getBrandSketches(): Collection
    {
        return $this->brandSketches;
    }

    /**
     * @return Collection|BrandSketch[]
     */
    public function getBrandSketchesNotRemoved(): Collection
    {
        return $this->getBrandSketches()->filter(function(BrandSketch $sketch){
            return $sketch->getIsRemoved() == false;
        });
    }

    public function addBrandSketch(BrandSketch $brandSketch): self
    {
        if (!$this->brandSketches->contains($brandSketch)) {
            $this->brandSketches[] = $brandSketch;
            $brandSketch->setQuote($this);
        }

        return $this;
    }

    public function removeBrandSketch(BrandSketch $brandSketch): self
    {
        if ($this->brandSketches->contains($brandSketch)) {
            $this->brandSketches->removeElement($brandSketch);
            // set the owning side to null (unless already changed)
            if ($brandSketch->getQuote() === $this) {
                $brandSketch->setQuote(null);
            }
        }

        return $this;
    }

    public function getIsRemoved(): ?bool
    {
        return $this->isRemoved;
    }

    public function setIsRemoved(bool $isRemoved): self
    {
        $this->isRemoved = $isRemoved;

        return $this;
    }

    public function getShippingTo(): ?string
    {
        return $this->shippingTo;
    }

    public function setShippingTo(?string $shippingTo): self
    {
        $this->shippingTo = $shippingTo;

        return $this;
    }

    public function getShipping(): ?Shipping
    {
        return $this->shipping;
    }

    public function setShipping(?Shipping $shipping): self
    {
        $this->shipping = $shipping;

        return $this;
    }

    public function getGeneratedAt(): ?\DateTimeInterface
    {
        return $this->generatedAt;
    }

    public function setGeneratedAt(?\DateTimeInterface $generatedAt): self
    {
        $this->generatedAt = $generatedAt;

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

}
