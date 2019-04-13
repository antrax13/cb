<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomProductInfoRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class CustomProductInfo
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
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @Assert\File(mimeTypesMessage="Please upload a valid Image")
     */
    private $image2;


    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $intro;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="text")
     */
    private $details;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $fetchOrder;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"type"})
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $createdBy;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $updatedBy;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFeatured = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductReference", mappedBy="customProduct")
     * @ORM\OrderBy({"position"="ASC"})
     */
    private $productReferences;

    public function __construct()
    {
        $this->productReferences = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getIntro(): ?string
    {
        return $this->intro;
    }

    public function setIntro(string $intro): self
    {
        $this->intro = $intro;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): self
    {
        $this->details = $details;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getFetchOrder(): ?int
    {
        return $this->fetchOrder;
    }

    public function setFetchOrder(int $fetchOrder): self
    {
        $this->fetchOrder = $fetchOrder;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(string $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(string $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage2()
    {
        return $this->image2;
    }

    /**
     * @param mixed $image2
     */
    public function setImage2($image2): void
    {
        $this->image2 = $image2;
    }

    public function getIsFeatured(): ?bool
    {
        return $this->isFeatured;
    }

    public function setIsFeatured(bool $isFeatured): self
    {
        $this->isFeatured = $isFeatured;

        return $this;
    }

    /**
     * @return Collection|ProductReference[]
     */
    public function getProductReferences(): Collection
    {
        return $this->productReferences;
    }

    public function addProductReference(ProductReference $productReference): self
    {
        if (!$this->productReferences->contains($productReference)) {
            $this->productReferences[] = $productReference;
            $productReference->setCustomProduct($this);
        }

        return $this;
    }

    public function removeProductReference(ProductReference $productReference): self
    {
        if ($this->productReferences->contains($productReference)) {
            $this->productReferences->removeElement($productReference);
            // set the owning side to null (unless already changed)
            if ($productReference->getCustomProduct() === $this) {
                $productReference->setCustomProduct(null);
            }
        }

        return $this;
    }

}
