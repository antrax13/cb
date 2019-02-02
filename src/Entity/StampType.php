<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StampTypeRepository")
 */
class StampType
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
    private $value;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StampQuoteSketch", mappedBy="stampType")
     */
    private $stampQuoteSketches;

    public function __construct()
    {
        $this->stampQuoteSketches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

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

    public function __toString()
    {
        return $this->getValue();
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
            $stampQuoteSketch->setStampType($this);
        }

        return $this;
    }

    public function removeStampQuoteSketch(StampQuoteSketch $stampQuoteSketch): self
    {
        if ($this->stampQuoteSketches->contains($stampQuoteSketch)) {
            $this->stampQuoteSketches->removeElement($stampQuoteSketch);
            // set the owning side to null (unless already changed)
            if ($stampQuoteSketch->getStampType() === $this) {
                $stampQuoteSketch->setStampType(null);
            }
        }

        return $this;
    }
}
