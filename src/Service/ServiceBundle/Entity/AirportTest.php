<?php

namespace Service\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AirportTest
 *
 * @ORM\Table(name="airport_test", indexes={@ORM\Index(name="iata_code", columns={"iata_code"}), @ORM\Index(name="icao_code", columns={"icao_code"}), @ORM\Index(name="name_rus", columns={"name_rus"}), @ORM\Index(name="name_eng", columns={"name_eng"}), @ORM\Index(name="city_rus", columns={"city_rus"}), @ORM\Index(name="city_eng", columns={"city_eng"}), @ORM\Index(name="gmt_offset", columns={"gmt_offset"}), @ORM\Index(name="country_rus", columns={"country_rus"}), @ORM\Index(name="country_eng", columns={"country_eng"}), @ORM\Index(name="iso_code", columns={"iso_code"})})
 * @ORM\Entity
 */
class AirportTest
{
    /**
     * @var string
     *
     * @ORM\Column(name="iso_code", type="string", length=2, nullable=false)
     */
    private $isoCode;

    /**
     * @var string
     *
     * @ORM\Column(name="iata_code", type="string", length=3, nullable=false)
     */
    private $iataCode;

    /**
     * @var string
     *
     * @ORM\Column(name="icao_code", type="string", length=4, nullable=false)
     */
    private $icaoCode;

    /**
     * @var string
     *
     * @ORM\Column(name="name_eng", type="string", length=64, nullable=false)
     */
    private $nameEng;

    /**
     * @var string
     *
     * @ORM\Column(name="city_eng", type="string", length=64, nullable=false)
     */
    private $cityEng;

    /**
     * @var string
     *
     * @ORM\Column(name="country_eng", type="string", length=64, nullable=false)
     */
    private $countryEng;

    /**
     * @var string
     *
     * @ORM\Column(name="name_rus", type="string", length=64, nullable=false)
     */
    private $nameRus;

    /**
     * @var string
     *
     * @ORM\Column(name="city_rus", type="string", length=64, nullable=false)
     */
    private $cityRus;

    /**
     * @var string
     *
     * @ORM\Column(name="country_rus", type="string", length=64, nullable=false)
     */
    private $countryRus;

    /**
     * @var string
     *
     * @ORM\Column(name="gmt_offset", type="string", length=5, nullable=false)
     */
    private $gmtOffset;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="text", length=65535, nullable=false)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="text", length=65535, nullable=false)
     */
    private $longitude;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set isoCode
     *
     * @param string $isoCode
     *
     * @return AirportTest
     */
    public function setIsoCode($isoCode)
    {
        $this->isoCode = $isoCode;

        return $this;
    }

    /**
     * Get isoCode
     *
     * @return string
     */
    public function getIsoCode()
    {
        return $this->isoCode;
    }

    /**
     * Set iataCode
     *
     * @param string $iataCode
     *
     * @return AirportTest
     */
    public function setIataCode($iataCode)
    {
        $this->iataCode = $iataCode;

        return $this;
    }

    /**
     * Get iataCode
     *
     * @return string
     */
    public function getIataCode()
    {
        return $this->iataCode;
    }

    /**
     * Set icaoCode
     *
     * @param string $icaoCode
     *
     * @return AirportTest
     */
    public function setIcaoCode($icaoCode)
    {
        $this->icaoCode = $icaoCode;

        return $this;
    }

    /**
     * Get icaoCode
     *
     * @return string
     */
    public function getIcaoCode()
    {
        return $this->icaoCode;
    }

    /**
     * Set nameEng
     *
     * @param string $nameEng
     *
     * @return AirportTest
     */
    public function setNameEng($nameEng)
    {
        $this->nameEng = $nameEng;

        return $this;
    }

    /**
     * Get nameEng
     *
     * @return string
     */
    public function getNameEng()
    {
        return $this->nameEng;
    }

    /**
     * Set cityEng
     *
     * @param string $cityEng
     *
     * @return AirportTest
     */
    public function setCityEng($cityEng)
    {
        $this->cityEng = $cityEng;

        return $this;
    }

    /**
     * Get cityEng
     *
     * @return string
     */
    public function getCityEng()
    {
        return $this->cityEng;
    }

    /**
     * Set countryEng
     *
     * @param string $countryEng
     *
     * @return AirportTest
     */
    public function setCountryEng($countryEng)
    {
        $this->countryEng = $countryEng;

        return $this;
    }

    /**
     * Get countryEng
     *
     * @return string
     */
    public function getCountryEng()
    {
        return $this->countryEng;
    }

    /**
     * Set nameRus
     *
     * @param string $nameRus
     *
     * @return AirportTest
     */
    public function setNameRus($nameRus)
    {
        $this->nameRus = $nameRus;

        return $this;
    }

    /**
     * Get nameRus
     *
     * @return string
     */
    public function getNameRus()
    {
        return $this->nameRus;
    }

    /**
     * Set cityRus
     *
     * @param string $cityRus
     *
     * @return AirportTest
     */
    public function setCityRus($cityRus)
    {
        $this->cityRus = $cityRus;

        return $this;
    }

    /**
     * Get cityRus
     *
     * @return string
     */
    public function getCityRus()
    {
        return $this->cityRus;
    }

    /**
     * Set countryRus
     *
     * @param string $countryRus
     *
     * @return AirportTest
     */
    public function setCountryRus($countryRus)
    {
        $this->countryRus = $countryRus;

        return $this;
    }

    /**
     * Get countryRus
     *
     * @return string
     */
    public function getCountryRus()
    {
        return $this->countryRus;
    }

    /**
     * Set gmtOffset
     *
     * @param string $gmtOffset
     *
     * @return AirportTest
     */
    public function setGmtOffset($gmtOffset)
    {
        $this->gmtOffset = $gmtOffset;

        return $this;
    }

    /**
     * Get gmtOffset
     *
     * @return string
     */
    public function getGmtOffset()
    {
        return $this->gmtOffset;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return AirportTest
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return AirportTest
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
