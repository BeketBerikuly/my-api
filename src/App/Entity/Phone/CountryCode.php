<?php

namespace App\Entity\Phone;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="country_codes")
 */
class CountryCode
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $code;
    /**
     * @ORM\Column(name="short_name", type="string", nullable=true)
     */
    private $shortName;
    /**
     * @ORM\Column(name="full_name", type="string", nullable=true)
     */
    private $fullName;

    public function __construct(string $code, string $shortName, string $fullName)
    {
        $this->code = $code;
        $this->shortName = $shortName;
        $this->fullName = $fullName;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function getShortName(): string
    {
        return $this->shortName;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }
}
