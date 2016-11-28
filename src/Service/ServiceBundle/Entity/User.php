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
     * @ORM\Column(name="ios_token", type="string", length=128, nullable=false)
     */
    private $iosToken;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=64, nullable=false)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64, nullable=false)
     */
    private $password;

    /**
     * @var integer
     *
     * @ORM\Column(name="count_badge", type="integer", nullable=false)
     */
    private $countBadge = '0';

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
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set iosToken
     *
     * @param string $iosToken
     *
     * @return User
     */
    public function setIosToken($iosToken)
    {
        $this->iosToken = $iosToken;
    
        return $this;
    }

    /**
     * Get iosToken
     *
     * @return string
     */
    public function getIosToken()
    {
        return $this->iosToken;
    }

    /**
     * Set login
     *
     * @param string $login
     *
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;
    
        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set countBadge
     *
     * @param integer $countBadge
     *
     * @return User
     */
    public function setCountBadge($countBadge)
    {
        $this->countBadge = $countBadge;
    
        return $this;
    }

    /**
     * Get countBadge
     *
     * @return integer
     */
    public function getCountBadge()
    {
        return $this->countBadge;
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
