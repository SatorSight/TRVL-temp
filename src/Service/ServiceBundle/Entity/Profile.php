<?php

namespace Service\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Profile
 *
 * @ORM\Table(name="profile", indexes={@ORM\Index(name="orientation", columns={"orientation"}), @ORM\Index(name="name", columns={"name"}), @ORM\Index(name="last_visit", columns={"last_visit"})})
 * @ORM\Entity
 */
class Profile
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64, nullable=false)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_visit", type="datetime", nullable=false)
     */
    private $lastVisit = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="about", type="text", length=65535, nullable=false)
     */
    private $about;

    /**
     * @var integer
     *
     * @ORM\Column(name="orientation", type="integer", nullable=false)
     */
    private $orientation;

    /**
     * @var string
     *
     * @ORM\Column(name="appearance", type="text", length=65535, nullable=false)
     */
    private $appearance;

    /**
     * @var string
     *
     * @ORM\Column(name="age", type="string", length=255, nullable=false)
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="sex", type="string", length=255, nullable=false)
     */
    private $sex;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=false)
     */
    private $city;

    /**
     * @var integer
     *
     * @ORM\Column(name="wanna_communicate", type="integer", nullable=false)
     */
    private $wannaCommunicate;

    /**
     * @var integer
     *
     * @ORM\Column(name="find_companion", type="integer", nullable=false)
     */
    private $findCompanion;

    /**
     * @var integer
     *
     * @ORM\Column(name="find_couple", type="integer", nullable=false)
     */
    private $findCouple;

    /**
     * @var integer
     *
     * @ORM\Column(name="find_friends", type="integer", nullable=false)
     */
    private $findFriends;

    /**
     * @var integer
     *
     * @ORM\Column(name="free", type="integer", nullable=false)
     */
    private $free;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $userId;



    /**
     * Set name
     *
     * @param string $name
     *
     * @return Profile
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
     * Set lastVisit
     *
     * @param \DateTime $lastVisit
     *
     * @return Profile
     */
    public function setLastVisit($lastVisit)
    {
        $this->lastVisit = $lastVisit;

        return $this;
    }

    /**
     * Get lastVisit
     *
     * @return \DateTime
     */
    public function getLastVisit()
    {
        return $this->lastVisit;
    }

    /**
     * Set about
     *
     * @param string $about
     *
     * @return Profile
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
    }

    /**
     * Get about
     *
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * Set orientation
     *
     * @param integer $orientation
     *
     * @return Profile
     */
    public function setOrientation($orientation)
    {
        $this->orientation = $orientation;

        return $this;
    }

    /**
     * Get orientation
     *
     * @return integer
     */
    public function getOrientation()
    {
        return $this->orientation;
    }

    /**
     * Set appearance
     *
     * @param string $appearance
     *
     * @return Profile
     */
    public function setAppearance($appearance)
    {
        $this->appearance = $appearance;

        return $this;
    }

    /**
     * Get appearance
     *
     * @return string
     */
    public function getAppearance()
    {
        return $this->appearance;
    }

    /**
     * Set age
     *
     * @param string $age
     *
     * @return Profile
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return string
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set sex
     *
     * @param string $sex
     *
     * @return Profile
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Profile
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set wannaCommunicate
     *
     * @param integer $wannaCommunicate
     *
     * @return Profile
     */
    public function setWannaCommunicate($wannaCommunicate)
    {
        $this->wannaCommunicate = $wannaCommunicate;

        return $this;
    }

    /**
     * Get wannaCommunicate
     *
     * @return integer
     */
    public function getWannaCommunicate()
    {
        return $this->wannaCommunicate;
    }

    /**
     * Set findCompanion
     *
     * @param integer $findCompanion
     *
     * @return Profile
     */
    public function setFindCompanion($findCompanion)
    {
        $this->findCompanion = $findCompanion;

        return $this;
    }

    /**
     * Get findCompanion
     *
     * @return integer
     */
    public function getFindCompanion()
    {
        return $this->findCompanion;
    }

    /**
     * Set findCouple
     *
     * @param integer $findCouple
     *
     * @return Profile
     */
    public function setFindCouple($findCouple)
    {
        $this->findCouple = $findCouple;

        return $this;
    }

    /**
     * Get findCouple
     *
     * @return integer
     */
    public function getFindCouple()
    {
        return $this->findCouple;
    }

    /**
     * Set findFriends
     *
     * @param integer $findFriends
     *
     * @return Profile
     */
    public function setFindFriends($findFriends)
    {
        $this->findFriends = $findFriends;

        return $this;
    }

    /**
     * Get findFriends
     *
     * @return integer
     */
    public function getFindFriends()
    {
        return $this->findFriends;
    }

    /**
     * Set free
     *
     * @param integer $free
     *
     * @return Profile
     */
    public function setFree($free)
    {
        $this->free = $free;

        return $this;
    }

    /**
     * Get free
     *
     * @return integer
     */
    public function getFree()
    {
        return $this->free;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
