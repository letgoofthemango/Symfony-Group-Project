<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Producer
 *
 * @ORM\Table(name="producer", indexes={@ORM\Index(name="address_Id", columns={"address_Id"})})
 * @ORM\Entity
 */
class Producer
{
    /**
     * @var int
     *
     * @ORM\Column(name="producer_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $producerId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="producerName", type="string", length=255, nullable=true)
     */
    private $producername;

    /**
     * @var string|null
     *
     * @ORM\Column(name="website", type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @var \Address
     *
     * @ORM\ManyToOne(targetEntity="Address")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="address_Id", referencedColumnName="address_Id")
     * })
     */
    private $address;

    public function getProducerId(): ?int
    {
        return $this->producerId;
    }

    public function getProducername(): ?string
    {
        return $this->producername;
    }

    public function setProducername(?string $producername): self
    {
        $this->producername = $producername;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }


}
