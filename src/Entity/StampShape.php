<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StampShapeRepository")
 */
class StampShape
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
     * @ORM\OneToMany(targetEntity="App\Entity\StampQuoteSketch", mappedBy="stampShape")
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
            $stampQuoteSketch->setStampShape($this);
        }

        return $this;
    }

    public function removeStampQuoteSketch(StampQuoteSketch $stampQuoteSketch): self
    {
        if ($this->stampQuoteSketches->contains($stampQuoteSketch)) {
            $this->stampQuoteSketches->removeElement($stampQuoteSketch);
            // set the owning side to null (unless already changed)
            if ($stampQuoteSketch->getStampShape() === $this) {
                $stampQuoteSketch->setStampShape(null);
            }
        }

        return $this;
    }
}
