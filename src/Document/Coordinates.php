<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JsonSerializable;

/**
 * Class Coordinates
 *
 * @package App\Document
 *
 * @MongoDB\EmbeddedDocument()
 */
class Coordinates implements JsonSerializable
{
    /**
     * @MongoDB\Field(type="float")
     *
     * @var float $latitude
     */
    protected $latitude;

    /**
     * @MongoDB\Field(type="float")
     *
     * @var float $longitude
     */
    protected $longitude;

    /**
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     *
     * @return Coordinates
     */
    public function setLatitude(float $latitude): Coordinates
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     *
     * @return Coordinates
     */
    public function setLongitude(float $longitude): Coordinates
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            "latitude" => $this->latitude,
            "longitude" => $this->longitude
        ];
    }
}