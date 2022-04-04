<?php

namespace App\Entity\Phone;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="feedbacks")
 */
class Feedback
{
    /**
     * @ORM\ManyToOne(targetEntity="Phone", inversedBy="feedbacks")
     * @ORM\JoinColumn(name="phone_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $phone;
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="text")
     */
    private $text;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;
    /**
     * @ORM\Column(type="integer")
     */
    private $rating;


    public function __construct(Phone $phone, string $text, ?string $name, int $rating)
    {
        $this->phone = $phone;
        $this->text = $text;
        $this->name = $name;
        $this->rating = $rating;
    }

    public function edit($text): void
    {
        $this->text = $text;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getRating(): float
    {
        return $this->rating;
    }
}
