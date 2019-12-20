<?php

namespace App\Document;

use App\Repository\RestaurantRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JsonSerializable;

/**
 * Class Restaurant
 *
 * @package App\Document
 *
 * @MongoDB\Document(repositoryClass=RestaurantRepository::class)
 */
class Restaurant implements JsonSerializable
{
    /**
     * @MongoDB\Id()
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     *
     * @var string
     */
    protected $name;

    /**
     * @MongoDB\Field(type="string")
     *
     * @var string
     */
    protected $address;

    /**
     * @MongoDB\Field(type="string")
     *
     * @var string
     */
    protected $zipCode;

    /**
     * @MongoDB\Field(type="string")
     *
     * @var string
     */
    protected $city;

    /**
     * @MongoDB\Field(type="string")
     *
     * @var string
     */
    protected $phone;

    /**
     * @MongoDB\EmbedOne(targetDocument=Coordinates::class)
     *
     * @var Coordinates
     */
    public $coordinates;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Restaurant
     */
    public function setName(string $name): Restaurant
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return Restaurant
     */
    public function setAddress(string $address): Restaurant
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     *
     * @return Restaurant
     */
    public function setZipCode(string $zipCode): Restaurant
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return Restaurant
     */
    public function setCity(string $city): Restaurant
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return Restaurant
     */
    public function setPhone(string $phone): Restaurant
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'name'        => $this->name,
            'coordinates' => $this->coordinates,
        ];
    }

}