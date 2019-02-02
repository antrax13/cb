<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FaqCategoryRepository")
 */
class FaqCategory
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
     * @ORM\OneToMany(targetEntity="App\Entity\FaqQuestion", mappedBy="faqCategory")
     */
    private $faqQuestions;

    public function __construct()
    {
        $this->faqQuestions = new ArrayCollection();
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

    /**
     * @return Collection|FaqQuestion[]
     */
    public function getFaqQuestions(): Collection
    {
        return $this->faqQuestions;
    }

    public function addFaqQuestion(FaqQuestion $faqQuestion): self
    {
        if (!$this->faqQuestions->contains($faqQuestion)) {
            $this->faqQuestions[] = $faqQuestion;
            $faqQuestion->setFaqCategory($this);
        }

        return $this;
    }

    public function removeFaqQuestion(FaqQuestion $faqQuestion): self
    {
        if ($this->faqQuestions->contains($faqQuestion)) {
            $this->faqQuestions->removeElement($faqQuestion);
            // set the owning side to null (unless already changed)
            if ($faqQuestion->getFaqCategory() === $this) {
                $faqQuestion->setFaqCategory(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getValue();
    }
}
