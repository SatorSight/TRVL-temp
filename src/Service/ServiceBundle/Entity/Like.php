<?php

namespace Service\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Like
 *
 * @ORM\Table(name="`like`")
 * @ORM\Entity
 */
class Like
{
    
    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="User", inversedBy="like_from")
     * @ORM\JoinColumn(name="user_from_id", referencedColumnName="id")
     * @ORM\Id
     */
    protected $user_from;
    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="User", inversedBy="like_to")
     * @ORM\JoinColumn(name="user_to_id", referencedColumnName="id")
     * @ORM\Id
     */
    protected $user_to;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created = 'CURRENT_TIMESTAMP';


    /**
     * Set userFrom
     *
     * @param \Service\ServiceBundle\Entity\User $userFrom
     *
     * @return Like
     */
    public function setUserFrom(\Service\ServiceBundle\Entity\User $userFrom)
    {
        $this->user_from = $userFrom;

        return $this;
    }

    /**
     * Get userFrom
     *
     * @return \Service\ServiceBundle\Entity\User
     */
    public function getUserFrom()
    {
        return $this->user_from;
    }

    /**
     * Set userTo
     *
     * @param \Service\ServiceBundle\Entity\User $userTo
     *
     * @return Like
     */
    public function setUserTo(\Service\ServiceBundle\Entity\User $userTo)
    {
        $this->user_to = $userTo;

        return $this;
    }

    /**
     * Get userTo
     *
     * @return \Service\ServiceBundle\Entity\User
     */
    public function getUserTo()
    {
        return $this->user_to;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Like
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }
}
