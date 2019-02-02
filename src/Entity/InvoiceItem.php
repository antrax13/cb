<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvoiceItemRepository")
 */
class InvoiceItem
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
    private $name;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotBlank()
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $qty;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Invoice", inversedBy="invoiceItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $invoice;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $weightPerItem;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPaypalItem = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isShippingItem = false;

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

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getQty(): ?int
    {
        return $this->qty;
    }

    public function setQty(int $qty): self
    {
        $this->qty = $qty;

        return $this;
    }

    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function getWeightPerItem()
    {
        return $this->weightPerItem;
    }

    public function setWeightPerItem($weightPerItem): self
    {
        $this->weightPerItem = $weightPerItem;

        return $this;
    }

    public function getIsPaypalItem(): ?bool
    {
        return $this->isPaypalItem;
    }

    public function setIsPaypalItem(bool $isPaypalItem): self
    {
        $this->isPaypalItem = $isPaypalItem;

        return $this;
    }

    public function getIsShippingItem(): ?bool
    {
        return $this->isShippingItem;
    }

    public function setIsShippingItem(bool $isShippingItem): self
    {
        $this->isShippingItem = $isShippingItem;

        return $this;
    }
}
