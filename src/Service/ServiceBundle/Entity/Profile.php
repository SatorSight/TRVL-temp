<?php

namespace Service\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Profile
 *
 * @ORM\Table(name="profile", uniqueConstraints={@ORM\UniqueConstraint(name="email", columns={"email"})}, indexes={@ORM\Index(name="orientation", columns={"orientation"}), @ORM\Index(name="relations", columns={"relations"}), @ORM\Index(name="i_want", columns={"i_want"}), @ORM\Index(name="gender", columns={"gender"}), @ORM\Index(name="bd", columns={"bd"}), @ORM\Index(name="name", columns={"name"}), @ORM\Index(name="family", columns={"family"}), @ORM\Index(name="vk", columns={"vk"}), @ORM\Index(name="vk_token", columns={"vk_token"}), @ORM\Index(name="fb", columns={"fb"}), @ORM\Index(name="fb_token", columns={"fb_token"}), @ORM\Index(name="city_id", columns={"city_id"}), @ORM\Index(name="last_visit", columns={"last_visit"})})
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
     * @var string
     *
     * @ORM\Column(name="family", type="string", length=64, nullable=false)
     */
    private $family;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=64, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="vk", type="string", length=64, nullable=false)
     */
    private $vk;

    /**
     * @var string
     *
     * @ORM\Column(name="vk_token", type="string", length=128, nullable=false)
     */
    private $vkToken;

    /**
     * @var string
     *
     * @ORM\Column(name="fb", type="string", length=64, nullable=false)
     */
    private $fb;

    /**
     * @var string
     *
     * @ORM\Column(name="fb_token", type="string", length=128, nullable=false)
     */
    private $fbToken;

    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="integer", nullable=false)
     */
    private $cityId;

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
     * @var \DateTime
     *
     * @ORM\Column(name="bd", type="date", nullable=false)
     */
    private $bd;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", nullable=false)
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="i_want", type="string", nullable=false)
     */
    private $iWant;

    /**
     * @var string
     *
     * @ORM\Column(name="relations", type="string", nullable=false)
     */
    private $relations;

    /**
     * @var string
     *
     * @ORM\Column(name="orientation", type="string", nullable=false)
     */
    private $orientation;

    /**
     * @var string
     *
     * @ORM\Column(name="appearance", type="text", length=65535, nullable=false)
     */
    private $appearance;

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
     * Set family
     *
     * @param string $family
     *
     * @return Profile
     */
    public function setFamily($family)
    {
        $this->family = $family;
    
        return $this;
    }

    /**
     * Get family
     *
     * @return string
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Profile
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set vk
     *
     * @param string $vk
     *
     * @return Profile
     */
    public function setVk($vk)
    {
        $this->vk = $vk;
    
        return $this;
    }

    /**
     * Get vk
     *
     * @return string
     */
    public function getVk()
    {
        return $this->vk;
    }

    /**
     * Set vkToken
     *
     * @param string $vkToken
     *
     * @return Profile
     */
    public function setVkToken($vkToken)
    {
        $this->vkToken = $vkToken;
    
        return $this;
    }

    /**
     * Get vkToken
     *
     * @return string
     */
    public function getVkToken()
    {
        return $this->vkToken;
    }

    /**
     * Set fb
     *
     * @param string $fb
     *
     * @return Profile
     */
    public function setFb($fb)
    {
        $this->fb = $fb;
    
        return $this;
    }

    /**
     * Get fb
     *
     * @return string
     */
    public function getFb()
    {
        return $this->fb;
    }

    /**
     * Set fbToken
     *
     * @param string $fbToken
     *
     * @return Profile
     */
    public function setFbToken($fbToken)
    {
        $this->fbToken = $fbToken;
    
        return $this;
    }

    /**
     * Get fbToken
     *
     * @return string
     */
    public function getFbToken()
    {
        return $this->fbToken;
    }

    /**
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return Profile
     */
    public function setCityId($cityId)
    {
        $this->cityId = $cityId;
    
        return $this;
    }

    /**
     * Get cityId
     *
     * @return integer
     */
    public function getCityId()
    {
        return $this->cityId;
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
     * Set bd
     *
     * @param \DateTime $bd
     *
     * @return Profile
     */
    public function setBd($bd)
    {
        $this->bd = $bd;
    
        return $this;
    }

    /**
     * Get bd
     *
     * @return \DateTime
     */
    public function getBd()
    {
        return $this->bd;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return Profile
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    
        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set iWant
     *
     * @param string $iWant
     *
     * @return Profile
     */
    public function setIWant($iWant)
    {
        $this->iWant = $iWant;
    
        return $this;
    }

    /**
     * Get iWant
     *
     * @return string
     */
    public function getIWant()
    {
        return $this->iWant;
    }

    /**
     * Set relations
     *
     * @param string $relations
     *
     * @return Profile
     */
    public function setRelations($relations)
    {
        $this->relations = $relations;
    
        return $this;
    }

    /**
     * Get relations
     *
     * @return string
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * Set orientation
     *
     * @param string $orientation
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
     * @return string
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
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
