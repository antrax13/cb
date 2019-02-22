<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CountryRepository")
 */
class Country
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
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $fedexCode;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $fedexDeliveryDay;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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

    public function __toString()
    {
        return $this->getName();
    }

    public function getFedexCode(): ?string
    {
        return $this->fedexCode;
    }

    public function setFedexCode(?string $fedexCode): self
    {
        $this->fedexCode = $fedexCode;

        return $this;
    }

    public function getFedexDeliveryDay(): ?int
    {
        return $this->fedexDeliveryDay;
    }

    public function setFedexDeliveryDay(?int $fedexDeliveryDay): self
    {
        $this->fedexDeliveryDay = $fedexDeliveryDay;

        return $this;
    }
}
