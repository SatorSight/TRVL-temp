<?php

namespace Service\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserFlight
 *
 * @ORM\Table(name="user_flight")
 * @ORM\Entity
 */
class UserFlight
{
    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userFlight")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @ORM\Id
     */
    protected $user;
    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="Flight", inversedBy="userFlight")
     * @ORM\JoinColumn(name="flight_id", referencedColumnName="id")
     * @ORM\Id
     */
    protected $flight;



    /**
     * Set user
     *
     * @param \Service\ServiceBundle\Entity\User $user
     *
     * @return UserFlight
     */
    public function setUser(\Service\ServiceBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Service\ServiceBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set flight
     *
     * @param \Service\ServiceBundle\Entity\Flight $flight
     *
     * @return UserFlight
     */
    public function setFlight(\Service\ServiceBundle\Entity\Flight $flight)
    {
        $this->flight = $flight;

        return $this;
    }

    /**
     * Get flight
     *
     * @return \Service\ServiceBundle\Entity\Flight
     */
    public function getFlight()
    {
        return $this->flight;
    }
}
