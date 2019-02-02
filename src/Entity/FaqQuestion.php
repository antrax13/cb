<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FaqQuestionRepository")
 */
class FaqQuestion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FaqCategory", inversedBy="faqQuestions")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $faqCategory;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $question;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $answer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFaqCategory(): ?FaqCategory
    {
        return $this->faqCategory;
    }

    public function setFaqCategory(?FaqCategory $faqCategory): self
    {
        $this->faqCategory = $faqCategory;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(?string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }
}
