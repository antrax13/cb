<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvoiceRepository")
 * @UniqueEntity(fields={"quote"}, message="Quote is used in different invoice")
 */
class Invoice
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2)
     * @Assert\NotBlank()
     */
    private $vat;

    /**
     * @ORM\Column(type="datetime")
     */
    private $generatedAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank()
     */
    private $billingAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $billingAddressFirst;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InvoiceItem", mappedBy="invoice")
     */
    private $invoiceItems;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $invoicedVat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $invoicedPhone;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Quote", inversedBy="invoice", cascade={"persist", "remove"})
     */
    private $quote;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $shippingAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $shippingPhone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRecalculationRequired;

    public function __construct()
    {
        $this->invoiceItems = new ArrayCollection();
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getVat()
    {
        return $this->vat;
    }

    public function setVat($vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    public function getGeneratedAt(): ?\DateTimeInterface
    {
        return $this->generatedAt;
    }

    public function setGeneratedAt(\DateTimeInterface $generatedAt): self
    {
        $this->generatedAt = $generatedAt;

        return $this;
    }

    public function getBillingAddress(): ?string
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(?string $billingAddress): self
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    public function getBillingAddressFirst(): ?string
    {
        return $this->billingAddressFirst;
    }

    public function setBillingAddressFirst(?string $billingAddressFirst): self
    {
        $this->billingAddressFirst = $billingAddressFirst;

        return $this;
    }

    /**
     * @return Collection|InvoiceItem[]
     */
    public function getInvoiceItems(): Collection
    {
        return $this->invoiceItems;
    }

    public function addInvoiceItem(InvoiceItem $invoiceItem): self
    {
        if (!$this->invoiceItems->contains($invoiceItem)) {
            $this->invoiceItems[] = $invoiceItem;
            $invoiceItem->setInvoice($this);
        }

        return $this;
    }

    public function removeInvoiceItem(InvoiceItem $invoiceItem): self
    {
        if ($this->invoiceItems->contains($invoiceItem)) {
            $this->invoiceItems->removeElement($invoiceItem);
            // set the owning side to null (unless already changed)
            if ($invoiceItem->getInvoice() === $this) {
                $invoiceItem->setInvoice(null);
            }
        }

        return $this;
    }

    public function getInvoicedVat(): ?string
    {
        return $this->invoicedVat;
    }

    public function setInvoicedVat(?string $invoicedVat): self
    {
        $this->invoicedVat = $invoicedVat;

        return $this;
    }

    public function getInvoicedPhone(): ?string
    {
        return $this->invoicedPhone;
    }

    public function setInvoicedPhone(?string $invoicedPhone): self
    {
        $this->invoicedPhone = $invoicedPhone;

        return $this;
    }

    public function getQuote(): ?Quote
    {
        return $this->quote;
    }

    public function setQuote(?Quote $quote): self
    {
        $this->quote = $quote;

        return $this;
    }

    public function getShippingAddress(): ?string
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(?string $shippingAddress): self
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    public function getShippingPhone(): ?string
    {
        return $this->shippingPhone;
    }

    public function setShippingPhone(?string $shippingPhone): self
    {
        $this->shippingPhone = $shippingPhone;

        return $this;
    }

    public function getIsRecalculationRequired(): ?bool
    {
        return $this->isRecalculationRequired;
    }

    public function setIsRecalculationRequired(bool $isRecalculationRequired): self
    {
        $this->isRecalculationRequired = $isRecalculationRequired;

        return $this;
    }
}
