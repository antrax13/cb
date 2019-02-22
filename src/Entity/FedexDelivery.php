<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FedexDeliveryRepository")
 */
class FedexDelivery
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $codeA;

    /**
     * @ORM\Column(type="integer")
     */
    private $codeB;

    /**
     * @ORM\Column(type="integer")
     */
    private $codeC;

    /**
     * @ORM\Column(type="integer")
     */
    private $codeD;

    /**
     * @ORM\Column(type="integer")
     */
    private $codeE;

    /**
     * @ORM\Column(type="integer")
     */
    private $codeEu1;

    /**
     * @ORM\Column(type="integer")
     */
    private $codeEu2;

    /**
     * @ORM\Column(type="integer")
     */
    private $codeEu3;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $weight;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeA(): ?int
    {
        return $this->codeA;
    }

    public function setCodeA(int $codeA): self
    {
        $this->codeA = $codeA;

        return $this;
    }

    public function getCodeB(): ?int
    {
        return $this->codeB;
    }

    public function setCodeB(int $codeB): self
    {
        $this->codeB = $codeB;

        return $this;
    }

    public function getCodeC(): ?int
    {
        return $this->codeC;
    }

    public function setCodeC(int $codeC): self
    {
        $this->codeC = $codeC;

        return $this;
    }

    public function getCodeD(): ?int
    {
        return $this->codeD;
    }

    public function setCodeD(int $codeD): self
    {
        $this->codeD = $codeD;

        return $this;
    }

    public function getCodeE(): ?int
    {
        return $this->codeE;
    }

    public function setCodeE(int $codeE): self
    {
        $this->codeE = $codeE;

        return $this;
    }

    public function getCodeEu1(): ?int
    {
        return $this->codeEu1;
    }

    public function setCodeEu1(int $codeEu1): self
    {
        $this->codeEu1 = $codeEu1;

        return $this;
    }

    public function getCodeEu2(): ?int
    {
        return $this->codeEu2;
    }

    public function setCodeEu2(int $codeEu2): self
    {
        $this->codeEu2 = $codeEu2;

        return $this;
    }

    public function getCodeEu3(): ?int
    {
        return $this->codeEu3;
    }

    public function setCodeEu3(int $codeEu3): self
    {
        $this->codeEu3 = $codeEu3;

        return $this;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setWeight($weight): self
    {
        $this->weight = $weight;

        return $this;
    }
}
