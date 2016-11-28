<?php

namespace Service\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Station
 *
 * @ORM\Table(name="station", uniqueConstraints={@ORM\UniqueConstraint(name="transport_2", columns={"transport", "code"})}, indexes={@ORM\Index(name="code", columns={"code"}), @ORM\Index(name="type", columns={"type"}), @ORM\Index(name="num", columns={"num"}), @ORM\Index(name="name", columns={"name"}), @ORM\Index(name="transport", columns={"transport"}), @ORM\Index(name="country_name_code", columns={"country_name_code"}), @ORM\Index(name="country_name", columns={"country_name"}), @ORM\Index(name="administrative_area_name", columns={"administrative_area_name"}), @ORM\Index(name="locality_name", columns={"point"}), @ORM\Index(name="locality", columns={"locality"})})
 * @ORM\Entity
 */
class Station
{
    /**
     * @var string
     *
     * @ORM\Column(name="transport", type="string", nullable=false)
     */
    private $transport;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=16, nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="num", type="integer", nullable=false)
     */
    private $num;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="country_name_code", type="string", length=2, nullable=false)
     */
    private $countryNameCode;

    /**
     * @var string
     *
     * @ORM\Column(name="country_name", type="string", length=64, nullable=false)
     */
    private $countryName;

    /**
     * @var string
     *
     * @ORM\Column(name="administrative_area_name", type="string", length=64, nullable=false)
     */
    private $administrativeAreaName;

    /**
     * @var string
     *
     * @ORM\Column(name="locality", type="string", length=64, nullable=false)
     */
    private $locality;

    /**
     * @var string
     *
     * @ORM\Column(name="point", type="string", length=64, nullable=false)
     */
    private $point;

    /**
     * @var string
     *
     * @ORM\Column(name="addr", type="string", length=255, nullable=false)
     */
    private $addr;

    /**
     * @var string
     *
     * @ORM\Column(name="geocode", type="text", length=65535, nullable=false)
     */
    private $geocode;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set transport
     *
     * @param string $transport
     *
     * @return Station
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;
    
        return $this;
    }

    /**
     * Get transport
     *
     * @return string
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Station
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Station
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set num
     *
     * @param integer $num
     *
     * @return Station
     */
    public function setNum($num)
    {
        $this->num = $num;
    
        return $this;
    }

    /**
     * Get num
     *
     * @return integer
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Station
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set countryNameCode
     *
     * @param string $countryNameCode
     *
     * @return Station
     */
    public function setCountryNameCode($countryNameCode)
    {
        $this->countryNameCode = $countryNameCode;
    
        return $this;
    }

    /**
     * Get countryNameCode
     *
     * @return string
     */
    public function getCountryNameCode()
    {
        return $this->countryNameCode;
    }

    /**
     * Set countryName
     *
     * @param string $countryName
     *
     * @return Station
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;
    
        return $this;
    }

    /**
     * Get countryName
     *
     * @return string
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * Set administrativeAreaName
     *
     * @param string $administrativeAreaName
     *
     * @return Station
     */
    public function setAdministrativeAreaName($administrativeAreaName)
    {
        $this->administrativeAreaName = $administrativeAreaName;
    
        return $this;
    }

    /**
     * Get administrativeAreaName
     *
     * @return string
     */
    public function getAdministrativeAreaName()
    {
        return $this->administrativeAreaName;
    }

    /**
     * Set locality
     *
     * @param string $locality
     *
     * @return Station
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;
    
        return $this;
    }

    /**
     * Get locality
     *
     * @return string
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * Set point
     *
     * @param string $point
     *
     * @return Station
     */
    public function setPoint($point)
    {
        $this->point = $point;
    
        return $this;
    }

    /**
     * Get point
     *
     * @return string
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Set addr
     *
     * @param string $addr
     *
     * @return Station
     */
    public function setAddr($addr)
    {
        $this->addr = $addr;
    
        return $this;
    }

    /**
     * Get addr
     *
     * @return string
     */
    public function getAddr()
    {
        return $this->addr;
    }

    /**
     * Set geocode
     *
     * @param string $geocode
     *
     * @return Station
     */
    public function setGeocode($geocode)
    {
        $this->geocode = $geocode;
    
        return $this;
    }

    /**
     * Get geocode
     *
     * @return string
     */
    public function getGeocode()
    {
        return $this->geocode;
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
