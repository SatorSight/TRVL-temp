<?php

namespace Service\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity
 */
class Message
{
    /**
     * @var integer
     *
     * @ORM\Column(name="user_id_from", type="integer", nullable=false)
     */
    private $userIdFrom;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id_to", type="integer", nullable=false)
     */
    private $userIdTo;

    /**
     * @var integer
     *
     * @ORM\Column(name="media_id", type="integer", nullable=false)
     */
    private $mediaId;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", length=65535, nullable=false)
     */
    private $text;

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
     * Set userIdFrom
     *
     * @param integer $userIdFrom
     *
     * @return Message
     */
    public function setUserIdFrom($userIdFrom)
    {
        $this->userIdFrom = $userIdFrom;
    
        return $this;
    }

    /**
     * Get userIdFrom
     *
     * @return integer
     */
    public function getUserIdFrom()
    {
        return $this->userIdFrom;
    }

    /**
     * Set userIdTo
     *
     * @param integer $userIdTo
     *
     * @return Message
     */
    public function setUserIdTo($userIdTo)
    {
        $this->userIdTo = $userIdTo;
    
        return $this;
    }

    /**
     * Get userIdTo
     *
     * @return integer
     */
    public function getUserIdTo()
    {
        return $this->userIdTo;
    }

    /**
     * Set mediaId
     *
     * @param integer $mediaId
     *
     * @return Message
     */
    public function setMediaId($mediaId)
    {
        $this->mediaId = $mediaId;
    
        return $this;
    }

    /**
     * Get mediaId
     *
     * @return integer
     */
    public function getMediaId()
    {
        return $this->mediaId;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Message
     */
    public function setText($text)
    {
        $this->text = $text;
    
        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Message
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
     * @return Message
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
