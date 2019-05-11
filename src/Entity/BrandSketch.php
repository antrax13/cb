<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BrandSketchRepository")
 */
class BrandSketch
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $file;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Assert\NotBlank()
     */
    private $weight;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Assert\NotBlank()
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     */
    private $dimension;
    

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quote", inversedBy="brandSketches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quote;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $extension;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $originalFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $size;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRemoved = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\StampType")
     * @Assert\NotBlank()
     */
    private $stampType;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $handle;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank()
     */
    private $qty;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\StampShape")
     * @Assert\NotBlank()
     */
    private $stampShape;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $position = 0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SketchReference", mappedBy="brandSketch")
     * @ORM\OrderBy({"createdAt"="DESC"})
     */
    private $sketchReferences;

    public function __construct()
    {
        $this->sketchReferences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

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

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDimension(): ?string
    {
        return $this->dimension;
    }

    public function setDimension(?string $dimension): self
    {
        $this->dimension = $dimension;

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

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getOriginalFile(): ?string
    {
        return $this->originalFile;
    }

    public function setOriginalFile(string $originalFile): self
    {
        $this->originalFile = $originalFile;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): self
    {
        $this->size = $size;

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

    public function getstampType(): ?StampType
    {
        return $this->stampType;
    }

    public function setstampType(?StampType $stampType): self
    {
        $this->stampType = $stampType;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getHandle(): ?string
    {
        return $this->handle;
    }

    public function setHandle(?string $handle): self
    {
        $this->handle = $handle;

        return $this;
    }

    public function getQty(): ?int
    {
        return $this->qty;
    }

    public function setQty(?int $qty): self
    {
        $this->qty = $qty;

        return $this;
    }

    public function getStampShape(): ?StampShape
    {
        return $this->stampShape;
    }

    public function setStampShape(?StampShape $stampShape): self
    {
        $this->stampShape = $stampShape;

        return $this;
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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Collection|SketchReference[]
     */
    public function getSketchReferences(): Collection
    {
        return $this->sketchReferences;
    }

    public function addSketchReference(SketchReference $sketchReference): self
    {
        if (!$this->sketchReferences->contains($sketchReference)) {
            $this->sketchReferences[] = $sketchReference;
            $sketchReference->setBrandSketch($this);
        }

        return $this;
    }

    public function removeSketchReference(SketchReference $sketchReference): self
    {
        if ($this->sketchReferences->contains($sketchReference)) {
            $this->sketchReferences->removeElement($sketchReference);
            // set the owning side to null (unless already changed)
            if ($sketchReference->getBrandSketch() === $this) {
                $sketchReference->setBrandSketch(null);
            }
        }

        return $this;
    }

}
