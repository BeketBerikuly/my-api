<?php

namespace App\Entity\Phone;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="phones")
 */
class Phone
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $phone_number;
    /**
     * @var ArrayCollection|Feedback[]
     * @ORM\OneToMany(targetEntity="Feedback", mappedBy="phone", orphanRemoval=true, cascade={"persist"})
     * @ORM\OrderBy({"date" = "ASC"})
     */
    private $feedbacks;

    public function __construct(string $phone_number)
    {
        $this->phone_number = $phone_number;
        $this->feedbacks = new ArrayCollection();
    }

    public function edit(string $phone_number): void
    {
        $this->phone_number = $phone_number;
    }

    public function addFeedback(string $text, ?string $name, int $rating): void
    {
        $this->feedbacks->add(new Feedback($this, $text, $name, $rating));
    }

    public function removeFeedback(int $id): void
    {
        foreach ($this->feedbacks as $feedback) {
            if ($feedback->getId() === $id) {
                $this->feedbacks->removeElement($feedback);
            }
        }
        throw new \DomainException('Feedback is not found.');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPhoneNumber(): string
    {
        return $this->phone_number;
    }

    /**
     * @return Feedback[]
     */
    public function getFeedbacks(): array
    {
        return $this->feedbacks->toArray();
    }
}
