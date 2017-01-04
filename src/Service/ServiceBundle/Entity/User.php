<?php

namespace Service\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User
{
    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", nullable=false)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="app_id", type="bigint", nullable=false)
     */
    private $appId;

    /**
     * @var string
     *
     * @ORM\Column(name="app_type", type="string", nullable=false)
     */
    private $appType;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inserted", type="datetime", nullable=false)
     */
    private $inserted = 'CURRENT_TIMESTAMP';

    /**
     * @var boolean
     *
     * @ORM\Column(name="banned", type="boolean", nullable=false)
     */
    private $banned;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="UserFlight", mappedBy="user", cascade={"all"})
     */
    private $userFlights;

    /**
     * @ORM\OneToOne(targetEntity="Profile", orphanRemoval=true)
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     */
    private $profile;


    /**
     * Set token
     *
     * @param string $token
     *
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set appId
     *
     * @param integer $appId
     *
     * @return User
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;

        return $this;
    }

    /**
     * Get appId
     *
     * @return integer
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * Set appType
     *
     * @param string $appType
     *
     * @return User
     */
    public function setAppType($appType)
    {
        $this->appType = $appType;

        return $this;
    }

    /**
     * Get appType
     *
     * @return string
     */
    public function getAppType()
    {
        return $this->appType;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set inserted
     *
     * @param \DateTime $inserted
     *
     * @return User
     */
    public function setInserted($inserted)
    {
        $this->inserted = $inserted;

        return $this;
    }

    /**
     * Get inserted
     *
     * @return \DateTime
     */
    public function getInserted()
    {
        return $this->inserted;
    }

    /**
     * Set banned
     *
     * @param boolean $banned
     *
     * @return User
     */
    public function setBanned($banned)
    {
        $this->banned = $banned;

        return $this;
    }

    /**
     * Get banned
     *
     * @return boolean
     */
    public function getBanned()
    {
        return $this->banned;
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
     * Constructor
     */
    public function __construct()
    {
        $this->userFlights = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add userFlight
     *
     * @param \Service\ServiceBundle\Entity\UserFlight $userFlight
     *
     * @return User
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
     * Set profile
     *
     * @param \Service\ServiceBundle\Entity\Profile $profile
     *
     * @return User
     */
    public function setProfile(\Service\ServiceBundle\Entity\Profile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \Service\ServiceBundle\Entity\Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
