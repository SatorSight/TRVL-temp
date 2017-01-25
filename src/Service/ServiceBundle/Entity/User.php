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
     * @var integer
     *
     * @ORM\Column(name="chat_id", type="integer", nullable=true)
     */
    private $chatId;
    /**
     * @var string
     *
     * @ORM\Column(name="chat_pass", type="string", nullable=true)
     */
    private $chatPass;
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
     * @ORM\OneToMany(targetEntity="Like", mappedBy="user", cascade={"all"})
     */
    private $like_from;

    /**
     * @ORM\OneToMany(targetEntity="Like", mappedBy="user", cascade={"all"})
     */
    private $like_to;

    /**
     * @ORM\OneToOne(targetEntity="Profile", orphanRemoval=true)
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     */
    private $profile;

    /**
     * @ORM\OneToMany(targetEntity="Photo", mappedBy="user", cascade={"all"})
     * @ORM\OrderBy({"uploaded" = "ASC"})
     */
    private $photos;


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

    /**
     * Set chatId
     *
     * @param integer $chatId
     *
     * @return User
     */
    public function setChatId($chatId)
    {
        $this->chatId = $chatId;

        return $this;
    }

    /**
     * Get chatId
     *
     * @return integer
     */
    public function getChatId()
    {
        return $this->chatId;
    }

    /**
     * Set chatPass
     *
     * @param string $chatPass
     *
     * @return User
     */
    public function setChatPass($chatPass)
    {
        $this->chatPass = $chatPass;

        return $this;
    }

    /**
     * Get chatPass
     *
     * @return string
     */
    public function getChatPass()
    {
        return $this->chatPass;
    }

    /**
     * Add likeFrom
     *
     * @param \Service\ServiceBundle\Entity\Like $likeFrom
     *
     * @return User
     */
    public function addLikeFrom(\Service\ServiceBundle\Entity\Like $likeFrom)
    {
        $this->like_from[] = $likeFrom;

        return $this;
    }

    /**
     * Remove likeFrom
     *
     * @param \Service\ServiceBundle\Entity\Like $likeFrom
     */
    public function removeLikeFrom(\Service\ServiceBundle\Entity\Like $likeFrom)
    {
        $this->like_from->removeElement($likeFrom);
    }

    /**
     * Get likeFrom
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLikeFrom()
    {
        return $this->like_from;
    }

    /**
     * Add likeTo
     *
     * @param \Service\ServiceBundle\Entity\Like $likeTo
     *
     * @return User
     */
    public function addLikeTo(\Service\ServiceBundle\Entity\Like $likeTo)
    {
        $this->like_to[] = $likeTo;

        return $this;
    }

    /**
     * Remove likeTo
     *
     * @param \Service\ServiceBundle\Entity\Like $likeTo
     */
    public function removeLikeTo(\Service\ServiceBundle\Entity\Like $likeTo)
    {
        $this->like_to->removeElement($likeTo);
    }

    /**
     * Get likeTo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLikeTo()
    {
        return $this->like_to;
    }

    /**
     * Add photo
     *
     * @param \Service\ServiceBundle\Entity\Photo $photo
     *
     * @return User
     */
    public function addPhoto(\Service\ServiceBundle\Entity\Photo $photo)
    {
        $this->photos[] = $photo;

        return $this;
    }

    /**
     * Remove photo
     *
     * @param \Service\ServiceBundle\Entity\Photo $photo
     */
    public function removePhoto(\Service\ServiceBundle\Entity\Photo $photo)
    {
        $this->photos->removeElement($photo);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhotos()
    {
        return $this->photos;
    }
}
