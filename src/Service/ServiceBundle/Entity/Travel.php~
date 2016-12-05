<?php

namespace Service\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Travel
 *
 * @ORM\Table(name="travel")
 * @ORM\Entity
 */
class Travel
{
    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="travel_type_id", type="integer", nullable=false)
     */
    private $travelTypeId;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Travel
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    
        return $this;
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

    /**
     * Set travelTypeId
     *
     * @param integer $travelTypeId
     *
     * @return Travel
     */
    public function setTravelTypeId($travelTypeId)
    {
        $this->travelTypeId = $travelTypeId;
    
        return $this;
    }

    /**
     * Get travelTypeId
     *
     * @return integer
     */
    public function getTravelTypeId()
    {
        return $this->travelTypeId;
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
