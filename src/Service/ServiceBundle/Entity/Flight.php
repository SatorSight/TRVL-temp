<?php

namespace Service\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Flight
 *
 * @ORM\Table(name="flight")
 * @ORM\Entity(repositoryClass="Service\ServiceBundle\Entity\Repository\FlightRepository")
 */
class Flight
{
    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer", nullable=true)
     * 0 - plane
     * 1 - train
     */
    private $type;
    /**
     * @var integer
     *
     * @ORM\Column(name="no", type="integer", nullable=true)
     */
    private $no;

    /**
     * @var string
     *
     * @ORM\Column(name="from_hello", type="string", nullable=true)
     */
    private $from;

    /**
     * @var string
     *
     * @ORM\Column(name="to_hello", type="string", nullable=true)
     */
    private $to;

    /**
     * @var string
     *
     * @ORM\Column(name="airlineCode", type="string", nullable=true)
     */
    private $airlineCode;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", nullable=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="from_code", type="string", nullable=true)
     */
    private $fromCode;

    /**
     * @var string
     *
     * @ORM\Column(name="from_airport", type="string", nullable=true)
     */
    private $fromAirport;

    /**
     * @var string
     *
     * @ORM\Column(name="from_city", type="string", nullable=true)
     */
    private $fromCity;

    /**
     * @var string
     *
     * @ORM\Column(name="from_country", type="string", nullable=true)
     */
    private $fromCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="to_code", type="string", nullable=true)
     */
    private $toCode;

    /**
     * @var string
     *
     * @ORM\Column(name="to_airport", type="string", nullable=true)
     */
    private $toAirport;

    /**
     * @var string
     *
     * @ORM\Column(name="to_city", type="string", nullable=true)
     */
    private $toCity;

    /**
     * @var string
     *
     * @ORM\Column(name="to_country", type="string", nullable=true)
     */
    private $toCountry;

    /**
     * @var \DateTime
     * @ORM\Column(name="from_date", type="datetime", nullable=true)
     */
    protected $fromDate;

    /**
     * @var \DateTime
     * @ORM\Column(name="to_date", type="datetime", nullable=true)
     */
    protected $toDate;

    /**
     * @ORM\OneToMany(targetEntity="UserFlight", mappedBy="flight", cascade={"all"})
     */
    private $userFlights;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userFlights = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set no
     *
     * @param integer $no
     *
     * @return Flight
     */
    public function setNo($no)
    {
        $this->no = $no;

        return $this;
    }

    /**
     * Get no
     *
     * @return integer
     */
    public function getNo()
    {
        return $this->no;
    }

    /**
     * Set airlineCode
     *
     * @param string $airlineCode
     *
     * @return Flight
     */
    public function setAirlineCode($airlineCode)
    {
        $this->airlineCode = $airlineCode;

        return $this;
    }

    /**
     * Get airlineCode
     *
     * @return string
     */
    public function getAirlineCode()
    {
        return $this->airlineCode;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Flight
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
     * Set fromCode
     *
     * @param string $fromCode
     *
     * @return Flight
     */
    public function setFromCode($fromCode)
    {
        $this->fromCode = $fromCode;

        return $this;
    }

    /**
     * Get fromCode
     *
     * @return string
     */
    public function getFromCode()
    {
        return $this->fromCode;
    }

    /**
     * Set fromAirport
     *
     * @param string $fromAirport
     *
     * @return Flight
     */
    public function setFromAirport($fromAirport)
    {
        $this->fromAirport = $fromAirport;

        return $this;
    }

    /**
     * Get fromAirport
     *
     * @return string
     */
    public function getFromAirport()
    {
        return $this->fromAirport;
    }

    /**
     * Set fromCity
     *
     * @param string $fromCity
     *
     * @return Flight
     */
    public function setFromCity($fromCity)
    {
        $this->fromCity = $fromCity;

        return $this;
    }

    /**
     * Get fromCity
     *
     * @return string
     */
    public function getFromCity()
    {
        return $this->fromCity;
    }

    /**
     * Set fromCountry
     *
     * @param string $fromCountry
     *
     * @return Flight
     */
    public function setFromCountry($fromCountry)
    {
        $this->fromCountry = $fromCountry;

        return $this;
    }

    /**
     * Get fromCountry
     *
     * @return string
     */
    public function getFromCountry()
    {
        return $this->fromCountry;
    }

    /**
     * Set toCode
     *
     * @param string $toCode
     *
     * @return Flight
     */
    public function setToCode($toCode)
    {
        $this->toCode = $toCode;

        return $this;
    }

    /**
     * Get toCode
     *
     * @return string
     */
    public function getToCode()
    {
        return $this->toCode;
    }

    /**
     * Set toAirport
     *
     * @param string $toAirport
     *
     * @return Flight
     */
    public function setToAirport($toAirport)
    {
        $this->toAirport = $toAirport;

        return $this;
    }

    /**
     * Get toAirport
     *
     * @return string
     */
    public function getToAirport()
    {
        return $this->toAirport;
    }

    /**
     * Set toCity
     *
     * @param string $toCity
     *
     * @return Flight
     */
    public function setToCity($toCity)
    {
        $this->toCity = $toCity;

        return $this;
    }

    /**
     * Get toCity
     *
     * @return string
     */
    public function getToCity()
    {
        return $this->toCity;
    }

    /**
     * Set toCountry
     *
     * @param string $toCountry
     *
     * @return Flight
     */
    public function setToCountry($toCountry)
    {
        $this->toCountry = $toCountry;

        return $this;
    }

    /**
     * Get toCountry
     *
     * @return string
     */
    public function getToCountry()
    {
        return $this->toCountry;
    }

    /**
     * Set fromDate
     *
     * @param \DateTime $fromDate
     *
     * @return Flight
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    /**
     * Get fromDate
     *
     * @return \DateTime
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * Set toDate
     *
     * @param \DateTime $toDate
     *
     * @return Flight
     */
    public function setToDate($toDate)
    {
        $this->toDate = $toDate;

        return $this;
    }

    /**
     * Get toDate
     *
     * @return \DateTime
     */
    public function getToDate()
    {
        return $this->toDate;
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

    /**
     * Add userFlight
     *
     * @param \Service\ServiceBundle\Entity\UserFlight $userFlight
     *
     * @return Flight
     */
    public function addUserFlight(\Service\ServiceBundle\Entity\UserFlight $userFlight)
    {
        $this->userFlights[] = $userFlight;

        return $this;
    }

    /**
     * Remove userFlight
     *
     * @param \Service\ServiceBundle\Entity\UserFlight $userFlight
     */
    public function removeUserFlight(\Service\ServiceBundle\Entity\UserFlight $userFlight)
    {
        $this->userFlights->removeElement($userFlight);
    }

    /**
     * Get userFlights
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserFlights()
    {
        return $this->userFlights;
    }

    /**
     * Set from
     *
     * @param string $from
     *
     * @return Flight
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get from
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set to
     *
     * @param string $to
     *
     * @return Flight
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get to
     *
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return Flight
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }
}
