<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StampQuoteSketchRepository")
 */
class StampQuoteSketch
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\StampQuote", inversedBy="stampQuoteSketches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $stampQuote;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $originalFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $extension;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileSize;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\StampType", inversedBy="stampQuoteSketches")
     */
    private $stampType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\StampShape", inversedBy="stampQuoteSketches")
     */
    private $stampShape;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isSphere;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sphereDiameter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sizeSideA;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sizeSideB;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sizeDiameter;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sizeCustomNote;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sizeNote;


    /**
     * @ORM\Column(type="integer")
     */
    private $qty;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\HandleColor")
     */
    private $handleColor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\HandleShape")
     */
    private $handleShape;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStampQuote(): ?StampQuote
    {
        return $this->stampQuote;
    }

    public function setStampQuote(?StampQuote $stampQuote): self
    {
        $this->stampQuote = $stampQuote;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getOriginalFile(): ?string
    {
        return $this->originalFile;
    }

    public function setOriginalFile(?string $originalFile): self
    {
        $this->originalFile = $originalFile;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(?string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getFileSize(): ?string
    {
        return $this->fileSize;
    }

    public function setFileSize(?string $fileSize): self
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    public function getStampType(): ?StampType
    {
        return $this->stampType;
    }

    public function setStampType(?StampType $stampType): self
    {
        $this->stampType = $stampType;

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

    public function getIsSphere(): ?bool
    {
        return $this->isSphere;
    }

    public function setIsSphere(?bool $isSphere): self
    {
        $this->isSphere = $isSphere;

        return $this;
    }

    public function getSphereDiameter(): ?string
    {
        return $this->sphereDiameter;
    }

    public function setSphereDiameter(?string $sphereDiameter): self
    {
        $this->sphereDiameter = $sphereDiameter;

        return $this;
    }

    public function getSizeSideA(): ?string
    {
        return $this->sizeSideA;
    }

    public function setSizeSideA(?string $sizeSideA): self
    {
        $this->sizeSideA = $sizeSideA;

        return $this;
    }

    public function getSizeSideB(): ?string
    {
        return $this->sizeSideB;
    }

    public function setSizeSideB(?string $sizeSideB): self
    {
        $this->sizeSideB = $sizeSideB;

        return $this;
    }

    public function getSizeDiameter(): ?string
    {
        return $this->sizeDiameter;
    }

    public function setSizeDiameter(?string $sizeDiameter): self
    {
        $this->sizeDiameter = $sizeDiameter;

        return $this;
    }

    public function getSizeCustomNote(): ?string
    {
        return $this->sizeCustomNote;
    }

    public function setSizeCustomNote(?string $sizeCustomNote): self
    {
        $this->sizeCustomNote = $sizeCustomNote;

        return $this;
    }

    public function getSizeNote(): ?string
    {
        return $this->sizeNote;
    }

    public function setSizeNote(?string $sizeNote): self
    {
        $this->sizeNote = $sizeNote;

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

    public function getHandleColor(): ?HandleColor
    {
        return $this->handleColor;
    }

    public function setHandleColor(?HandleColor $handleColor): self
    {
        $this->handleColor = $handleColor;

        return $this;
    }

    public function getHandleShape(): ?HandleShape
    {
        return $this->handleShape;
    }

    public function setHandleShape(?HandleShape $handleShape): self
    {
        $this->handleShape = $handleShape;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
